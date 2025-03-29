<?php ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Perfil Estudiantes</title>
  <link rel="icon" type="image/png" href="../../assets/img/UNAH-escudo.png" />
  <link rel="stylesheet" href="../../css/styles.css" />
  <link rel="stylesheet" href="../../css/studentStyles.css" />
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
            <h1 class="navbar-brand">Estudiantes</h1>
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
            <div class="list-group">
              <a class="list-group-item list-group-item-action disabled" aria-disabled="true">
                <i class="fa-regular fa-rectangle-list"></i> Matricular Clases
              </a>
              <a class="list-group-item list-group-item-action disabled" aria-disabled="true">
                <i class="fa-regular fa-rectangle-xmark"></i> Cancelar Clases
              </a>
              <a class="list-group-item list-group-item-action disabled" aria-disabled="true">
                <i class="fa-regular fa-address-card"></i> Forma 03
              </a>
              <a class="list-group-item list-group-item-action disabled" aria-disabled="true">
                <i class="fa-regular fa-paste"></i> Biblioteca Virtual
              </a>
              <a class="list-group-item list-group-item-action disabled" aria-disabled="true">
                <i class="fa-regular fa-clipboard"></i> Calificaciones
              </a>
              <a href="#" class="list-group-item list-group-item-action">
                <i class="fa-regular fa-paste"></i> Solicitudes
              </a>
              <a href="#" class="list-group-item list-group-item-action">
                <i class="fa-regular fa-id-badge"></i> Certificado Academico
              </a>
              <a class="list-group-item list-group-item-action disabled" aria-disabled="true">
                <i class="fa-regular fa-money-bill-1"></i> Estado de Cuenta
              </a>
              <a href="#" class="list-group-item list-group-item-action">
                <i class="fa-regular fa-user"></i> Contactos
              </a>
            </div>
          </div>
          <div class="sidebar-ft">
            <log-out></log-out>
          </div>
        </div>
        <div class="col-sm-9">
          <div class="row">
            <div class="col-8 col-sm-6">
              <header id="profile-bar-h">
                <h3>Perfil de Estudiante</h3>
              </header>
              <section style="height: 25vh; border-bottom: 1px solid !important">
                <aside id="welcome-msg">
                  <h4>Bienvenido Estudiante!</h4>
                </aside>
                <div id="user-container">
                  <div id="profile-img">
                    <img src="../../assets/img/default-profile.png" class="rounded" alt="..." style="background-color: black" />
                    <button class="btn"><i class="fa-regular fa-pen-to-square"></i> Subir foto de perfil</button>
                  </div>
                  <button type="button" class="btn btn-primary position-relative">
                    <i class="fa-regular fa-message"></i> Mensajeria
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                      99+
                      <span class="visually-hidden">Mensajes sin leer</span>
                    </span>
                  </button>
                </div>
              </section>
              <section>
                <div class="info-card">
                  <div class="info-header">
                    <span>Información Personal</span>
                    <button class="edit-btn "><i class="fa-regular fa-pen-to-square"></i> Editar</button>
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
                  <div class="info-card">
                    <div class="info-header">
                      <span>Historial Academico</span>
                    </div>
                    <hr />
                    <div class="info-row info-labels">
                      <span>Indice Global</span>
                      <span>Indice Periodo</span>
                    </div>
                    <div class="info-row">
                      <span id="globalIndex">80%</span>
                      <span id="periodIndex ">97%</span>
                    </div>
                    <div class="card text-center">
                      <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                          <li class="nav-item">
                            <a class="nav-link active" aria-current="true" href="#">Historial</a>
                          </li>
                        </ul>
                      </div>
                      <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                          <div
                            class="tab-pane fade show active"
                            id="historial"
                            role="tabpanel"
                            aria-labelledby="historial-tab">
                            <div class="mt-3">
                              <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle">
                                  <thead class="table-dark">
                                    <tr>
                                      <th scope="col">CÓDIGO</th>
                                      <th scope="col">ASIGNATURA</th>
                                      <th scope="col">UV</th>
                                      <th scope="col">SECCIÓN</th>
                                      <th scope="col">AÑO</th>
                                      <th scope="col">PERÍODO</th>
                                      <th scope="col">CALIFICACIÓN</th>
                                      <th scope="col">OBS</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>CDG01</td>
                                      <td>NOMBRE CLASE</td>
                                      <td>5.0</td>
                                      <td>1001</td>
                                      <td>2025</td>
                                      <td>1</td>
                                      <td>95</td>
                                      <td>AP</td>
                                    </tr>
                                    <tr>
                                      <td>CDG02</td>
                                      <td>NOMBRE CLASE</td>
                                      <td>4.0</td>
                                      <td>1002</td>
                                      <td>2025</td>
                                      <td>2</td>
                                      <td>90</td>
                                      <td>AP</td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                              <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end">
                                  <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                                  </li>
                                  <li class="page-item active">
                                    <a class="page-link" href="#">1</a>
                                  </li>
                                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                                  <li class="page-item">
                                    <a class="page-link" href="#">Siguiente</a>
                                  </li>
                                </ul>
                              </nav>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer-unah></footer-unah>



  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <script src="../../js/components/navbar.js"></script>
  <script src="../../js/components/footer.js"></script>
  <script src="../../js/components/log-out.js"></script>
  <script src="../../js/components/reviewerModal.js"></script>

</body>

</html>