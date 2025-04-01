// Función para cargar la lista de chats
function loadChats() {
  const messageList = document.getElementById("messageList");
  const loadingIndicator = document.getElementById("loadingMessages");
  const errorMessage = document.getElementById("errorMessages");

  loadingIndicator.style.display = "block";
  messageList.innerHTML = "";
  errorMessage.style.display = "none";

  fetch("/api/students/controllers/getInbox.php")
    .then((response) => response.json())
    .then((data) => {
      loadingIndicator.style.display = "none";

      if (data.status === "failure") {
        errorMessage.style.display = "block";
        errorMessage.textContent =
          "Error al cargar los chats: " + data.error.message;
        return;
      }

      if (data.data.length === 0) {
        messageList.innerHTML =
          '<p class="text-muted">No hay mensajes disponibles.</p>';
        return;
      }

      data.data.forEach((chat, index) => {
        const contactName = chat.name || "Desconocido";
        const contactEmail = chat.email || "No disponible";
        const lastMessage = chat.lastMessage || "No hay mensajes previos";
        const lastMessageDate = chat.lastMessageDate
          ? new Date(chat.lastMessageDate).toLocaleString()
          : "Fecha desconocida";

        const chatHTML = `
          <a href="#" class="list-group-item list-group-item-action" onclick="openChatFromMessages('${contactName}', '${chat.chatId}')">
            <div class="d-flex w-100 justify-content-between">
              <div>
                <h6 class="mb-1">${contactName}</h6>
                <p class="mb-1 small">Cuenta: ${contactEmail}</p>
              </div>
              <small class="text-muted">${lastMessageDate}</small>
            </div>
            <p class="mb-1">${lastMessage}</p>
          </a>
        `;
        messageList.innerHTML += chatHTML;
      });
    })
    .catch((error) => {
      loadingIndicator.style.display = "none";
      errorMessage.style.display = "block";
      errorMessage.textContent = "Error al obtener los mensajes.";
    });
}

// Función para cargar mensajes chat
function loadChat(contactName, contactId) {
  // Actualizar el estado del chat
  currentChatId = contactId;
  currentContactName = contactName;

  document.getElementById("chatContactName").innerText = contactName;

  // Limpiar los mensajes antiguos
  const chatMessages = document.getElementById("chatMessages");
  chatMessages.innerHTML = ""; // Limpiar mensajes anteriores

  fetch(
    `/api/students/controllers/getConversation.php?otherStudentId=${contactId}`
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "failure") {
        chatMessages.innerHTML =
          "<p class='text-muted'>No hay mensajes disponibles.</p>";
        return;
      }

      // Mostrar los mensajes en el chat
      data.data.forEach((message) => {
        const messageHTML = `
          <div class="d-flex ${
            message.SENDER_ID === contactId ? "" : "justify-content-end"
          } mb-2">
            <div class="p-2 ${
              message.SENDER_ID === contactId
                ? "bg-light"
                : "bg-primary text-white"
            } rounded" style="max-width: 70%;">
              <p class="mb-1">${message.message}</p>
              <small class="text-muted">${new Date(
                message.sentAt
              ).toLocaleString()} - ${message.READ_STATUS}</small>
            </div>
          </div>
        `;
        chatMessages.innerHTML += messageHTML;
      });

      // Desplazar al final de los mensajes
      chatMessages.scrollTop = chatMessages.scrollHeight;
    })
    .catch((error) => {
      chatMessages.innerHTML =
        "<p class='text-danger'>Error al cargar los mensajes.</p>";
      console.error("Error al obtener los mensajes:", error);
    });
}

//Funcion para abrir bandeja
function openChatFromMessages(contactName, contactId) {
  // Limpia mensajes previos y carga el chat
  const chatMessages = document.getElementById("chatMessages");
  chatMessages.innerHTML = "";
  loadChat(contactName, contactId);

  const chatContactName = document.getElementById("chatContactName");
  chatContactName.innerText = contactName;
  chatContactName.setAttribute("data-contact-id", contactId);

  var chatModal = new bootstrap.Modal(document.getElementById("chatModal"));
  chatModal.show();
}

//Funcion para abrir chat desde contactos
function openChatFromContacts(contactName, contactId) {
  const chatMessages = document.getElementById("chatMessages");
  chatMessages.innerHTML = "";
  loadChat(contactName, contactId);

  const chatContactName = document.getElementById("chatContactName");
  chatContactName.innerText = contactName;
  chatContactName.setAttribute("data-contact-id", contactId);

  const contactsModalElem = document.getElementById("contactsModal");
  const contactsModal = bootstrap.Modal.getInstance(contactsModalElem);
  
  document.body.focus();

  if (contactsModal) {
    contactsModalElem.addEventListener("hidden.bs.modal", function handler() {
      contactsModalElem.removeEventListener("hidden.bs.modal", handler);

      const backdrop = document.querySelector(".modal-backdrop");
      if (backdrop) {
        backdrop.remove();
      }

      const chatModal = new bootstrap.Modal(document.getElementById("chatModal"));
      chatModal.show();
    });

    contactsModal.hide();
  } else {
    const chatModal = new bootstrap.Modal(document.getElementById("chatModal"));
    chatModal.show();
  }
}

// Función para enviar el mensaje
function sendMessage() {
  const messageInput = document.getElementById("messageInput");
  const messageText = messageInput.value.trim();

  if (messageText !== "") {
    const chatMessages = document.getElementById("chatMessages");
    const messageBubble = document.createElement("div");
    messageBubble.className = "d-flex justify-content-end mb-2";
    messageBubble.innerHTML = `
      <div class="p-2 bg-primary text-white rounded" style="max-width: 70%;">
        <p class="mb-1">${messageText}</p>
        <small class="text-light">Ahora - Enviado</small>
      </div>
    `;
    chatMessages.appendChild(messageBubble);
    messageInput.value = "";
    chatMessages.scrollTop = chatMessages.scrollHeight;

    const contactElement = document.getElementById("chatContactName");
    const contactId = contactElement.getAttribute("data-contact-id");
    const contactName = contactElement.innerText;

    if (!contactId) {
      console.error("No se encontró un ID de contacto válido.");
      return;
    }

    const receiverType = "STUDENT";

    const messageData = {
      receiverId: contactId,
      receiverType: receiverType,
      content: messageText,
    };

    // Enviar el mensaje al servidor
    fetch("/api/students/controllers/sendMessage.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(messageData),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          loadChats();
          loadChat(contactName, contactId);
        } else {
          console.error("Error al enviar mensaje:", data.error);
        }
      })
      .catch((error) => {
        console.error("Error al enviar el mensaje:", error);
      });
  }
}

// Función para simular el envío de un mensaje en el chat grupal
function sendGroupMessage() {
  var messageInput = document.getElementById("groupMessageInput");
  var messageText = messageInput.value.trim();
  if (messageText !== "") {
    var chatContainer = document.querySelector(
      "#groupChatModal .chat-container"
    );
    var messageBubble = document.createElement("div");
    messageBubble.className = "d-flex justify-content-end mb-2";
    messageBubble.innerHTML = `
        <div class="p-2 bg-primary text-white rounded" style="max-width: 70%;">
          <p class="mb-1">${messageText}</p>
          <small class="text-light">Ahora - Enviado</small>
        </div>`;
    chatContainer.appendChild(messageBubble);
    messageInput.value = "";
    chatContainer.scrollTop = chatContainer.scrollHeight;
  }
}

document.addEventListener("DOMContentLoaded", function () {
  loadChats();
});
