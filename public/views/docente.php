<?php ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Perfil Docente</title>
  <link rel="icon" type="image/png" href="assets/img/UNAH-escudo.png" />
  <link rel="stylesheet" href="css/style.css" />
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
        <div class="col-sm-3">
          <header id="left-bar-h">
            <h1>UNAH</h1>
          </header>
          <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#videoModal">
            Subir Video
          </button>
          <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#notasModal">
            Subir Notas
          </button>
          <log-out></log-out>
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
                <img id="profile-photo" src="assets/img/default-profile.png" class="rounded mx-auto d-block" alt="..." style="background-color: black" />
              </section>
              <section>
                <div class="info-card">
                  <div class="info-header">
                    <span>Información Personal</span>
                    <button class="edit-btn" id="editButton" title="Editar">
                      <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                  </div>
                  <hr />
                  <div class="info-row info-labels">
                    <span>Nombre</span>
                    <span>Correo</span>
                    <span>Teléfono</span>
                  </div>
                  <div class="info-row">
                    <span id="name">Nombre de ejemplo</span>
                    <span id="mail">ejemplo@correo.com</span>
                    <span id="phone">00000000</span>
                  </div>
                  <div class="buttons">
                    <button id="saveButton" class="save-btn">Guardar</button>
                    <button id="cancelButton" class="cancel-btn">
                      Cancelar
                    </button>
                  </div>
                </div>
              </section>
              <section>
                <div class="container my-5">
                  <h1 class="text-center mb-4">Mis Clases</h1>
                  <div class="table-responsive">
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
                        <tr>
                          <td>MAT101</td>
                          <td>1100</td>
                          <td>Matemáticas Básicas</td>
                          <td><button class="btn btn-primary btn-sm" onclick="openModal('MAT101')">Ver Alumnos</button>
                          </td>
                        </tr>
                        <tr>
                          <td>HIS202</td>
                          <td>1400</td>
                          <td>Historia Universal</td>
                          <td><button class="btn btn-primary btn-sm" onclick="openModal('HIS202')">Ver Alumnos</button>
                          </td>
                        </tr>
                        <tr>
                          <td>CIE303</td>
                          <td>1001</td>
                          <td>Ciencias Naturales</td>
                          <td><button class="btn btn-primary btn-sm" onclick="openModal('CIE303')">Ver Alumnos</button>
                          </td>
                        </tr>
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
          <h5 class="modal-title" id="alumnosModalLabel">Lista de Alumnos - <span id="claseCodigo"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-light table-hover" id="tablaAlumnos">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Apellido</th>
                  <th scope="col">Email</th>
                </tr>
              </thead>
              <tbody>
                <!-- Datos estáticos de ejemplo -->
                <tr>
                  <td>1</td>
                  <td>Juan</td>
                  <td>Pérez</td>
                  <td>juan.perez@example.com</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>María</td>
                  <td>López</td>
                  <td>maria.lopez@example.com</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Carlos</td>
                  <td>Gómez</td>
                  <td>carlos.gomez@example.com</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" onclick="exportTableToCSV('alumnos.csv')">Descargar Excel</button>
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
          <h5 class="modal-title" id="videoModalLabel">Subir Video a Clase</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="videoUrl" class="form-label">URL del Video:</label>
            <input type="text" class="form-control" id="videoUrl" placeholder="Ingresa la URL del video" />
          </div>
          <div id="videoPreview" class="mb-3" style="display: none;">
            <label class="form-label">Preview:</label>
            <div class="ratio ratio-16x9">
              <iframe id="videoIframe" src="#" title="Video preview" allowfullscreen></iframe>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" onclick="deleteVideo()">
            Borrar Video
          </button>
          <button type="button" class="btn btn-secondary" onclick="updateVideo()">
            Actualizar Video
          </button>
          <button type="button" class="btn btn-primary" onclick="uploadVideo()">
            Subir Video
          </button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para Subir Notas -->
  <div class="modal fade" id="notasModal" tabindex="-1" aria-labelledby="notasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="notasModalLabel">Subir Notas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="csvFile" class="form-label">Selecciona archivo CSV:</label>
            <input type="file" class="form-control" id="csvFile" accept=".csv" />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="uploadCSV()">
            Subir CSV
          </button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Ventana modal revisores -->
  <div class="modal fade" id="reviewerModal" tabindex="-1" aria-labelledby="reviewerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewerModalLabel">Revisores</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="btn-group" role="group" aria-label="Select reviewer type">
                        <a href="login.php"><button type="button" class="btn btn-warning mx-3" id="admissionReviewer">
                                Revisor de Solicitud de Admision
                            </button></a>
                        <a href="login.php"><button type="button" class="btn btn-warning mx-3" id="examReviewer">
                                Revisor de Examen de Admision
                            </button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <script src="js/docentes.js"></script>
  <script src="js/components/navbar.js"></script>
  <script src="js/components/footer.js"></script>
  <script src="js/components/log-out.js"></script>


</body>

</html>