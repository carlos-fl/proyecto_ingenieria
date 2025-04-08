<?php
session_start();
if (empty($_SESSION)) {
  header('Location: loginEstudiantes.php');
}
?>
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
              <a href="../../enrollment/index.php" class="list-group-item list-group-item-action" aria-disabled="true">
                <i class="fa-regular fa-rectangle-list"></i> Matricular Clases
              </a>
              <a class="list-group-item list-group-item-action disabled" aria-disabled="true">
                <i class="fa-regular fa-rectangle-xmark"></i> Cancelar Clases
              </a>
              <a class="list-group-item list-group-item-action disabled" aria-disabled="true">
                <i class="fa-regular fa-address-card"></i> Forma 03
              </a>
              <a class="list-group-item list-group-item-action" href="../../library/home/index.php">
                <i class="fa-regular fa-paste"></i> Biblioteca Virtual
              </a>
              <a class="list-group-item list-group-item-action disabled" aria-disabled="true">
                <i class="fa-regular fa-clipboard"></i> Calificaciones
              </a>
              <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#requestsModal">
                <i class="fa-regular fa-paste"></i> Solicitudes
              </a>
              <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#academicCertificateModal">
                <i class="fa-regular fa-id-badge"></i> Certificado Académico
              </a>
              <a class="list-group-item list-group-item-action disabled" aria-disabled="true">
                <i class="fa-regular fa-money-bill-1"></i> Estado de Cuenta
              </a>
              <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#contactsModal">
                <i class="fa-regular fa-user"></i> Contactos
              </a>
              <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#groupsModal">
                <i class="fa-regular fa-handshake"></i> Grupos
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
              <section style="height: 25%; border-bottom: 1px solid !important">
                <aside id="welcome-msg">
                  <h4>Bienvenido Estudiante!</h4>
                </aside>
                <div id="user-container">
                  <div id="profile-img">
                    <img id="profileImg" src="../../assets/img/default-profile.png" class="rounded" alt="Profile Picture" />
                    <button class="btn" id="uploadBtn">
                      <i class="fa-regular fa-pen-to-square"></i> Subir foto de perfil
                    </button>
                    <span id="uploadSpinner" class="spinner-border spinner-border-sm" style="display: none; position: absolute; top: 10px; right: 10px;" role="status" aria-hidden="true"></span>
                    <input type="file" id="fileInput" style="display: none;" accept="image/*" onchange="handleProfileImageUpload(this.files)" />
                  </div>
                  <button type="button" class="btn btn-primary position-relative" data-bs-toggle="modal" data-bs-target="#messageListModal">
                    <i class="fa-regular fa-message"></i> Mensajeria
                    </span>
                  </button>
                </div>
              </section>
              <section>
                <div class="info-card">
                  <div class="info-header">
                    <span>Información Personal</span>
                    <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#editInfoModal">
                      <i class="fa-regular fa-pen-to-square"></i> Editar
                    </button>
                  </div>
                  <hr />
                  <div class="info-row info-labels">
                    <span>Número de Cuenta</span>
                    <span>Nombre Completo</span>
                    <span>Correo</span>
                    <span>Teléfono</span>
                  </div>
                  <div class="info-row" style="margin-bottom: 1rem;">
                    <span id="accountNumber"></span>
                    <span id="name"></span>
                    <span id="email"></span>
                    <span id="phone"></span>
                  </div>
                  <div class="card description-card" style="border: 1px solid #e0e0e0; border-radius: 8px;">
                    <div class="card-header" style="background-color: #f7f7f7; border-bottom: 1px solid #e0e0e0; font-weight: bold; color: #333;">
                      Descripción
                    </div>
                    <div class="card-body" style="padding: 1rem;">
                      <p class="card-text" id="description" style="margin: 0;"></p>
                    </div>
                  </div>
                </div>
              </section>
              <section>
                <div id="loadingContainer" class="text-center my-3" style="display:none;">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                  </div>
                  <div>Cargando…</div>
                </div>
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
                      <span id="globalIndex"></span>
                      <span id="periodIndex "></span>
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
                                      <td></td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                              <div id="paginationContainer"></div>
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

  <!-- Modal Solicitudes-->
  <div class="modal fade" id="requestsModal" tabindex="-1" aria-labelledby="requestsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="requestsModalLabel">Solicitudes</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="list-group">
            <a href="../requests/classCancel.php" class="list-group-item list-group-item-action">
              <i class="fa-solid fa-ban"></i> Solicitar Cancelacion Excepcional
            </a>
            <a href="replacement-payment.html" class="list-group-item list-group-item-action">
              <i class="fa-solid fa-money-bill-wave"></i> Solicitar Pago de Reposicion
            </a>
            <a href="../requests/centerChange.php" class="list-group-item list-group-item-action">
              <i class="fa-solid fa-building"></i> Solicitar Cambio de Centro
            </a>
            <a href="../requests/majorChange.php" class="list-group-item list-group-item-action">
              <i class="fa-solid fa-graduation-cap"></i> Solicitar Cambio de Carrera
            </a>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Certificado Academico-->
  <div class="modal fade" id="academicCertificateModal" tabindex="-1" aria-labelledby="academicCertificateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="academicCertificateModalLabel">Descargar Certificado Académico</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p>Haz clic en el botón de descarga para obtener tu certificado académico.</p>
        </div>
        <div class="modal-footer">
          <!-- Enlace de descarga -->
          <a href="download-academic-certificate.html" class="btn btn-primary">
            <i class="fa-solid fa-download"></i> Descargar Certificado
          </a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Contactos -->
  <div class="modal fade" id="contactsModal" tabindex="-1" aria-labelledby="contactsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="contactsModalLabel">Contactos</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <!-- Sección para crear grupos -->
          <div class="mb-4">
            <h6 class="mb-2">Crear Grupo</h6>
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Nombre del Grupo" aria-label="Group Name" id="groupNameInput">
              <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                Crear Grupo
              </button>
            </div>
          </div>
          <!-- Sección para agregar contacto -->
          <div class="mb-4">
            <button class="btn btn-outline-success w-100" type="button" data-bs-toggle="collapse" data-bs-target="#addContactCollapse" aria-expanded="false" aria-controls="addContactCollapse">
              <i class="fa-solid fa-plus"></i> Agregar Contacto
            </button>
            <div class="collapse mt-3" id="addContactCollapse">
              <div class="card card-body">
                <div class="mb-3">
                  <label for="contactEmailInput" class="form-label">Correo institucional</label>
                  <input type="email" class="form-control" id="contactEmailInput" placeholder="correo@institucion.edu">
                </div>
                <button class="btn btn-primary" type="button" onclick="sendFriendRequest()">
                  Enviar solicitud de amistad
                </button>
              </div>
            </div>
          </div>
          <!-- Lista de Contactos -->
          <div id="loadingContacts" class="text-center text-muted">Cargando Contactos…</div>
          <div id="errorContacts" class="text-danger text-center" style="display: none;"></div>
          <div id="contactsList" class="list-group" style="max-height: 300px; overflow-y: auto;"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para agregar usuarios al grupo -->
  <div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createGroupModalLabel">Agregar miembros del grupo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="list-group" style="max-height: 300px; overflow-y: auto;">
            <div class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-1"></h6>
                <small></small>
              </div>
              <div>
                <button class="btn btn-sm btn-outline-success toggle-user-btn" data-user-id="001">
                  <i class="fa-solid fa-plus"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary">Confirmar creacion</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Editar Información Personal -->
  <div class="modal fade" id="editInfoModal" tabindex="-1" aria-labelledby="editInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editInfoModalLabel">Editar Información Personal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="editInfoForm">
            <!-- Campo Teléfono -->
            <div class="mb-3">
              <label for="editPhone" class="form-label">Teléfono</label>
              <input type="text" class="form-control" id="editPhone" placeholder="Ingrese su teléfono">
            </div>
            <!-- Campo Descripción -->
            <div class="mb-3">
              <label for="editDescription" class="form-label">Descripción</label>
              <textarea class="form-control" id="editDescription" rows="3" maxlength="300" placeholder="Escribe una breve descripción sobre ti (máximo 300 caracteres)"></textarea>
              <div id="descHelp" class="form-text">Máximo 300 caracteres.</div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="updateInfo()">Confirmar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Lista de Chats -->
  <div class="modal fade" id="messageListModal" tabindex="-1" aria-labelledby="messageListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="messageListModalLabel">Mensajes</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div id="loadingMessages" style="display: none;">
            <p>Cargando...</p>
          </div>
          <div id="errorMessages" style="display: none;">
            <p class="text-danger">Error al cargar los mensajes.</p>
          </div>
          <div class="list-group" id="messageList">
            <!-- Los mensajes se agregarán aquí dinámicamente -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Chat Individual -->
  <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="chatModalLabel">Mensaje con <span id="chatContactName">Nombre contacto</span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <!-- Contenedor de mensajes -->
          <div class="chat-container" style="max-height: 400px; overflow-y: auto;" id="chatMessages">
          </div>
        </div>
        <div class="modal-footer">
          <!-- Input para escribir mensajes -->
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Escribe un mensaje" aria-label="Mensaje" id="messageInput">
            <button class="btn btn-primary" type="button" onclick="sendMessage()">Enviar</button>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- Modal Lista de Grupos -->
