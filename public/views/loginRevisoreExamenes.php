<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="assets/img/UNAH-escudo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.cdnfonts.com/css/helvetica-neue-5" rel="stylesheet">
    <link rel="stylesheet" href="css/revisoreExamenesStyle.css">
</head>
<body style="background-color: var(--secondary-color);">
    <section id="loginSection" class="container d-flex justify-content-center">
        <div id="loginComponent" class="outline-grey">
            <img id="heroUnah" src="assets/img/unah.png" alt="#UNAH" >
            <div id="loginBox" class="pt-3 ps-3 pe-5">
                <h3 class="fw-bold">Servicios al Revisor de Examenes</h3>
                <small>Para acceder a los servicios de revisor debes autenticarte</small>
                <p class="mt-5">Ingrese su correo y contraseña</p>
                <hr>
                <div id="loginForm" class="mt-5">
                    <div>
                        <label for="email">Correo</label>
                        <input id="email" placeholder="example@unah.edu.hn" type="email" class="form-control">
                    </div>
                    <div class="mt-2">
                        <label for="password">Contraseña</label>
                        <input id="password" placeholder="Contraseña" type="password" class="form-control">
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="views/revisoreExamenes.php"><button class="btn btn-primary mt-3">Ingresar</button></a>
                    </div>
                    <small class="float-end mt-2"><a href="" class="text-danger no-underline">Olvidé mi contraseña</a></small>
                </div>
                <br>
                <div class="text-center mt-3">
                    <a href="admisiones.php" id="back-link">← Regresar</a>
                </div>
            </div>
        </div>
    </section>

<!-- Ventana modal -->
    <reviewer-modal tag-id="reviewer" application="loginRevisorSolicitudesAd.php" exam="./admissions/uploadExamResults/login.php"></reviewer-modal>
<!---->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="module" src="js/components/reviewerModal.js"></script>
</body>
</html>
