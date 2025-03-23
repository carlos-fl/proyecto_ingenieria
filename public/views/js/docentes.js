import { showPopUp } from "./modules/utlis.mjs";

// Lógica para la carga y comportamiento de la vista docente.php
const editButton = document.getElementById("editButton");
const saveButton = document.getElementById("saveButton");
const cancelButton = document.getElementById("cancelButton");
const teacherNumber = localStorage.getItem("teacherNumber");

// Variable global para almacenar la URL actual del video
let currentVideoUrl = "";
let selectedVideoClass = "";

// Abre la modal y muestra el código de la clase seleccionado
function openModal(modalTitleSuffix, modalId) {
  let modal = document.getElementById(modalId)
  modal.querySelector(".titleSuffix").textContent = modalTitleSuffix;
  // Aquí se podría actualizar la lista de alumnos según la clase.
  let alumnosModal = new bootstrap.Modal(modal);
  alumnosModal.show();
}

// Lógica para la carga de datos en la página
function loadTeacherProfile() {
  // Fetch and load the teacher's profile data
  let name = document.getElementById("name");
  let email = document.getElementById("email");
  let phone = document.getElementById("phone");
  let employeeNumber = document.getElementById("employeeNumber");
  let photo = document.getElementById("profile-photo");
  name.innerText = `${localStorage.getItem("userFirstName")} ${localStorage.getItem("userLastName")}`
  email.innerText = `${localStorage.getItem("userInstEmail")}`
  phone.innerText =`${localStorage.getItem("userPhoneNumber")}`
  employeeNumber.innerText = `${localStorage.getItem("employeeNumber")}`
}

function newTableData(content, name = "") {
  let data = document.createElement("td");
  data.className = name;
  data.innerText = content ?? "Manolo";
  return data;
}

function newPrimaryBtn(content) {
  // Create a new Primary button HTML Element
  let btn = document.createElement("button");
  btn.innerText = content;
  btn.className = "btn btn-primary btn-sm mx-2";
  return btn;
}

function sectionModalTitle(event){
  // Retorna el título para el título de una modal de una sección
  let parent = event.target.parentElement.parentElement;
  let classCode = parent.querySelector(".classCode");
  let sectionCode = parent.querySelector(".sectionCode");
  let className = parent.querySelector(".className");
  return `${classCode.innerText} ${className.innerText} - Sección ${sectionCode.innerText}` 
}

function loadShowSectionData(event) {
  // Load and show a section's data
  let studentTable = document.getElementById("tablaAlumnos");
  let modalTitle = sectionModalTitle(event) 
  let section = event.target.parentElement.parentElement
  // Show the modal
  openModal(modalTitle, "alumnosModal");
  // TODO: SOLVE PROBLEM WITH PRIVATE SECTION IDS
  let sectionId = section.dataset.sectionId;
  fetch(`/api/teachers/controllers/section.php?section-id=${sectionId}`, {
    METHOD: "GET",
  })
    .then((response) => {
      return response.json()
    })
    .then((data) => {
      if (data.status === "failure"){
        console.log("No se pudo traer la lista de estudiantes")
        console.log(data.response)
        return
      }
      let studentTableBody = studentTable.querySelector("tbody");;
      let counter = 1;
      studentTableBody.innerHTML = ""
      for (let student of data.data.students) {
        let row = document.createElement("tr");
        let rowCount = newTableData(counter);
        let studentAccountNumber = newTableData(student["STUDENT_ACCOUNT_NUMBER"], "studentFirstName"); 
        let studentFirstName = newTableData(student["FIRST_NAME"], "studentFirstName");
        let studentLastName = newTableData(student["LAST_NAME"], "studentLastName");
        let studentEmail = newTableData(student["INST_EMAIL"], "studentEmail");
        row.appendChild(rowCount);
        row.appendChild(studentAccountNumber)
        row.appendChild(studentFirstName);
        row.appendChild(studentLastName);
        row.appendChild(studentEmail);
        studentTableBody.appendChild(row);
        counter++;
      }
    })
    .catch(error => {
      // TODO: Hacer pantalla que muestre que hubo un error con el servidor
      console.log("Hubo un error con el servidor")
    });
}

function loadUploadGrades(event){
  let modalSuffix = sectionModalTitle(event)
  openModal(modalSuffix, "notasModal")
}

