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
            <img src="../../assets/img/default-profile.png" alt="Foto de perfil" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">
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

          let groupActionButton = "";

          if (group.ownerId === parseInt(loggedUserId)) {
            groupActionButton = `
              <button class="btn btn-sm btn-outline-danger delete-group-btn me-1" data-group-id="${group.groupId}">
                <i class="fa-solid fa-trash"></i> Borrar Grupo
              </button>`;
          } else {
            groupActionButton = `
              <button class="btn btn-sm btn-outline-danger leave-group-btn me-1" data-group-id="${group.groupId}">
                <i class="fa-solid fa-right-from-bracket"></i> Salir del Grupo
              </button>`;
          }

          groupItem.innerHTML = `
            <div class="d-flex justify-content-between align-items-center group-header" style="cursor: pointer;">
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
                ${groupActionButton}
              </div>
            </div>
            <div class="group-members mt-2 ms-3 collapse" id="groupMembers-${group.groupId}">
              <div class="text-muted">Cargando miembros...</div>
            </div>
            <!-- Contenedor para el mensaje de confirmación -->
            <div class="confirmation-message" id="confirmationMessage-${group.groupId}" style="display: none;">
              <p class="text-muted">¿Estás seguro de que quieres salir de este grupo?</p>
              <button class="btn btn-sm btn-danger" id="confirmLeave-${group.groupId}">Confirmar</button>
              <button class="btn btn-sm btn-secondary" id="cancelLeave-${group.groupId}">Cancelar</button>
            </div>
          `;

          groupItem
            .querySelector(".group-header")
            .addEventListener("click", (e) => {
              if (e.target.closest("button")) return;
              const membersDiv = groupItem.querySelector(
                `#groupMembers-${group.groupId}`
              );
              const isCollapsed = membersDiv.classList.contains("collapse");
              document
                .querySelectorAll(".group-members")
                .forEach((el) => el.classList.add("collapse"));
              if (isCollapsed) {
                membersDiv.classList.remove("collapse");
                loadGroupMembers(group.groupId, membersDiv);
              } else {
                membersDiv.classList.add("collapse");
              }
            });

          const leaveBtn = groupItem.querySelector(".leave-group-btn");
          if (leaveBtn) {
            leaveBtn.addEventListener("click", (e) => {
              e.stopPropagation();

              const groupId = e.currentTarget.dataset.groupId;
              const confirmationMessage = document.getElementById(
                `confirmationMessage-${groupId}`
              );

              confirmationMessage.style.display = "block";
            });
          }

          const cancelLeaveBtn = groupItem.querySelector(
            `#cancelLeave-${group.groupId}`
          );
          if (cancelLeaveBtn) {
            cancelLeaveBtn.addEventListener("click", () => {
              const confirmationMessage = document.getElementById(
                `confirmationMessage-${group.groupId}`
              );
              confirmationMessage.style.display = "none";
            });
          }

          const confirmLeaveBtn = groupItem.querySelector(
            `#confirmLeave-${group.groupId}`
          );
          if (confirmLeaveBtn) {
            confirmLeaveBtn.addEventListener("click", () => {
              const groupId = group.groupId;
              leaveGroup(groupId, groupItem);
              const confirmationMessage = document.getElementById(
                `confirmationMessage-${groupId}`
              );
              confirmationMessage.style.display = "none";
            });
          }

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

let currentAddMemberGroupId = null;
let currentGroupMembersSet = new Set();

// Modal para agregar miembros al grupo
function openAddMembersModal(groupId) {
  currentAddMemberGroupId = groupId;
  currentGroupMembersSet = new Set();

  const addMemberModalEl = document.getElementById("addMemberModal");
  const addMemberModal = new bootstrap.Modal(addMemberModalEl);
  addMemberModal.show();

  // Cargar los miembros actuales del grupo
  fetch(`/api/students/controllers/getGroupMembers.php?groupId=${groupId}`, {
    method: "GET",
    credentials: "include",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success" && Array.isArray(data.data)) {
        data.data.forEach((member) => {
          if (member.institutionalEmail) {
            currentGroupMembersSet.add(member.institutionalEmail.toLowerCase());
          }
        });
      }
    })
    .catch((error) => {
      console.error("Error al obtener miembros del grupo:", error);
    })
    .finally(() => {
      loadGroupContacts(1);
    });
}

