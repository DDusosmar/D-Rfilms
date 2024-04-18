<?php
session_start();

$servername = "localhost";
$username = "dbuser";
$password = "zGfaIW9YDH1GFa2e";
$dbname = "movies_cc_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filmId'])) {
    $filmId = $_POST['filmId'];

    // SQL to delete record
    $sql = "DELETE FROM films WHERE FilmID = '$filmId'";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
        header('Location: ../adminFilms.php');
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
        header('Location: ../adminFilms.php');
        exit();
    }
}

$conn->close();
