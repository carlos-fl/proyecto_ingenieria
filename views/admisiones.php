<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripciones</title>
    <link rel="icon" type="image/png" href="assets/img/UNAH-escudo.png">
    <link rel="stylesheet" href="views/css/admisionesStyles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/6130fb0810.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <img src="assets/img/logo-unah.png" alt="">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="views/construccion.php">Estudiantes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="views/construccion.php">Docentes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#reviewerModal">Revisores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="views/adminLogin.php">Administradores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="views/index.php">Registro</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="views/construccion.php">Biblioteca</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="top-bar">
            <div>
                <nav class="navbar bg-body-tertiary" id="header-bar">
                    <div class="container-fluid d-flex justify-content-between align-items-center">
                        <h1 class="navbar-brand">
                            Direccion del Sistema de Admisiones
                        </h1>
                        <div id="carouselSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="/assets/img/banner-2025-08-PAC.jpg" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="/assets/img/banner-pruebas2-11.jpg" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="/assets/img/banner-pruebas2-12.jpg" class="d-block w-100" alt="...">
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <section class="main-navbar">
            <div class="container text-center">
                <div class="row align-items-center">
                    <div class="col">
                        <a href="views/inscripciones.php">
                            <div class="custom-box">Inscripciones</div>
                        </a>
                    </div>
                    <div class="col">
                        <a href="views/resultados.php">
                            <div class="custom-box">Resultados</div>
                        </a>
                    </div>
                    <div class="col">
                        <div class="custom-box" id="reviewersBox">Revisores</div>
                    </div>
                    <div class="col">
                        <a href="views/construccion.php">
                            <div class="custom-box">Preguntas Frecuentes</div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="container text-center">
                <div class="row row-cols-4">
                    <a href="views/requisitosCarreras.php"><div class="col"><img src="/assets/img/Odontologia-color.png" alt=""></div></a>
                    <a href="views/construccion.php"><div class="col"><img src="/assets/img/Ciencias-color.png" alt=""></div></a>
                    <a href="views/construccion.php"><div class="col"><img src="/assets/img/Ciencias-Economicas-color.png" alt=""></div></a>
                    <a href="views/construccion.php"><div class="col"><img src="/assets/img/Ciencias-Juridicas-color.png" alt=""></div></a>
                    <a href="views/construccion.php"><div class="col"><img src="/assets/img/Ciencias-Sociales-color.png" alt=""></div></a>
                    <a href="views/construccion.php"><div class="col"><img src="/assets/img/Ingenieria-color.png" alt=""></div></a> 
                </div>
              </div>
        </section>
    </main>
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
                <a href="views/loginSolicitudesAdmision.php"><button type="button" class="btn btn-warning mx-3" id="admissionReviewer">Revisor de Solicitud de Admision</button></a>
                <a href="views/construccion.php"><button type="button" class="btn btn-warning mx-3" id="examReviewer">Revisor de Examen de Admision</button></a>  
            </div>
            </div>
        </div>
        </div>
    </div>  
<!---->
    <footer>
        <nav class="navbar fixed-bottom bg-body-tertiary">
            <div class="container-fluid">
              <p>&copy; 2024 Universidad Nacional Autónoma de Honduras</p>
            </div>
          </nav>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.getElementById('reviewersBox').addEventListener('click', function() {
            var myModal = new bootstrap.Modal(document.getElementById('reviewerModal'));
            myModal.show();
        });
    </script>
</body>
</html>