let allBooks = [];
let totalPages = 1;
let currentPage = 1;
let availableTags = [];
let selectedTags = [];

// Función para mostrar alertas
function showAlert(message, type = "success") {
  const alertContainer = document.createElement("div");
  alertContainer.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
  alertContainer.style.zIndex = 1050;
  alertContainer.role = "alert";
  alertContainer.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  `;
  document.body.appendChild(alertContainer);
  setTimeout(() => {
    const bsAlert = bootstrap.Alert.getOrCreateInstance(alertContainer);
    bsAlert.close();
  }, 5000);
}

// Función para mostrar el modal de confirmación de eliminación
function confirmDeletion() {
  return new Promise((resolve) => {
    const confirmModalElement = document.getElementById("confirmModal");
    const confirmModal = new bootstrap.Modal(confirmModalElement);
    confirmModal.show();

    const confirmDeleteButton = document.getElementById("confirmDeleteButton");
    const newButton = confirmDeleteButton.cloneNode(true);
    confirmDeleteButton.parentNode.replaceChild(newButton, confirmDeleteButton);

    newButton.addEventListener("click", () => {
      confirmModal.hide();
      resolve(true);
    });

    confirmModalElement.addEventListener(
      "hidden.bs.modal",
      () => {
        resolve(false);
      },
      { once: true }
    );
  });
}

// Función para obtener los libros con paginación
function fetchBooks(page = 1) {
  currentPage = page;
  const gridContainer = document.querySelector(".books-container .grid");
  gridContainer.innerHTML = `
    <div class="col-12 text-center my-5">
      <span class="spinner-border" role="status" aria-hidden="true"></span>
      <p>Cargando Libros…</p>
    </div>
  `;

  fetch(`/api/library/controllers/booksByRol.php?page=${page}`, {
    method: "GET",
    credentials: "include",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        allBooks = data.data;
        totalPages = data.totalPages;
        renderBooks(allBooks);
        renderPagination(totalPages, currentPage);
      } else {
        console.error("Error en el endpoint:", data);
        gridContainer.innerHTML = `
          <div class="col-12 text-center my-5">
            <p>Error al cargar los libros.</p>
          </div>
        `;
      }
    })
    .catch((error) => {
      console.error("Error fetching books:", error);
      gridContainer.innerHTML = `
        <div class="col-12 text-center my-5">
          <p>Error al cargar los libros.</p>
        </div>
      `;
    });
}

// Función para renderizar los libros en el contenedor
function renderBooks(bookList) {
  const gridContainer = document.querySelector(".books-container .grid");
  gridContainer.innerHTML = "";

  if (bookList.length === 0) {
    gridContainer.innerHTML = `
      <div class="col-12 text-center my-5">
        <p>No se encontraron libros.</p>
      </div>
    `;
    return;
  }

  bookList.forEach((book, index) => {
    const bookId = book.id || book.bookId;
    const cardHTML = `
      <div class="g-col-6">
        <div class="card book-card" style="width: 18rem; cursor: pointer;">
          <img src="../../assets/img/book.jpg" class="card-img-top" alt="${
            book.title
          }">
          <div class="card-body">
            <h5 class="card-title">${book.title}</h5>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">${book.author}</li>
            <li class="list-group-item">${book.majorName}</li>
            <li class="list-group-item">${book.uploadDate}</li>
            <li class="list-group-item">
              ${
                book.tags && book.tags.length > 0
                  ? book.tags.join(", ")
                  : "Sin etiquetas"
              }
            </li>
          </ul>
          <div class="card-body">
            <button class="btn btn-danger delete-book" data-book-id="${bookId}">Eliminar libro</button>
          </div>
        </div>
      </div>
    `;
    gridContainer.insertAdjacentHTML("beforeend", cardHTML);
  });

  applyCardEvents();
}

// Función para renderizar la paginación
function renderPagination(totalPages, currentPage) {
  const paginationContainerId = "paginationContainer";
  let paginationContainer = document.getElementById(paginationContainerId);

  if (paginationContainer) {
    paginationContainer.remove();
  }

  paginationContainer = document.createElement("div");
  paginationContainer.id = paginationContainerId;
  paginationContainer.className = "w-100 d-flex justify-content-center my-4";

  let paginationHTML = `<nav aria-label="Page navigation"><ul class="pagination">`;

  const prevDisabled = currentPage === 1 ? "disabled" : "";
  paginationHTML += `
    <li class="page-item ${prevDisabled}">
      <a class="page-link" href="#" aria-label="Anterior" data-page="${
        currentPage - 1
      }">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
  `;

  for (let i = 1; i <= totalPages; i++) {
    const activeClass = currentPage === i ? "active" : "";
    paginationHTML += `
      <li class="page-item ${activeClass}">
        <a class="page-link" href="#" data-page="${i}">${i}</a>
      </li>
    `;
  }

  const nextDisabled = currentPage === totalPages ? "disabled" : "";
  paginationHTML += `
    <li class="page-item ${nextDisabled}">
      <a class="page-link" href="#" aria-label="Siguiente" data-page="${
        currentPage + 1
      }">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  `;

  paginationHTML += `</ul></nav>`;
  paginationContainer.innerHTML = paginationHTML;

  document.querySelector(".books-container").appendChild(paginationContainer);

  const pageLinks = paginationContainer.querySelectorAll("a.page-link");
  pageLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      const page = parseInt(link.getAttribute("data-page"));
      if (page >= 1 && page <= totalPages && page !== currentPage) {
        fetchBooks(page);
      }
    });
  });
}

