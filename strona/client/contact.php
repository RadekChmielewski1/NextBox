<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>NextBox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="style/addional.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php
include 'header.php';
?>
<main class="d-flex justify-content-center align-items-center mx-auto h-100"  style="margin-top: 100px;">
    <div class="w-50 main-box align-items-center">
        <h1 class="text-center">Contact Information</h1>
        <div class="text-center">
            <div class="row">

                <div class="col-md-6">
                    <h3>Phone:</h3>
                    <p>Hotline: <a href="tel:+48787329887">+48 787 329 887</a></p>
                    <p>Sales: <a href="tel:+48578043702">+48 578 043 702</a></p>
                    <p>Support: <a href="tel:+48393441282">+48 393 441 282</a></p>
                </div>

                <div class="col-md-6">
                    <h3>E-mail</h3>
                    <p>General: <a href="mailto:kw311915@student.polsl.pl">kw311915@student.polsl.pl</a></p>
                    <p>Sales: <a href="mailto:kn311841@student,polsl.pl">kn311841@student,polsl.pl</a></p>
                    <p>Support: <a href="mailto:rc311594@student.polsl.pl">rc311594@student.polsl.pl</a></p>
                </div>

                <div class="col-md-6">
                    <h3>Address</h3>
                    <p>ul.Akademicka 16</p>
                    <p>44-100 Gliwice</p>
                    <p>Poland</p>
                </div>

                <div class="col-md-6">
                    <h3>Business Hours</h3>
                    <p>Monday - Friday: 8:00 AM - 6:00 PM</p>
                    <p>Saturday: 9:00 AM - 2:00 PM</p>
                    <p>Sunday: Closed</p>
                </div>
                </p>
            </div>
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
