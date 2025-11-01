<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Movie Ticket Booking</title>
<link rel="stylesheet" href="style.css">

<style>
/* Seat map styling */
.seat {
  display: inline-block;
  width: 40px;
  height: 40px;
  margin: 5px;
  background-color: #444;
  color: #fff;
  text-align: center;
  line-height: 40px;
  cursor: pointer;
  border-radius: 5px;
}
.seat.selected { background-color: #ff9800; }
.seat.booked { background-color: #999; cursor: not-allowed; }
#seat-map { margin: 15px 0; }
</style>
</head>
<body>

<div class="container">
<h1>🎬 Movie Ticket Booking</h1>

<label for="name">Your Name:</label>
<input type="text" id="name" required>

<label for="movie">Select Movie:</label>
<select id="movie" onchange="loadTheaters()" required>
  <option value="">-- Loading Movies... --</option>
</select>

<label for="theater">Select Theater:</label>
<select id="theater" onchange="loadTimes()" required>
  <option value="">-- Select Theater --</option>
</select>

<label for="time">Select Show Time:</label>
<select id="time" onchange="loadSeats()" required>
  <option value="">-- Select Time --</option>
  <option value="1.00">1.00</option>
  <option value="4.00">4.00</option>
  <option value="8.00">8.00</option>
</select>

<div id="seat-map"></div>

<p>Total Seats: <span id="seat-count">0</span></p>
<p>Price per Seat: $10</p>
<p>Total Price: $<span id="total-price">0</span></p>

<button onclick="submitBooking()">Book Tickets</button>

<center><a href="view_bookings.php">⬅ View Bookings</a></center>

<center><h2><a href="order.php">Do you want to order foods?</a></h2></center>
</div>

<script>
// Global variables
let selectedSeats = [];
const seatPrice = 10;

// Load movies from database
document.addEventListener("DOMContentLoaded", loadMovies);

function loadMovies() {
  fetch('get_movies.php')
    .then(res => res.json())
    .then(data => {
      let movieSelect = document.getElementById('movie');
      movieSelect.innerHTML = '<option value="">-- Select Movie --</option>';
      data.forEach(movie => {
        movieSelect.innerHTML += `<option value="${movie.id}">${movie.title}</option>`;
      });
    })
    .catch(err => {
      console.error('Error loading movies:', err);
      alert('Error loading movies. Please check get_movies.php or database connection.');
    });
}

// Fetch theaters for selected movie
function loadTheaters() {
  let movieId = document.getElementById('movie').value;
  if (!movieId) return;
  fetch('get_theaters.php?movie_id=' + movieId)
  .then(res => res.json())
  .then(data => {
    let theaterSelect = document.getElementById('theater');
    theaterSelect.innerHTML = '<option value="">-- Select Theater --</option>';
    data.forEach(t => {
      theaterSelect.innerHTML += `<option value="${t.theater_id}">${t.name}</option>`;
    });
    document.getElementById('time').innerHTML = '<option value="">-- Select Time --</option>';
    document.getElementById('seat-map').innerHTML = '';
  });
}

// Load available times for selected theater
function loadTimes() {
  let movieId = document.getElementById('movie').value;
  let theaterId = document.getElementById('theater').value;
  if (!movieId || !theaterId) return;

  fetch(`get_times.php?movie_id=${movieId}&theater_id=${theaterId}`)
  .then(res => res.json())
  .then(data => {
    let timeSelect = document.getElementById('time');
    timeSelect.innerHTML = '<option value="">-- Select Time --</option>';
    data.forEach(t => {
      timeSelect.innerHTML += `<option value="${t.time_id}">${t.show_time}</option>`;
    });
    document.getElementById('seat-map').innerHTML = '';
  });
}

// Fetch seats for selected time
function loadSeats() {
  selectedSeats = [];
  updatePrice();

  let movieId = document.getElementById('movie').value;
  let theaterId = document.getElementById('theater').value;
  let timeId = document.getElementById('time').value;
  if (!movieId || !theaterId || !timeId) return;

  fetch(`get_seats.php?movie_id=${movieId}&theater_id=${theaterId}&time_id=${timeId}`)
  .then(res => res.json())
  .then(bookedSeats => {
    const allSeats = ['A1','A2','A3','A4','A5','B1','B2','B3','B4','B5','C1','C2','C3','C4','C5'];
    const seatMap = document.getElementById('seat-map');
    seatMap.innerHTML = '';
    allSeats.forEach(seat => {
      const div = document.createElement('div');
      div.classList.add('seat');
      div.textContent = seat;
      if (bookedSeats.includes(seat)) div.classList.add('booked');
      else div.onclick = () => toggleSeat(seat, div);
      seatMap.appendChild(div);
    });
  });
}

// Toggle seat selection
function toggleSeat(seat, div) {
  if (selectedSeats.includes(seat)) {
    selectedSeats = selectedSeats.filter(s => s != seat);
    div.classList.remove('selected');
  } else {
    selectedSeats.push(seat);
    div.classList.add('selected');
  }
  updatePrice();
}

// Update total price
function updatePrice() {
  document.getElementById('seat-count').textContent = selectedSeats.length;
  document.getElementById('total-price').textContent = selectedSeats.length * seatPrice;
}

// Submit booking
function submitBooking() {
  let name = document.getElementById('name').value;
  let movie = document.getElementById('movie').value;
  let theater = document.getElementById('theater').value;
  let time = document.getElementById('time').value;

  if (!name || !movie || !theater || !time || selectedSeats.length == 0) {
    alert("Please fill all fields and select at least one seat.");
    return;
  }

  fetch('book_multiple.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({name, movie, theater, time, seats:selectedSeats})
  })
  .then(res => res.json())
  .then(data => {
    alert(data.message);
    if (data.success) loadSeats(); // Refresh seat map
  });
}
</script>
</body>
</html>
