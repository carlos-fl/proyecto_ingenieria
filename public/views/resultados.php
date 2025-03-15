<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados</title>
    <link rel="icon" type="image/png" href="assets/img/UNAH-escudo.png">
    <link rel="stylesheet" href="css/admisionesStyles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/6130fb0810.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <navbar-unah></navbar-unah>
        <div class="top-bar">
            <div>
                <nav class="navbar bg-body-tertiary" id="header-bar">
                    <div class="container-fluid d-flex justify-content-between align-items-center">
                        <h1 class="navbar-brand">
                            Resultados
                        </h1>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <main class="container my-5">
      <h1 class="mb-4 text-center">Resultados</h1>
      <form class="row g-3 justify-content-center mb-5">
        <div class="col-auto">
          <label for="numSolicitud" class="visually-hidden">Número de Solicitud</label>
          <input
            type="text"
            class="form-control"
            id="numSolicitud"
            placeholder="Número de Solicitud"
          />
        </div>
        <div class="col-auto">
          <label for="correo" class="visually-hidden">Correo</label>
          <input
            type="email"
            class="form-control"
            id="correo"
            placeholder="Correo"
          />
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary" id="result-btn">
            Ver Resultados
          </button>
        </div>
      </form>
      <h2 class="mb-3">Resultados de Examen de Admisión</h2>
      <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
          <thead class="table-light">
            <tr>
              <th>Examen</th>
              <th>Fecha</th>
              <th>Clasificación</th>
            </tr>
          </thead>
          <tbody>
            <!-- fetch -->
            <tr>
              <td>PUMAH</td>
              <td>10/23/2024</td>
              <td>1200</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="mt-4">
        <a href="admisiones.php" class="text-decoration-none" id="back-link">← Regresar</a>
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
                  <a href="loginSolicitudesAdmision.php"><button type="button" class="btn btn-warning mx-3" id="admissionReviewer">Revisor de Solicitud de Admision</button></a>
                  <a href="loginRevisoreExamenes.php"><button type="button" class="btn btn-warning mx-3" id="examReviewer">Revisor de Examen de Admision</button></a>  
              </div>
            </div>
          </div>
        </div>
      </div>  
<!---->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/components/navbar.js"></script>
    <script src="js/components/footer.js"></script>
</body>
</html>