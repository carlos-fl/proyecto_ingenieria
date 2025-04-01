<?php 

  if (empty($_SESSION) || !in_array('COORDINATOR', $_SESSION['ROLES'])) {
    header('Location: /');
  }

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Carga académica</title>
  <link rel="icon" type="image/png" href="../assets/img/UNAH-escudo.png" />
  <link rel="stylesheet" href="../css/sideSectionComponent.css">
  <link rel="stylesheet" href="../css/userProfileComponent.css">
  <link rel="stylesheet" href="../css/coordinatorHome.css">
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="../css/loader.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <script src="https://kit.fontawesome.com/6130fb0810.js" crossorigin="anonymous"></script>
</head>
<body>
  <header>
    <navbar-unah></navbar-unah>
    <div class="top-bar">
      <div>
        <nav class="navbar bg-body-tertiary" id="header-bar">
          <div class="container-fluid">
            <h1 class="navbar-brand">Coordinadores</h1>
          </div>
        </nav>
      </div>
    </div>
  </header>
  <main>
    <div class="d-flex container-s" style="height: 70vh;">
      <div class="order-1 border border-right w-25">
        <side-section>
          <button id="requests" class="btn btn-sm b">VER SOLICITUDES</button>
          <button id="students-history" class="btn btn-sm b">VER HISTORIAL ESTUDIANTES</button>
          <button id="academic-load" class="btn btn-sm b">VER CARGA ACADÉMICA</button>
        </side-section>
      </div>
      <div class="d-flex flex-column p-5 justify-content-start align-items-center w-75 order-2">
        <d-table class="w-90" tag-id="academic-load-table" table-row='["Fecha","PAC","Descargar como PDF", "Descargar como CSV"]'></d-table>
      </div>
    </div>
  </main> 
  <!--Footer de la página-->
  <footer-unah></footer-unah>

  <loading-modal tag-id="loading" modal-id="loading-modal"></loading-modal>
  <modal-error tag-id="academic-error" modal-id="academic-m-error" arial-label-led-by="academic-modal-error" header-title="" arial-label="TEXT" hidden="false"></modal-error>

<!-- modal to show history table  -->
  <d-modal tag-id="academic-modal" modal-id="academic-student-modal" arial-label-led-by="academic-student-modal-results" header-title="Historial" arial-label="student-history" hidden="false">
    <div class="d-flex">
      <button class="me-2 btn btn-sm b">PDF</button>
      <button class="btn btn-sm b">CSV</button>
    </div>
  </d-modal>

<!-- pop up to show errors -->
  <pop-up
    id="popUp"
    imgsource="/views/assets/img/crossmark.png"
    popupclass="fail-popup"
    message="">
  </pop-up>


  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <script src="../js/components/modal.js"></script>
  <script src="../js/components/loading.js"></script>
  <script src="../js/components/failModal.js"></script>
  <script src="../js/components/navbar.js"></script>
  <script src="../js/components/footer.js"></script>
  <script src="../js/components/pop-up.js"></script>
  <script src="../js/components/log-out.js"></script>
  <script src="../js/components/sideSection.js"></script>
  <script src="../js/components/table.js"></script>
  <script src="./controllers/redirect.js"></script>
  <script type="module" src="./controllers/academicLoad.js"></script>

</body>

