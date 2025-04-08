let allBooks = [];

document.addEventListener("DOMContentLoaded", () => {
  const gridContainer = document.querySelector(".books-container .grid");

  gridContainer.innerHTML = `
    <div class="col-12 text-center my-5">
      <span class="spinner-border" role="status" aria-hidden="true"></span>
      <p>Cargando Libros…</p>
    </div>
  `;

  fetch("/api/library/controllers/booksByRol.php", {
    method: "GET",
    credentials: "include",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        allBooks = data.data;
        renderBooks(allBooks);
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
});

// Función para renderizar los libros en el contenedor
function renderBooks(bookList) {
  const gridContainer = document.querySelector(".books-container .grid");
  gridContainer.innerHTML = "";

  bookList.forEach((book) => {
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
            <li class="list-group-item">${
              book.tags && book.tags.length > 0
                ? book.tags.join(", ")
                : "Sin etiquetas"
            }</li>
          </ul>
          <div class="card-body">
            <button class="btn btn-danger delete-book" data-book-id="${bookId}">
              Eliminar libro
            </button>
          </div>
        </div>
      </div>
    `;
    gridContainer.insertAdjacentHTML("beforeend", cardHTML);
  });

  applyCardEvents();
}

// Función para aplicar eventos a cada tarjeta
function applyCardEvents(bookList) {
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

    card.addEventListener("click", () => {
      const selectedBook = bookList[index];
      const modalTitle = document.getElementById("bookModalLabel");
      const modalBody = document.querySelector("#bookModal .modal-body");

      modalTitle.textContent = selectedBook.title;

      if (selectedBook.url && selectedBook.url.endsWith(".pdf")) {
        fetch(selectedBook.url, { method: "HEAD" })
          .then((response) => {
            if (response.ok) {
              modalBody.innerHTML = `
                <iframe src="${selectedBook.url}" allowfullscreen></iframe>
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
}

// Evento para el formulario de búsqueda de libros
document.getElementById("searchForm").addEventListener("submit", (e) => {
  e.preventDefault();
  const searchValue = document
    .getElementById("searchInput")
    .value.toLowerCase()
    .trim();

  const filteredBooks = allBooks.filter((book) =>
    book.title.toLowerCase().includes(searchValue)
  );

  renderBooks(filteredBooks);
});

document.getElementById("bookModal").addEventListener("hidden.bs.modal", () => {
  document.activeElement.blur();
});

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

// Función para cargar las etiquetas
function loadTags() {
  const dropdownMenu = document.getElementById("tagsDropdownMenu");
  dropdownMenu.innerHTML =
    '<span class="dropdown-item-text">Cargando tags...</span>';

  fetch("/api/library/controllers/getTags.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        dropdownMenu.innerHTML = "";
        if (data.data.length > 0) {
          data.data.forEach((tag) => {
            const itemDiv = document.createElement("div");
            itemDiv.className = "dropdown-item";

            const checkboxDiv = document.createElement("div");
            checkboxDiv.className = "form-check";

            const checkbox = document.createElement("input");
            checkbox.className = "form-check-input tag-checkbox";
            checkbox.type = "checkbox";
            checkbox.value = tag.tagId;
            checkbox.id = `tag_${tag.tagId}`;

            const label = document.createElement("label");
            label.className = "form-check-label";
            label.setAttribute("for", `tag_${tag.tagId}`);
            label.textContent = tag.tagName;

            checkboxDiv.appendChild(checkbox);
            checkboxDiv.appendChild(label);
            itemDiv.appendChild(checkboxDiv);
            dropdownMenu.appendChild(itemDiv);
          });
        } else {
          dropdownMenu.innerHTML =
            '<span class="dropdown-item-text">No hay tags disponibles</span>';
        }
      } else {
        console.error("Error en el endpoint de etiquetas:", data.message);
        dropdownMenu.innerHTML =
          '<span class="dropdown-item-text text-danger">Error al cargar tags</span>';
      }
    })
    .catch((error) => {
      console.error("Error al obtener las etiquetas:", error);
      dropdownMenu.innerHTML =
        '<span class="dropdown-item-text text-danger">Error al cargar tags</span>';
    });
}

// Función para el resumen de etiquetas seleccionadas
function updateSelectedTags() {
  const selectedTagsDiv = document.getElementById("selectedTags");
  const checked = document.querySelectorAll(
    "#tagsDropdownMenu .tag-checkbox:checked"
  );
  let names = [];
  checked.forEach((cb) => {
    const label = cb.parentElement.querySelector("label");
    if (label) names.push(label.textContent);
  });
  if (names.length > 0) {
    selectedTagsDiv.textContent =
      "Etiquetas seleccionadas: " + names.join(", ");
  } else {
    selectedTagsDiv.textContent = "";
  }
}

document.addEventListener("change", function (event) {
  if (event.target && event.target.classList.contains("tag-checkbox")) {
    updateSelectedTags();
  }
});

document
  .getElementById("uploadModal")
  .addEventListener("show.bs.modal", function () {
    loadClasses();
    loadTags();
  });

document.addEventListener("DOMContentLoaded", () => {
  const currentDateInput = document.getElementById("currentDate");
  const today = new Date().toISOString().split("T")[0];
  currentDateInput.value = today;
});

// Envío del formulario
document.getElementById("uploadForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  const checked = document.querySelectorAll(
    "#tagsDropdownMenu .tag-checkbox:checked"
  );
  checked.forEach((cb) => {
    formData.append("tags[]", cb.value);
  });

  fetch("/api/library/controllers/uploadBook.php", {
    method: "POST",
    body: formData,
    credentials: "include",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        alert("Libro subido exitosamente. ID: " + data.bookId);
        e.target.reset();
        document.getElementById("selectedTags").textContent = "";
        document.getElementById("currentDate").value = new Date()
          .toISOString()
          .split("T")[0];
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Error en el envío:", error);
      alert("Error al subir el libro.");
    });
});

// Agregar eventos de Eliminar libro
function applyCardEvents() {
  const deleteButtons = document.querySelectorAll(".delete-book");

  deleteButtons.forEach((button) => {
    button.addEventListener("click", (e) => {
      const bookId = e.currentTarget.getAttribute("data-book-id");
      if (!bookId) {
        console.error("No se encontró el ID del libro.");
        return;
      }

      const confirmDelete = window.confirm(
        "¿Estás seguro de eliminar este libro?"
      );
      if (!confirmDelete) return;

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
            alert("Libro eliminado con éxito.");
          } else {
            console.error("Error al eliminar el libro:", data);
            alert("Error al eliminar el libro.");
          }
        })
        .catch((error) => {
          console.error("Error en la petición de eliminación:", error);
          alert("Error en la petición de eliminación.");
        });
    });
  });
}
