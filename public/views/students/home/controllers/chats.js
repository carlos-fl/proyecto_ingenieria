document.addEventListener("DOMContentLoaded", function () {
  let currentChat = null;

  let pollingTimeout = null;

  // Función para cargar bandeja de chats
  function loadMessageList(page = 1) {
    page = page && page > 0 ? page : 1;

    const loading = document.getElementById("loadingMessages");
    const error = document.getElementById("errorMessages");
    loading.style.display = "block";
    error.style.display = "none";

    // Cargar Mensajes Privados
    fetch(`/api/students/controllers/getInbox.php?page=${page}`)
      .then((response) => response.json())
      .then((data) => {
        loading.style.display = "none";

        if (data.status === "success") {
          const messageList = document.getElementById("messageList");
          messageList.innerHTML = "";

          data.data.forEach((chat) => {
            const item = document.createElement("button");
            item.className =
              "btn text-start w-100 border rounded-3 mb-2 p-3 shadow-sm bg-white hover-shadow";
            item.style.whiteSpace = "normal";

            item.innerHTML = `
              <div class="d-flex justify-content-between align-items-center">
                <div class="fw-semibold fs-6 text-primary">${
                  chat.full_name
                }</div>
                <small class="text-muted">${chat.institutional_email}</small>
              </div>
              <div class="text-muted text-truncate mt-1">${
                chat.last_message || "Sin mensajes"
              }</div>
            `;

            item.addEventListener("click", () => {
              openChatModal(chat.chat_id, chat.full_name);
            });

            messageList.appendChild(item);
          });
        } else {
          error.style.display = "block";
        }
      })
      .catch((err) => {
        console.error("Error al cargar mensajes privados", err);
        loading.style.display = "none";
        error.style.display = "block";
      });

    // Cargar Mensajes Grupales
    fetch(`/api/students/controllers/getGroupInbox.php?page=${page}`)
      .then((response) => response.json())
      .then((data) => {
        console.log("Datos de chats grupales:", data);
        if (data.status === "success") {
          const groupMessageList = document.getElementById("groupMessageList");
          groupMessageList.innerHTML = "";

          data.data.forEach((group) => {
            const item = document.createElement("button");
            item.className =
              "btn text-start w-100 border rounded-3 mb-2 p-3 shadow-sm bg-light hover-shadow";
            item.style.whiteSpace = "normal";

            item.innerHTML = `
              <div class="d-flex justify-content-between align-items-center">
                <div class="fw-semibold fs-6 text-dark">${group.groupName}</div>
                <small class="text-muted">${group.membersCount} miembros</small>
              </div>
              <div class="text-muted text-truncate mt-1">${
                group.lastMessage || "Sin mensajes"
              }</div>
            `;

            item.addEventListener("click", () => {
              openGroupChatModal(group.groupId, group.groupName);
            });

            groupMessageList.appendChild(item);
          });
        } else {
          console.error("La respuesta de chats grupales no es exitosa.", data);
        }
      })
      .catch((err) => {
        console.error("Error al cargar mensajes grupales", err);
      });
  }

  // Función modal de chat individual y carga la conversación.
  function openChatModal(receiverId, contactName) {
    currentChat = { receiverId: receiverId, receiverType: "STUDENT" };

    document.getElementById("chatContactName").textContent = contactName;

    document.getElementById("messageInput").value = "";
    document.getElementById("chatMessages").innerHTML = "";

    const chatModal = new bootstrap.Modal(document.getElementById("chatModal"));
    chatModal.show();

    loadConversation();

    document.getElementById("messageInput").onkeydown = function (event) {
      if (event.key === "Enter") {
        sendMessage();
        event.preventDefault();
      }
    };
  }

  // Función modal de chat grupal
  function openGroupChatModal(groupId, groupName) {
    currentChat = { receiverId: groupId, receiverType: "GROUP" };

    document.getElementById("groupChatName").textContent = groupName;

    document.getElementById("groupMessageInput").value = "";
    document.getElementById("groupChatMessages").innerHTML = "";

    const groupChatModal = new bootstrap.Modal(
      document.getElementById("groupChatModal")
    );
    groupChatModal.show();

    loadConversation();

    document.getElementById("groupMessageInput").onkeydown = function (event) {
      if (event.key === "Enter") {
        sendGroupMessage();
        event.preventDefault();
      }
    };
  }

  // Función Cargar conversación
  function loadConversation(lastMessageId = null) {
    if (!currentChat) return;

    const receiverId = currentChat.receiverId;
    const receiverType = currentChat.receiverType;
    let url = `/api/students/controllers/getConversation.php?receiverId=${receiverId}&receiverType=${receiverType}`;
    if (lastMessageId) {
      url += `&lastMessageId=${lastMessageId}`;
    }

    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          const container =
            receiverType === "STUDENT"
              ? document.getElementById("chatMessages")
              : document.getElementById("groupChatMessages");
          if (!lastMessageId) {
            container.innerHTML = "";
          }
          data.data.reverse().forEach((message) => {
            const messageDiv = document.createElement("div");
            messageDiv.className =
              "chat-message " +
              (message.senderId == loggedUserId ? "mine" : "their");
            messageDiv.textContent = message.message;
            container.appendChild(messageDiv);
          });
          container.scrollTop = container.scrollHeight;
        }
      })
      .catch((error) => console.error("Error al cargar conversación", error))
      .finally(() => {
        pollingTimeout = setTimeout(loadConversation, 2000);
      });
  }

  // Función detener el refresco periódico
  function stopPolling() {
    clearTimeout(pollingTimeout);
    currentChat = null;
  }

  // Función envio de mensaje en  chat individual
  function sendMessage() {
    const input = document.getElementById("messageInput");
    const content = input.value.trim();
    if (!content || !currentChat) return;
    const payload = {
      receiverId: currentChat.receiverId,
      receiverType: currentChat.receiverType,
      content: content,
    };
    fetch("/api/students/controllers/sendMessage.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          input.value = "";
          loadConversation();
        } else {
          alert("Error al enviar el mensaje.");
        }
      })
      .catch((error) => console.error("Error al enviar mensaje:", error));
  }

  // Función envio de mensaje en chat grupal
  function sendGroupMessage() {
    const input = document.getElementById("groupMessageInput");
    const content = input.value.trim();
    if (!content || !currentChat) return;
    const payload = {
      receiverId: currentChat.receiverId,
      receiverType: currentChat.receiverType,
      content: content,
    };
    fetch("/api/students/controllers/sendMessage.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          input.value = "";
          loadConversation();
        } else {
          alert("Error al enviar mensaje de grupo.");
        }
      })
      .catch((error) =>
        console.error("Error al enviar mensaje de grupo:", error)
      );
  }

  document
    .getElementById("messageListModal")
    .addEventListener("show.bs.modal", loadMessageList);

  function openChatFromContacts(contactName, studentId) {
    openChatModal(studentId, contactName);
  }

  function openGroupChat(groupName, groupId) {
    openGroupChatModal(groupId, groupName);
  }

  window.stopPolling = stopPolling;
  window.sendMessage = sendMessage;
  window.sendGroupMessage = sendGroupMessage;
  window.openChatModal = openChatModal;
  window.openGroupChatModal = openGroupChatModal;
  window.openChatFromContacts = openChatFromContacts;
  window.openGroupChat = openGroupChat;
});
