<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Movies.cc/Upload Film</title>
</head>
<?php
$servername = "localhost";
$username = "dbuser";
$password = "zGfaIW9YDH1GFa2e";
$dbname = "movies_cc_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

function escape($data)
{
    global $conn;
    return $conn->real_escape_string($data);
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["create"]) && $_POST["create"] == 1) {
        if (
            !empty($_POST["titel"]) &&
            !empty($_POST["beschrijving"]) &&
            !empty($_POST["genres"]) &&
            ((!empty($_POST["regisseur_select"]) && $_POST["regisseur_select"] != "Voer een Regisseur in") ||
                (!empty($_POST["regisseur_input"]))) &&
            !empty($_POST["releasejaar"]) &&
            !empty($_POST["uploadedby"]) &&
            isset($_POST['uren']) &&
            isset($_POST['minuten'])
        ) {
            $titel = escape($_POST["titel"]);
            $beschrijving = escape($_POST["beschrijving"]);
            $genres = $_POST["genres"];

            $uren = intval($_POST['uren']);
            $minuten = intval($_POST['minuten']);
            $totale_minuten = ($uren * 60) + $minuten;

            $regisseur = "";
            if (!empty($_POST["regisseur_select"]) && $_POST["regisseur_select"] != "Voer een Regisseur in") {
                $regisseur = escape($_POST["regisseur_select"]);
            } elseif (!empty($_POST["regisseur_input"])) {
                $regisseur_input = escape($_POST["regisseur_input"]);
                $check_query = "SELECT Regisseurnaam FROM regisseur WHERE Regisseurnaam = '$regisseur_input'";
                $check_result = $conn->query($check_query);
                if ($check_result->num_rows > 0) {
                    $row = $check_result->fetch_assoc();
                    $regisseur = $row['Regisseurnaam'];
                } else {
                    $insert_query = "INSERT INTO regisseur (Regisseurnaam) VALUES ('$regisseur_input')";
                    if ($conn->query($insert_query) === TRUE) {
                        $regisseur = $regisseur_input;
                    } else {
                        echo "<script>alert('Fout bij het toevoegen van de regisseur: " . $conn->error . "');</script>";
                        exit();
                    }
                }
            }

            $releasejaar = escape($_POST["releasejaar"]);
            $uploadedby = escape($_POST["uploadedby"]);

            $sql = "INSERT INTO films (Titel, Beschrijving, Regisseurnaam, Releasejaar, Speeltijd, UploadedBy) 
                        VALUES ('$titel', '$beschrijving', '$regisseur', '$releasejaar', '$totale_minuten', '$uploadedby')";

            if ($conn->query($sql) === TRUE) {
                $nieuweFilmID = $conn->insert_id;

                foreach ($genres as $genreID) {
                    $genreID = escape($genreID);

                    $sql = "INSERT INTO film_genre (FilmID, GenreID) 
                                VALUES ('$nieuweFilmID', '$genreID')";

                    if ($conn->query($sql) !== TRUE) {
                        echo "<script>alert('Fout bij het toevoegen van genre: " . $conn->error . "');</script>";
                        exit();
                    }
                }

                echo "<script>alert('Nieuwe film succesvol toegevoegd');</script>";
                //header("Location: ".$_SERVER['PHP_SELF']);
                //exit();
            } else {
                echo "<script>alert('De aanvraag is niet gelukt: " . $conn->error . ". SQL-query: " . $sql . "');</script>";
            }
        } else {
            echo "<script>alert('Vul alle vereiste velden in');</script>";
        }
    }
}
?>
<body>
    <div  class="bg-[url('Images/bg.png')] bg-center bg-cover">
    <nav class="bg-transparent p-5 flex justify-between items-center mb-10">
        <a href=""><img src="Images/popcorn.svg" alt="Movies.cc Logo" class="w-10"></a>
        <h1 class="text-3xl font-bold text-white">D&R Films</h1>
    </nav>
    <section class="min-h-screen flex justify-center items-center">
        <div class="backdrop-filter backdrop-blur-lg bg-gray-100 bg-opacity-25 h-auto w-96 p-8 rounded-lg shadow-md">
            <div>
                <h1 class="text-3xl font-bold text-center text-white mb-6">Upload een Film</h1>
                <div>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-4">
                        <!-- Form inputs -->
                        <div>
                            <label for="titel" class="text-white">Titel:</label><br>
                            <input type="text" id="titel" name="titel" class="w-full px-4 py-2 bg-white bg-opacity-50 border rounded-lg focus:outline-none focus:border-blue-500"><br>
                        </div>
                        <div>
                            <label for="beschrijving" class="text-white">Beschrijving:</label><br>
                            <textarea id="beschrijving" name="beschrijving" class="w-full px-4 py-2 bg-white bg-opacity-50 border rounded-lg focus:outline-none focus:border-blue-500"></textarea><br>
                        </div>
                        <div>
                            <label for="genre" class="text-white">Genres:</label><br>
                            <select name="genres[]" multiple id="genre_select" class="w-full px-4 py-2 bg-white bg-opacity-50 border rounded-lg focus:outline-none focus:border-blue-500">
                                <?php
                                $query = "SELECT * FROM genre";
                                $result = $conn->query($query);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row["GenreID"] . "'>" . $row["Genrenaam"] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label for="regisseur" class="text-white">Regisseur:</label><br>
                            <select id="regisseur_select" name="regisseur_select" onchange="toggleInput()" class="w-full px-4 py-2 bg-white bg-opacity-50 border rounded-lg focus:outline-none focus:border-blue-500">
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
                                <option value="Voer een Regisseur in">Voer een Regisseur in</option>
                            </select><br>
                            <input type="text" id="regisseur_input" name="regisseur_input" style="display:none;" class="w-full px-4 py-2 bg-white bg-opacity-50 border rounded-lg focus:outline-none focus:border-blue-500">
                            <script>
                                function toggleInput() {
                                    var selectBox = document.getElementById('regisseur_select');
                                    var inputField = document.getElementById('regisseur_input');
                                    if (selectBox.value === 'Voer een Regisseur in') {
                                        inputField.style.display = 'block';
                                        inputField.focus();
                                    } else {
                                        inputField.style.display = 'none';
                                    }
                                }
                            </script>
                        </div>
                        <div>
                            <label for="releasejaar" class="text-white">Releasejaar:</label><br>
                            <input type="date" id="releasejaar" name="releasejaar" min="2000" class="w-full px-4 py-2 bg-white bg-opacity-50 border rounded-lg focus:outline-none focus:border-blue-500"><br>
                        </div>
                        <div>
                            <label for="uren" class="text-white">Uren:</label><br>
                            <input type="number" id="uren" name="uren" min="0" class="w-full px-4 py-2 bg-white bg-opacity-50 border rounded-lg focus:outline-none focus:border-blue-500"><br>
                        </div>
                        <div>
                            <label for="minuten" class="text-white">Minuten:</label><br>
                            <input type="number" id="minuten" name="minuten" min="0" max="59" class="w-full px-4 py-2 bg-white bg-opacity-50 border rounded-lg focus:outline-none focus:border-blue-500"><br>
                        </div>
                        <div>
                            <label for="uploadedby" class="text-white">Geüpload door:</label><br>
                            <input type="text" id="uploadedby" name="uploadedby" class="w-full px-4 py-2 bg-white bg-opacity-50 border rounded-lg focus:outline-none focus:border-blue-500"><br>
                        </div>
                        <input type="hidden" name="create" value="1"><br>
                        <input type="submit" value="Aanmaken" class="w-full px-4 py-2 bg-white bg-opacity-50 border rounded-lg focus:outline-none focus:border-blue-500">
                    </form>
                </div>
            </div>
        </div>
    </section>
    <footer class="py-4">
        <div class="container mx-auto">
            <p class="text-center text-gray-600">&copy; 2024 Alle rechten voorbehouden <a href="#" class="text-blue-600">D&R Filmen</a></p>
            <img src="Images/footer-bottom-img.png" alt="Online banking Companies" class="mx-auto mt-4" style="width: 200px;">
        </div>
    </footer>
    </div>
    <script>
        document.getElementById('genre_select').addEventListener('change', function() {
            var selectedOptions = [];
            var options = this && this.options;
            var opt;

            for (var i = 0, len = options.length; i < len; i++) {
                opt = options[i];

                if (opt.selected) {
                    selectedOptions.push(opt.textContent || opt.innerText);
                }
            }
            document.getElementById('selected_genres').innerHTML = "Geselecteerde genres: " + selectedOptions.join(", ");
        });
    </script>
</body>

</html>
