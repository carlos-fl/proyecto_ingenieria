
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="../assets/img/UNAH-escudo.png" />
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/loader.css">
  <link rel="stylesheet" href="../css/coordinator.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <script src="https://kit.fontawesome.com/6130fb0810.js" crossorigin="anonymous"></script>
  <title>Solicitudes</title>
</head>
<body>
  
  <navbar-unah></navbar-unah>
  <div class="top-bar">
    <div>
      <nav class="navbar bg-body-tertiary" id="header-bar">
        <div class="container-fluid">
          <h1 class="navbar-brand">Solicitudes</h1>
        </div>
      </nav>
    </div>
  </div>

    <div class="container">
      <div class="request-container p-3 d-flex flex-column justify-content-between align-items-between">
        <side-section class="h-75">
          <button id="requests" class="btn btn-sm b">VER SOLICITUDES</button>
          <button id="students-history" class="btn btn-sm b">VER HISTORIAL ESTUDIANTES</button>
          <button id="academic-load" class="btn btn-sm b">VER CARGA ACADÉMICA</button>
        </side-section>
        <hr>
        <div class="request-nav-container d-flex justify-content-between">
          <button id="cancellations" class="btn btn-sm b">Cancelaciones</button>
          <button id="majorChange" class="btn btn-sm b">Cambio Carrera</button>
          <button id="campusTransfer" class="btn btn-sm b">Cambio Centro</button>
        </div>
        
      </div>
      <div class="request-view-container d-flex justify-content-center align-items-start overflow-scroll">
        <h4 id="temp-text" class="text-black-50">Sin solicitudes</h4>
        <d-table class="p-3" table-row='["Estudiante", "Número de cuenta", "GPA", "Fecha de solicitud", "Documento"]' body-id="table-body-results" tag-id="r-table" style="display: none;"></d-table>
      </div>
    </div>

  <footer-unah></footer-unah>


  <d-modal tag-id="request-modal" modal-id="y-student-modal" arial-label-led-by="history-student-modal-results" header-title="Historial" arial-label="student-history" hidden="false"></d-modal>
  
  <d-modal tag-id="current-classes-modal" modal-body-id="classes-modal-body-id" modal-id="classes-modal" arial-label-led-by="classes-student-modal-results" header-title="Clases Matriculadas" arial-label="student-classes" hidden="false">
    <d-table class="p-3" table-row='["Clase", "UV", "Sección", "Hora Inicio", "Hora Final", "Docente", "Cancelar"]' body-id="table-body-student-classes" tag-id="c-table" style="display: none;"></d-table>
  </d-modal>

<!-- pop up to show errors -->
  <pop-up
    id="popUp"
    imgsource="/views/assets/img/crossmark.png"
    popupclass="fail-popup"
    message="">
  </pop-up>



  <loading-modal tag-id="loading" modal-id="loading-modal"></loading-modal>
  <modal-error tag-id="request-error" modal-id="request-fail" arial-label-led-by="requestError" header-title="Un error Ha Sucedido. Intente de Nuevo" arial-label="error" hidden="false"></modal-error>

  <modal-success tag-id="request-success" modal-id="request-success-p" arial-label-led-by="requestSuccess" header-title="Solicitud se ha actualizado Correctamente" arial-label="error" hidden="false"></modal-success>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

  <script src="../js/components/modal.js"></script>
  <script src="../js/components/navbar.js"></script>
  <script src="../js/components/footer.js"></script>
  <script src="../js/components/loading.js"></script>
  <script src="../js/components/failModal.js"></script>
  <script src="../js/components/sideSection.js"></script>
  <script src="../js/components/log-out.js"></script>
  <script src="../js/components/successModal.js"></script>
  <script src="../js/components/pop-up.js"></script>
  <script src="../js/components/table.js"></script>
  <script type="module" src="./controllers/getRequest.js"></script>
  <script src="./controllers/redirect.js"></script>
</body>
</html>