<?php
// service.php
session_start();
include('db.php');

$session_id = session_id();

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    header('Content-Type: application/json; charset=utf-8');

    if ($action === 'add_watchlist' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $movie_id = intval($input['movie_id'] ?? 0);
        if (!$movie_id) { echo json_encode(['ok'=>false,'err'=>'movie_id required']); exit; }
        $stmt = $conn->prepare("INSERT INTO watchlists (session_id, movie_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE created_at = NOW()");
        $stmt->bind_param('si', $session_id, $movie_id);
        $ok = $stmt->execute();
        echo json_encode(['ok'=>$ok]); exit;
    }

    if ($action === 'remove_watchlist' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $movie_id = intval($input['movie_id'] ?? 0);
        if (!$movie_id) { echo json_encode(['ok'=>false,'err'=>'movie_id required']); exit; }
        $stmt = $conn->prepare("DELETE FROM watchlists WHERE session_id = ? AND movie_id = ?");
        $stmt->bind_param('si', $session_id, $movie_id);
        $ok = $stmt->execute();
        echo json_encode(['ok'=>$ok]); exit;
    }

    if ($action === 'get_watchlist') {
        $rows=[];
        $stmt=$conn->prepare("SELECT w.movie_id,m.title,m.poster,m.release_date FROM watchlists w JOIN movies m ON w.movie_id=m.id WHERE w.session_id=? ORDER BY w.created_at DESC");
        $stmt->bind_param('s',$session_id);
        $stmt->execute();
        $res=$stmt->get_result();
        while($r=$res->fetch_assoc())$rows[]=$r;
        echo json_encode(['ok'=>true,'data'=>$rows]);exit;
    }

    if ($action === 'chart_data') {
        $sql="SELECT m.id,m.title,ROUND(AVG(r.rating),2) AS avg_rating,COUNT(r.id) AS cnt
              FROM movies m JOIN reviews r ON m.id=r.movie_id
              GROUP BY m.id ORDER BY cnt DESC LIMIT 8";
        $res=$conn->query($sql);
        $labels=[];$data=[];$counts=[];
        while($row=$res->fetch_assoc()){
            $labels[]=$row['title'];$data[]=(float)$row['avg_rating'];$counts[]=(int)$row['cnt'];
        }
        echo json_encode(['ok'=>true,'labels'=>$labels,'data'=>$data,'counts'=>$counts]);exit;
    }

    if ($action === 'vote_actor' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $input=json_decode(file_get_contents('php://input'),true);
        $actor_id=intval($input['actor_id']??0);
        $vote=intval($input['vote']??0);
        if(!$actor_id||$vote<1||$vote>5){echo json_encode(['ok'=>false,'err'=>'invalid']);exit;}
        $stmt=$conn->prepare("INSERT INTO vote_actor (session_id,actor_id,vote) VALUES (?,?,?) ON DUPLICATE KEY UPDATE vote=VALUES(vote),created_at=NOW()");
        $stmt->bind_param('sii',$session_id,$actor_id,$vote);
        $ok=$stmt->execute();
        echo json_encode(['ok'=>$ok]);exit;
    }

    if ($action === 'actor_votes') {
        $sql="SELECT a.id,a.ac_name,a.lives_in,a.latest_film,a.photo,
              COALESCE(ROUND(AVG(v.vote),2),0) AS avg_vote,
              COUNT(v.id) AS vote_count,
              MAX(CASE WHEN v.session_id='$session_id' THEN v.vote ELSE NULL END) AS user_vote
              FROM actors a 
              LEFT JOIN vote_actor v ON a.id=v.actor_id
              GROUP BY a.id ORDER BY avg_vote DESC,vote_count DESC";
        $res=$conn->query($sql);
        $rows=[];while($r=$res->fetch_assoc())$rows[]=$r;
        echo json_encode(['ok'=>true,'data'=>$rows]);exit;
    }

    echo json_encode(['ok'=>false,'err'=>'unknown action']);exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Flick-Fix — Services</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
