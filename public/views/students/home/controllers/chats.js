let chatPollTimeout = null;
let chatListPollTimeout = null;
let isFetchingChat = false;
let currentPage = 1;
let totalPages = 1;

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
  const paginationContainer = document.getElementById("paginationContainer");

  loadingIndicator.style.display = "block";
  messageList.innerHTML = "";
  errorMessage.style.display = "none";

  if (chatListPollTimeout) {
    clearTimeout(chatListPollTimeout);
  }

  fetch(`/api/students/controllers/getInbox.php?page=${currentPage}`)
    .then((response) => response.json())
    .then((data) => {
      loadingIndicator.style.display = "none";

      if (data.status === "failure") {
        errorMessage.style.display = "block";
        errorMessage.textContent =
          "Error al cargar los chats: " + data.error.message;
        return;
      }

      messageList.innerHTML = "";
      data.data.forEach((chat) => {
        const contactName = chat.full_name || "Desconocido";
        const contactEmail = chat.institutional_email || "No disponible";
        const lastMessage = chat.last_message || "No hay mensajes previos";
        const lastMessageDate = chat.last_message_date
          ? new Date(chat.last_message_date).toLocaleString()
          : "Fecha desconocida";

        const chatHTML = `
          <a href="#" class="list-group-item list-group-item-action" onclick="openChatFromMessages('${contactName}', '${chat.chat_id}')">
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

      totalPages = data.totalPages;
      updatePagination();

      loadGroupChats(currentPage);

      chatListPollTimeout = setTimeout(loadChats, 5000);
    })
    .catch((error) => {
      loadingIndicator.style.display = "none";
      errorMessage.style.display = "block";
      errorMessage.textContent = "Error al obtener los mensajes.";
      chatListPollTimeout = setTimeout(loadChats, 5000);
    });
}

// Función para cargar los chats grupales
function loadGroupChats(page) {
  const groupMessageList = document.getElementById("groupMessageList");

  fetch(`/api/students/controllers/getGroupInbox.php?page=${page}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        groupMessageList.innerHTML = "";
        data.data.forEach((group) => {
          const groupName = group.groupName || "Grupo Desconocido";
          const lastMessage = group.lastMessage || "No hay mensajes previos";
          const lastMessageDate = group.lastMessageDate
            ? new Date(group.lastMessageDate).toLocaleString()
            : "Fecha desconocida";

          const groupHTML = `
            <a href="#" class="list-group-item list-group-item-action" onclick="openGroupChat('${groupName}', '${group.groupId}')">
              <div class="d-flex w-100 justify-content-between">
                <div>
                  <h6 class="mb-1">${groupName}</h6>
                </div>
                <small class="text-muted">${lastMessageDate}</small>
              </div>
              <p class="mb-1">${lastMessage}</p>
            </a>
          `;
          groupMessageList.innerHTML += groupHTML;
        });
      } else {
        console.error("Error al cargar los grupos", data.error);
      }
    })
    .catch((err) => console.error("Error en la carga de grupos", err));
}

// Función para actualizar los controles de paginación
function updatePagination() {
  const paginationContainer = document.getElementById("paginationContainer");

  paginationContainer.innerHTML = "";
  if (currentPage > 1) {
    const prevButton = document.createElement("button");
    prevButton.className = "btn btn-outline-primary";
    prevButton.textContent = "Anterior";
    prevButton.onclick = () => changePage(currentPage - 1);
    paginationContainer.appendChild(prevButton);
  }

  if (currentPage < totalPages) {
    const nextButton = document.createElement("button");
    nextButton.className = "btn btn-outline-primary";
    nextButton.textContent = "Siguiente";
    nextButton.onclick = () => changePage(currentPage + 1);
    paginationContainer.appendChild(nextButton);
  }
}

