let currentIndex = 0;
let totalPending = 0;
let currentApplicationCode = null; // Almacenará el APPLICATION_CODE de la solicitud actual

// Función para actualizar el contador en la parte superior
function updateCounter() {
  const counterDiv = document.getElementById("counter");
  counterDiv.innerHTML = `Solicitud ${currentIndex + 1} / ${totalPending}`;
}

// Función para obtener el total de solicitudes pendientes
function loadPendingCount() {
  return fetch("/api/reviewers/controllers/getPendingCount.php")
    .then(response => response.json())
    .then(data => {
      if (data.status === "success") {
        totalPending = data.count;
      } else {
        totalPending = 0;
      }
    })
    .catch(error => {
      console.error("Error fetching pending count", error);
      totalPending = 0;
    });
}

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
            <button type="button" id="certificate-btn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#certificateModal" data-certificate-url="${request.certificateUrl}">
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
    fallbackLink.href = "#";
  }
});

// Función para cargar la siguiente solicitud pendiente y actualizar la interfaz
function loadRequest() {
  const detailsDiv = document.getElementById("requestDetails"); // Contenedor donde se mostrará la tarjeta
  fetch("/api/reviewers/controllers/getNextPendingApplication.php")
    .then(response => response.json())
    .then(data => {
      // Si la respuesta indica error, mostramos mensaje 
      if (data.status === "0") {
        detailsDiv.innerHTML = `<p>${data.error.errorMessage || "No se encontró una aplicación pendiente."}</p>`;
        return;
      }
      
      // objeto request con los datos obtenidos
      let request = {
        id: data.data["APPLICATION_CODE"],
        nombre: data.data["FIRST_NAME"],
        apellido: data.data["LAST_NAME"],
        identidad: data.data["IDENTIDAD"],
        email: data.data["CORREO"],
        centroRegional: data.data["CENTRO_REGIONAL"],
        carreraPrincipal: data.data["CARRERA_PRINCIPAL"],
        carreraSecundaria: data.data["CARRERA_SECUNDARIA"] || "",
        certificateUrl: data.data["CERTIFICATE_FILE"]
      };

      // Guardamos el ID de la solicitud actual para actualizarla al aprobar/rechazar
      currentApplicationCode = request.id;

      // Renderizamos la tarjeta con los datos y la insertamos en el contenedor
      detailsDiv.innerHTML = renderRequest(request);

      // Se actualiza el atributo data del botón del certificado por si se requiere más adelante
      let certificateBtn = document.getElementById("certificate-btn");
      if (certificateBtn) {
        certificateBtn.setAttribute("data-certificate-url", request.certificateUrl);
      }
    })
    .catch(error => {
      console.error("No se pudo traer una aplicación!", error);
      detailsDiv.innerHTML = `<p>Error al cargar la aplicación.</p>`;
    });
}

// Función para actualizar el estado de la solicitud
function updateApplicationStatus(applicationCode, status, reason = "") {
  return fetch("/api/reviewers/controllers/updateApplicationStatus.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      APPLICATION_CODE: applicationCode,
      STATUS: status,
      REASON: reason
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.status !== "success") {
      throw new Error(data.error.errorMessage || "Error al actualizar la solicitud");
    }
    return data;
  });
}

// Manejo de eventos para aprobar o rechazar la solicitud
document.addEventListener("click", function (event) {
  if (event.target && event.target.id === "approveButton") {
    updateApplicationStatus(currentApplicationCode, 1)
      .then(() => {
        alert("Solicitud aprobada");
        currentIndex++;
        totalPending--;
        updateCounter();
        loadRequest();
      })
      .catch(error => {
        console.error("Error al aprobar la solicitud:", error);
      });
  }
  if (event.target && event.target.id === "rejectButton") {
    let reason = prompt("Ingrese el motivo de rechazo:");
    if (reason) {
      updateApplicationStatus(currentApplicationCode, 2, reason)
        .then(() => {
          alert("Solicitud rechazada: " + reason);
          currentIndex++;
          totalPending--;
          updateCounter();
          loadRequest();
        })
        .catch(error => {
          console.error("Error al rechazar la solicitud:", error);
        });
    }
  }
});

// Inicializamos la carga del contador y la primera solicitud cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", function() {
  loadPendingCount().then(() => {
    updateCounter();
    loadRequest();
  });
});
