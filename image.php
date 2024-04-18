<?php
    $servername = "localhost";
    $username = "dbuser";
    $password = "zGfaIW9YDH1GFa2e";
    $dbname = "movies_cc_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Verbinding mislukt: " . $conn->connect_error);
    }

    // Handle file upload
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/"; // Specify the directory where you want to store the uploaded images
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
        // Check if the file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            // Allow certain file formats
            if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg") {
                // Move the uploaded file to the specified directory
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // Insert the image path into the database
                    $imagePath = $target_file;
                    // Perform the SQL query to insert the image path into your database table
                    $sql = "INSERT INTO films (PosterImage) VALUES ('$imagePath')";
                    if ($conn->query($sql) === TRUE) {
                        echo "The file ". htmlspecialchars( basename( $_FILES["image"]["name"])). " has been uploaded and the path has been stored in the database.";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "Sorry, only JPG, JPEG & PNG files are allowed.";
            }
        } else {
            echo "File is not an image.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Search</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Form for uploading images -->
        <form method="POST" action="" enctype="multipart/form-data" class="mb-8">
            <input type="file" name="image" class="border border-gray-300 rounded-md py-2 px-4 focus:outline-none focus:ring focus:border-blue-500">
            <button type="submit" class="bg-blue-500 text-white py-2 px-6 rounded-md ml-2 hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-500">Upload Image</button>
        </form>

        <form method="GET" action="" class="mb-8">
            <input type="text" name="search" placeholder="Search..." class="border border-gray-300 rounded-md py-2 px-4 focus:outline-none focus:ring focus:border-blue-500">
            <button type="submit" class="bg-blue-500 text-white py-2 px-6 rounded-md ml-2 hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-500">Search</button>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php
            if (isset($_GET['search'])) {
                $search = $_GET['search'];

                $sql = "SELECT f.FilmID, f.Titel, r.Regisseurnaam, f.Releasejaar, f.Beschrijving, f.PosterImage, GROUP_CONCAT(g.Genrenaam SEPARATOR ', ') AS Genres
                        FROM films f
                        JOIN regisseur r ON f.Regisseurnaam = r.Regisseurnaam
                        JOIN film_genre fg ON f.FilmID = fg.FilmID
                        JOIN genre g ON fg.GenreID = g.GenreID
                
                        WHERE f.Titel LIKE '%$search%' 
                        OR r.Regisseurnaam LIKE '%$search%' 
                        OR f.Releasejaar LIKE '%$search%'
                        OR g.GenreID LIKE '%$search%'";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition duration-300 ease-in-out">
                            <img src="<?php echo htmlspecialchars($row["PosterImage"]); ?>" alt="<?php echo htmlspecialchars($row["Titel"]); ?>" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h2 class="text-xl font-semibold mb-2"><?php echo $row["Titel"]; ?></h2>
                                <p class="text-gray-600 mb-2"><?php echo $row["Beschrijving"]; ?></p>
                                <p class="text-gray-800">Regisseur: <?php echo $row["Regisseurnaam"]; ?></p>
                                <p class="text-gray-800">Releasejaar: <?php echo $row["Releasejaar"]; ?></p>
                                <p class="text-gray-800">Genres: <?php echo $row["Genres"]; ?></p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='text-gray-600'>No results found</div>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
