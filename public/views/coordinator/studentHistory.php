
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Historial estudiantes</title>
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
      <div class="d-flex flex-column p-5 justify-content-center align-items-center w-75 order-2">
        <div class="d-flex justify-content-between align-items-center w-90 mb-4">
          <input id="account" class="w-50 p-2 me-2 rounded form-control" type="text" placeholder="Buscar estudiantes por número de cuenta">
          <div class="d-flex justify-content-around w-50 me-1">
            <input id="major-input" list="majors" placeholder="Filtrar por carrera" class="form-control">
            <input id="center-input" list="campus" placeholder="Filtrar por Centro" class="form-control">
            <datalist id="majors"></datalist>
            <datalist id="campus"></datalist>
          </div>
          <div>
            <button id="apply" class="btn b">Buscar</button>
          </div>
        </div>
        <div class="overflow-auto w-75">
          <d-table class="w-90" tag-id="student-history-table" table-row='["Estudiante","Número de cuenta","índice global", "Acción"]'></d-table>
        </div>
      </div>
    </div>
  </main> 
  <!--Footer de la página-->
  <footer-unah></footer-unah>

  <!-- modal to show history table  -->
  <d-modal tag-id="history-modal" modal-id="history-student-modal" arial-label-led-by="history-student-modal-results" header-title="Historial" arial-label="student-history" hidden="false"></d-modal>
  <loading-modal tag-id="loading" modal-id="loading-modal"></loading-modal>
  <modal-error tag-id="history-error" modal-id="history-m-error" arial-label-led-by="history-modal-error" header-title="" arial-label="TEXT" hidden="false"></modal-error>


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
  <script src="../js/components/video-frame.js"></script>
  <script src="../js/components/pop-up.js"></script>
  <script src="../js/components/log-out.js"></script>
  <script src="../js/components/reviewerModal.js"></script>
  <script src="../js/components/userProfile.js"></script>
  <script src="../js/components/sideSection.js"></script>
  <script src="../js/components/table.js"></script>
  <script type="module" src="./controllers/getStudentHistory.js"></script>
  <script type="module" src="./controllers/historyFilters.js"></script>
  <script src="./controllers/redirect.js"></script>

</body>
