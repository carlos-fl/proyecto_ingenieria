<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resultados</title>
  <link rel="icon" type="image/png" href="../../assets/img/UNAH-escudo.png">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="../../css/admisionesStyles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/6130fb0810.js" crossorigin="anonymous"></script>
</head>

<body>
  <header>
    <navbar-unah></navbar-unah>
    <div class="top-bar">
      <div>
        <nav class="navbar bg-body-tertiary" id="header-bar">
          <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="navbar-brand">Resultados</h1>
          </div>
        </nav>
      </div>
    </div>
  </header>

<!-- modal to see exam results -->
  <d-modal tag-id="d-modal" modal-id="results" arial-label-led-by="resultsModal" header-title="Resultados de Admisión" arial-label="TEXT" hidden="false">
    <d-table id="tb" body-id="table-body-results" table-row='["Examen", "Fecha", "Calificación", "Numero de Solicitud"]'></d-table>
  </d-modal>

<!-- pop up to show errors -->
  <pop-up
    id="popUp"
    imgsource="./../../assets/img/crossmark.png"
    popupclass="fail-popup"
    message="">
  </pop-up>

  <main class="container my-5 d-flex flex-column justify-content-between" id="result-main">
    <div class="d-flex flex-column align-items-center">
      <h1 class="mb-4 text-center">Resultados</h1>
      <h4>Ingrese su número de solicitud</h4>
    </div>
    <div class="row g-3 justify-content-center mb-5" id="searchForm">
      <div class="col-auto">
        <label for="numSolicitud" class="visually-hidden">Número de Solicitud</label>
        <input type="text" class="form-control" id="numSolicitud" placeholder="Número de Solicitud" />
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-primary" id="result-btn">Ver Resultados</button>
      </div>
    </div>
    <div class="mt-4">
      <a href="../../admisiones.php" class="text-decoration-none" id="back-link">← Regresar</a>
    </div>
  </main>
  <footer-unah></footer-unah>
  

  <script src="../../js/components/navbar.js"></script>
  <script src="../../js/components/modal.js"></script>
  <script src="../../js/components/failModal.js"></script>
  <script src="../../js/components/pop-up.js"></script>
  <script src="../../js/components/footer.js"></script>
  <script src="../../js/components/table.js"></script>
  <script type="module" src="../../js/resultados.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
