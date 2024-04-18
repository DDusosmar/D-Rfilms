<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
</head>
<body class="bg-gray-100">
<nav class="bg-transparent p-5 flex justify-between items-center">
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
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">About Us</h1>
        <div class="flex flex-col md:flex-row gap-8">
            <div class="md:w-1/2">
                <img src="https://via.placeholder.com/500" alt="Company Logo" class="rounded-lg">
            </div>
            <div class="md:w-1/2">
                <p class="text-lg mb-4">Welkom bij D&R Films, jouw bestemming voor filmvermaak en informatie! Wij zijn Denzel en Rico, een dynamisch duo van programmeurs met een gedeelde passie voor cinema.</p>
                <p class="text-lg mb-4">Als fervente filmliefhebbers besloten we onze krachten te bundelen en een plek te creëren waar filmfans zoals jij een ware schat aan films kunnen ontdekken en verkennen. Met onze gezamenlijke expertise in programmeren hebben we D&R Films tot leven gebracht, een platform dat niet alleen het nieuwste filmnieuws en recensies biedt, maar ook een uitgebreide verzameling films om van te genieten.</p>
                <p class="text-lg mb-4">Bij D&R Films geloven we in de kracht van film om te informeren, inspireren en vermaken. Of je nu op zoek bent naar een spannende thriller, een hartverwarmend drama of een verfrissende komedie, wij hebben voor elk wat wils. Onze toegewijde team streeft er voortdurend naar om onze site te verbeteren en je de beste filmervaring te bieden die je verdient.</p>
                <p class="text-lg mb-4">Dus, waar wacht je nog op? Duik in de wereld van D&R Films en laat je meevoeren op een onvergetelijke filmische reis. Het is tijd om je popcorn klaar te maken en jezelf onder te dompelen in de magie van de zevende kunst!</p>
                <p class="text-lg">Veel kijkplezier,<br>
                Denzel en Rico,<br>
                D&R Films</p>
            </div>
        </div>
    </div>
    
    <footer class="bg-gray-200 py-4">
        <div class="container mx-auto">
            <p class="text-center text-gray-600">&copy; 2024 Alle rechten voorbehouden <a href="#" class="text-blue-600">D&R Filmen</a></p>
            <img src="images/footer-bottom-img.png" alt="Online banking Companies" class="mx-auto mt-4" style="width: 200px;">
        </div>
    </footer>
</body>
</html>
