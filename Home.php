<!DOCTYPE html>
<html lang="nl">
    <?php
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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Movies.cc - Welcome</title>
    <style>
        /* Custom styles */
        .split-background {
            background: linear-gradient(to top, rgba(255, 255, 255, 0) 50%, rgba(0, 0, 0, 0.8) 100%), url('Images/bg1.png') center/cover;
        }
    </style>
</head>
<body class="bg-gray-100">
        <nav class="split-background bg-transparent  flex justify-between items-center mb-10 p-20">
            <a href=""><img src="Images/popcorn.svg" alt="Movies.cc Logo" class="w-10"></a>
            <h1 class="text-3xl font-bold text-white">D&R Films</h1>
            <form method="GET" action="Search.php">
                <input type="text" name="search" placeholder="Search..." class="border border-gray-300 rounded-md py-2 px-4">
                <input type="submit" value="Search" class="bg-blue-500 text-white py-2 px-4 rounded-md ml-2">
            </form>
        </nav>
    <main >
        <section class="py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-6 ">Filmen, TV Shows, & Meer.</h1>
                <p class="">People choose free movie sites for a variety of reasons. <br/> Many of us wish to cut back on excessive spending. Some people don’t even watch that many movies and TV shows to pay a monthly membership for premium movie service.<br/> Furthermore, many people prefer free entertainment. Whatever your motive, you should only visit safe sites.<br/> There are thousands of free movie websites on the Internet, but only a handful are secure. Free sites rely on advertisements for revenue, and advertisements might include viruses and spyware.<br/> That’s why people choose premium streaming services, it’s solely for security reasons.<br/> Today, we’d like to save you from the headache of having to search for the perfect free movie sites by introducing you to D&R Films.<br/>D&R Films presents no harm to your device or identity because it is ad-free.<br/> The website also has a large variety of movies available for free streaming, HD resolution, a quick loading speed, continuous content updates, and many other impressive features.<br/> Saving a penny equals earning a penny. <br/>Earn money by viewing movies online for free on D&R Films! 
                            <br/></p>
                       
                            <?php
                                session_start();

                                // Check if the user is logged in
                                if (isset($_SESSION["gebruikersnaam"])) {
                                    // Display welcome message
                                    echo "<div>Welkom, " . $_SESSION["gebruikersnaam"] . "!</div>";
                                    echo '<div><a href="./Films.php"><button class="mt-6 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Bekijk De site</button></a></div>';
                                    echo '<div>Wil je met een ander Account inloggen?<br>Doe dat <a href="./Login.html">hier</a>.</div>';
                                    
                                    // Check if the user's email contains the word "admin"
                                    if (strpos($_SESSION["gebruikersnaam"], 'admin') !== false) {
                                        echo "<div><a href=\"./adminFilms.php\"><button class=\"mt-6 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600\">Beheer Films</button></a></div>";
                                    }
                                } else {
                                    // If the user is not logged in
                                    echo "<div>Wil je de films bekijken, meld je dan aan om verder te gaan.</div>";
                                    echo "<div><a href=\"./Login.html\"><button class=\"mt-6 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600\">Log In / Sign Up</button></a></div>";
                                }
                            ?>
            </div>
        </section>
    </main>
    <footer class="bg-gray-200 py-4">
        <div class="container mx-auto">
            <p class="text-center text-gray-600">&copy; 2024 Alle rechten voorbehouden <a href="#" class="text-blue-600">D&R Filmen</a></p>
            <img src="images/footer-bottom-img.png" alt="Online banking Companies" class="mx-auto mt-4" style="width: 200px;">
        </div>
    </footer>

    <!-- Script for sliding text animation -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const span = document.querySelector('.animate-slidein');
            span.classList.add('transition-transform', 'duration-1000', 'delay-1000', 'ease-in-out', 'transform', 'translate-x-full');
        });
    </script>
</body>
</html>
