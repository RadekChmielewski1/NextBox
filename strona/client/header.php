<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
}
?>
<header class="p-3 mb-3 border-bottom text-bg-dark fixed-top">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-evenly mb-md-0">
                <li>
                    <a href="index.php" class="nav-link px-2 text-secondary">Home</a>
                </li>
                <li>
                    <a href="aboutus.php" class="nav-link px-2 text-white">About us</a>
                </li>
                <li>
                    <a href="map.php" class="nav-link px-2 text-white">Find your NextBox</a>
                </li>
                <li>
                    <a href="contact.php" class="nav-link px-2 text-white">Contact</a>
                </li>
            </ul>
                <?php
                if(!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true) {
                    echo '<a class="btn btn-warning me-1" href="loginpanel.php">Log in</a>';
                }
                else{
                    echo '<a class="btn btn-info me-1" href="panel.php">Panel</a>';
                    echo '<a class="btn btn-warning me-1" href="logout.php">Log out</a>';
                }
                if(isset($_SESSION['rola'])) {
                    if ($_SESSION['rola'] == 'KURIER' || $_SESSION['rola'] == 'ADMIN') {
                        echo "<div class='text-end'><a class='btn btn-warning me-1' href='delivery.php'>Delivery panel</a></div>";
                    }
                    if ($_SESSION['rola'] == 'ADMIN') {
                        echo "<div class='text-end'><a class='btn btn-danger me-1' href='admin.php'>Admin panel</a></div>";
                    }
                }
                ?>
        </div>
    </div>
</header>