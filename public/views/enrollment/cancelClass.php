<?php
  session_start();

  if (empty($_SESSION) || !in_array('STUDENTS', $_SESSION['ROLES'])) {
    header('Location: /views/loginEstudiantes.php');
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/loader.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <script src="https://kit.fontawesome.com/6130fb0810.js" crossorigin="anonymous"></script>
  <title>Matricula</title>
</head>
<body>
  <header>
    <navbar-unah></navbar-unah>
    <div class="top-bar">
      <div>
        <nav class="navbar bg-body-tertiary" id="header-bar">
          <div class="container-fluid">
            <h1 class="navbar-brand">Matricula</h1>
          </div>
        </nav>
      </div>
    </div>
  </header>
  <main class="d-flex flex-column align-items-center" style="height: 70vh;">
  <!-- clases matriculadas: class_name, uv, hora_inicio, hora_final -->
    <div class="d-flex flex-column justify-content-center w-75 h-50">
      <div>
        <h6 style="color: var(--primary-color)">Clases matriculadas</h6>
      </div>
      <div class="overflow-auto h-100">
        <d-table tag-id="enroll-table" table-row='["UV", "clase", "hora inicio", "hora final", "Cancelar"]' class="w-100 rounded"></d-table>
      </div>
    </div>
      <footer-unah class="w-100 position-fixed bottom-0"></footer-unah>
  </main>

  <loading-modal tag-id="loading" modal-id="loading-modal"></loading-modal>

  <pop-up
        id="popUp"
        imgsource="assets/img/crossmark.png"
        popupclass=""
        message="">
    </pop-up>

  <modal-success tag-id="success-modal" modal-id="modal-d" arial-label-led-by="Modal" header-title="Clase matriculada Éxitosamente" arial-label="enrollment" hidden="false"></modal-success>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <script src="../js/components/navbar.js"></script>
  <script src="../js/components/modal.js"></script>
  <script src="../js/components/successModal.js"></script>
  <script src="../js/components/loading.js"></script>
  <script src="../js/components/footer.js"></script>
  <script src="../js/components/table.js"></script>
  <script src="../js/components/pop-up.js"></script>
  <script type="module" src="./controllers/cancelClass.js"></script>
  <script type="module" src="./controllers/verifyActiveEnrollment.js"></script>
</body>
</html>