let currentContactPage = 1;
const contactsPerPage = 10;

// Cargar contactos con paginación
function loadContacts() {
  const contactsList = document.getElementById("contactsList");
  const loadingIndicator = document.getElementById("loadingContacts");
  const errorMessage = document.getElementById("errorContacts");

  if (!contactsList || !loadingIndicator || !errorMessage) {
    console.warn("Elementos de contactos no encontrados.");
    return;
  }

  loadingIndicator.style.display = "block";
  contactsList.innerHTML = "";
  errorMessage.style.display = "none";

  fetch("/api/students/controllers/getStudentContacts.php")
    .then((response) => response.json())
    .then((data) => {
      loadingIndicator.style.display = "none";

      if (data.status === "failure") {
        errorMessage.style.display = "block";
        errorMessage.textContent =
          "Error al cargar contactos: " + data.error.message;
        return;
      }

      if (!data.data || data.data.length === 0) {
        contactsList.innerHTML =
          '<p class="text-muted">No hay contactos disponibles.</p>';
        return;
      }

      window.contactsData = data.data;
      renderContactsPage(currentContactPage);
      renderPagination(window.contactsData);
    })
    .catch((error) => {
      console.error("Error al obtener contactos:", error);
      loadingIndicator.style.display = "none";
      if (errorMessage) {
        errorMessage.style.display = "block";
        errorMessage.textContent = "Error al obtener contactos.";
      }
    });
}

// Página actual de contactos
function renderContactsPage(page) {
  const contactsList = document.getElementById("contactsList");
  contactsList.innerHTML = "";

  const contacts = window.contactsData || [];
  const start = (page - 1) * contactsPerPage;
  const end = start + contactsPerPage;
  const contactsToShow = contacts.slice(start, end);

  contactsToShow.forEach((contact, index) => {
    const realIndex = start + index;
    const contactHTML = `
      <div class="list-group-item list-group-item-action">
        <div class="d-flex w-100 justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <img src="${contact.profilePhoto}" alt="Foto de perfil" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">
            <div>
              <h6 class="mb-1">${contact.fullName}</h6>
              <p class="mb-1 small text-muted">Email: ${contact.institutionalEmail}</p>
            </div>
          </div>
          <div>
            <a href="#" class="btn btn-sm btn-outline-success me-1" title="Chat" onclick="openChatFromContacts('${contact.fullName}', '${contact.studentId}')">
              <i class="fa-solid fa-comments"></i>
            </a>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#contactActions${realIndex}" aria-expanded="false">
              <i class="fa-solid fa-ellipsis-v"></i>
            </button>
          </div>
        </div>
        <div class="collapse mt-2" id="contactActions${realIndex}">
          <div class="d-flex">
            <button class="btn btn-sm btn-primary me-2">Agregar a Grupo</button>
            <button class="btn btn-sm btn-danger" onclick="deleteContact(this)" data-target-id="${contact.studentId}">
              Eliminar Contacto
            </button>
          </div>
        </div>
      </div>`;
    contactsList.innerHTML += contactHTML;
  });
}

// Controles de paginación
function renderPagination(contacts) {
  const paginationContainer = document.getElementById("paginationContacts");
  if (!paginationContainer) return;
  const totalPages = Math.ceil(contacts.length / contactsPerPage);
  let paginationHTML = `<nav aria-label="Paginación de contactos">
    <ul class="pagination justify-content-center">`;
  paginationHTML += `
    <li class="page-item ${currentContactPage === 1 ? "disabled" : ""}">
      <a class="page-link" href="#" onclick="changePage(${
        currentContactPage - 1
      }); return false;">Anterior</a>
    </li>`;
  for (let i = 1; i <= totalPages; i++) {
    paginationHTML += `<li class="page-item ${
      currentContactPage === i ? "active" : ""
    }">
        <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
      </li>`;
  }
  paginationHTML += `
    <li class="page-item ${
      currentContactPage === totalPages ? "disabled" : ""
    }">
      <a class="page-link" href="#" onclick="changePage(${
        currentContactPage + 1
      }); return false;">Siguiente</a>
    </li>
  </ul>
  </nav>`;

  paginationContainer.innerHTML = paginationHTML;
}

