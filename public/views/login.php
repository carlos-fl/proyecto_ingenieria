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
    <link rel="stylesheet" href="/public/views/css/style.css" />
    <link rel="stylesheet" href="/public/views/css/loginStyle.css" />
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <div>
                    <a href="/public/index.php">
                        <img style="width: 45%" src="assets/img/logo-unah.png" alt="Logo UNAH" />
                    </a>
                </div>
                <div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                        aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarText">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="/public/index.php">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./login.php">Estudiantes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./login.php">Docentes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="modal"
                                    data-bs-target="#reviewerModal">Revisores</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./login.php">Matrícula</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./login.php">Biblioteca</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <div class="top-bar">
            <nav class="navbar bg-body-tertiary" id="header-bar">
                <div class="container-fluid">
                    <h1 class="navbar-brand">Inicio de Sesión</h1>
                </div>
            </nav>
        </div>
    </header>
    
    <main>
        <login-form action="src/services/auth/controllers/login.php" imgSource="assets/img/unah.png" imgAlt="Logo UNAH"
            heading="Inicio de Sesión" message="Debes autenticarte para usar este servicio">
        </login-form>
    </main>

    <footer>
        <nav class="navbar bg-body-tertiary">
            <div class="container-fluid" id="footer-cf">
                <p>&copy; 2024 Universidad Nacional Autónoma de Honduras</p>
            </div>
        </nav>
    </footer>
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
                        <a href="./login.php"><button type="button" class="btn btn-warning mx-3" id="admissionReviewer">
                                Revisor de Solicitud de Admision
                            </button></a>
                        <a href="./login.php"><button type="button" class="btn btn-warning mx-3" id="examReviewer">
                                Revisor de Examen de Admision
                            </button></a>
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
</body>

</html>