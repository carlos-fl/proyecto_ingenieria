let currentContactPage = 1;
const contactsPerPage = 10;

// Cargar contactos
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
                <button class="btn btn-sm btn-outline-primary me-1" onclick="openGroupChat('${group.groupName}', '${group.groupId}')">
                  <i class="fa-solid fa-comments"></i> Chat
                </button>
                <button class="btn btn-sm btn-outline-success me-1" onclick="openAddMembersModal('${group.groupId}')">
                  <i class="fa-solid fa-user-plus"></i> Agregar Miembro
                </button>
                <button class="btn btn-sm btn-outline-danger delete-group-btn" data-group-id="${group.groupId}">
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

// Función para eliminar contacto
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

document.addEventListener("DOMContentLoaded", function () {
  let currentPage = 1;
  const limit = 20;

  // Función para cargar solicitudes de amistad
  function loadFriendRequests(page = 1) {
    currentPage = page;
    const url = `/api/students/controllers/getPendingContactRequests.php?page=${page}`;
    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        const container = document.getElementById("friendRequestsContent");
        container.innerHTML = "";
        if (data.status === "success" && data.data.length > 0) {
          data.data.forEach((request) => {
            const item = document.createElement("div");
            item.className = "card mb-2";
            item.innerHTML = `
              <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                  <img src="../../assets/img/default-profile.png" alt="Foto" class="rounded-circle me-2" width="40">
                  <strong>${request.FIRST_NAME}</strong>
                </div>
                <div>
                  <button class="btn btn-sm btn-success me-1" onclick="handleFriendRequest(${request.ID_STUDENT}, 'accept')">Aceptar</button>
                  <button class="btn btn-sm btn-danger" onclick="handleFriendRequest(${request.ID_STUDENT}, 'reject')">Rechazar</button>
                </div>
              </div>
            `;
            container.appendChild(item);
          });
        } else {
          container.innerHTML =
            '<p class="text-center">No hay solicitudes pendientes.</p>';
        }
        setupPagination(data.totalPages, data.currentPage);
      })
      .catch((err) => {
        console.error(err);
        showMessage("Error al cargar solicitudes.", "danger");
      });
  }

  function setupPagination(totalPages, currentPage) {
    const pagination = document.getElementById("friendRequestsPagination");
    pagination.innerHTML = "";
    const prevItem = document.createElement("li");
    prevItem.className = `page-item ${currentPage == 1 ? "disabled" : ""}`;
    prevItem.innerHTML = `<a class="page-link" href="#" aria-label="Anterior"><span aria-hidden="true">&laquo;</span></a>`;
    prevItem.addEventListener("click", (e) => {
      e.preventDefault();
      if (currentPage > 1) loadFriendRequests(currentPage - 1);
    });
    pagination.appendChild(prevItem);
    for (let i = 1; i <= totalPages; i++) {
      const pageItem = document.createElement("li");
      pageItem.className = `page-item ${i === currentPage ? "active" : ""}`;
      pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
      pageItem.addEventListener("click", (e) => {
        e.preventDefault();
        loadFriendRequests(i);
      });
      pagination.appendChild(pageItem);
    }
    const nextItem = document.createElement("li");
    nextItem.className = `page-item ${
      currentPage == totalPages ? "disabled" : ""
    }`;
    nextItem.innerHTML = `<a class="page-link" href="#" aria-label="Siguiente"><span aria-hidden="true">&raquo;</span></a>`;
    nextItem.addEventListener("click", (e) => {
      e.preventDefault();
      if (currentPage < totalPages) loadFriendRequests(currentPage + 1);
    });
    pagination.appendChild(nextItem);
  }

  // Función para manejar aceptar/rechazar solicitud
  window.handleFriendRequest = function (requestId, action) {
    const actionUpper = action.toUpperCase();
    fetch(`/api/students/controllers/respondContactRequest.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ requesterId: requestId, action: actionUpper }),
    })
      .then((response) => response.json())
      .then((result) => {
        if (result.status === "success") {
          showMessage(
            `Solicitud ${
              actionUpper === "ACCEPT"
                ? "aceptada"
                : actionUpper === "REJECT"
                ? "rechazada"
                : "procesada"
            } correctamente.`,
            "success"
          );
          loadFriendRequests(currentPage);
        } else {
          showMessage(
            `Error al procesar la solicitud (${actionUpper}).`,
            "danger"
          );
        }
      })
      .catch((err) => {
        console.error(err);
        showMessage("Error al procesar la solicitud.", "danger");
      });
  };

  // Función para mostrar mensajes
  function showMessage(message, type = "info") {
    const messageContainer = document.getElementById("notificationMessage");
    messageContainer.innerHTML = `
      <div class="alert alert-${type} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
      </div>
    `;
  }

  const notificationsModal = document.getElementById("notificationsModal");
  notificationsModal.addEventListener("shown.bs.modal", () => {
    if (
      document.querySelector("#friendRequests-tab").classList.contains("active")
    ) {
      loadFriendRequests(1);
    }
  });

  const friendRequestsTab = document.getElementById("friendRequests-tab");
  friendRequestsTab.addEventListener("shown.bs.tab", function () {
    loadFriendRequests(1);
  });
});

document
  .getElementById("createGroupBtn")
  .addEventListener("click", function () {
    const groupNameInput = document.getElementById("groupNameInput");
    const feedbackDiv = document.getElementById("groupFeedback");
    const groupName = groupNameInput.value.trim();
    feedbackDiv.innerHTML = "";

    if (!groupName) {
      feedbackDiv.innerHTML = `
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        El nombre del grupo es obligatorio.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>`;
      return;
    }

    feedbackDiv.innerHTML = `
    <div class="d-flex align-items-center text-primary">
      <div class="spinner-border me-2" role="status" aria-hidden="true"></div>
      Creando grupo...
    </div>`;

    fetch("/api/students/controllers/createGroup.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ groupName }),
    })
      .then((res) => res.json())
      .then((result) => {
        if (result.status === "success") {
          feedbackDiv.innerHTML = `
        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
          Grupo <strong>${groupName}</strong> creado exitosamente. ID: ${result.data.groupId}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
          groupNameInput.value = "";
        } else {
          throw new Error(result.error?.message || "Error desconocido");
        }
      })
      .catch((error) => {
        feedbackDiv.innerHTML = `
      <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        Hubo un error al crear el grupo: ${error.message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>`;
      });
  });

