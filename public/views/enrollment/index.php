<?php
  session_start();

  if (!isset($_SESSION)) {
    header('Location: /');
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
        <d-table tag-id="enroll-table" table-row='["UV", "clase", "hora inicio", "hora final"]' class="w-100 rounded"></d-table>
      </div>
    </div>
  <!-- matricular clases -->
    <div class="h-50 w-75 mb-5 d-flex flex-column justify-content-center align-content-center">
      <div class="d-flex justify-content-between align-items-center h-75">
        <div class="h-100" style="width: 33.3%">
          <select id="departments" class="h-100 w-100">
            <option value="">Departamentos</option>
          </select>
        </div>
        <div class="h-100" style="width: 33.3%">
          <select id="classes" class="h-100 w-100">
            <option value="">Clases</option>
          </select>
        </div>
        <div class="h-100" style="width: 33.3%">
          <select id="sections" class="h-100 w-100">
            <option value="">Secciones</option>
          </select>
        </div>
      </div>
      <div>
        <button class="btn btn-primary mt-2" id="enrol">Matricular</button>
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

  <d-modal tag-id="major-modal" modal-body-id="content-major" modal-id="modal-d" arial-label-led-by="Modal-major" header-title="Escoge la carrera" arial-label="enrollment-major" hidden="false"></d-modal>

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
  <script type="module" src="./controllers/enrolledClasses.js"></script>
  <script type="module" src="./controllers/verifyActiveEnrollment.js"></script>
  <script type="module" src="./controllers/selectClass.js"></script>
  <script type="module" src="./controllers/enrol.js"></script>
  <script type="module" src="./controllers/setStudentMajor.js"></script>
</body>
</html>