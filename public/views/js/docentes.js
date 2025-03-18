// Lógica para la carga y comportamiento de la vista docente.php
const editButton = document.getElementById("editButton");
const saveButton = document.getElementById("saveButton");
const cancelButton = document.getElementById("cancelButton");
const teacherNumber = localStorage.getItem("teacherNumber");

// Variable global para almacenar la URL actual del video
let currentVideoUrl = "";
let selectedVideoClass = "";

// Abre la modal y muestra el código de la clase seleccionado
function openModal(codigoClase) {
  document.getElementById("claseCodigo").textContent = codigoClase;
  // Aquí se podría actualizar la lista de alumnos según la clase.
  let alumnosModal = new bootstrap.Modal(
    document.getElementById("alumnosModal")
  );
  alumnosModal.show();
}

// Lógica para la carga de datos en la página
function loadTeacherProfile() {
  // Fetch and load the teacher's profile data
  let name = document.getElementById("name");
  let mail = document.getElementById("mail");
  let phone = document.getElementById("phone");
  let employeeNumber = document.getElementById("employee-number");
  let photo = document.getElementById("profile-photo");
  fetch(
    `/api/teachers/controllers/getTeacher.php?teacher-number=${teacherNumber}`,
    { METHOD: "GET" }
  )
    .then((response) => response.json())
    .then((data) => {
      name.innerText = data.firstName + " " + data.lastName;
      mail.innerTex = data.email;
      phone.innerText = data.phone;
      employeeNumber.innerText = data.employeeNumber;
      // TODO: Implement Image change
    })
    // TODO: Implementar pantalla de error de conexión
    .catch(
      console.log(
        "Hubo un error de conexión con el servidor. No se pudieron conseguir los datos del perfil"
      )
    );
}

function newTableData(content, name = "") {
  let data = document.createElement("td");
  data.name = name;
  data.innerText = content;
  return data;
}

function newPrimaryBtn(content) {
  // Create a new Primary button HTML Element
  let btn = document.createElement("button");
  btn.innerText = content;
  viewStudentsBtn.className = "btn btn-primary btn-sm";
  return btn;
}

function loadShowSectionData(event) {
  // Load and show a section's data
  let studentTable = document.getElementById("tablaAlumnos");
  let parent = event.target.parent;
  let classCode = parent.getElementsByName("class-code")[0];
  let section = parent.getElementsByName("section-code")[0];
  let sectionId = section.dataset.sectionId;
  //TODO: CHECK ENDPOINT PATH
  fetch(`/api/teachers/controllers/sections.php?section-id=${sectionId}`, {
    METHOD: "GET",
  })
    .then((response) => response.json())
    .then((data) => {
      let studentTableBody = studentTable.querySelector("tbody");
      studentTableBody.innerHTML = "";
      let counter = 1;
      for (let student of data.students) {
        let row = document.createElement("tr");
        let rowCount = newTableData(counter);
        //TODO: Implement logic to add student's account number
        let studentFirstName = newTableData(
          student.firstName,
          "student-firstname"
        );
        let studentLastName = newTableData(
          student.lastName,
          "student-lastname"
        );
        let studentEmail = newTableData(student.email, "student-email");
        row.appendChild(rowCount);
        row.appendChild(studentFirstName);
        row.appendChild(studentLastName);
        row.appendChild(studentEmail);
        studentTable.appendChild(row);
        counter++;
      }
      // Show the modal
      openModal(classCode);
    })
    .catch();
}

function getTeacherSections() {
  // Conseguir las secciones que imparte un docente
  let classesTableBody = document.getElementById("teacher-sections");
  fetch(
    `/api/teachers/controllers/teacherSections.php?teacher-number=${teacherNumber}`,
    { METHOD: "GET" }
  )
    .then((response) => response.json())
    .then((data) => {
      for (let classData of data) {
        let row = document.createElement("tr");
        let classCode = newTableData(classData.classCode, "class-code");
        let sectionCode = newTableData(classData.sectionCode, "section-code");
        sectionCode.dataset.sectionId = classData.sectionId;
        let className = newTableData(classData.className, "class-name");
        let actions = document.createElement("td");
        let viewStudentsBtn = newPrimaryBtn("Ver Alumnos");
        let uploadGradesBtn = newPrimaryBtn("Subir Notas");
        viewStudentsBtn.onclick = loadShowSectionData;
        // TODO: Implement logic to upload a class' results
        actions.appendChild(viewStudentsBtn);
        actions.appendChild(uploadGradesBtn);
        row.appendChild(classCode);
        row.appendChild(sectionCode);
        row.appendChild(className);
        row.appendChild(actions);
        classesTableBody.appendChild(row);
      }
    })
    // TODO: Implementar pantalla de error de conexión
    .catch(
      console.log(
        "Hubo un error de conexión con el servidor. No se pudieron conseguir las clases"
      )
    );
}
function main() {
  loadTeacherProfile();
  getTeacherSections();
}

main()

editButton.addEventListener("click", function () {
  let nameSpan = document.getElementById("name");
  let phoneSpan = document.getElementById("phone");

  // Guardar los valores actuales para cancelar si es necesario
  let currentName = nameSpan.innerText;
  let currentPhone = phoneSpan.innerText;

  // Reemplazar los spans con inputs
  nameSpan.innerHTML = `<input type="text" id="nameInput" value="${currentName}">`;
  phoneSpan.innerHTML = `<input type="text" id="phoneInput" value="${currentPhone}">`;

  // Mostrar botones de guardar y cancelar
  saveButton.style.display = "block";
  cancelButton.style.display = "block";

  // Agregar evento para cancelar edición
  cancelButton.onclick = function () {
    nameSpan.innerText = currentName;
    phoneSpan.innerText = currentPhone;
    saveButton.style.display = "none";
    cancelButton.style.display = "none";
  };
});

