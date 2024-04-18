<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Update Movie Information</title>
</head>
<body class="bg-gray-100">
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-4 text-center">Update Movie Information</h1>
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

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['filmId'])) {
        $filmId = $_GET['filmId'];
        $sql = "SELECT * FROM films WHERE FilmID = '$filmId'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            <form method="post" action="update_process.php" class="max-w-md mx-auto bg-white p-6 rounded-md shadow-md">
                <input type="hidden" name="filmId" value="<?php echo $row['FilmID']; ?>">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-bold mb-2">Title</label>
                    <input type="text" id="title" name="title" value="<?php echo $row['Titel']; ?>" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                    <textarea id="description" name="description" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500"><?php echo $row['Beschrijving']; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="director" class="block text-gray-700 font-bold mb-2">Director</label>
                    <input type="text" id="director" name="director" value="<?php echo $row['Regisseurnaam']; ?>" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="release_year" class="block text-gray-700 font-bold mb-2">Release Year</label>
                    <input type="text" id="release_year" name="release_year" value="<?php echo $row['Releasejaar']; ?>" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="runtime" class="block text-gray-700 font-bold mb-2">Runtime</label>
                    <input type="text" id="runtime" name="runtime" value="<?php echo $row['Speeltijd']; ?>" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="rating" class="block text-gray-700 font-bold mb-2">Rating</label>
                    <input type="text" id="rating" name="rating" value="<?php echo $row['Rating']; ?>" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Movie
                </button>
            </form>
            <?php
        } else {
            echo "No movie found with the given ID.";
        }
    } else {
        echo "Invalid request.";
    }

    $conn->close();
    ?>
</div>
</body>
</html>