// Eliminar Grupo
let groupIdToDelete = null;
let deleteBtnElement = null;

function showToast(message, type = "success") {
  const container = document.getElementById("toastContainer");
  const toast = document.createElement("div");
  toast.className = `alert alert-${type} alert-dismissible fade show`;
  toast.role = "alert";
  toast.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  `;
  container.appendChild(toast);
  setTimeout(() => {
    toast.classList.remove("show");
    toast.classList.add("hide");
    setTimeout(() => toast.remove(), 300);
  }, 4000);
}

document.addEventListener("click", function (event) {
  const btn = event.target.closest(".delete-group-btn");
  if (btn) {
    groupIdToDelete = parseInt(btn.dataset.groupId);
    deleteBtnElement = btn;
    const groupName = btn
      .closest(".list-group-item")
      ?.querySelector("h6")
      ?.textContent?.trim();
    if (groupName) {
      document.getElementById("groupNameInModal").textContent = groupName;
    }

    const modal = new bootstrap.Modal(
      document.getElementById("confirmDeleteGroupModal")
    );
    modal.show();
  }
});

document
  .getElementById("confirmDeleteGroupBtn")
  .addEventListener("click", function () {
    if (!groupIdToDelete || !deleteBtnElement) return;

    const spinner = document.getElementById("deleteSpinner");
    spinner.classList.remove("d-none");
    this.disabled = true;

    fetch("/api/students/controllers/deleteGroup.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      credentials: "include",
      body: JSON.stringify({ groupId: groupIdToDelete }),
    })
      .then((res) => res.json())
      .then((result) => {
        const modalEl = bootstrap.Modal.getInstance(
          document.getElementById("confirmDeleteGroupModal")
        );
        modalEl.hide();

        if (result.status === "success") {
          const item = deleteBtnElement.closest(".list-group-item");
          if (item) item.remove();
          showToast("Grupo eliminado correctamente.", "success");
        } else {
          showToast(
            "No se pudo eliminar el grupo: " + (result?.error?.message || ""),
            "danger"
          );
        }
      })
      .catch((err) => {
        console.error(err);
        showToast("Error inesperado al eliminar el grupo.", "danger");
      })
      .finally(() => {
        groupIdToDelete = null;
        deleteBtnElement = null;
        document.getElementById("confirmDeleteGroupBtn").disabled = false;
        spinner.classList.add("d-none");
      });
  });


  let selectedGroupId = null;
  
function openAddMembersModal(groupId) {
  selectedGroupId = groupId;

  // Cierra el modal de grupos inmediatamente
  const groupsModal = bootstrap.Modal.getInstance(document.getElementById("groupsModal"));
  groupsModal.hide();

  // Muestra el modal de agregar miembros sin delay
  const addMembersModal = new bootstrap.Modal(document.getElementById("addMembersModal"));
  addMembersModal.show();

  // Llama a loadStudentContacts pasando el groupId
  loadStudentContacts(groupId);
}

function loadStudentContacts(groupId, page = 1) {
  const loading = document.getElementById("contactsLoading");
  const list = document.getElementById("contactsList");
  const feedback = document.getElementById("contactsFeedback");

  feedback.innerHTML = '';  // Limpiar mensajes previos
  loading.classList.remove("hidden");
  list.classList.add("hidden");
  list.innerHTML = '';  // Limpiar lista anterior

  fetch(`/api/students/controllers/getStudentContacts.php?page=${page}`, {
    method: "GET",
    credentials: "include",
  })
    .then((res) => res.json())
    .then((data) => {
      loading.classList.add("hidden");
      if (data.status === "success" && data.data.length > 0) {
        list.classList.remove("hidden");
        data.data.forEach((contact) => {
          const item = document.createElement("div");
          item.className = "list-group-item d-flex justify-content-between align-items-center";
          item.innerHTML = `
            <div class="d-flex align-items-center">
              <img src="${contact.profilePhoto}" alt="${contact.fullName}'s photo" class="rounded-circle me-2" width="40" height="40">
              <div>
                <strong>${contact.fullName}</strong><br>
                <small>${contact.institutionalEmail}</small>
              </div>
            </div>
            <button class="btn btn-sm btn-primary" onclick="addMemberToGroup('${groupId}', '${contact.institutionalEmail}')">
              <i class="fa-solid fa-user-plus"></i> Agregar
            </button>
          `;
          list.appendChild(item);
        });
      } else {
        list.innerHTML = `<div class="text-muted text-center w-100">No tienes contactos disponibles.</div>`;
        list.classList.remove("hidden");
      }
    })
    .catch((error) => {
      loading.classList.add("hidden");
      list.classList.remove("hidden");
      list.innerHTML = `<div class="text-danger text-center w-100">Error al cargar los contactos.</div>`;
    });
}

function addMemberToGroup(email) {
  const feedback = document.getElementById("contactsFeedback");

  feedback.innerHTML = `
    <div class="text-info mb-2">
      <i class="fa-solid fa-spinner fa-spin me-1"></i> Agregando a ${email}…
    </div>`;

  fetch("/api/students/controllers/addGroupMember.php", {
    method: "POST",
    credentials: "include",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      groupId: selectedGroupId,
      memberIdentifier: email,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "success") {
        feedback.innerHTML = `
          <div class="text-success mb-2">
            <i class="fa-solid fa-check-circle me-1"></i> ${email} fue agregado correctamente.
          </div>`;
      } else {
        feedback.innerHTML = `
          <div class="text-danger mb-2">
            <i class="fa-solid fa-circle-exclamation me-1"></i> ${data.error.message}
          </div>`;
      }
    })
    .catch(() => {
      feedback.innerHTML = `
        <div class="text-danger mb-2">
          <i class="fa-solid fa-triangle-exclamation me-1"></i> Error al intentar agregar a ${email}.
        </div>`;
    });
}

