<?php
session_start();
if (empty($_SESSION)) {
    header('Location: ../../library/login/libraryLogin.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Virtual</title>
    <link rel="icon" type="image/png" href="../../assets/img/UNAH-escudo.png" />
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/library.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
</head>

<body>
    <header>
        <navbar-unah></navbar-unah>
        <div class="top-bar">
            <div>
                <nav class="navbar bg-body-tertiary" id="header-bar">
                    <div class="container-fluid">
                        <h1 class="navbar-brand">Biblioteca</h1>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <main class="container-xl" style="background-color: white; max-width: 98%; height: 100vh; margin: 1%; border-radius:8px;">
        <div class="main-container">
            <section class="v-nav">
                <nav class="navbar bg-body-tertiary" id="search-bar">
                    <div class="container-fluid">
                        <form id="searchForm" class="d-flex" role="search">
                            <input id="searchInput" class="form-control me-2" type="search" placeholder="Buscar por título..." aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Buscar</button>
                        </form>
                    </div>
                </nav>
                <aside>
                    <nav class="nav flex-column">
                        <a class="nav-link" aria-current="page" href="../../docentes.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                            </svg>
                            Perfil
                        </a>
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
                            </svg>
                            Subir recurso...
                        </a>
                    </nav>
                </aside>
                <div class="sidebar-ft">
                    <log-out></log-out>
                </div>
            </section>
            <section class="books-container">
                <div class="grid text-center">
                </div>
            </section>
        </div>
    </main>

    <footer-unah></footer-unah>

    <!-- Modal para visualizar PDF -->
    <div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl-custom">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="bookModalLabel">Título del libro</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0 bg-light modal-body-custom" id="modalPDFContainer">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Subir libro -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Subir archivo PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="pdfFile" class="form-label">Archivo PDF</label>
                            <input class="form-control" type="file" id="pdfFile" name="file" accept="application/pdf" required>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Título</label>
                            <input class="form-control" type="text" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Autor</label>
                            <input class="form-control" type="text" id="author" name="author" required>
                        </div>
                        <div class="mb-3">
                            <label for="idClass" class="form-label">Clase</label>
                            <select id="idClass" name="idClass" class="form-select" required>
                                <option value="">Cargando clases...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Etiquetas</label>
                            <input type="text" class="form-control" id="tagInput" placeholder="Escribe para buscar o añadir una etiqueta">
                            <div id="tagSuggestions" class="list-group mt-1"></div>
                            <div id="selectedTags" class="mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label for="currentDate" class="form-label">Fecha Actual</label>
                            <input class="form-control" type="date" id="currentDate" name="currentDate" readonly>
                        </div>
                    </form>
                    <div id="uploadStatus" style="display: none; text-align: center; margin-top: 15px;">
                        <p>Subiendo archivo, por favor espere...</p>
                        <div class="spinner" style="
                border: 4px solid rgba(0, 0, 0, 0.1);
                width: 36px;
                height: 36px;
                border-radius: 50%;
                border-left-color: #09f;
                animation: spin 1s linear infinite;
                margin: 0 auto;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="uploadForm" class="btn btn-primary">Subir</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este libro?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="confirmDeleteButton" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.min.js" integrity="sha384-Re460s1NeyAhufAM5JwfIGWosokaQ7CH15ti6W5Y4wC/m4eJ5opJ2ivohxVM05Wd" crossorigin="anonymous"></script>
    <script src="../../js/components/navbar.js"></script>
    <script src="../../js/components/footer.js"></script>
    <script src="../../js/components/log-out.js"></script>
    <script src="../../js/components/reviewerModal.js"></script>
    <script src="./controllers/manageBooks.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById("currentDate").value = today;
        });
    </script>

</body>

</html>