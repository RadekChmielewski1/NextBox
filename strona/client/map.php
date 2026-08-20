<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>NextBox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="style/addional.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body class="bg-light">
<?php
include 'header.php';
?>
<main class="d-flex justify-content-center align-items-center mx-auto h-100">
    <div class="w-50 main-box align-items-center">
        <h1 class="text-center">Find your <b>NextBox</b></h1>
        <div id="map" class="h-100"></div>
    </div>
</main>
<footer class="mt-auto text-dark text-center">
    <p>Made with &#10084;&#65039; in Gliwice!</p>
</footer>

<!-- JavaScript Bootstrap (wymaga Popper) -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

<script src="scripts/map.js" defer></script>
</body>
</html>