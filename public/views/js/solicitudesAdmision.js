let currentIndex = 0;
let totalPending = 0;
let currentApplicationCode = null; // Almacenará el APPLICATION_CODE de la solicitud actual

// Limpiar el localStorage al cargar la página
document.addEventListener("DOMContentLoaded", function () {
  localStorage.removeItem("pendingApplications");
  loadPendingCount().then(() => {
    updateCounter();
    if (totalPending > 0) {
      loadRequest();
    } else {
      document.getElementById("requestDetails").innerHTML =
        "<p>No hay solicitudes pendientes.</p>";
    }
  });
});

// Función para actualizar el contador en la parte superior
function updateCounter() {
  const counterDiv = document.getElementById("counter");
  if (totalPending === 0) {
    counterDiv.innerHTML = "No hay solicitudes pendientes";
  } else {
    counterDiv.innerHTML = `Solicitudes pendientes: ${totalPending}`;
  }
}

// Función para obtener el total de solicitudes pendientes
function loadPendingCount() {
  return fetch(
    `/api/reviewers/controllers/getPendingCount.php?reviewer_id=${reviewerId}`
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        totalPending = data.count;
      } else {
        totalPending = 0;
      }
    })
    .catch((error) => {
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
  certificateModal.removeAttribute("aria-hidden");

  var button = event.relatedTarget;
  var certificateUrl = button.getAttribute("data-certificate-url");
  var modalObject = document.getElementById("modalCertificateObject");
  modalObject.setAttribute("data", certificateUrl);

  var fallbackLink = modalObject.querySelector("a");
  if (fallbackLink) {
    fallbackLink.href = certificateUrl;
  }
});

// Función para almacenar una solicitud en el localStorage
function storeRequestInLocalStorage(request) {
  let storedRequests =
    JSON.parse(localStorage.getItem("pendingApplications")) || [];
  if (!storedRequests.some((item) => item.id === request.id)) {
    storedRequests.push(request);
    localStorage.setItem("pendingApplications", JSON.stringify(storedRequests));
  }
}

// Función para eliminar una solicitud del localStorage
function removeFromLocalStorage(applicationCode) {
  let storedRequests =
    JSON.parse(localStorage.getItem("pendingApplications")) || [];
  storedRequests = storedRequests.filter((item) => item.id !== applicationCode);
  localStorage.setItem("pendingApplications", JSON.stringify(storedRequests));
}

// Función para cargar la solicitud pendiente
function loadRequest() {
  const detailsDiv = document.getElementById("requestDetails"); // Contenedor donde se mostrará la tarjeta

  // Mostrar spinner y mensaje mientras se carga la solicitud
  detailsDiv.innerHTML = `
    <div class="d-flex flex-column justify-content-center align-items-center">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-3">Cargando Solicitudes…</p>
    </div>
  `;

  fetch(
    `/api/reviewers/controllers/getNextPendingApplication.php?reviewer_id=${reviewerId}`
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "failure") {
        console.error("La respuesta no contiene data:", data);
        detailsDiv.innerHTML = `<p>${data.error.errorMessage}</p>`;
        return;
      }

      if (!data.data) {
        console.error("La respuesta no contiene data:", data);
        detailsDiv.innerHTML = "<p>Error al cargar la aplicación.</p>";
        return;
      }

      const application = data.data;
      let request = {
        id: application.APPLICATION_CODE,
        nombre: application.FIRST_NAME,
        apellido: application.LAST_NAME,
        identidad: application.DNI,
        email: application.EMAIL,
        centroRegional: application.CENTER_NAME,
        carreraPrincipal: application.MAJOR_NAME,
        carreraSecundaria: application.SECOND_MAJOR_NAME || "",
        certificateUrl: application.CERTIFICATE_FILE || "",
      };

      currentApplicationCode = request.id;

      // Almacenar la solicitud en el localStorage
      storeRequestInLocalStorage(request);

      detailsDiv.innerHTML = renderRequest(request);

      let certificateBtn = document.getElementById("certificate-btn");
      if (certificateBtn) {
        certificateBtn.setAttribute(
          "data-certificate-url",
          request.certificateUrl
        );
      }
    })
    .catch((error) => {
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
      REASON: reason,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status !== "success") {
        throw new Error(
          data.error.errorMessage || "Error al actualizar la solicitud"
        );
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
        // Remover de localStorage ya que el status cambió
        removeFromLocalStorage(currentApplicationCode);
        currentIndex++;
        totalPending--;
        updateCounter();
        if (totalPending > 0) {
          loadRequest();
        } else {
          document.getElementById("requestDetails").innerHTML =
            "<p>No hay solicitudes pendientes.</p>";
        }
      })
      .catch((error) => {
        console.error("Error al aprobar la solicitud:", error);
      });
  }
  if (event.target && event.target.id === "rejectButton") {
    let reason = prompt("Ingrese el motivo de rechazo:");
    if (reason) {
      updateApplicationStatus(currentApplicationCode, 2, reason)
        .then(() => {
          alert("Solicitud rechazada: " + reason);
          // Remover de localStorage ya que el status cambió
          removeFromLocalStorage(currentApplicationCode);
          currentIndex++;
          totalPending--;
          updateCounter();
          if (totalPending > 0) {
            loadRequest();
          } else {
            document.getElementById("requestDetails").innerHTML =
              "<p>No hay solicitudes pendientes.</p>";
          }
        })
        .catch((error) => {
          console.error("Error al rechazar la solicitud:", error);
        });
    }
  }
});
