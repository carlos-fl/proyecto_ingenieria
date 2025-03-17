// Datos estáticos de exámenes pendientes de revisión, cada uno asociado a un número de solicitud
const exams = [
  {
    id: 1,
    numSolicitud: "REQ001",
    nombre: "Juan",
    apellido: "Pérez",
    examTitle: "PHUMA",
    examDate: "23/10/2024",
    nota: 80,
    examUrl: "assets/img/certificado.webp",
  },
  {
    id: 2,
    numSolicitud: "REQ002",
    nombre: "María",
    apellido: "García",
    examTitle: "PAA",
    examDate: "24/10/2024",
    nota: 75,
    examUrl: "assets/img/certificado.webp",
  },
  {
    id: 3,
    numSolicitud: "REQ003",
    nombre: "Carlos",
    apellido: "López",
    examTitle: "PHUMA",
    examDate: "25/10/2024",
    nota: 90,
    examUrl: "assets/img/certificado.webp",
  },
];

let currentIndex = 0;

// Función para renderizar cada examen
function renderExam(exam) {
  return `
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-primary text-white text-center">
        <h3 class="mb-0">${exam.examTitle}</h3>
      </div>
      <div class="card-body">
        <form>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label fw-bold">Alumno:</label>
            <div class="col-sm-8">
              <input type="text" readonly class="form-control-plaintext" value="${exam.nombre} ${exam.apellido}">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label fw-bold">Número de Solicitud:</label>
            <div class="col-sm-8">
              <input type="text" readonly class="form-control-plaintext" value="${exam.numSolicitud}">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label fw-bold">Fecha del Examen:</label>
            <div class="col-sm-8">
              <input type="text" readonly class="form-control-plaintext" value="${exam.examDate}">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label fw-bold">Nota Actual:</label>
            <div class="col-sm-8">
              <input type="text" readonly id="nota-${exam.id}" class="form-control-plaintext" value="${exam.nota}">
            </div>
          </div>
          <div class="text-center mb-4">
            <img src="${exam.examUrl}" alt="Examen" class="img-thumbnail"
                 style="max-width: 300px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#examModal-${exam.id}">
          </div>
          <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-warning me-3" onclick="correctGrade(${exam.id})">Corregir Nota</button>
            <button type="button" class="btn btn-success" onclick="uploadResults(${exam.id})">Subir Resultados</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal individual para ver el examen completo -->
    <div class="modal fade" id="examModal-${exam.id}" tabindex="-1" aria-labelledby="examModalLabel-${exam.id}" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="examModalLabel-${exam.id}">Detalle del Examen</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body text-center">
            <img src="${exam.examUrl}" alt="Detalle del Examen" class="img-fluid w-100">
          </div>
        </div>
      </div>
    </div>
  `;
}

// Carga el examen actual y actualiza el contador
function loadExam() {
  const counterDiv = document.getElementById("counter");
  const detailsDiv = document.getElementById("examDetails");
  if (currentIndex < exams.length) {
    counterDiv.innerText = `Examen ${currentIndex + 1} de ${exams.length}`;
    detailsDiv.innerHTML = renderExam(exams[currentIndex]);
  } else {
    counterDiv.innerText = "";
    detailsDiv.innerHTML =
      '<p class="text-center">No hay más exámenes para revisar.</p>';
  }
}

// Solicita una nueva nota y actualiza el campo
function correctGrade(examId) {
  const newGrade = prompt("Ingrese la nueva nota:");
  if (newGrade !== null && newGrade !== "") {
    const exam = exams.find((e) => e.id === examId);
    exam.nota = newGrade;
    document.getElementById(`nota-${examId}`).value = newGrade;
    alert("Nota corregida a " + newGrade);
  }
}

// Guarda el examen (con nota actualizada y número de solicitud) en localStorage y pasa al siguiente
function uploadResults(examId) {
  const exam = exams.find((e) => e.id === examId);
  let examResults = JSON.parse(localStorage.getItem("examResults")) || [];
  const index = examResults.findIndex((r) => r.id === examId);
  if (index !== -1) {
    examResults[index] = exam;
  } else {
    examResults.push(exam);
  }
  localStorage.setItem("examResults", JSON.stringify(examResults));
  alert("Resultados subidos para " + exam.examTitle);
  currentIndex++;
  loadExam();
}

document.addEventListener("DOMContentLoaded", loadExam);