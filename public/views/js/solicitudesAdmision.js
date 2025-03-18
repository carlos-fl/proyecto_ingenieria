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
    certificateUrl: "assets/img/certificado.webp",
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
    certificateUrl: "assets/img/certificado.webp",
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
    certificateUrl: "assets/img/certificado.webp",
  },
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
          <!-- Botón para mostrar el certificado -->
          <div class="text-center mb-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#certificateModal" data-certificate-url="${request.certificateUrl}">
              Mostrar Certificado
            </button>
          </div>
          <div class="d-flex justify-content-center">
            <button type="button" id="approveButton" class="btn btn-aprobar me-3">Aprobar</button>
            <button type="button" id="rejectButton" class="btn btn-danger">Rechazar</button>
          </div>
        </form>
      </div>
    </div>
  `;
}

// Listener para actualizar el certificado en la modal al abrirla
var certificateModal = document.getElementById("certificateModal");
certificateModal.addEventListener("show.bs.modal", function (event) {
  var button = event.relatedTarget;
  var certificateUrl = button.getAttribute("data-certificate-url");
  var modalObject = document.getElementById("modalCertificateObject");
  modalObject.setAttribute("data", certificateUrl);
  var fallbackLink = modalObject.querySelector("a");
  if (fallbackLink) {
    fallbackLink.href = certificateUrl;
  }
});

// Función para cargar la solicitud y actualizar el contador
function loadRequest() {
  const counterDiv = document.getElementById("counter");
  const detailsDiv = document.getElementById("requestDetails");
  if (currentIndex < requests.length) {
    counterDiv.innerText = `Solicitud ${currentIndex + 1} de ${
      requests.length
    }`;
    detailsDiv.innerHTML = renderRequest(requests[currentIndex]);
  } else {
    counterDiv.innerText = "";
    detailsDiv.innerHTML =
      '<p class="text-center">No hay más solicitudes asignadas.</p>';
  }
}

// Manejo de eventos para aprobar o rechazar
document.addEventListener("click", function (event) {
  if (event.target && event.target.id === "approveButton") {
    alert("Solicitud aprobada");
    currentIndex++;
    loadRequest();
  }
  if (event.target && event.target.id === "rejectButton") {
    let reason = prompt("Ingrese el motivo de rechazo:");
    if (reason) {
      alert("Solicitud rechazada: " + reason);
      currentIndex++;
      loadRequest();
    }
  }
});

document.addEventListener("click", function (event) {
  if (
    event.target &&
    event.target.tagName === "IMG" &&
    event.target.dataset.bsTarget === "#certificateModal"
  ) {
    document.getElementById("modalCertificateImage").src = event.target.src;
  }
});

// Inicializar la carga de la primera solicitud cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", loadRequest);
