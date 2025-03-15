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
    <navbar-unah></navbar-unah>
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
            <a href="login.php"><button type="button" class="btn btn-warning mx-3" id="admissionReviewer">Revisor de Solicitud de Admision</button></a>
            <a href="login.php"><button type="button" class="btn btn-warning mx-3" id="examReviewer">Revisor de Examen de Admision</button></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!---->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/components/navbar.js"></script>
  <script src="js/components/footer.js"></script>
  <script src="js/solicitudesAdmision.js"></script>

</body>

</html>