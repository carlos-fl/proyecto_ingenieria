// Función para cargar el chat del contacto seleccionado
function loadChat(contactName, contactId) {
  document.getElementById("chatContactName").innerText = contactName;
}

// Función que se invoca al hacer clic en el botón de chat de un contacto
function openChat(contactName, contactId) {
  document.getElementById("chatContactName").innerText = contactName;

  // Si la modal de contactos está abierta, se oculta
  var contactsModal = bootstrap.Modal.getInstance(
    document.getElementById("contactsModal")
  );
  if (contactsModal) {
    contactsModal.hide();
  }

  // Muestra la modal de chat
  var chatModal = new bootstrap.Modal(document.getElementById("chatModal"));
  chatModal.show();

  console.log("Cargando chat para:", contactName, "ID:", contactId);
}

// Función para simular el envío de un mensaje
function sendMessage() {
  var messageInput = document.getElementById("messageInput");
  var messageText = messageInput.value.trim();
  if (messageText !== "") {
    var chatContainer = document.querySelector("#chatModal .chat-container");
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

//Funciones chat grupal
function openGroupChat(groupName, groupId) {
    document.getElementById('groupChatName').innerText = groupName;
    
    var groupsModalEl = document.getElementById('groupsModal');
    var groupsModal = bootstrap.Modal.getInstance(groupsModalEl);
    if (groupsModal) {
      groupsModal.hide();
    }
    
    var groupChatModal = new bootstrap.Modal(document.getElementById('groupChatModal'));
    groupChatModal.show();
    
    console.log("Abriendo chat grupal para:", groupName, "ID:", groupId);
  }
  
  // Función para simular el envío de un mensaje en el chat grupal
  function sendGroupMessage() {
    var messageInput = document.getElementById('groupMessageInput');
    var messageText = messageInput.value.trim();
    if (messageText !== "") {
      var chatContainer = document.querySelector('#groupChatModal .chat-container');
      var messageBubble = document.createElement('div');
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