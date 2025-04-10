let allBooks = [];
let totalPages = 1;
let currentPage = 1;

// Función para realizar la búsqueda pasando la página correspondiente
function fetchBooks(page = 1) {
  currentPage = page;
  const gridContainer = document.querySelector(".books-container .grid");
  gridContainer.innerHTML = `
    <div class="col-12 text-center my-5">
      <span class="spinner-border" role="status" aria-hidden="true"></span>
      <p>Cargando Libros…</p>
    </div>
  `;

  fetch(`/api/library/controllers/booksByStudent.php?page=${page}`)
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

// Función para renderizar los libros
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

  bookList.forEach((book) => {
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

// Función para crear y renderizar la paginación
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
              modalBody.innerHTML = `<iframe src="${selectedBook.url}" allowfullscreen></iframe>`;
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

document.addEventListener("DOMContentLoaded", () => {
  fetchBooks(1);
});
