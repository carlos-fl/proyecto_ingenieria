<?php ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Revisión de Solicitudes</title>
  <link rel="icon" type="image/png" href="assets/img/UNAH-escudo.png" />
  <link rel="stylesheet" href="/public/views/css/style.css">
  <link rel="stylesheet" href="/public/views/css/solicitudesAdmisionStyles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/6130fb0810.js" crossorigin="anonymous"></script>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <div>
          <a href="/public/index.php"><img style="width: 45%;" src="assets/img/logo-unah.png" alt="Logo UNAH" /></a>
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
                <a class="nav-link" href="/public/views/login.php">Estudiantes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/public/views/login.php">Docentes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#reviewerModal">Revisores</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/public/views/login.php">Matricula</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/public/views/login.php">Biblioteca</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
    <div class="top-bar">
      <div>
        <nav class="navbar bg-body-tertiary" id="header-bar">
          <div class="container-fluid">
            <h1 class="navbar-brand">
              Revisión de Solicitudes
            </h1>
          </div>
        </nav>
      </div>
  </header>
  <main>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 75vh;">
      <div class="card shadow" id="form-card" style="max-width: 900px; width: 100%;">
        <div class="card-body">
          <div id="counter" class="text-center mb-3"></div>
          <div id="requestDetails" class="mb-3"></div>
        </div>
      </div>
    </div>
  </main>
  <footer>
    <nav class="navbar bg-body-tertiary">
      <div class="container-fluid" id="footer-cf">
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
            <a href="/public/views/login.php"><button type="button" class="btn btn-warning mx-3" id="admissionReviewer">Revisor de Solicitud de Admision</button></a>
            <a href="/public/views/login.php"><button type="button" class="btn btn-warning mx-3" id="examReviewer">Revisor de Examen de Admision</button></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!---->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/solicitudesAdmision.js"></script>
</body>

</html>