<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="../assets/img/UNAH-escudo.png" />
  <link rel="stylesheet" href="../css/styles.css">
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
        <div class="request-nav-container d-flex justify-content-between">
          <button class="btn btn-sm b">Cancelaciones</button>
          <button class="btn btn-sm b">Cambio Carrera</button>
          <button class="btn btn-sm b">Cambio Centro</button>
        </div>
        <hr>
        <div class="request-list-container h-75"></div>
      </div>
      <div class="request-view-container d-flex justify-content-center align-items-center">
        <h4 class="text-black-50">Sin solicitudes</h4>
        <d-table tag-id="r-table" style="display: none;"></d-table>
      </div>
    </div>

  <footer-unah></footer-unah>
  <modal-error tag-id="request-error" modal-id="request-fail" arial-label-led-by="requestError" header-title="Un error Ha Sucedido. Intente de Nuevo" arial-label="error" hidden="false"></modal-error>
  <loading-modal tag-id="loading" modal-id="loading-modal"></loading-modal>

  <script src="../js/components/navbar.js"></script>
  <script src="../js/components/footer.js"></script>
  <script src="../js/components/loading.js"></script>
  <script src="../js/components/failModal.js"></script>
  <script src="../js/components/table.js"></script>
</body>
</html>