function fetchVideo(event){
  // Init all variables
  let section = event.target.parentElement.parentElement;
  let sectionId = section.dataset.sectionId;
  let videoInput = document.getElementById("videoUrl")
  let videoWrapper = document.getElementById("videoWrapper")
  let deleteVideoBtn = document.getElementById("deleteVideoBtn")
  let uploadVideoBtn = document.getElementById("uploadVideoBtn")
  deleteVideoBtn.setAttribute("disabled", "disabled")
  uploadVideoBtn.setAttribute("disabled", "disabled")
  videoWrapper.innerHTML = ""
  fetch(`/api/teachers/controllers/getSectionVideo.php?section=${sectionId}`, {method: "GET"})
  .then(response => response.json())
  .then((data) => {
    if (data.status === "failure"){
      showPopUp("No se pudo traer el video")
    }
    if (!data.videoUrl){
      videoWrapper.classList.add("text-center")
      videoWrapper.innerText = "Aún no hay video para esta sección"
      return
    }
    deleteVideoBtn.removeAttribute("disabled")
    videoInput.value = data.videoUrl;
    let iframe = `<video-frame video-source="${"https://www.youtube.com/embed/AqHbYZjKsXw"}" width="466" height="262"></video-frame>` 
    videoWrapper.innerHTML =iframe;
  })
  .catch((error) => {
    showPopUp("Hubo un error con el servidor ")
    console.log(error.message)
  })
}

function showVideoModal(event){
  // TODO: CONSIDER STOPPING THE VIDEO WHEN THE MODAL IS CLOSED
  let section = event.target.parentElement.parentElement
  let sectionId = section.dataset.sectionId
  let videoModalId = "videoModal"
  let modalTitle = sectionModalTitle(event)
  let videoModal = document.getElementById(videoModalId)
  videoModal.dataset.currentSection = sectionId 
  openModal(modalTitle, videoModalId)
  
  fetchVideo(event)
}

function getTeacherSections() {
  // Conseguir las secciones que imparte un docente
  let classesTableBody = document.getElementById("teacher-sections");
  fetch(
    `/api/teachers/controllers/teacherSections.php`,
    { METHOD: "GET" }
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.data.length == 0){
        let tableContainer = document.getElementById("tableContainer")
        let noClassesDiv = document.createElement("div")
        noClassesDiv.innerText = "No tienes clases asignadas en este momento"
        noClassesDiv.classList.add("text-center", "text-muted")
        tableContainer.appendChild(noClassesDiv)
        return
      }
      for (let classData of data.data) {
        let row = document.createElement("tr")
        let classCode = newTableData(classData["CLASS_CODE"], "classCode")
        let sectionCode = newTableData(classData["SECTION_CODE"], "sectionCode")
        let className = newTableData(classData["CLASS_NAME"], "className")
        let sectionId = classData["ID_SECTION"]
        let actions = document.createElement("td")
        let viewStudentsBtn = newPrimaryBtn("Ver Alumnos")
        let uploadGradesBtn = newPrimaryBtn("Subir Notas")
        let uploadVideoBtn = newPrimaryBtn("Subir Video")
        viewStudentsBtn.onclick = loadShowSectionData
        uploadGradesBtn.onclick = loadUploadGrades
        uploadVideoBtn.onclick = showVideoModal
        actions.appendChild(viewStudentsBtn)
        actions.appendChild(uploadGradesBtn)
        actions.appendChild(uploadVideoBtn)
        row.appendChild(classCode)
        row.appendChild(sectionCode)
        row.appendChild(className)
        row.appendChild(actions)
        row.dataset.sectionId = sectionId
        classesTableBody.appendChild(row)

      }
    })
    .catch((error) =>{
      // TODO: Implementar pantalla de error de conexión
      console.log("Hubo un error de conexión con el servidor. No se pudieron conseguir las clases")
    }
    )
}

function deleteVideo(event){
  let videoModal = document.getElementById("videoModal")
  let currentVideoSection = videoModal.dataset.currentSection
  let body = JSON.stringify({"sectionId": currentVideoSection})
  fetch(`/api/teachers/controllers/deleteVideo.php?`, 
    {
      method: "DELETE",
      body: body
    })
  .then((response) => response.json())
  .then((data) => {
    // TODO: Show Success Modal
  let videoInput = document.getElementById("videoUrl")
  videoInput.value = ""
  
     
  })
  .catch((error) => {
    
  })
}

function main() {
  let deleteVideoBtn = document.getElementById("deleteVideoBtn")
  loadTeacherProfile();
  getTeacherSections();
  deleteVideoBtn.addEventListener("click", deleteVideo);
}

main()

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


function openNotasModal(codigoClase) {
  // Actualiza el título del modal para indicar a qué clase se subirán las notas
  document.getElementById("notasModalLabel").textContent =
    "Subir Notas - " + codigoClase;
  //
  var notasModal = new bootstrap.Modal(document.getElementById("notasModal"));
  notasModal.show();
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