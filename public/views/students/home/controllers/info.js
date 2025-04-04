// Función para cargar la información personal del estudiante
function loadPersonalInfo() {
  document.getElementById("accountNumber").innerText = "Cargando...";
  document.getElementById("name").innerText = "Cargando...";
  document.getElementById("email").innerText = "Cargando...";
  document.getElementById("phone").innerText = "Cargando...";
  document.getElementById("description").innerText = "Cargando...";

  fetch("../../../../api/students/controllers/getStudentPersonalInfo.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        let fullName = `${data.data.firstName || ""} ${data.data.lastName || ""}`.trim();
        fullName = fullName || "N/A";

        document.getElementById("accountNumber").innerText = data.data.studentAccountNumber || "N/A";
        document.getElementById("name").innerText = fullName || "N/A";
        document.getElementById("email").innerText = data.data.email || "N/A";
        document.getElementById("phone").innerText = data.data.phone || "N/A";
        document.getElementById("description").innerText = data.data.description || "Sin descripción";
      } else {
        console.error("Error al cargar la información personal", data.error);
      }
    })
    .catch((error) => console.error("Error en la petición", error));
}

// Mostrar la modal y precargar los valores actuales
document.getElementById("editInfoModal").addEventListener("show.bs.modal", function () {
  // Precargar teléfono y descripción actuales
  document.getElementById("editPhone").value = document.getElementById("phone").innerText;
  document.getElementById("editDescription").value = document.getElementById("description").innerText;

  // Ajustar atributos de accesibilidad
  this.removeAttribute("aria-hidden");
  this.setAttribute("aria-modal", "true");

  // Mover el foco al primer elemento interactivo dentro del modal
  const firstButton = this.querySelector('.btn-primary');
  if (firstButton) {
    firstButton.focus();
  }
});

// Restaurar el atributo aria-hidden cuando se cierra la modal
document.getElementById("editInfoModal").addEventListener("hidden.bs.modal", function () {
  this.setAttribute("aria-hidden", "true");
});

// Función para actualizar la información del perfil
function updateInfo() {
  var phone = document.getElementById("editPhone").value.trim();
  var description = document.getElementById("editDescription").value;

  if (!phone) {
    alert("El número de teléfono es obligatorio");
    return;
  }

  fetch("../../../../api/students/controllers/updateStudentProfile.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ phone, description }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        document.getElementById("phone").innerText = phone;
        document.getElementById("description").innerText = description;
      } else {
        console.error("Error al actualizar la información", data.error || data.message);
      }
    })
    .catch((error) => console.error("Error en la petición de actualización", error))
    .finally(() => {
      var editModal = bootstrap.Modal.getInstance(document.getElementById("editInfoModal"));
      editModal.hide();
    });
}

// Función para actualizar foto de perfil
function uploadProfileImage(file) {
  var formData = new FormData();
  formData.append("profileImage", file);

  var uploadBtn = document.getElementById("uploadBtn");
  var spinner = document.getElementById("uploadSpinner");

  if (uploadBtn) {
    uploadBtn.disabled = true;
  }
  if (spinner) {
    spinner.style.display = "inline-block";
  }

  fetch("../../../../api/students/updateProfileImage.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        // Actualiza la imagen de perfil con la URL devuelta por el servidor
        document.getElementById("profileImg").src = data.data.profileImageUrl;
      } else {
        console.error("Error al subir la imagen", data.error);
      }
    })
    .catch((error) => console.error("Error en la petición de imagen", error))
    .finally(() => {
      if (uploadBtn) {
        uploadBtn.disabled = false;
      }
      if (spinner) {
        spinner.style.display = "none";
      }
    });
}

// Evento para el botón de subir imagen de perfil
document.getElementById("uploadBtn").addEventListener("click", function () {
  document.getElementById("fileInput").click();
});

function handleProfileImageUpload(files) {
  if (files.length > 0) {
    uploadProfileImage(files[0]);
  }
}

