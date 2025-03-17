<?php ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Registro</title>
  <link rel="icon" type="image/png" href="views/assets/img/UNAH-escudo.png">
  <link rel="stylesheet" href="views/css/styles.css">
  <link rel="stylesheet" href="views/css/indexStyles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/6130fb0810.js" crossorigin="anonymous"></script>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <div>
          <a href="index.php"><img style="width: 45%;" src="views/assets/img/logo-unah.png" alt="Logo UNAH" /></a>
        </div>
        <div>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="index.php">Inicio</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./views/loginEstudiantes.php">Estudiantes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./views/loginDocentes.php">Docentes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#reviewerModal">Revisores</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./views/login.php">Matricula</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./views/admisiones.php">Admisiones</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./views/loginEstudiantes.php">Biblioteca</a>
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
              Sistema de Registro
            </h1>
          </div>
        </nav>
      </div>
  </header>
  <main>
    <div class="banner-container">
      <img src="views/assets/img/1.jpg" alt="Descripción" class="img-fluid">
    </div>
    </div>
    <section class="box">
      <nav class="user-selection">
        <div class="row w-100">
          <div class="col">
            <a href="views/loginEstudiantes.php">
              <div><i class="fa-solid fa-user"></i></div>
              <strong>Estudiantes</strong><br>Ingresa como estudiante
            </a>
          </div>
          <div class="col">
            <a href="./views/loginDocentes.php">
              <div><i class="fa-solid fa-user-tie"></i></div>
              <strong>Docentes</strong><br>Ingresa como docente
            </a>
          </div>
          <div class="col">
            <a href="views/admisiones.php">
              <div><i class="fa-solid fa-file"></i></i></div>
              <strong>Admisiones</strong><br>Ingresa a admisiones
            </a>
          </div>
        </div>
      </nav>
      <div class="aside-container">
        <aside class="image-container">
          <img src="views/assets/img/2.jpg" alt="Imagen ilustrativa">
        </aside>
        <aside class="content">
          <div class="line">
            <h2>Biblioteca Virtual</h2>
          </div>
          <p>
            Brinda acceso a bases de datos a texto completo de revistas académicas científicas
            sin importar el lugar o el momento en que el usuario los desee consultar.
          </p>
          <button id="library-btn">Ingresar</button>
        </aside>
      </div>
    </section>
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
            <a href="./views/loginRevisorSolicitudesAd.php"><button type="button" class="btn btn-warning mx-3" id="admissionReviewer">Revisor de Solicitud de Admision</button></a>
            <a href="./views/loginRevisorExamenes.php"><button type="button" class="btn btn-warning mx-3" id="examReviewer">Revisor de Examen de Admision</button></a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>