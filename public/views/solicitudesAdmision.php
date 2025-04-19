<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Revisión de Solicitudes</title>
  <link rel="icon" type="image/png" href="assets/img/UNAH-escudo.png" />
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/solicitudesAdmisionStyles.css">
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
        <log-out id="logOut"></log-out>
      </div>
    </div>
  </main>
  <footer-unah></footer-unah>

  <!-- Ventana modal-->
  <div class="modal fade" id="certificateModal" tabindex="-1" aria-labelledby="certificateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="certificateModalLabel">Certificado de Secundaria</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body" id="modalBody">
          <div id="spinnerCertificate" style="display: none;" class="d-flex justify-content-center align-items-center mb-3">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
          <!-- contenedor para visualizacion de certificado -->
          <div id="certificate-iframe-render-container"></div> 
        </div>
      </div>
    </div>
  </div>

  <!-- Ventana modal -->
  <reviewer-modal tag-id="reviewer" application="loginRevisorSolicitudesAd.php" exam="./admissions/uploadExamResults/login.php"></reviewer-modal>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/components/navbar.js"></script>
  <script src="js/components/footer.js"></script>
  <script>
    const reviewerId = <?php echo isset($_SESSION['USER_ID']) ? json_encode($_SESSION['USER_ID']) : 'null'; ?>;
  </script><!--IdReviewer-->
  <script src="js/solicitudesAdmision.js"></script>
  <script src="js/components/log-out.js"></script>
  <script src="js/components/reviewerModal.js"></script>

</body>

</html>