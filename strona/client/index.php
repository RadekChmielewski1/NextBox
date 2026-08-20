<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>NextBox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="style/addional.css" rel="stylesheet">
    <style>
        /* Definicja animacji */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(20px); /* Tekst lekko się uniesie */
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .tekst {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>
</head>
<body class="bg-light">
<?php
include 'header.php';
?>
<div class="h-50 mb-5"> . </div>
<main class="w-75 d-flex justify-content-center align-items-center mx-auto h-100 row main-index mt-5">
    <div class="w-50 main-box align-items-center col-1 h-100 tekst">
        <h1 class="text-center">NextBox</h1>
        <p class="lead text-center">
            At  <b>NextBox</b>, we believe that smart technology should make everyday life simpler.
            Our mission is to revolutionize the way people send and receive parcels — by combining innovation, automation, and user-friendly design.

            Born from a passion for modern logistics and intelligent systems,  <b>NextBox</b> offers a secure, efficient, and eco-friendly solution for parcel delivery and collection.
            We focus on creating technology that connects people and simplifies processes — whether for individuals, couriers, or businesses.

            <b>NextBox</b> isn’t just a locker.
            It’s the next generation of smart delivery
        </p>
    </div>
    <div class="w-50 main-box align-items-center col-1 h-100 tekst">
        <img src="img/1.png" class="w-100 shadow">
        </p>
    </div>
    <div class="w-50 main-box align-items-center col-1 h-100 tekst">
        <img src="img/2.png" class="w-100 shadow">
        </p>
    </div>
    <div class="w-50 main-box align-items-center col-1 h-100 tekst">
        <h1 class="text-center">Logistic centers</h1>
        <p class="lead text-center">
            At <b>NextBox</b>, every parcel follows a seamless logistics process designed for speed,
            safety, and efficiency. Each shipment is registered and tracked in real time,
            then automatically sorted at our logistics centers using smart algorithms.
            Parcels are securely delivered to local lockers, protected by multi-level access systems.
            Once delivered, customers receive instant notifications with pickup details and can collect
            their parcels 24/7. With advanced automation and transparent tracking,
            <b>NextBox</b> ensures a smooth and reliable delivery experience from start to finish.
        </p>
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

</body>
</html>