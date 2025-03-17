<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripciones</title>
    <link rel="icon" type="image/png" href="assets/img/UNAH-escudo.png">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/admisionesStyles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/6130fb0810.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <navbar-unah></navbar-unah>
        <div class="top-bar">
            <div>
                <nav class="navbar bg-body-tertiary" id="header-bar">
                    <div class="container-fluid d-flex justify-content-between align-items-center" id="banner-container">
                        <h1 class="navbar-brand">
                            Direccion del Sistema de Admisiones
                        </h1>
                        <div id="carouselSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="assets/img/banner-2025-08-PAC.jpg" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="assets/img/banner-pruebas2-11.jpg" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="assets/img/banner-pruebas2-12.jpg" class="d-block w-100" alt="...">
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
                        <a href="inscripciones.php">
                            <div class="custom-box">Inscripciones</div>
                        </a>
                    </div>
                    <div class="col">
                        <a href="resultados.php">
                            <div class="custom-box">Resultados</div>
                        </a>
                    </div>
                    <div class="col">
                        <div class="custom-box" id="reviewersBox">Revisores</div>
                    </div>
                    <div class="col">
                        <a href="construccion.php">
                            <div class="custom-box">Preguntas Frecuentes</div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="container text-center">
                <div class="row row-cols-4">
                    <a href="requisitosCarreras.php">
                        <div class="col"><img src="assets/img/Odontologia-color.png" alt=""></div>
                    </a>
                    <a href="construccion.php">
                        <div class="col"><img src="assets/img/Ciencias-color.png" alt=""></div>
                    </a>
                    <a href="construccion.php">
                        <div class="col"><img src="assets/img/Ciencias-Economicas-color.png" alt=""></div>
                    </a>
                    <a href="construccion.php">
                        <div class="col"><img src="assets/img/Ciencias-Juridicas-color.png" alt=""></div>
                    </a>
                    <a href="construccion.php">
                        <div class="col"><img src="assets/img/Ciencias-Sociales-color.png" alt=""></div>
                    </a>
                    <a href="construccion.php">
                        <div class="col"><img src="assets/img/Ingenieria-color.png" alt=""></div>
                    </a>
                </div>
            </div>
        </section>
    </main>
    <footer-unah></footer-unah>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.getElementById('reviewersBox').addEventListener('click', function() {
            var myModal = new bootstrap.Modal(document.getElementById('reviewerModal'));
            myModal.show();
        });
    </script>
    <script src="js/components/navbar.js"></script>
    <script src="js/components/footer.js"></script>
</body>

</html>