// Función para cambiar de página
function changePage(page) {
  if (page >= 1 && page <= totalPages) {
    currentPage = page;
    loadChats();
  }
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

  let lastMessageId = null;
  const firstMessageElem = chatMessages.querySelector("[data-message-id]");
  if (firstMessageElem) {
    lastMessageId = firstMessageElem.getAttribute("data-message-id");
  }

  let url = `/api/students/controllers/getConversation.php?receiverId=${contactId}&receiverType=STUDENT`;
  if (lastMessageId) {
    url += `&lastMessageId=${lastMessageId}`;
  }

  fetch(url)
    .then((response) => response.json())
    .then((data) => {
      isFetchingChat = false;
      chatMessages.classList.remove("updating");

      if (data.status === "failure") {
        chatMessages.innerHTML =
          "<p class='text-muted'>No hay mensajes disponibles.</p>";
        return;
      }

      if (!lastMessageId) {
        chatMessages.innerHTML = "";
      }

      data.data.forEach((message) => {
        const isFromLoggedUser =
          message.senderId.toString() !== contactId.toString();
        const alignmentClass = isFromLoggedUser
          ? "justify-content-end"
          : "justify-content-start";
        const bubbleClass = isFromLoggedUser ? "me" : "other";

        const messageId = message.messageId || 0;

        const messageHTML = `
        <div data-message-id="${messageId}" class="d-flex ${alignmentClass} mb-2 fade-in">
          <div class="chat-bubble ${bubbleClass}">
            <p class="mb-1">${message.message}</p>
            <small class="text-muted">${new Date(
              message.sentAt
            ).toLocaleString()} - ${message.readStatus}</small>
          </div>
        </div>
        `;
        chatMessages.innerHTML = messageHTML + chatMessages.innerHTML;
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

// Función para cargar los mensajes de un chat grupal
function loadGroupChat(groupName, groupId) {
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

  let lastMessageId = null;
  const firstMessageElem = chatMessages.querySelector("[data-message-id]");
  if (firstMessageElem) {
    lastMessageId = firstMessageElem.getAttribute("data-message-id");
  }

  let url = `/api/students/controllers/getConversation.php?receiverId=${groupId}&receiverType=GROUP`;
  if (lastMessageId) {
    url += `&lastMessageId=${lastMessageId}`;
  }

  fetch(url)
    .then((response) => response.json())
    .then((data) => {
      isFetchingChat = false;
      chatMessages.classList.remove("updating");

      if (data.status === "failure") {
        chatMessages.innerHTML =
          "<p class='text-muted'>No hay mensajes disponibles.</p>";
        return;
      }

      if (!lastMessageId) {
        chatMessages.innerHTML = "";
      }

      data.data.forEach((message) => {
        const isFromLoggedUser =
          message.senderId.toString() !== groupId.toString();
        const alignmentClass = isFromLoggedUser
          ? "justify-content-end"
          : "justify-content-start";
        const bubbleClass = isFromLoggedUser ? "me" : "other";

        const messageId = message.messageId || 0;
        const senderName = message.senderName || "Desconocido";

        const messageHTML = `
          <div data-message-id="${messageId}" class="d-flex ${alignmentClass} mb-2 fade-in">
            <div class="chat-bubble ${bubbleClass}">
              <p class="mb-1">${senderName}: ${message.message}</p>
              <small class="text-muted">${new Date(
                message.sentAt
              ).toLocaleString()} - ${message.readStatus}</small>
            </div>
          </div>
        `;
        chatMessages.innerHTML = messageHTML + chatMessages.innerHTML;
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
        loadGroupChat(groupName, groupId);
      }, 4000);
    })
    .catch((error) => {
      isFetchingChat = false;
      chatMessages.classList.remove("updating");
      chatMessages.innerHTML =
        "<p class='text-danger'>Error al cargar los mensajes.</p>";
      console.error("Error al obtener los mensajes:", error);
      chatPollTimeout = setTimeout(() => {
        loadGroupChat(groupName, groupId);
      }, 5000);
    });
}

// Función para enviar un mensaje en el chat grupal
function sendGroupMessage() {
  const modal = document.getElementById("groupChatModal");
  const groupId = modal.getAttribute("data-group-id");
  const groupName = modal.getAttribute("data-group-name");

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

    const messageData = {
      receiverId: groupId,
      receiverType: "GROUP",
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
          loadGroupChat(groupName, groupId);
        } else {
          console.error("Error al enviar mensaje:", data.error);
        }
      })
      .catch((error) => {
        console.error("Error al enviar el mensaje:", error);
      });
  }
}

const modal = document.getElementById("groupChatModal");
modal.addEventListener("hidden.bs.modal", () => {
  document.activeElement.blur();
});
