<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
<main class="w-100">
    <div class="row">
        <header class="w-100 text-center">Control Panel</header>
    </div>
    <div class="container">
        <div class="row align-items-start">
            <div class="col">
                <h2 class="text-center">Open:</h2>
                <div class="pt-2 d-flex justify-content-evenly">
                    <button href="#" class="btn btn-primary" onclick="openLocker(1)">Small locker</button>
                    <button href="#" class="btn btn-primary" onclick="openLocker(2)">Medium locker</button>
                    <button href="#" class="btn btn-primary" onclick="openLocker(3)">Large locker</button>
                </div>
            </div>
            <div class="col">
                <h2 class="text-center">Medical box info:</h2>
                <div class="pt-2 d-flex justify-content-evenly">
                    <div id="peliter"><h5>Temperatura peltier: <p id="tempText"></p></h5></div>
                    <div id="skrytka"><h5>Temperatura skrytki: <p id="tempSkrytkaText">5</p></h5></div>
                </div>
            </div>
        </div>
</main>
<script src="scripts/controlPanel.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-primary">
            <div class="modal-body text-center text-white p-4">
                <h4 class="text-center">Opening box...</h4>
            </div>
        </div>
    </div>
</div>