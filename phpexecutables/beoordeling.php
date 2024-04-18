<?php
$servername = "localhost";
$username = "dbuser";
$password = "zGfaIW9YDH1GFa2e";
$dbname = "movies_cc_db";
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleren op fouten
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Controleren of het formulier is ingediend
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ontvangen van de gegevens van het formulier
    $film_id = $_POST["film_id"];
    $rating = $_POST["rating"];
    $gebruiker_id = $_POST["gebruiker_id"];
    // Invoegen van de beoordeling in de database
    $sql = "INSERT INTO film_beoordelingen (FilmID, GebruikerID, Beoordeling) VALUES ('$film_id', '$gebruiker_id', '$rating')";
    if ($conn->query($sql) === TRUE) {
        echo "Beoordeling succesvol toegevoegd.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Sluit de databaseverbinding
$conn->close();