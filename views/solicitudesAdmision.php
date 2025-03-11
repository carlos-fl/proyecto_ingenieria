<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Solicitudes de Admision</title>
  <link rel="icon" type="image/png" href="/assets/img/UNAH-escudo.png">
  <link rel="stylesheet" href="/css/solicitudesAdmisionStyle.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
</head>
<body style="background-color: #000E33;">
  <div class="container" id="profile-container">
      <header>
          <nav class="navbar">
              <div class="container-fluid" id="top-bar">
                  <a class="navbar-brand" href="#"><img src="/assets/img/logo-unah.png" alt="Logo"></a>
                  <span class="navbar-text mx-auto">
                      Solicitudes de Admision
                  </span>
              </div>
          </nav>
      </header>
      <main>
          <div class="container-fluid">
              <div class="row">
                  <div class="col-3 sidebar">
                      <ul class="nav nav-tabs mt-3" id="requestsTab" role="tablist">
                          <li class="nav-item" role="presentation">
                              <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">Pendientes</button>
                          </li>
                          <li class="nav-item" role="presentation">
                              <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="false">Aprobadas</button>
                          </li>
                          <li class="nav-item" role="presentation">
                              <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false">Rechazadas</button>
                          </li>
                      </ul>
                      <div class="tab-content requests-list" id="requestsTabContent">
                          <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                              <ul class="list-group list-group-flush" id="listPending">
                                  <li class="list-group-item request-item" data-id="1" data-name="Prueba1" data-lastname="PruebaApellido" data-idnum="123456789" data-email="prueba@correo.com" data-center="Centro 1" data-major1="Carrera1" data-major2="Carrera2">Solicitud 1</li>
                              </ul>
                          </div>
                          <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                              <ul class="list-group list-group-flush" id="listApproved">
                                  <li class="list-group-item request-item" data-id="1" data-name="Prueba2" data-lastname="PruebaApellido1" data-idnum="123456789" data-email="prueba1@correo.com" data-center="Centro 2" data-major1="Carrera3" data-major2="Carrera4">Solicitud 2</li>
                              </ul>
                          </div>
                          <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                              <ul class="list-group list-group-flush" id="listRejected">
                                  <li class="list-group-item request-item" data-id="1" data-name="Prueba3" data-lastname="PruebaApellido2" data-idnum="123456789" data-email="prueba2@correo.com" data-center="Centro 3" data-major1="Carrera6" data-major2="Carrera5">Solicitud 3</li>
                              </ul>
                          </div>
                      </div>
                      <a href="/js/modules/loginSolicitudesAdmision.php"><button class="btn btn-danger w-100 mt-3">Cerrar Sesion</button></a>
                  </div>
                  <div class="col-9 main-content d-none" id="requestDetail">
                      <div class="request-box mx-auto">
                          <h4 id="requestTitle" class="text-center mb-3">Solicitud</h4>
                          <form id="requestForm">
                              <div class="mb-2"><strong>Nombres:</strong> <span id="requestName"></span></div>
                              <div class="mb-2"><strong>Apellidos:</strong> <span id="requestLastName"></span></div>
                              <div class="mb-2"><strong>ID:</strong> <span id="requestID"></span></div>
                              <div class="mb-2"><strong>Correo:</strong> <span id="requestEmail"></span></div>
                              <div class="mb-2"><strong>Centro Regional:</strong> <span id="requestCenter"></span></div>
                              <div class="mb-2"><strong>Carrera Principal:</strong> <span id="requestMajor1"></span></div>
                              <div class="mb-2"><strong>Carrera Secundaria:</strong> <span id="requestMajor2"></span></div>
                              <button type="button" class="btn btn-info w-100 mt-2" data-bs-toggle="modal" data-bs-target="#modalCertificate">Ver Certificado de Secundaria</button>
                              <div class="mt-3">
                                  <label for="comments" class="form-label">Observaciones:</label>
                                  <textarea id="comments" class="form-control" rows="3"></textarea>
                              </div>
                              <div class="d-flex justify-content-between mt-3">
                                  <button type="button" class="btn btn-success" id="btnApprove">Aprobar</button>
                                  <button type="button" class="btn btn-danger" id="btnReject">Rechazar</button>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
      </main>
  </div>

<!--Ventana Modal-->
  <div class="modal fade" id="modalCertificate" tabindex="-1" aria-labelledby="modalCertificateLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="modalCertificateLabel">Certificado de Secundaria</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-center">
                  <img id="certificateImage" src="#" alt="Certificado" class="img-fluid">
              </div>
          </div>
      </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.querySelectorAll('.request-item').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.request-item').forEach(el => el.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('requestName').textContent = this.dataset.name;
        document.getElementById('requestLastName').textContent = this.dataset.lastname;
        document.getElementById('requestID').textContent = this.dataset.idnum;
        document.getElementById('requestEmail').textContent = this.dataset.email;
        document.getElementById('requestCenter').textContent = this.dataset.center;
        document.getElementById('requestMajor1').textContent = this.dataset.major1;
        document.getElementById('requestMajor2').textContent = this.dataset.major2;
        document.getElementById('requestDetail').classList.remove('d-none');
        });
    });
  </script>
</body>
</html>