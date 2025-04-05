let chatPollTimeout = null;
let chatListPollTimeout = null;
let isFetchingChat = false;

document.addEventListener("DOMContentLoaded", () => {
  const chatModalElem = document.getElementById("chatModal");
  const chatModalInstance = new bootstrap.Modal(chatModalElem);

  chatModalElem.addEventListener("hidden.bs.modal", () => {
    if (chatPollTimeout) {
      clearTimeout(chatPollTimeout);
      chatPollTimeout = null;
    }
    document.body.focus();
  });

  // Función para abrir el chat desde la lista de mensajes
  window.openChatFromMessages = function (contactName, contactId) {
    const chatMessages = document.getElementById("chatMessages");
    chatMessages.innerHTML = "";
    loadChat(contactName, contactId);

    const chatContactName = document.getElementById("chatContactName");
    chatContactName.innerText = contactName;
    chatContactName.setAttribute("data-contact-id", contactId);

    chatModalInstance.show();
  };

  // Función para abrir el chat desde contactos
  window.openChatFromContacts = function (contactName, contactId) {
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
        chatModalInstance.show();
      });
      contactsModal.hide();
    } else {
      chatModalInstance.show();
    }
  };

  const messageInput = document.getElementById("messageInput");
  messageInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();
      sendMessage();
    }
  });

  loadChats();
});

// Función para cargar la lista de chats
function loadChats() {
  const messageList = document.getElementById("messageList");
  const loadingIndicator = document.getElementById("loadingMessages");
  const errorMessage = document.getElementById("errorMessages");

  if (chatListPollTimeout) {
    clearTimeout(chatListPollTimeout);
  }

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
      } else {
        messageList.innerHTML = "";
        data.data.forEach((chat) => {
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
      }
      chatListPollTimeout = setTimeout(loadChats, 5000);
    })
    .catch((error) => {
      loadingIndicator.style.display = "none";
      errorMessage.style.display = "block";
      errorMessage.textContent = "Error al obtener los mensajes.";
      chatListPollTimeout = setTimeout(loadChats, 5000);
    });
}

// Función para cargar los mensajes de un chat
function loadChat(contactName, contactId) {
  if (isFetchingChat) return;
  isFetchingChat = true;

  const chatMessages = document.getElementById("chatMessages");
  chatMessages.classList.add("updating");

  const threshold = 20;
  const isAtBottom =
    chatMessages.scrollHeight -
      chatMessages.clientHeight -
      chatMessages.scrollTop <
    threshold;
  const previousScrollTop = chatMessages.scrollTop;

  if (chatPollTimeout) {
    clearTimeout(chatPollTimeout);
  }

  fetch(
    `/api/students/controllers/getConversation.php?otherStudentId=${contactId}`
  )
    .then((response) => response.json())
    .then((data) => {
      isFetchingChat = false;
      chatMessages.classList.remove("updating");

      if (data.status === "failure") {
        chatMessages.innerHTML =
          "<p class='text-muted'>No hay mensajes disponibles.</p>";
        return;
      }

      chatMessages.innerHTML = "";
      data.data.forEach((message) => {
        const isFromLoggedUser =
          message.SENDER_ID.toString() !== contactId.toString();
        const alignmentClass = isFromLoggedUser
          ? "justify-content-end"
          : "justify-content-start";
        const bubbleClass = isFromLoggedUser ? "me" : "other";

        const messageHTML = `
          <div class="d-flex ${alignmentClass} mb-2 fade-in">
            <div class="chat-bubble ${bubbleClass}">
              <p class="mb-1">${message.message}</p>
              <small class="text-muted">${new Date(
                message.sentAt
              ).toLocaleString()} - ${message.READ_STATUS}</small>
            </div>
          </div>
        `;
        chatMessages.innerHTML += messageHTML;
      });

      if (isAtBottom) {
        chatMessages.scrollTo({
          top: chatMessages.scrollHeight,
          behavior: "smooth",
        });
      } else {
        chatMessages.scrollTop = previousScrollTop;
      }

      chatPollTimeout = setTimeout(() => {
        loadChat(contactName, contactId);
      }, 4000);
    })
    .catch((error) => {
      isFetchingChat = false;
      chatMessages.classList.remove("updating");
      chatMessages.innerHTML =
        "<p class='text-danger'>Error al cargar los mensajes.</p>";
      console.error("Error al obtener los mensajes:", error);
      chatPollTimeout = setTimeout(() => {
        loadChat(contactName, contactId);
      }, 5000);
    });
}

// Función para enviar el mensaje
function sendMessage() {
  const messageInput = document.getElementById("messageInput");
  const messageText = messageInput.value.trim();

  if (messageText !== "") {
    const chatMessages = document.getElementById("chatMessages");
    const messageBubble = document.createElement("div");
    messageBubble.className = "d-flex justify-content-end mb-2 fade-in";
    messageBubble.innerHTML = `
      <div class="chat-bubble me">
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