// Función para aplicar eventos en cada tarjeta
function applyCardEvents() {
  const cards = document.querySelectorAll(".book-card");

  cards.forEach((card, index) => {
    card.addEventListener("mouseenter", () => {
      card.classList.add("shadow-lg");
      card.style.transform = "scale(1.03)";
      card.style.transition = "transform 0.2s ease";
    });
    card.addEventListener("mouseleave", () => {
      card.classList.remove("shadow-lg");
      card.style.transform = "scale(1)";
    });

    card.addEventListener("click", (e) => {
      if (e.target.closest(".delete-book")) return;

      const selectedBook = allBooks[index];
      const modalTitle = document.getElementById("bookModalLabel");
      const modalBody = document.querySelector("#bookModal .modal-body");

      modalTitle.textContent = selectedBook.title;

      if (selectedBook.url && selectedBook.url.endsWith(".pdf")) {
        fetch(selectedBook.url, { method: "HEAD" })
          .then((response) => {
            if (response.ok) {
              modalBody.innerHTML = `
                <iframe src="${selectedBook.url}" allowfullscreen style="width: 100%; height: 100%;"></iframe>
              `;
            } else {
              modalBody.innerHTML = `
                <div class="alert alert-warning text-center m-4" role="alert">
                  URL de PDF no válida o archivo no disponible.
                </div>
              `;
            }
          })
          .catch((error) => {
            console.error("Error al verificar el PDF:", error);
            modalBody.innerHTML = `
              <div class="alert alert-warning text-center m-4" role="alert">
                URL de PDF no válida o archivo no disponible.
              </div>
            `;
          });
      } else {
        modalBody.innerHTML = `
          <div class="alert alert-warning text-center m-4" role="alert">
            URL de PDF no válida o archivo no disponible.
          </div>
        `;
      }

      const modal = new bootstrap.Modal(document.getElementById("bookModal"));
      modal.show();
    });
  });

  const deleteButtons = document.querySelectorAll(".delete-book");
  deleteButtons.forEach((button) => {
    button.addEventListener("click", (e) => {
      e.stopPropagation();
      const bookId = button.getAttribute("data-book-id");
      if (!bookId) {
        console.error("No se encontró el ID del libro.");
        return;
      }
      confirmDeletion().then((confirmed) => {
        if (!confirmed) return;
        fetch("/api/library/controllers/deactivateBook.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          credentials: "include",
          body: new URLSearchParams({ bookId: bookId }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.status === "success") {
              allBooks = allBooks.filter(
                (book) => (book.id || book.bookId) != bookId
              );
              renderBooks(allBooks);
              showAlert("Libro eliminado con éxito.", "success");
            } else {
              console.error("Error al eliminar el libro:", data);
              showAlert("Error al eliminar el libro.", "danger");
            }
          })
          .catch((error) => {
            console.error("Error en la petición de eliminación:", error);
            showAlert("Error en la petición de eliminación.", "danger");
          });
      });
    });
  });
}

// Función para cargar las etiquetas
function loadTags() {
  fetch("/api/library/controllers/getTags.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        availableTags = data.data;
      } else {
        console.error("Error en el endpoint de etiquetas:", data.message);
      }
    })
    .catch((error) => {
      console.error("Error al obtener las etiquetas:", error);
    });
}