// Cuando se selecciona un archivo, se muestra una vista previa y se envía al servidor
document.getElementById("fileInput").addEventListener("change", function (event) {
  var file = event.target.files[0];
  if (file) {
    // Mostrar vista previa
    var reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById("profileImg").src = e.target.result;
    };
    reader.readAsDataURL(file);

    // Subir la imagen al servidor
    uploadProfileImage(file);
  }
});

// Función para cargar el historial académico
function loadClassHistorial() {
  const tableBody = document.querySelector("table tbody");
  const loadingContainer = document.getElementById("loadingContainer");
  const paginationContainer = document.getElementById("paginationContainer");

  loadingContainer.style.display = "block";
  // Limpia la tabla y el paginador
  tableBody.innerHTML = "";
  paginationContainer.innerHTML = "";

  fetch("../../../../api/students/controllers/getStudentClassHistory.php")
    .then((response) => response.json())
    .then((data) => {
      loadingContainer.style.display = "none";

      if (data.status === "success") {
        const records = data.data;
        const itemsPerPage = 10;
        let currentPage = 1;
        const totalPages = Math.ceil(records.length / itemsPerPage);

        // Función para renderizar una página específica
        function renderPage(page) {
          tableBody.innerHTML = "";
          const startIndex = (page - 1) * itemsPerPage;
          const endIndex = startIndex + itemsPerPage;
          const pageRecords = records.slice(startIndex, endIndex);
          pageRecords.forEach((record) => {
            const row = document.createElement("tr");
            row.innerHTML = `
              <td>${record.classCode || ""}</td>
              <td>${record.className || ""}</td>
              <td>${record.uv || ""}</td>
              <td>${record.section || ""}</td>
              <td>${record.year || ""}</td>
              <td>${record.period || ""}</td>
              <td>${record.calification || ""}</td>
              <td>${record.status || ""}</td>
            `;
            tableBody.appendChild(row);
          });
        }

        // Función para generar el paginador dinámico
        function renderPagination() {
          paginationContainer.innerHTML = "";
          const ul = document.createElement("ul");
          ul.className = "pagination justify-content-end";

          // Botón "Anterior"
          const liPrev = document.createElement("li");
          liPrev.className = "page-item" + (currentPage === 1 ? " disabled" : "");
          const aPrev = document.createElement("a");
          aPrev.className = "page-link";
          aPrev.href = "#";
          aPrev.innerText = "Anterior";
          aPrev.addEventListener("click", (e) => {
            e.preventDefault();
            if (currentPage > 1) {
              currentPage--;
              renderPage(currentPage);
              renderPagination();
            }
          });
          liPrev.appendChild(aPrev);
          ul.appendChild(liPrev);

          // Botones numéricos de páginas
          for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement("li");
            li.className = "page-item" + (i === currentPage ? " active" : "");
            const a = document.createElement("a");
            a.className = "page-link";
            a.href = "#";
            a.innerText = i;
            a.addEventListener("click", (e) => {
              e.preventDefault();
              currentPage = i;
              renderPage(currentPage);
              renderPagination();
            });
            li.appendChild(a);
            ul.appendChild(li);
          }

          // Botón "Siguiente"
          const liNext = document.createElement("li");
          liNext.className = "page-item" + (currentPage === totalPages ? " disabled" : "");
          const aNext = document.createElement("a");
          aNext.className = "page-link";
          aNext.href = "#";
          aNext.innerText = "Siguiente";
          aNext.addEventListener("click", (e) => {
            e.preventDefault();
            if (currentPage < totalPages) {
              currentPage++;
              renderPage(currentPage);
              renderPagination();
            }
          });
          liNext.appendChild(aNext);
          ul.appendChild(liNext);

          paginationContainer.appendChild(ul);
        }

        // Renderiza la primera página
        renderPage(currentPage);

        // Si hay más de 1 página, muestra el paginador
        if (totalPages > 1) {
          renderPagination();
        }
      } else {
        console.error("Error al cargar el historial", data.error);
      }
    })
    .catch((error) => {
      loadingContainer.style.display = "none";
      console.error("Error en la petición", error);
    });
}

document.addEventListener("DOMContentLoaded", function () {
  loadPersonalInfo();
  loadClassHistorial();
});