// Datos estáticos de solicitudes
const requests = [
  {
    id: 1,
    nombre: "Juan",
    apellido: "Pérez",
    identidad: "0801199912345",
    email: "juanperez@example.com",
    centroRegional: "CU",
    carreraPrincipal: "Ingeniería en Sistemas",
    carreraSecundaria: "Informática Administrativa",
    certificateUrl: "assets/img/certificado.webp"
  },
  {
    id: 2,
    nombre: "María",
    apellido: "García",
    identidad: "0802199923456",
    email: "mariagarcia@example.com",
    centroRegional: "TE",
    carreraPrincipal: "Medicina",
    carreraSecundaria: "Enfermería",
    certificateUrl: "assets/img/certificado.webp"
  },
  {
    id: 3,
    nombre: "Carlos",
    apellido: "López",
    identidad: "0803199934567",
    email: "carloslopez@example.com",
    centroRegional: "OC",
    carreraPrincipal: "Ingeniería Civil",
    carreraSecundaria: "Arquitectura",
    certificateUrl: "assets/img/certificado.webp"
  }
];

let currentIndex = 0;

// Función para renderizar la solicitud
function renderRequest(request) {
  return `
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-primary text-white text-center">
        <h3 class="mb-0">${request.nombre} ${request.apellido}</h3>
      </div>
      <div class="card-body">
        <form>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label fw-bold">Identidad:</label>
            <div class="col-sm-8">
              <input type="text" readonly class="form-control-plaintext" value="${request.identidad}">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label fw-bold">Correo:</label>
            <div class="col-sm-8">
              <input type="text" readonly class="form-control-plaintext" value="${request.email}">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label fw-bold">Centro Regional:</label>
            <div class="col-sm-8">
              <input type="text" readonly class="form-control-plaintext" value="${request.centroRegional}">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label fw-bold">Carrera Principal:</label>
            <div class="col-sm-8">
              <input type="text" readonly class="form-control-plaintext" value="${request.carreraPrincipal}">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label fw-bold">Carrera Secundaria:</label>
            <div class="col-sm-8">
              <input type="text" readonly class="form-control-plaintext" value="${request.carreraSecundaria}">
            </div>
          </div>
          <div class="text-center mb-4">
            <img src="${request.certificateUrl}" alt="Certificado de Secundaria" class="img-thumbnail"
                 style="max-width: 300px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#certificateModal">
          </div>
          <div class="d-flex justify-content-center">
            <button type="button" id="approveButton" class="btn btn-success me-3">Aprobar</button>
            <button type="button" id="rejectButton" class="btn btn-danger">Rechazar</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal fade" id="certificateModal" tabindex="-1" aria-labelledby="certificateModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="certificateModalLabel">Certificado de Secundaria</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <img id="modalCertificateImage" src="${request.certificateUrl}" alt="Certificado Completo" class="img-fluid w-100">
          </div>
        </div>
      </div>
    </div>
  `;
}

// Función para cargar la solicitud y actualizar el contador
function loadRequest() {
  const counterDiv = document.getElementById('counter');
  const detailsDiv = document.getElementById('requestDetails');
  if (currentIndex < requests.length) {
    counterDiv.innerText = `Solicitud ${currentIndex + 1} de ${requests.length}`;
    detailsDiv.innerHTML = renderRequest(requests[currentIndex]);
  } else {
    counterDiv.innerText = '';
    detailsDiv.innerHTML = '<p class="text-center">No hay más solicitudes asignadas.</p>';
  }
}

// Manejo de eventos para aprobar o rechazar
document.addEventListener('click', function (event) {
  if (event.target && event.target.id === 'approveButton') {
    alert('Solicitud aprobada');
    currentIndex++;
    loadRequest();
  }
  if (event.target && event.target.id === 'rejectButton') {
    let reason = prompt('Ingrese el motivo de rechazo:');
    if (reason) {
      alert('Solicitud rechazada: ' + reason);
      currentIndex++;
      loadRequest();
    }
  }
});

document.addEventListener('click', function (event) {
  if (event.target && event.target.tagName === 'IMG' && event.target.dataset.bsTarget === "#certificateModal") {
      document.getElementById('modalCertificateImage').src = event.target.src;
  }
});

// Inicializar la carga de la primera solicitud cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', loadRequest);