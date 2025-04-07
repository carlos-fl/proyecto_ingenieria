let allBooks = []; // Global para guardar todos los libros

document.addEventListener("DOMContentLoaded", () => {
  const gridContainer = document.querySelector(".books-container .grid");
  gridContainer.innerHTML = `
    <div class="col-12 text-center my-5">
      <span class="spinner-border" role="status" aria-hidden="true"></span>
      <p>Cargando Libros…</p>
    </div>
  `;

  fetch("/api/library/controllers/booksByStudent.php")
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

// Función para renderizar libros
function renderBooks(bookList) {
  const gridContainer = document.querySelector(".books-container .grid");
  gridContainer.innerHTML = "";

  bookList.forEach((book) => {
    const cardHTML = `
      <div class="g-col-6">
        <div class="card book-card" style="width: 18rem; cursor: pointer;">
          <img src="../../assets/img/book.jpg" class="card-img-top" alt="${book.title}">
          <div class="card-body">
            <h5 class="card-title">${book.title}</h5>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">${book.author}</li>
            <li class="list-group-item">${book.className}</li>
            <li class="list-group-item">${book.uploadDate}</li>
            <li class="list-group-item">${
              book.tags.length > 0 ? book.tags.join(", ") : "Sin etiquetas"
            }</li>
          </ul>
        </div>
      </div>
    `;
    gridContainer.insertAdjacentHTML("beforeend", cardHTML);
  });

  applyCardEvents(bookList);
}

// Eventos de hover y modal para cada tarjeta
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
                <iframe src="${selectedBook.url}" width="100%" height="500px" style="border: none;"></iframe>
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

// Evento para el formulario de búsqueda
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
