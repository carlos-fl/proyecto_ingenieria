function loadContacts() {
  const contactsList = document.getElementById("contactsList");
  const loadingIndicator = document.getElementById("loadingContacts");
  const errorMessage = document.getElementById("errorContacts");

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

      if (data.data.length === 0) {
        contactsList.innerHTML =
          '<p class="text-muted">No hay contactos disponibles.</p>';
        return;
      }

      data.data.forEach((contact, index) => {
        const contactHTML = `
                    <div class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${contact.firstName} ${contact.lastName}</h6>
                                <p class="mb-1 small text-muted">Email: ${contact.email}</p>
                            </div>
                            <div>
                                <a href="#" class="btn btn-sm btn-outline-success me-1" title="Chat" onclick="openChatFromContacts('${contact.firstName} ${contact.lastName}','${contact.studentId}')">
                                    <i class="fa-solid fa-comments"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#contactActions${index}" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                        <div class="collapse mt-2" id="contactActions${index}">
                            <div class="d-flex">
                                <button class="btn btn-sm btn-primary me-2">Agregar a Grupo</button>
                                <button class="btn btn-sm btn-danger">Eliminar Contacto</button>
                            </div>
                        </div>
                    </div>`;
        contactsList.innerHTML += contactHTML;
      });
    })
    .catch((error) => {
      loadingIndicator.style.display = "none";
      errorMessage.style.display = "block";
      errorMessage.textContent = "Error al obtener contactos.";
    });
}

document.addEventListener("DOMContentLoaded", function () {
  loadContacts();
});

// Función para obtener los grupos del estudiante
function loadGroups() {
  const loadingMsg = document.querySelector('#groupsLoading');
  const groupsList = document.querySelector('#groupsList');

  if (!loadingMsg || !groupsList) {
    console.error("No se encontró el mensaje de carga o la lista de grupos.");
    return;
  }

  loadingMsg.classList.remove('hidden');
  groupsList.classList.add('hidden');

  fetch('../../../../api/students/controllers/getStudentGroups.php', {
    method: 'GET',
    credentials: 'include'
  })
    .then(response => response.json())
    .then(data => {
      groupsList.innerHTML = '';

      loadingMsg.classList.add('hidden');
      groupsList.classList.remove('hidden');

      if (data.status === "success" && Array.isArray(data.data) && data.data.length > 0) {
        data.data.forEach(function (group) {
          const groupItem = document.createElement('div');
          groupItem.className = 'list-group-item';
          groupItem.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-1">${group.groupName}</h6>
                <small>Miembros: ${group.memberCount}</small>
              </div>
              <div>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="openGroupChat('${group.groupName}', '${group.groupId}')">
                  <i class="fa-solid fa-comments"></i> Chat
                </button>
                <button class="btn btn-sm btn-outline-success me-1">
                  <i class="fa-solid fa-user-plus"></i> Agregar Miembro
                </button>
                <button class="btn btn-sm btn-outline-danger">
                  <i class="fa-solid fa-trash"></i> Borrar Grupo
                </button>
              </div>
            </div>
          `;
          groupsList.appendChild(groupItem);
        });
      } else {
        groupsList.innerHTML = `<div class="list-group-item text-center text-muted">No tienes grupos aún.</div>`;
      }
    })
    .catch(error => {
      console.error("Error al obtener grupos:", error);
      loadingMsg.classList.add('hidden');
      groupsList.classList.remove('hidden');
      groupsList.innerHTML = `<div class="list-group-item text-center text-danger">Error al cargar los grupos.</div>`;
    });
}

document.addEventListener("DOMContentLoaded", function() {
  var groupsModal = document.getElementById('groupsModal');
  groupsModal.addEventListener('shown.bs.modal', function () {
    loadGroups();
  });
});

// Función abrir el chat de un grupo
function openGroupChat(groupName, groupId) {
  console.log('Abrir chat para:', groupName, groupId);
}