<div class="modal fade" id="groupsModal" tabindex="-1" aria-labelledby="groupsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="groupsModalLabel">Mis Grupos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <!-- Mensaje de Cargando Grupos... -->
        <div id="groupsLoading" class="text-center" style="height: 200px; line-height: 200px;">
          Cargando Grupos...
        </div>
        <!-- Lista de grupos (inicialmente oculta) -->
        <div id="groupsList" class="list-group hidden" style="max-height: 400px; overflow-y: auto;">
          <!-- Se llenará dinámicamente -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


  <!-- Modal Chat Grupal -->
  <div class="modal fade" id="groupChatModal" tabindex="-1" aria-labelledby="groupChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="groupChatModalLabel">Chat grupal: <span id="groupChatName">Nombre del Grupo</span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <!-- Contenedor de mensajes -->
          <div class="chat-container" style="max-height: 400px; overflow-y: auto;">
            <!-- Ejemplo de mensaje recibido -->
            <div class="d-flex mb-2">
              <div class="p-2 bg-light rounded" style="max-width: 70%;">
                <p class="mb-1">Hola a todos, ¿cómo están?</p>
                <small class="text-muted">10:00 AM - Visto</small>
              </div>
            </div>
            <!-- Ejemplo de mensaje enviado -->
            <div class="d-flex justify-content-end mb-2">
              <div class="p-2 bg-primary text-white rounded" style="max-width: 70%;">
                <p class="mb-1">¡Muy bien, gracias!</p>
                <small class="text-light">10:05 AM - Enviado</small>
              </div>
            </div>
            <!-- Se agregarán más mensajes -->
          </div>
        </div>
        <div class="modal-footer">
          <!-- Input para escribir mensajes -->
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Escribe un mensaje" aria-label="Mensaje" id="groupMessageInput">
            <button class="btn btn-primary" type="button" onclick="sendGroupMessage()">Enviar</button>
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
  <script src="../../js/components/navbar.js"></script>
  <script src="../../js/components/footer.js"></script>
  <script src="../../js/components/log-out.js"></script>
  <script src="../../js/components/reviewerModal.js"></script>
  <script src="./controllers/info.js"></script>
  <script src="./controllers/chats.js"></script>
  <script src="./controllers/contacts.js"></script>

</body>

</html>