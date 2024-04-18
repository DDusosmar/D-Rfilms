<!DOCTYPE html>
<html lang="nl">
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
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Movies.cc</title>
    <script>
        function showAccountsTab() {
            var tab = document.querySelector('.accounts-tab');
            tab.style.height = '150px';
        }
    
        function hideAccountsTab() {
            var tab = document.querySelector('.accounts-tab');
            tab.style.height = '0';
        }
    </script>
    <style>
        .split-background {
            background: linear-gradient(to top, rgba(255, 255, 255, 0) 50%, rgba(0, 0, 0, 0.8) 100%), url('Images/bg2.jpg') center/cover;
        }
    </style>
</head>
<body class="bg-gray-100">
<nav class="split background bg-transparent p-5 flex justify-between items-center">
    <a href=""><img src="Images/popcorn.svg" alt="Movies.cc Logo" class="w-10"></a>
    <form method="GET" action="Search.php">
        <input type="text" name="search" placeholder="Search..." class="border border-gray-300 rounded-md py-2 px-4">
        <input type="submit" value="Search" class="bg-blue-500 text-white py-2 px-4 rounded-md ml-2">
    </form>
    <div class="hidden md:flex md:items-center md:w-auto">
        <div class="md:flex ">
            <a href="Home.php" class="block mt-4 md:inline-block md:mt-0 text-black hover:text-gray-500 mr-4">
                Home
            </a>
            <a href="AboutUs.php" class="block mt-4 md:inline-block md:mt-0 text-black hover:text-gray-500 mr-4">
                About
            </a>
            <a href="Films.php" class="block mt-4 md:inline-block md:mt-0 text-black hover:text-gray-500">
                Movies
            </a>
        </div>
    </div>    
    <form action="" method="post">
        <div class="accounts-tab fixed bottom-0 right-0 bg-gray-100 border border-gray-300 w-40 h-0 overflow-hidden transition-height duration-500">
            <input type="submit" name="submit" value="Logout" class="block py-2 px-4 no-underline text-gray-700 hover:bg-gray-200" />
            <a href="#" class="block py-2 px-4 no-underline text-gray-700 hover:bg-gray-200" onclick="hideAccountsTab()">Cancel</a>
        </div>
        <div class="cursor-pointer" onclick="showAccountsTab()">
            <img src="Images/person-icon.png" class="h-10 w-10 rounded-full" alt="Person Icon"/>
        </div>
    </form>
</nav>

    <?php 
    if ( isset( $_POST['submit'] ) && $_POST['submit'] == 'Logout') {
        // Logout code
        session_start();
        session_unset();
        session_destroy();
        header('Location: Home.php');
        exit();
      }
    ?>

    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-4 text-center">Films, TV Shows, & Meer</h1>
        <form method="get" id="filterForm" class="mb-4">
            <label for="genre" class="block mb-2">Genre</label>
            <select id="genre" name="genre" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                <option value="" disabled selected>Kies een genre</option>
                <?php
                $query = "SELECT Genrenaam FROM genre";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['Genrenaam'] . "'>" . $row['Genrenaam'] . "</option>";
                    }
                }
                ?>
            </select>
            <label for="regisseur" class="block mt-4 mb-2">Regisseur</label>
            <select id="regisseur" name="regisseur" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                <option value="" disabled selected>Kies een regisseur</option>
                <?php
                $query = "SELECT Regisseurnaam FROM regisseur";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['Regisseurnaam'] . "'>" . $row['Regisseurnaam'] . "</option>";
                    }
                }
                ?>
            </select>
            <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Filter
            </button>
            <button type="button" onclick="resetForm()" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                Reset
            </button>
        </form>
        <div class="grid grid-cols-2 gap-4">
            <?php
            $genre = isset($_GET['genre']) ? $_GET['genre'] : '';
            $regisseur = isset($_GET['regisseur']) ? $_GET['regisseur'] : '';

            $query = "SELECT f.FilmID, f.Titel, r.Regisseurnaam, f.Releasejaar, f.Beschrijving, f.PosterImage, GROUP_CONCAT(g.Genrenaam SEPARATOR ', ') AS Genres
                      FROM films f
                      JOIN film_genre fg ON f.FilmID = fg.FilmID
                      JOIN genre g ON fg.GenreID = g.GenreID
                      JOIN regisseur r ON f.Regisseurnaam = r.Regisseurnaam";

            $conditions = array();

            if (!empty($genre)) {
                $conditions[] = "g.Genrenaam = '$genre'";
            }

            if (!empty($regisseur)) {
                $conditions[] = "r.Regisseurnaam = '$regisseur'";
            }

            if (!empty($conditions)) {
                $query .= " WHERE " . implode(" AND ", $conditions);
            }

            $query .= " GROUP BY f.FilmID";

            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='bg-white rounded-lg shadow-md p-4'>";
                    echo "<img src='" . htmlspecialchars($row["PosterImage"]) . "' alt='" . htmlspecialchars($row["Titel"]) . "' class='w-full'>";
                    echo "<h2 class='text-lg font-semibold mt-2'><a href='filmdetails.php?filmId=" . $row["FilmID"] . "'>" . htmlspecialchars($row["Titel"]) . "</a></h2>";
                    echo "<p class='text-gray-500 mb-2'>Genres: " . htmlspecialchars($row["Genres"]) . "</p>";
                    echo "<p class='text-gray-500 mb-2'>Regisseur: " . htmlspecialchars($row["Regisseurnaam"]) . "</p>";
                    echo "<p class='text-gray-500 mb-2'>Releasejaar: " . htmlspecialchars($row["Releasejaar"]) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-gray-500'>Geen films gevonden.</p>";
            }
            ?>
        </div>
    </div>
    <script>
        function resetForm() {
            document.getElementById("filterForm").reset();
            window.location.href = window.location.href.split('?')[0];
        }
    </script>
    <footer class="bg-gray-200 py-4">
        <div class="container mx-auto">
            <p class="text-center text-gray-600">&copy; 2024 Alle rechten voorbehouden <a href="#" class="text-blue-600">D&R Filmen</a></p>
            <img src="images/footer-bottom-img.png" alt="Online banking Companies" class="mx-auto mt-4" style="width: 200px;">
        </div>
    </footer>
</body>
</html>