:root{--bg:#070707;--accent:#e22b20;--muted:#9b9b9b;--text:#eaeaea;}
body{background:#0b0b0b;color:var(--text);font-family:"Exo 2",sans-serif;}
.navbar{background:#0b0b0b;}
.navbar-brand{color:var(--accent);}
.section-title{color:var(--accent);font-family:"Orbitron",sans-serif;}
.movie-thumb img{width:100%;height:100%;object-fit:cover}
.btn-accent{background:var(--accent);color:white}
.actor-card img{width:100%;height:180px;object-fit:cover;border-radius:10px;}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
<div class="container">
<a class="navbar-brand" href="index.php">🎬 Flick-Fix</a>
<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
<div id="nav" class="collapse navbar-collapse">
<ul class="navbar-nav ms-auto">
<li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
<li class="nav-item"><a href="service.php" class="nav-link active">Services</a></li>
<li class="nav-item"><a href="reviews.php" class="nav-link">Reviews</a></li>
<li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
</ul></div></div></nav>

<section class="hero p-4 text-center bg-black">
<h1 style="color:var(--muted)">We Are Planet</h1>
<h2 style="color:var(--accent)">Providing Movie Production & Review Services</h2>
<p class="small-muted">Explore top rated & trending movies, filter by genre and release date; save to watchlist, vote for actors and view insights.</p>
</section>

<div class="container py-4">
<div class="row g-4">
<div class="col-lg-8">
<!-- Browse Movies -->
<div class="card p-3 mb-3 bg-dark text-white">
<div class="d-flex flex-wrap justify-content-between align-items-center">
<span class="section-title">Browse Movies</span>
<div class="d-flex flex-wrap gap-2">
<select id="filter_genre" class="form-select form-select-sm" style="width:170px"><option value="">All genres</option></select>
<input id="filter_from" type="date" class="form-control form-control-sm" style="width:150px">
<input id="filter_to" type="date" class="form-control form-control-sm" style="width:150px">
<button id="applyFilters" class="btn btn-sm btn-accent">Apply</button>
<button id="clearFilters" class="btn btn-sm btn-secondary">Clear</button>
</div></div></div>

<h4 class="section-title mt-4">All Movies</h4>
<div id="allMoviesGrid" class="row g-3"></div>

<!-- ✅ Movies by Genre -->
<h4 class="section-title mt-5">Movies by Genre</h4>
<div id="moviesByGenre" class="row g-3"></div>

<!-- ✅ Insights Graph -->
<h4 class="section-title mt-5">Insights</h4>
<div style="max-width:600px;margin:auto;">
<canvas id="chartInsights" height="120"></canvas>
</div>

<!-- ✅ Vote Actors -->
<h4 class="section-title mt-5">Vote for Actors</h4>
<div id="actorVotes" class="row g-3"></div>

</div>
<div class="col-lg-4">
<h5 class="section-title mb-3">Your Watchlist</h5>
<div id="watchlistContainer" class="small-muted">Loading...</div>
</div></div></div>

<footer class="text-center text-muted py-3 border-top border-secondary">© <?php echo date('Y'); ?> Flick-Fix</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const API_BASE='service.php?action=';

async function ajax(action,opts={}) {
  const url=API_BASE+action;
  const res=await fetch(url,opts.method==='POST'?{
    method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(opts.body||{})}:{});
  return res.json();
}

function esc(s){return String(s||'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));}

const SERVER_GENRES=<?php
  $g=[];$r=$conn->query("SELECT DISTINCT genre FROM movies WHERE genre<>'' ORDER BY genre");
  while($x=$r->fetch_assoc())$g[]=$x['genre'];echo json_encode($g);
?>;
const SERVER_MOVIES=<?php
  $m=[];$r=$conn->query("SELECT id,title,poster,genre,release_date,year FROM movies ORDER BY title");
  while($x=$r->fetch_assoc())$m[]=$x;echo json_encode($m);
?>;

function renderGenres(){
  const sel=document.getElementById('filter_genre');
  sel.innerHTML='<option value="">All genres</option>';
  SERVER_GENRES.forEach(g=>{
    const o=document.createElement('option');o.value=g;o.text=g;sel.appendChild(o);
  });
}

function parseDate(d){if(!d)return null;const t=new Date(d);return isNaN(t)?null:t;}

function loadMovies(filters={}){
  let movies=[...SERVER_MOVIES];
  if(filters.genre) movies=movies.filter(m=>(m.genre||'').toLowerCase()===filters.genre.toLowerCase());
  if(filters.from){const f=parseDate(filters.from);movies=movies.filter(m=>parseDate(m.release_date)&&parseDate(m.release_date)>=f);}
  if(filters.to){const t=parseDate(filters.to);movies=movies.filter(m=>parseDate(m.release_date)&&parseDate(m.release_date)<=t);}
  const grid=document.getElementById('allMoviesGrid');
  grid.innerHTML='';if(!movies.length){grid.innerHTML='<div class="text-muted">No movies found.</div>';return;}
  movies.forEach(m=>{
    const col=document.createElement('div');col.className='col-6 col-md-4';
    col.innerHTML=`<div class="bg-dark p-2 rounded">
      <div class="movie-thumb" style="height:220px"><img src="uploads/${esc(m.poster||'placeholder.jpg')}" alt="${esc(m.title)}"></div>
      <div class="mt-2"><strong>${esc(m.title)}</strong><br><small>${esc(m.genre||'Unknown')} • ${m.year||''}</small></div>
      <div class="d-flex justify-content-between align-items-center mt-2">
        <button class="btn btn-sm btn-accent btn-watchlist" data-id="${m.id}">🔖 Save</button>
        <small class="text-muted">${m.release_date?new Date(m.release_date).toLocaleDateString():""}</small>
      </div></div>`;
    grid.appendChild(col);
  });
  bindWatchlistButtons();
}

// ✅ Movies by Genre with posters & watchlist buttons
function renderMoviesByGenre(){
  const container=document.getElementById('moviesByGenre');container.innerHTML='';
  if(!SERVER_GENRES.length){container.textContent='No genres found';return;}
  SERVER_GENRES.forEach(g=>{
    const genreMovies=SERVER_MOVIES.filter(m=>m.genre===g);
    if(!genreMovies.length)return;
    const row=document.createElement('div');
    row.innerHTML=`<h5 class="text-danger mb-2">${esc(g)}</h5><div class="row g-2 mb-4">`+
      genreMovies.map(m=>`
        <div class="col-6 col-md-4">
          <div class="bg-dark p-2 rounded">
            <div class="movie-thumb" style="height:200px"><img src="uploads/${esc(m.poster||'placeholder.jpg')}"></div>
            <div class="mt-1"><strong>${esc(m.title)}</strong></div>
            <button class="btn btn-sm btn-accent btn-watchlist mt-1" data-id="${m.id}">🔖 Save</button>
          </div>
        </div>`).join('')+`</div>`;
    container.appendChild(row);
  });
  bindWatchlistButtons();
}

function bindWatchlistButtons(){
  document.querySelectorAll('.btn-watchlist').forEach(b=>{
    b.onclick=async()=>{b.disabled=true;
      const r=await ajax('add_watchlist',{method:'POST',body:{movie_id:b.dataset.id}});
      b.disabled=false;if(r.ok){b.textContent='Saved';loadWatchlist();}else alert('Failed');};
  });
}

// ✅ Insights Graph with multiple colors
async function loadChart(){
  const res=await ajax('chart_data');if(!res.ok)return;
  const colors=res.labels.map((_,i)=>`hsl(${i*50},80%,55%)`);
  const ctx=document.getElementById('chartInsights');
  new Chart(ctx,{
    type:'bar',
    data:{labels:res.labels,datasets:[{label:'Avg Rating',data:res.data,backgroundColor:colors}]},
    options:{scales:{y:{beginAtZero:true,max:5}},plugins:{legend:{display:false}}}
  });
}

// ✅ Actor Votes remain unchanged
async function loadActors(){
  const res=await ajax('actor_votes');
  const cont=document.getElementById('actorVotes');cont.innerHTML='';
  if(!res.ok){cont.textContent='Unable to load';return;}
  res.data.forEach(a=>{
    const col=document.createElement('div');col.className='col-md-6';
    col.innerHTML=`<div class="actor-card bg-dark p-2 text-center">
      <img src="uploads/${esc(a.photo||'placeholder.jpg')}" alt="${esc(a.ac_name)}">
      <h6 class="mt-2 text-danger">${esc(a.ac_name)}</h6>
      <p class="small text-muted mb-1">${esc(a.lives_in)}</p>
      <p class="small">🎬 ${esc(a.latest_film)}</p>
      <div>Avg Vote: <b>${a.avg_vote}</b> ⭐ (${a.vote_count} votes)</div>
      <div class="mt-2">`+
      [1,2,3,4,5].map(i=>`<span class="star" data-id="${a.id}" data-v="${i}" style="cursor:pointer;color:${a.user_vote>=i?'gold':'gray'};">★</span>`).join('')+
      `</div></div>`;
    cont.appendChild(col);
  });
  cont.querySelectorAll('.star').forEach(s=>{
    s.onclick=async()=>{
      const id=s.dataset.id,v=s.dataset.v;
      await ajax('vote_actor',{method:'POST',body:{actor_id:id,vote:v}});
      loadActors();
    };
  });
}

async function loadWatchlist(){
  const res=await ajax('get_watchlist');
  const c=document.getElementById('watchlistContainer');
  if(!res.ok){c.innerHTML='Unable to load';return;}
  const list=res.data;if(!list.length){c.innerHTML='Your watchlist is empty.';return;}
  c.innerHTML='';
  list.forEach(it=>{
    const d=document.createElement('div');
    d.className='border p-2 mb-1 rounded d-flex justify-content-between align-items-center';
    d.innerHTML=`<div><b>${esc(it.title)}</b><br><small>${it.release_date?new Date(it.release_date).toLocaleDateString():""}</small></div>
    <button class="btn btn-sm btn-outline-light btn-remove" data-id="${it.movie_id}">Remove</button>`;
    c.appendChild(d);
  });
  c.querySelectorAll('.btn-remove').forEach(b=>{
    b.onclick=async()=>{
      const r=await ajax('remove_watchlist',{method:'POST',body:{movie_id:b.dataset.id}});
      if(r.ok)loadWatchlist();
    };
  });
}

function bindFilters(){
  renderGenres();
  document.getElementById('applyFilters').onclick=()=>{
    const g=document.getElementById('filter_genre').value;
    const f=document.getElementById('filter_from').value;
    const t=document.getElementById('filter_to').value;
    loadMovies({genre:g,from:f,to:t});
  };
  document.getElementById('clearFilters').onclick=()=>{
    document.getElementById('filter_genre').value='';
    document.getElementById('filter_from').value='';
    document.getElementById('filter_to').value='';
    loadMovies();
  };
}

bindFilters();
loadMovies();
loadWatchlist();
renderMoviesByGenre();
loadChart();
loadActors();
</script>
</body>
</html>