function changePage(page) {
  const totalPages = Math.ceil(
    (window.contactsData || []).length / contactsPerPage
  );
  if (page < 1 || page > totalPages) return;
  currentContactPage = page;
  renderContactsPage(currentContactPage);
  renderPagination(window.contactsData);
}

// Función para mostrar mensajes
function displayMessage(message, type = "info") {
  const messageContainer = document.getElementById("friendRequestMessage");

  const alertHTML = `
    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  `;

  messageContainer.innerHTML = alertHTML;
}

// Función para enviar solicitud de amistad
function sendFriendRequest() {
  const emailInput = document.getElementById("contactEmailInput");
  const email = emailInput.value.trim();

  if (!email) {
    displayMessage("Por favor, introduce un correo electrónico.", "warning");
    return;
  }

  fetch("/api/students/controllers/sendFriendRequest.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ receiverEmail: email }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        displayMessage(
          "Solicitud de amistad enviada exitosamente a: " + email,
          "success"
        );
        emailInput.value = "";
      } else {
        displayMessage(
          "Error al enviar solicitud de amistad: " +
            (data.error && data.error.message
              ? data.error.message
              : "Correo de estudiante no valido"),
          "danger"
        );
      }
    })
    .catch((error) => {
      console.error("Error en la solicitud de amistad:", error);
      displayMessage(
        "Error al procesar la solicitud. Por favor, inténtalo de nuevo más tarde.",
        "danger"
      );
    });
}

let currentGroupPage = 1;
let totalGroupPages = 1;

