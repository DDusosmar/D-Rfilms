<?php
session_start();

$servername = "localhost";
$username = "dbuser";
$password = "zGfaIW9YDH1GFa2e";
$dbname = "movies_cc_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filmId'])) {
    $filmId = $_POST['filmId'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $director = $_POST['director'];
    $release_year = $_POST['release_year'];
    $runtime = $_POST['runtime'];
    $rating = $_POST['rating'];

    // Update query
    $sql = "UPDATE films SET Titel = '$title', Beschrijving = '$description', Regisseurnaam = '$director', Releasejaar = '$release_year', Speeltijd = '$runtime' WHERE FilmID = '$filmId'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Movie information updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating movie: ');</script>" . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();