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
        totalPending = data.data.pending_count;
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

// Función que determina y muestra el visor adecuado según la extensión
function showCertificate(url) {
  var spinner = document.getElementById("spinnerCertificate");

  // Si la URL es nula, vacía o no existe, mostrar mensaje y salir
  if (!url) {
    if (spinner) spinner.style.display = "none";
    var modalBody = document.getElementById("modalBody");
    if (modalBody) {
      modalBody.innerHTML = `
        <div class="text-center">
          <p>No se encontró certificado.</p>
        </div>
      `;
    }
    return;
  }

  if (spinner) spinner.style.display = "block";

  // Ocultar todos los contenedores de visor
  var pdfViewer = document.getElementById("pdfViewer");
  var imgViewer = document.getElementById("imgViewer");
  var docViewer = document.getElementById("docViewer");
  var fallbackViewer = document.getElementById("fallbackViewer");

  if (pdfViewer) pdfViewer.style.display = "none";
  if (imgViewer) imgViewer.style.display = "none";
  if (docViewer) docViewer.style.display = "none";
  if (fallbackViewer) fallbackViewer.style.display = "none";

  // Obtener la extensión del archivo
  var extension = url.split(".").pop().toLowerCase();

  if (extension === "pdf") {
    var pdfObject = document.getElementById("pdfObject");
    if (pdfObject) {
      pdfObject.setAttribute("data", url);
      var pdfDownloadLink = document.getElementById("pdfDownloadLink");
      if (pdfDownloadLink) pdfDownloadLink.setAttribute("href", url);
      // Cuando se cargue el PDF, ocultar el spinner
      pdfObject.onload = function () {
        if (spinner) spinner.style.display = "none";
      };
      if (pdfViewer) pdfViewer.style.display = "block";
    }
  } else if (["jpg", "jpeg", "png", "gif", "tiff", "bmp"].includes(extension)) {
    var certificateImg = document.getElementById("certificateImg");
    if (certificateImg) {
      certificateImg.onload = function () {
        if (spinner) spinner.style.display = "none";
      };
      certificateImg.setAttribute("src", url);
      if (imgViewer) imgViewer.style.display = "block";
    }
  } else if (
    ["doc", "docx", "xls", "xlsx", "ppt", "pptx"].includes(extension)
  ) {
    var viewerUrl =
      "https://docs.google.com/gview?url=" +
      encodeURIComponent(url) +
      "&embedded=true";
    var docIframe = document.getElementById("docIframe");
    if (docIframe) {
      docIframe.onload = function () {
        if (spinner) spinner.style.display = "none";
      };
      docIframe.setAttribute("src", viewerUrl);
      if (docViewer) docViewer.style.display = "block";
    }
  } else {
    var fallbackDownloadLink = document.getElementById("fallbackDownloadLink");
    if (fallbackDownloadLink) fallbackDownloadLink.setAttribute("href", url);
    // Si no hay visor definido, ocultamos el spinner inmediatamente
    if (spinner) spinner.style.display = "none";
    if (fallbackViewer) fallbackViewer.style.display = "block";
  }
}

// Listener para actualizar el contenido del modal al abrirlo
var certificateModal = document.getElementById("certificateModal");
certificateModal.addEventListener("show.bs.modal", function (event) {
  certificateModal.removeAttribute("aria-hidden");

  var button = event.relatedTarget;
  var certificateUrl = button.getAttribute("data-certificate-url");

  // Mostrar spinner y mensaje mientras se carga el certificado en el modal
  var modalBody = document.getElementById("modalBody");
  if (modalBody) {
    modalBody.innerHTML = `
      <div class="d-flex flex-column justify-content-center align-items-center">
        <div id="spinnerCertificate" class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3">Cargando Certificado…</p>
      </div>
    `;
  }

  // Llamar a la función que mostrará el certificado en el visor correspondiente
  showCertificate(certificateUrl);
});

certificateModal.addEventListener("hidden.bs.modal", function () {
  certificateModal.setAttribute("aria-hidden", "true");
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
        console.error("Error en la respuesta:", data);
        detailsDiv.innerHTML = `<p>${data.error.errorMessage}</p>`;
        return;
      }

      if (!data.data || !Array.isArray(data.data) || data.data.length === 0) {
        console.error("No hay solicitudes pendientes:", data);
        detailsDiv.innerHTML = "<p>No hay solicitudes pendientes.</p>";
        return;
      }

      const application = data.data[0]; // Primera solicitud del array

      if (!application || typeof application !== "object") {
        console.error("La solicitud recibida no es válida:", application);
        detailsDiv.innerHTML = "<p>Error al procesar la solicitud.</p>";
        return;
      }

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

      // Renderizar la solicitud
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
      console.error("Error al cargar la solicitud:", error);
      detailsDiv.innerHTML = `<p>Error al cargar la solicitud.</p>`;
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
  // Aprobar solicitud
  if (event.target && event.target.id === "approveButton") {
    fetch("/api/reviewers/controllers/approveApplication.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        applicationCode: currentApplicationCode,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
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
        } else {
          throw new Error(
            data.error.errorMessage || "Error al aprobar la solicitud"
          );
        }
      })
      .catch((error) => {
        console.error("Error al aprobar la solicitud:", error);
      });
  }

  // Rechazar solicitud
  if (event.target && event.target.id === "rejectButton") {
    let reason = prompt("Ingrese el motivo de rechazo:");
    if (reason) {
      fetch("/api/reviewers/controllers/rejectApplication.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          applicationCode: currentApplicationCode,
          commentary: reason,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
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
          } else {
            throw new Error(
              data.error.errorMessage || "Error al rechazar la solicitud"
            );
          }
        })
        .catch((error) => {
          console.error("Error al rechazar la solicitud:", error);
        });
    }
  }
});