// Cargar grupos
function loadGroups(page = 1) {
  const loadingMsg = document.getElementById("groupsLoading");
  const groupsList = document.getElementById("groupsList");
  const pagination = document.getElementById("groupPagination");

  if (!loadingMsg || !groupsList || !pagination) {
    console.warn("Elementos de grupos no encontrados.");
    return;
  }

  loadingMsg.classList.remove("hidden");
  groupsList.classList.add("hidden");
  pagination.classList.add("hidden");

  fetch(`/api/students/controllers/getStudentGroups.php?page=${page}`, {
    method: "GET",
    credentials: "include",
  })
    .then((response) => response.json())
    .then((data) => {
      loadingMsg.classList.add("hidden");
      groupsList.classList.remove("hidden");
      groupsList.innerHTML = "";

      if (
        data.status === "success" &&
        Array.isArray(data.data) &&
        data.data.length > 0
      ) {
        currentGroupPage = data.currentPage || 1;
        totalGroupPages = data.totalPages || 1;

        data.data.forEach((group) => {
          const groupItem = document.createElement("div");
          groupItem.className = "list-group-item";
          groupItem.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-1">${group.groupName}</h6>
                <small>Miembros: ${group.membersCount}</small>
              </div>
              <div>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="openGroupChat('${group.groupId}', '${group.groupName}')">
                  <i class="fa-solid fa-comments"></i> Chat
                </button>
                <button class="btn btn-sm btn-outline-success me-1">
                  <i class="fa-solid fa-user-plus"></i> Agregar Miembro
                </button>
                <button class="btn btn-sm btn-outline-danger">
                  <i class="fa-solid fa-trash"></i> Borrar Grupo
                </button>
              </div>
            </div>`;
          groupsList.appendChild(groupItem);
        });

        pagination.classList.remove("hidden");
        renderGroupPagination();
      } else {
        groupsList.innerHTML = `<div class="list-group-item text-center text-muted">No tienes grupos aún.</div>`;
      }
    })
    .catch((error) => {
      console.error("Error al obtener grupos:", error);
      loadingMsg.classList.add("hidden");
      groupsList.classList.remove("hidden");
      groupsList.innerHTML = `<div class="list-group-item text-center text-danger">Error al cargar los grupos.</div>`;
    });
}

function renderGroupPagination() {
  const pagination = document.getElementById("groupPagination");
  if (!pagination) return;

  pagination.innerHTML = `
    <button class="btn btn-sm btn-outline-secondary me-2" ${
      currentGroupPage <= 1 ? "disabled" : ""
    } onclick="loadGroups(${currentGroupPage - 1})">
      <i class="fa-solid fa-arrow-left"></i> Anterior
    </button>
    <span class="me-2">Página ${currentGroupPage} de ${totalGroupPages}</span>
    <button class="btn btn-sm btn-outline-secondary" ${
      currentGroupPage >= totalGroupPages ? "disabled" : ""
    } onclick="loadGroups(${currentGroupPage + 1})">
      Siguiente <i class="fa-solid fa-arrow-right"></i>
    </button>
  `;
}

// Función para abrir el chat de grupo
function openGroupChat(groupName, groupId) {
  document.getElementById("groupChatName").innerText = groupName;
  const modal = document.getElementById("groupChatModal");
  modal.setAttribute("data-group-id", groupId);
  modal.setAttribute("data-group-name", groupName);
  loadGroupChat(groupName, groupId);
  var groupChatModal = new bootstrap.Modal(
    document.getElementById("groupChatModal")
  );
  groupChatModal.show();
}

document.addEventListener("DOMContentLoaded", function () {
  loadContacts();

  const groupsModal = document.getElementById("groupsModal");
  if (groupsModal) {
    groupsModal.addEventListener("shown.bs.modal", function () {
      loadGroups();
    });
  }
});

// Función para mostrar el Toast
function showToast(header, message) {
  document.getElementById("toastHeader").innerText = header;
  document.getElementById("toastBody").innerText = message;
  var toastEl = document.getElementById("liveToast");
  var toast = new bootstrap.Toast(toastEl);
  toast.show();
}

// Función modal de confirmación
function showConfirmationModal(message) {
  return new Promise((resolve) => {
    const modalElement = document.getElementById("confirmationModal");
    document.getElementById("confirmationMessage").innerText = message;

    const modal = new bootstrap.Modal(modalElement);

    const confirmButton = document.getElementById("confirmButton");
    const cancelButton = document.getElementById("cancelButton");

    // Funciones manejadoras para evitar múltiples asignaciones
    const onConfirm = () => {
      cleanup();
      resolve(true);
    };

    const onCancel = () => {
      cleanup();
      resolve(false);
    };

    // Función para limpiar los event listeners y esconder el modal
    function cleanup() {
      confirmButton.removeEventListener("click", onConfirm);
      cancelButton.removeEventListener("click", onCancel);
      modalElement.removeEventListener("hidden.bs.modal", onCancel);
      modal.hide();
    }

    confirmButton.addEventListener("click", onConfirm);
    cancelButton.addEventListener("click", onCancel);
    modalElement.addEventListener("hidden.bs.modal", onCancel);

    modal.show();
  });
}

// Función para eliminar contacto utilizando Bootstrap para notificar
function deleteContact(button) {
  const targetId = button.getAttribute("data-target-id");

  if (!targetId) {
    console.error("No se proporcionó el ID del contacto a eliminar.");
    showToast("Error", "No se proporcionó el ID del contacto.");
    return;
  }

  showConfirmationModal(
    "¿Estás seguro de que deseas eliminar este contacto?"
  ).then((confirmed) => {
    if (!confirmed) {
      showToast("Cancelado", "La eliminación del contacto ha sido cancelada.");
      return;
    }

    const requestData = {
      targetId: targetId,
      action: "REJECTED",
    };

    fetch("/api/students/controllers/deleteOrBlockContact.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(requestData),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          showToast("Éxito", "Contacto eliminado correctamente.");
          showAnimatedSuccessMessage();
          setTimeout(() => {
            location.reload();
          }, 3200);
        } else {
          console.error("Error del servidor:", data?.error?.message);
          showToast("Error", "Ocurrió un error al eliminar el contacto.");
        }
      })
      .catch((error) => {
        console.error("Error de red:", error);
        showToast("Error", "No se pudo conectar con el servidor.");
      });
  });
}

function showAnimatedSuccessMessage() {
  const messageEl = document.getElementById("successMessage");
  messageEl.classList.add("show");
  setTimeout(() => {
    messageEl.classList.remove("show");
  }, 3000);
}