saveButton.addEventListener("click", function () {
  let nameInput = document.getElementById("nameInput");
  let phoneInput = document.getElementById("phoneInput");

  // Guardar los valores editados
  let newName = nameInput.value;
  let newPhone = phoneInput.value;

  // Reemplazar los inputs con los nuevos valores
  document.getElementById("name").innerText = newName;
  document.getElementById("phone").innerText = newPhone;

  // Ocultar botones de guardar y cancelar
  saveButton.style.display = "none";
  cancelButton.style.display = "none";
});

// Función para exportar la tabla de alumnos a CSV (simulación de descarga Excel)
function exportTableToCSV(filename) {
  let csv = [];
  let rows = document.querySelectorAll("#tablaAlumnos tr");

  for (let i = 0; i < rows.length; i++) {
    let row = [],
      cols = rows[i].querySelectorAll("td, th");
    for (let j = 0; j < cols.length; j++) {
      row.push('"' + cols[j].innerText + '"');
    }
    csv.push(row.join(","));
  }

  // Crea un Blob y genera el enlace de descarga
  let csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
  let downloadLink = document.createElement("a");
  downloadLink.download = filename;
  downloadLink.href = window.URL.createObjectURL(csvFile);
  downloadLink.style.display = "none";
  document.body.appendChild(downloadLink);
  downloadLink.click();
  document.body.removeChild(downloadLink);
}

// Abre la modal y muestra el código de la clase seleccionado
function openModal(codigoClase) {
  document.getElementById("claseCodigo").textContent = codigoClase;
  // Aquí se podría actualizar la lista de alumnos según la clase.
  var alumnosModal = new bootstrap.Modal(
    document.getElementById("alumnosModal")
  );
  alumnosModal.show();
}

function openNotasModal(codigoClase) {
  // Actualiza el título del modal para indicar a qué clase se subirán las notas
  document.getElementById("notasModalLabel").textContent =
    "Subir Notas - " + codigoClase;
  //
  var notasModal = new bootstrap.Modal(document.getElementById("notasModal"));
  notasModal.show();
}

// Función para exportar la tabla de alumnos a CSV (simulación de descarga Excel)
function exportTableToCSV(filename) {
  var csv = [];
  var rows = document.querySelectorAll("#tablaAlumnos tr");

  for (var i = 0; i < rows.length; i++) {
    var row = [],
      cols = rows[i].querySelectorAll("td, th");
    for (var j = 0; j < cols.length; j++) {
      row.push('"' + cols[j].innerText + '"');
    }
    csv.push(row.join(","));
  }

  // Crea un Blob y genera el enlace de descarga
  var csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
  var downloadLink = document.createElement("a");
  downloadLink.download = filename;
  downloadLink.href = window.URL.createObjectURL(csvFile);
  downloadLink.style.display = "none";
  document.body.appendChild(downloadLink);
  downloadLink.click();
  document.body.removeChild(downloadLink);
}

// Funciones para manejo del video
function openVideoClassModal() {
  var videoClassModal = new bootstrap.Modal(
    document.getElementById("videoClassModal")
  );
  videoClassModal.show();
}

function selectVideoClass(classCode) {
  selectedVideoClass = classCode;
  document.getElementById("videoClassCode").textContent = classCode;
  // Cerrar la modal de selección de clase
  var videoClassModalEl = document.getElementById("videoClassModal");
  var videoClassModal = bootstrap.Modal.getInstance(videoClassModalEl);
  videoClassModal.hide();
  // Abrir la modal de video
  var videoModal = new bootstrap.Modal(document.getElementById("videoModal"));
  videoModal.show();
}

function uploadVideo() {
  const url = document.getElementById("videoUrl").value.trim();
  if (url) {
    currentVideoUrl = url;
    document.getElementById("videoIframe").src = currentVideoUrl;
    document.getElementById("videoPreview").style.display = "block";
    alert("Video subido exitosamente para la clase " + selectedVideoClass);
  } else {
    alert("Por favor ingresa una URL válida");
  }
}

function updateVideo() {
  if (!currentVideoUrl) {
    alert("No hay un video subido para actualizar. Usa 'Subir Video' primero.");
    return;
  }
  const url = document.getElementById("videoUrl").value.trim();
  if (url) {
    currentVideoUrl = url;
    document.getElementById("videoIframe").src = currentVideoUrl;
    alert("Video actualizado exitosamente para la clase " + selectedVideoClass);
  } else {
    alert("Por favor ingresa una URL válida para actualizar");
  }
}

function deleteVideo() {
  if (!currentVideoUrl) {
    alert("No hay video para borrar");
    return;
  }
  currentVideoUrl = "";
  document.getElementById("videoUrl").value = "";
  document.getElementById("videoIframe").src = "";
  document.getElementById("videoPreview").style.display = "none";
  alert("Video borrado exitosamente para la clase " + selectedVideoClass);
}

function uploadCSV() {
  const fileInput = document.getElementById("csvFile");
  if (fileInput.files.length === 0) {
    alert("Por favor selecciona un archivo CSV");
    return;
  }
  // Simulación de subida de CSV
  alert("Archivo CSV subido exitosamente");
  fileInput.value = "";
}