// Carga y muestra la lista de contactos del usuario
function loadGroupContacts(page = 1) {
  const contactsLoading = document.getElementById("groupContactsLoading");
  const contactsList = document.getElementById("groupContactsList");

  contactsLoading.classList.remove("hidden");
  contactsList.classList.add("hidden");
  contactsList.innerHTML = "";

  fetch(`/api/students/controllers/getStudentContacts.php?page=${page}`, {
    method: "GET",
    credentials: "include",
  })
    .then((response) => response.json())
    .then((data) => {
      contactsLoading.classList.add("hidden");
      contactsList.classList.remove("hidden");

      if (
        data.status === "success" &&
        Array.isArray(data.data) &&
        data.data.length > 0
      ) {
        data.data.forEach((contact) => {
          const contactEmail = contact.institutionalEmail?.toLowerCase();
          const isAlreadyMember = currentGroupMembersSet.has(contactEmail);

          const contactItem = document.createElement("div");
          contactItem.className =
            "list-group-item d-flex justify-content-between align-items-center";

          contactItem.innerHTML = `
            <div><strong>${contact.fullName || "Contacto"}</strong></div>
            <div>
              <button 
                class="btn btn-sm ${
                  isAlreadyMember ? "btn-secondary" : "btn-outline-primary"
                }"
                ${
                  isAlreadyMember
                    ? "disabled"
                    : `onclick="confirmAddMember('${
                        contact.institutionalEmail
                      }', '${contact.fullName || "Contacto"}')"`
                }>
                ${isAlreadyMember ? "Ya es miembro" : "Agregar al grupo"}
              </button>
            </div>
          `;
          contactsList.appendChild(contactItem);
        });
      } else {
        contactsList.innerHTML = `<div class="list-group-item text-center text-muted">No tienes contactos.</div>`;
      }
    })
    .catch((error) => {
      console.error("Error al cargar contactos:", error);
      contactsLoading.classList.add("hidden");
      contactsList.classList.remove("hidden");
      contactsList.innerHTML = `<div class="list-group-item text-center text-danger">Error al cargar contactos.</div>`;
    });
}

function showNotification(message, type = "info", duration = 3000) {
  const container = document.getElementById("notificationContainer");
  if (!container) return;

  const alertDiv = document.createElement("div");
  alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
  alertDiv.role = "alert";
  alertDiv.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
  `;
  container.appendChild(alertDiv);

  setTimeout(() => {
    alertDiv.classList.remove("show");
    alertDiv.classList.add("hide");
    setTimeout(() => {
      container.removeChild(alertDiv);
    }, 500);
  }, duration);
}

function showConfirmation(message, onConfirm) {
  const container = document.getElementById("notificationContainer");
  if (!container) return;
  container.innerHTML = "";

  const confirmDiv = document.createElement("div");
  confirmDiv.className = `alert alert-warning`;
  confirmDiv.role = "alert";
  confirmDiv.innerHTML = `
    <p>${message}</p>
    <button id="confirmYes" class="btn btn-sm btn-primary me-2">Sí</button>
    <button id="confirmNo" class="btn btn-sm btn-secondary">Cancelar</button>
  `;
  container.appendChild(confirmDiv);

  document.getElementById("confirmYes").addEventListener("click", () => {
    onConfirm();
    container.removeChild(confirmDiv);
  });
  document.getElementById("confirmNo").addEventListener("click", () => {
    container.removeChild(confirmDiv);
  });
}

function confirmAddMember(memberIdentifier, memberName) {
  console.log("Correo del miembro a agregar:", memberIdentifier);
  showConfirmation(`¿Estás seguro de agregar a ${memberName} al grupo?`, () => {
    addGroupMember(memberIdentifier);
  });
}

// Envía la solicitud para agregar un miembro
function addGroupMember(memberIdentifier) {
  const addBtns = document.querySelectorAll(
    `button[onclick*="confirmAddMember('${memberIdentifier}'"]`
  );
  addBtns.forEach((btn) => {
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Agregando...`;
  });

  fetch("/api/students/controllers/addGroupMember.php", {
    method: "POST",
    credentials: "include",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      groupId: currentAddMemberGroupId,
      memberIdentifier: memberIdentifier,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        showNotification("Miembro agregado exitosamente.", "success");
        currentGroupMembersSet.add(memberIdentifier.toLowerCase());
        loadGroupContacts();
      } else {
        showNotification(
          "Error al agregar miembro: " +
            (data.error ? data.error.message : "Error desconocido"),
          "danger"
        );
        addBtns.forEach((btn) => {
          btn.disabled = false;
          btn.innerHTML = "Agregar al grupo";
        });
      }
    })
    .catch((error) => {
      console.error("Error al agregar miembro:", error);
      showNotification("Error al agregar miembro.", "danger");
      addBtns.forEach((btn) => {
        btn.disabled = false;
        btn.innerHTML = "Agregar al grupo";
      });
    });
}

//Trae miembros de un grupo
function loadGroupMembers(groupId, container) {
  container.innerHTML = `
    <div class="d-flex justify-content-center align-items-center my-4">
      <div class="spinner-border text-secondary me-2" role="status">
        <span class="visually-hidden">Cargando...</span>
      </div>
      <span class="text-secondary">Cargando miembros...</span>
    </div>
  `;

  fetch(`/api/students/controllers/getGroupMembers.php?groupId=${groupId}`, {
    method: "GET",
    credentials: "include",
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "success" && Array.isArray(data.data)) {
        if (data.data.length === 0) {
          container.innerHTML = `<div class="alert alert-info my-3">Este grupo no tiene miembros.</div>`;
          return;
        }

        const listGroup = document.createElement("ul");
        listGroup.className = "list-group my-3";
        data.data.forEach((member) => {
          const listItem = document.createElement("li");
          listItem.className =
            "list-group-item d-flex justify-content-between align-items-center";

          listItem.innerHTML = `
            <div>
              <strong>${member.fullName || "Sin nombre"}</strong>
              <br>
              <small class="text-muted">${member.institutionalEmail}</small>
            </div>
            <button 
              class="btn btn-sm btn-outline-danger" 
              title="Eliminar miembro del grupo"
              onclick="confirmRemoveGroupMember(${groupId}, ${
            member.studentId
          }, this)"
            >
              <i class="fa-solid fa-user-minus"></i> Quitar
            </button>
          `;
          listGroup.appendChild(listItem);
        });
        container.innerHTML = "";
        container.appendChild(listGroup);
      } else {
        container.innerHTML = `<div class="alert alert-danger my-3">Error al cargar los miembros.</div>`;
      }
    })
    .catch((err) => {
      console.error("Error al cargar miembros:", err);
      container.innerHTML = `<div class="alert alert-danger my-3">Error al cargar los miembros.</div>`;
    });
}

function confirmRemoveGroupMember(groupId, memberId, btn) {
  const confirmDiv = document.createElement("div");
  confirmDiv.className = "alert alert-warning alert-dismissible fade show mt-2";
  confirmDiv.role = "alert";
  confirmDiv.innerHTML = `
    <div class="mb-2">¿Estás seguro de eliminar a este miembro?</div>
    <div class="d-flex">
      <button class="btn btn-sm btn-danger me-2">Sí</button>
      <button class="btn btn-sm btn-secondary" data-bs-dismiss="alert">Cancelar</button>
    </div>
  `;

  const parent = btn.closest("li");
  parent.appendChild(confirmDiv);

  const yesBtn = confirmDiv.querySelector(".btn-danger");
  yesBtn.addEventListener("click", () => {
    removeGroupMember(groupId, memberId, parent);
    confirmDiv.remove();
  });
}

//Elimina un miembro del grupo
function removeGroupMember(groupId, memberId, container) {
  fetch("/api/students/controllers/removeGroupMember.php", {
    method: "POST",
    credentials: "include",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ groupId, memberId }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "success") {
        showNotification("Miembro eliminado del grupo.", "success");
        container.remove();
      } else {
        if (data.error && data.error.errorMessage) {
          showNotification(data.error.errorMessage, "danger");
        } else {
          showNotification("No se pudo eliminar el miembro.", "danger");
        }
      }
    })
    .catch((err) => {
      console.error("Error al eliminar miembro:", err);
      showNotification("Error al eliminar miembro.", "danger");
    });
}

function showNotification(message, type = "info", duration = 3000) {
  const container = document.getElementById("globalNotificationContainer");
  if (!container) return;

  const alertDiv = document.createElement("div");
  alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
  alertDiv.role = "alert";
  alertDiv.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
  `;

  container.appendChild(alertDiv);

  setTimeout(() => {
    alertDiv.classList.remove("show");
    alertDiv.classList.add("hide");
    setTimeout(() => {
      if (container.contains(alertDiv)) {
        container.removeChild(alertDiv);
      }
    }, 500);
  }, duration);
}

// Evento para salir del grupo
function leaveGroup(groupId, groupElement) {
  fetch("/api/students/controllers/leaveGroup.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    credentials: "include",
    body: JSON.stringify({ groupId }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "success") {
        showMessage("Has salido del grupo exitosamente.", "success");
        groupElement.remove();
      } else {
        showMessage("No se pudo salir del grupo.", "danger");
      }
    })
    .catch(() => {
      showMessage("Ocurrió un error al salir del grupo.", "danger");
    });
}

function showMessage(message, type = "info") {
  let alertContainer = document.getElementById("customAlertContainer");
  if (!alertContainer) {
    alertContainer = document.createElement("div");
    alertContainer.id = "customAlertContainer";
    alertContainer.style.position = "fixed";
    alertContainer.style.top = "1rem";
    alertContainer.style.right = "1rem";
    alertContainer.style.zIndex = "1050";
    document.body.appendChild(alertContainer);
  }

  const alert = document.createElement("div");
  alert.className = `alert alert-${type} alert-dismissible fade show`;
  alert.style.minWidth = "250px";
  alert.role = "alert";
  alert.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  `;

  alertContainer.appendChild(alert);

  setTimeout(() => {
    alert.classList.remove("show");
    alert.classList.add("hide");
    setTimeout(() => alert.remove(), 500);
  }, 4000);
}
