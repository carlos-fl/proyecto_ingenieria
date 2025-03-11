<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisitos de Carrera</title>
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
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
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
                            Requisitos
                        </h1>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <main class="container" id="main-requirements">
        <h1 class="mb-4">Requisitos</h1>
        <div id="card-requirements" class="card">
        <div class="card-body">
            <img src="assets/img/Odontologia-color.png"/>
            <div>
            <h5 class="card-title text-primary">Odontologia</h5>
            <p class="card-text">
                <strong>Requisitos:</strong><br />
                - PHUMA: 1200<br />
                - PCNS: 900<br />
            </p>
            </div>
        </div>
        </div>
        <div class="text-center mt-3">
            <a href="views/admisiones.php" id="back-link">← Regresar</a>
        </div>   
    </main>
    <footer>
        <nav class="navbar fixed-bottom bg-body-tertiary">
            <div class="container-fluid">
              <p>&copy; 2024 Universidad Nacional Autónoma de Honduras</p>
            </div>
          </nav>
    </footer>

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
                    <a href="views/loginRevisoreExamenes.php"><button type="button" class="btn btn-warning mx-3" id="examReviewer">Revisor de Examen de Admision</button></a>  
                </div>
                </div>
            </div>
            </div>
      </div>  
<!---->
      
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>