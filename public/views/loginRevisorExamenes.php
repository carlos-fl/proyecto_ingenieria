<?php ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="icon" type="image/png" href="assets/img/UNAH-escudo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link href="https://fonts.cdnfonts.com/css/helvetica-neue-5" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/loginStyles.css" />
</head>

<body>
    <header>
        <navbar-unah></navbar-unah>
        <div class="top-bar">
            <nav class="navbar bg-body-tertiary" id="header-bar">
                <div class="container-fluid">
                    <h1 class="navbar-brand">Inicio de Sesión de Revisor de Examenes</h1>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <login-form action="src/services/auth/controllers/login.php" imgSource="assets/img/unah.png" imgAlt="Logo UNAH"
            heading="Bienvenido Revisor" message="Debe autenticarse como revisor para usar este servicio" id="login-revisorExm"
            data-redirect-success="revisorExamenes.php">
        </login-form>
    </main>

    <footer-unah></footer-unah>
    <pop-up
        id="popUp"
        imgsource="assets/img/crossmark.png"
        popupclass="fail-popup"
        duration="4000"
        message="">
    </pop-up>

    <!-- Ventana modal -->
    <div class="modal fade" id="reviewerModal" tabindex="-1" aria-labelledby="reviewerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewerModalLabel">Revisores</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="btn-group" role="group" aria-label="Select reviewer type">
                        <a href="loginRevisorSolicitudesAd.php"><button type="button" class="btn btn-warning mx-3" id="admissionReviewer">Revisor de Solicitud de Admision</button></a>
                        <a href="loginRevisorExamenes.php"><button type="button" class="btn btn-warning mx-3" id="examReviewer">Revisor de Examen de Admision</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="js/login.js"></script>
    <script type="module" src="js/components/login-form.js"></script>
    <script type="module" src="js/components/pop-up.js"></script>
    <script type="module" src="js/components/navbar.js"></script>
    <script type="module" src="js/components/footer.js"></script>
    <script>
        document.querySelectorAll("login-form").forEach((form) => {
            form.addEventListener("login-form:success", () => {
                const redirectUrl = form.getAttribute("data-redirect-success");
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                }
            });
        });
    </script>

</body>

</html>