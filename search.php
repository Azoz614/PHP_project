<?php
require 'db.php';

$term = $_GET['term'] ?? '';
$term = $conn->real_escape_string($term);

if($term != '') {
    $sql = "SELECT id, title, year, poster 
            FROM movies 
            WHERE title LIKE '%$term%' 
            LIMIT 5";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        echo '<div class="list-group bg_grey text-white p-2">';
        while($row = $result->fetch_assoc()){
            $poster = !empty($row['poster']) ? $row['poster'] : 'default_movie.jpg';
            echo '<a href="movie.php?id='.$row['id'].'" class="list-group-item list-group-item-action bg-dark text-white mb-2 d-flex align-items-center">';
            echo '<img src="uploads/'.$poster.'" style="width:50px;height:70px;object-fit:cover;margin-right:10px;">';
            echo '<div>';
            echo '<strong>'.$row['title'].'</strong><br>';
            echo '<small>'.$row['year'].'</small>';
            echo '</div></a>';
        }
        echo '</div>';
    } else {
        echo '<div class="text-white mt-2">No movies found.</div>';
    }
}
?>