<!DOCTYPE html>
<html>
<head>
    <title>NextBox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="style/addional.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center py-4 bg-dark">
<main class="form-signin w-100 m-auto">
    <form id="loginForm" method="post" action="login.php" class="login">
        <h1 class="h3 mb-3 fw-normal text-white">Please log in</h1>]
        <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required autocomplete="off">
        </div>
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Username" aria-describedby="basic-addon1">
        </div>
        <button class="btn bg-warning mb-3 w-100" type="submit">Log in</button>
    </form>
    <button id="buttonLoginForm" class="loginForm btn bg-info w-100 mb-3" type="text" onclick="startRegister()">Sign up</button>
    <form id="registerForm" method="post" action="register.php" class="register" style="display: none">
        <h1 class="h3 mb-3 fw-normal text-white">Registration</h1>]
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="usernameReg" placeholder="Username" aria-label="Username">
        </div>
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="nameReg" placeholder="Name" aria-label="Name">
        </div>
        <div class="input-group mb-3">
            <input type="email" class="form-control" name="emailReg" placeholder="Email" aria-label="Email">
        </div>
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="surnameReg" placeholder="Surname" aria-label="Surname">
        </div>
        <div class="input-group mb-3">
            <input type="password" class="form-control" name="passwordReg" placeholder="Password" aria-label="Password">
        </div>
        <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Repeat password" aria-label="Password">
        </div>
        <button class="btn bg-warning w-100" type="submit">Register</button>
    </form>

</main>
<script src="scripts/register.js" defer></script>
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

</body>
</html>