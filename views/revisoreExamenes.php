<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Revisión de Exámenes de Admisión</title>
  <link rel="icon" type="image/png" href="/assets/img/UNAH-escudo.png">
  <link rel="stylesheet" href="/css/revisoreExamenesStyle.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
</head>
<body style="background-color: #000E33;">
  <div class="container" id="profile-container">
    <header>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <a class="navbar-brand" href="#"><img src="/assets/img/logo-unah.png" alt="Logo UNAH"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                  aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <span class="navbar-text mx-auto">
              Revisión de Exámenes de Admisión
            </span>
          </div>
        </div>
      </nav>
    </header>
    <main>
      <div class="container-fluid">
        <div class="row">
          <div class="col-3 sidebar">
            <ul class="nav nav-tabs mt-3" id="examStatusTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="completado-tab" data-bs-toggle="tab" data-bs-target="#completado" type="button" role="tab" aria-controls="completado" aria-selected="false">Completado</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="enRevision-tab" data-bs-toggle="tab" data-bs-target="#enRevision" type="button" role="tab" aria-controls="enRevision" aria-selected="true">En Revisión</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="pendiente-tab" data-bs-toggle="tab" data-bs-target="#pendiente" type="button" role="tab" aria-controls="pendiente" aria-selected="false">Pendiente</button>
              </li>
            </ul>
            <div class="tab-content exam-list" id="examStatusTabContent">
              <div class="tab-pane fade" id="completado" role="tabpanel" aria-labelledby="completado-tab">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item exam-item" 
                      data-exam-id="1" 
                      data-exam-name="Examen 1" 
                      data-exam-image="/img/exam1.jpg">
                    Examen 1
                  </li>
                  <li class="list-group-item exam-item" 
                      data-exam-id="2" 
                      data-exam-name="Examen 2" 
                      data-exam-image="/img/exam2.jpg">
                    Examen 2
                  </li>
                </ul>
              </div>
              <div class="tab-pane fade show active" id="enRevision" role="tabpanel" aria-labelledby="enRevision-tab">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item exam-item" 
                      data-exam-id="3" 
                      data-exam-name="Examen 3" 
                      data-exam-image="/img/exam3.jpg">
                    Examen 3
                  </li>
                  <li class="list-group-item exam-item" 
                      data-exam-id="4" 
                      data-exam-name="Examen 4" 
                      data-exam-image="/img/exam4.jpg">
                    Examen 4
                  </li>
                </ul>
              </div>
              <div class="tab-pane fade" id="pendiente" role="tabpanel" aria-labelledby="pendiente-tab">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item exam-item" 
                      data-exam-id="5" 
                      data-exam-name="Examen 5" 
                      data-exam-image="/img/exam5.jpg">
                    Examen 5
                  </li>
                  <li class="list-group-item exam-item" 
                      data-exam-id="6" 
                      data-exam-name="Examen 6" 
                      data-exam-image="/img/exam6.jpg">
                    Examen 6
                  </li>
                </ul>
              </div>
            </div>
            <div class="logout-btn">
              <a href="/js/modules/loginRevisoreExamenes.php">  <button class="btn btn-danger w-100">Cerrar Sesión</button></a>
            </div>
          </div>  
          <div class="col-9 main-content d-none">
            <div class="exam-box mx-auto">
              <h4 id="examTitle" class="text-center mb-3">Examen</h4>
              <img id="examImage" src="#" alt="Imagen del Examen" class="img-fluid w-100" 
                   style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalExam"/>
            </div>
            <div class="buttons-container d-flex flex-column align-items-end mt-3">
              <button class="btn btn-primary mb-2">Subir Resultados</button>
              <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalNote">Corregir Nota</button>
            </div>
          </div>              
        </div>
      </div>
    </main>
  </div>
  
  <!-- Ventanas modales -->
  <div class="modal fade" id="modalNote" tabindex="-1" aria-labelledby="modalNoteLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNoteLabel">Corregir Nota</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="notaForm">
            <div class="mb-3">
              <label for="newNote" class="form-label">Nueva Nota:</label>
              <input type="number" class="form-control" id="newNote" name="newNote" placeholder="Ej. 85" required/>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="saveNoteBtn">Guardar</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modalExam" tabindex="-1" aria-labelledby="modalExamLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalExamLabel">Examen</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body text-center">
          <img id="modalExamImage" src="#" alt="Examen en tamaño real" class="img-fluid">
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const examItems = document.querySelectorAll('.exam-item');
      const mainContent = document.querySelector('.main-content');
    
      examItems.forEach(item => {
        item.addEventListener('click', function () {
          const examName  = this.getAttribute('data-exam-name');
          const examImage = this.getAttribute('data-exam-image');
    
          document.getElementById('examTitle').textContent = examName;
          document.getElementById('examImage').src = examImage;
          document.getElementById('modalExamImage').src = examImage;
    
          mainContent.classList.remove('d-none');
        });
      });
    });

    const saveNoteBtn = document.getElementById('saveNoteBtn');
      saveNoteBtn.addEventListener('click', () => {
          const newNote = document.getElementById('newNote').value;
          if (newNote) {
            console.log("Nota corregida a:", newNote);
            document.getElementById('newNote').value = '';
          }
    });
    </script>
</body>
</html>