// búsqueda/agregado de etiqueta
document.getElementById("tagInput").addEventListener("input", function () {
  const inputValue = this.value.toLowerCase();
  const suggestionsContainer = document.getElementById("tagSuggestions");
  suggestionsContainer.innerHTML = "";

  if (inputValue.trim() === "") return;
  const matches = availableTags.filter((tag) =>
    tag.tagName.toLowerCase().includes(inputValue)
  );

  matches.forEach((tag) => {
    const suggestionButton = document.createElement("button");
    suggestionButton.type = "button";
    suggestionButton.className = "list-group-item list-group-item-action";
    suggestionButton.textContent = tag.tagName;
    suggestionButton.addEventListener("click", function () {
      addTag(tag);
      document.getElementById("tagInput").value = "";
      suggestionsContainer.innerHTML = "";
    });
    suggestionsContainer.appendChild(suggestionButton);
  });
});

document.getElementById("tagInput").addEventListener("keypress", function (e) {
  if (e.key === "Enter") {
    e.preventDefault();
    let tagName = this.value.trim();
    if (tagName === "") return;
    const existingTag = availableTags.find(
      (tag) => tag.tagName.toLowerCase() === tagName.toLowerCase()
    );
    if (existingTag) {
      addTag(existingTag);
    } else {
      addTag({ tagId: null, tagName: tagName });
    }
    this.value = "";
    document.getElementById("tagSuggestions").innerHTML = "";
  }
});

// Función para agregar una etiqueta a la lista de seleccionadas
function addTag(tag) {
  if (
    selectedTags.some(
      (t) => t.tagName.toLowerCase() === tag.tagName.toLowerCase()
    )
  )
    return;
  selectedTags.push(tag);
  renderSelectedTags();
}

// Función para eliminar una etiqueta de la lista
function removeTag(tagName) {
  selectedTags = selectedTags.filter(
    (t) => t.tagName.toLowerCase() !== tagName.toLowerCase()
  );
  renderSelectedTags();
}

function renderSelectedTags() {
  const selectedTagsContainer = document.getElementById("selectedTags");
  selectedTagsContainer.innerHTML = "";
  selectedTags.forEach((tag) => {
    const badge = document.createElement("span");
    badge.className = "badge bg-primary me-1 mb-1";
    badge.style.fontSize = "1rem";
    badge.textContent = tag.tagName;
    const closeButton = document.createElement("button");
    closeButton.type = "button";
    closeButton.className = "btn-close btn-close-white btn-sm ms-1";
    closeButton.style.fontSize = "0.6rem";
    closeButton.addEventListener("click", function () {
      removeTag(tag.tagName);
    });
    badge.appendChild(closeButton);
    selectedTagsContainer.appendChild(badge);
  });
}

// Función para cargar las clases
function loadClasses() {
  const classSelect = document.getElementById("idClass");
  classSelect.innerHTML = '<option value="">Cargando clases...</option>';

  fetch("/api/library/controllers/classesByAuthority.php", {
    method: "GET",
    credentials: "include",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        if (data.data.length > 0) {
          classSelect.innerHTML =
            '<option value="">Seleccione una clase</option>';
          data.data.forEach((clase) => {
            classSelect.innerHTML += `<option value="${clase.classId}">${clase.className}</option>`;
          });
        } else {
          classSelect.innerHTML =
            '<option value="">No hay clases disponibles</option>';
        }
      } else {
        console.error("Error en el endpoint de clases:", data.message);
        classSelect.innerHTML =
          '<option value="">Error al cargar clases</option>';
      }
    })
    .catch((error) => {
      console.error("Error al obtener las clases:", error);
      classSelect.innerHTML =
        '<option value="">Error al cargar clases</option>';
    });
}

document.getElementById("uploadForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  selectedTags.forEach((tag) => {
    formData.append("tags_name[]", tag.tagName);
  });

  fetch("/api/library/controllers/uploadBook.php", {
    method: "POST",
    body: formData,
    credentials: "include",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        showAlert("Libro subido exitosamente. ID: " + data.bookId, "success");
        e.target.reset();
        document.getElementById("selectedTags").innerHTML = "";
        document.getElementById("currentDate").value = new Date()
          .toISOString()
          .split("T")[0];
      } else {
        showAlert("Error: " + data.message, "danger");
      }
    })
    .catch((error) => {
      console.error("Error en el envío:", error);
      showAlert("Error al subir el libro.", "danger");
    });
});

document.addEventListener("DOMContentLoaded", () => {
  const currentDateInput = document.getElementById("currentDate");
  const today = new Date().toISOString().split("T")[0];
  currentDateInput.value = today;
  fetchBooks(1);
});

document
  .getElementById("uploadModal")
  .addEventListener("show.bs.modal", function () {
    selectedTags = [];
    document.getElementById("tagInput").value = "";
    document.getElementById("tagSuggestions").innerHTML = "";
    document.getElementById("selectedTags").innerHTML = "";
    loadClasses();
    loadTags();
  });
