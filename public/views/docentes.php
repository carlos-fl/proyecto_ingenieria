<?php ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Perfil Docente</title>
  <link rel="icon" type="image/png" href="assets/img/UNAH-escudo.png" />
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="css/docentesStyles.css" />
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
            <h1 class="navbar-brand">Docentes</h1>
          </div>
        </nav>
      </div>
    </div>
  </header>
  <main>
    <div class="container text-center" style="background-color: white">
      <div class="row">
        <div class="col-sm-3 sidebar pb-4">
          <div class="sidebar-hd">
            <header id="left-bar-h">
              <h1>UNAH</h1>
            </header>
          </div>
          <div class="sidebar-mn">
          </div>
          <div class="sidebar-ft">
            <log-out></log-out>
          </div>
        </div>
        <div class="col-sm-9">
          <div class="row">
            <div class="col-8 col-sm-6">
              <header id="profile-bar-h">
                <h3>Perfil de Docente</h3>
              </header>
              <section style="height: 30%; border-bottom: 1px solid !important">
                <aside id="welcome-msg">
                  <h4>Bienvenido Docente!</h4>
                </aside>
                <img src="assets/img/default-profile.png" class="rounded mx-auto d-block" alt="..." style="background-color: black" />
              </section>
              <section>
                <div class="info-card">
                  <div class="info-header">
                    <span>Información Personal</span>
                  </div>
                  <hr />
                  <div class="info-row info-labels">
                    <span>Número de Empleado</span>
                    <span>Nombre Completo</span>
                    <span>Correo</span>
                    <span>Teléfono</span>
                  </div>
                  <div class="info-row">
                    <span id="employeeNumber">123456</span>
                    <span id="name">Nombre de ejemplo</span>
                    <span id="email">ejemplo@correo.com</span>
                    <span id="phone">00000000</span>
                  </div>
                </div>
              </section>
              <section>
                <div class="container my-5">
                  <h1 class="text-center mb-4">Mis Clases</h1>
                  <div id="tableContainer" class="table-responsive">
                    <table class="table table-dark table-striped">
                      <thead>
                        <tr>
                          <th scope="col">Código Clase</th>
                          <th scope="col">Sección</th>
                          <th scope="col">Nombre de Clase</th>
                          <th scope="col">Acciones</th>
                        </tr>
                      </thead>
                      <tbody id="teacher-sections">
                      </tbody>
                    </table>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!--Footer de la página-->
  <footer-unah></footer-unah>

  <!-- Modal de Alumnos -->
  <div class="modal fade" id="alumnosModal" tabindex="-1" aria-labelledby="alumnosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="alumnosModalLabel">Lista de Alumnos: <span class="titleSuffix"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-light table-hover" id="tablaAlumnos">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">#Cuenta</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Apellido</th>
                  <th scope="col">Email</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button id="downloadStudentTableBtn" class="btn btn-success">Descargar Excel</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para Subir Video -->
  <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <!-- Se actualiza el título para mostrar la clase seleccionada -->
          <h5 class="modal-title" id="videoModalLabel">Subir Video a Clase: <span class="titleSuffix"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div id="videoUrlContainer" class="mb-3">
            <label for="videoUrl" class="form-label">URL del Video:</label>
            <input type="text" class="form-control" id="videoUrl" placeholder="Ingresa la URL del video" />
            <div id="videoUrlInfo"></div>
          </div>
          <div id="videoPreview" class="mb-3">
            <div id="videoWrapper"class="ratio ratio-16x9 bg-light d-flex justify-content-center align-items-center text-secondary">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="deleteVideoBtn" type="button" class="btn btn-danger">Borrar Video</button>
          <button id="uploadVideoBtn" type="button" class="btn btn-primary">Subir Video</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para Subir Notas -->
  <div class="modal fade" id="notasModal" tabindex="-1" aria-labelledby="notasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="notasModalLabel">Subir Notas <span class="titleSuffix"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="csvFile" class="form-label">Selecciona archivo CSV:</label>
            <input id="uploadGradesInput"type="file" class="form-control" id="csvFile" accept=".csv" />
            <div id="uploadGradesInputInfo">
            </div>
          </div>
          <div>
            <table id="gradesFormat" class="d-none">
              <thead>
                <tr>
                  <th>Numero de cuenta</td>
                  <th>Puntaje</td>
                  <th>OBS</th>
                </tr>
              </thead>
            </table>
          </div>
          <div class="table-overflow table-responsive">
            <table id="uploadedGradesTable" class="table table-dark table-striped table-hover d-none">
              <thead>
                <tr>
                  <th>Número de Cuenta</th>
                  <th>Puntuación</th>
                  <th>OBS</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" id="downloadGradesFormatBtn">Descargar Formato</button>
          <button id="uploadGradesBtn"type="button" class="btn btn-primary" disabled>
            Subir CSV
          </button>
        </div>
      </div>
    </div>
  </div>

  <pop-up
        id="popUp"
        imgsource="assets/img/crossmark.png"
        popupclass=""
        message="">
    </pop-up>

  <!-- Ventana modal -->
  <reviewer-modal tag-id="reviewer" application="loginRevisorSolicitudesAd.php" exam="./admissions/uploadExamResults/login.php"></reviewer-modal>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <script type="module" src="js/docentes.js"></script>
  <script src="js/components/navbar.js"></script>
  <script src="js/components/footer.js"></script>
  <script src="js/components/video-frame.js"></script>
  <script src="js/components/pop-up.js"></script>
  <script src="js/components/log-out.js"></script>
  <script src="js/components/reviewerModal.js"></script>

</body>

</html>