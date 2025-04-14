import { showPopUp, changeBorder, exportTableToCSV, readFileAsText, parseCsvToTable, cleanTableBody, newTableData, newPrimaryBtn} from "./modules/utlis.mjs";
import { isValidYoutubeUrl } from "./modules/validator.mjs";

// Lógica para la carga y comportamiento de la vista docente.php
const editButton = document.getElementById("editButton");
const saveButton = document.getElementById("saveButton");
const cancelButton = document.getElementById("cancelButton");
const teacherNumber = localStorage.getItem("teacherNumber");

function createVideoIframe(videoId){
  let videoWrapper = document.getElementById("videoWrapper")
  let iframe = `<video-frame video-id="${videoId}" width="466" height="262"></video-frame>` 
  videoWrapper.innerHTML =iframe;
}

function setCurrentVideoUrl(videoUrl){
  localStorage.setItem("currentVideoUrl", videoUrl)
}

function getCurrentVideoUrl(){
  return localStorage.getItem("currentVideoUrl")
}

function removeCurrentVideoUrl(){
  localStorage.removeItem("currentVideoUrl")
}

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
        return
      }
      let studentTableBody = studentTable.querySelector("tbody");;
      let counter = 1;
      studentTableBody.innerHTML = ""
      for (let student of data.students) {
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

function showUploadGradesModal(event){
  let modalSuffix = sectionModalTitle(event)
  let section = event.target.parentElement.parentElement
  let sectionId = section.dataset.sectionId
  let gradesModalId = "notasModal"
  let gradesModal = document.getElementById(gradesModalId)
  gradesModal.dataset.currentSection = sectionId
  openModal(modalSuffix, gradesModalId)
}

function getYoutubeVideoId(youtubeVideoUrl){
  let queryString = `?${youtubeVideoUrl.split("?")[1]}`
  let urlParams = new URLSearchParams(queryString)
  return urlParams.get("v")
}

function fetchVideo(event){
  // Init all variables
  let section = event.target.parentElement.parentElement;
  let sectionId = section.dataset.sectionId;
  let videoInput = document.getElementById("videoUrl")
  let deleteVideoBtn = document.getElementById("deleteVideoBtn")
  let uploadVideoBtn = document.getElementById("uploadVideoBtn")
  let youtubeVideoId
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
    youtubeVideoId = getYoutubeVideoId(data.videoUrl)
    createVideoIframe(youtubeVideoId) 
    setCurrentVideoUrl(data.videoUrl)
  })
  .catch((error) => {
    showPopUp("Hubo un error con el servidor ")
  })
}

function showVideoModal(event){
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
    `/api/teachers/controllers/teacherSections.php?`,
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
        uploadGradesBtn.onclick = showUploadGradesModal
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
  let videoWrapper = document.getElementById("videoWrapper")
  event.target.setAttribute("disabled", "disabled")
  fetch(`/api/teachers/controllers/deleteVideo.php?`, 
    {
      method: "DELETE",
      body: body
    })
  .then((response) => response.json())
  .then((data) => {
    if (data.status == "failure"){
      showPopUp("No se encontró el video en esta clase")
      return
    }
    let videoInput = document.getElementById("videoUrl")
    let videoFrame = document.querySelector("video-frame")
    videoFrame.remove()
    videoInput.value = ""
    // Deshabilitar el botón de borrar
    event.target.setAttribute("disabled", "disabled")
    removeCurrentVideoUrl()
    videoWrapper.innerText = "Aún no hay video para esta sección"
    showPopUp("Se borró el video exitosamente!", "success-popup", "/views/assets/img/checkmark.png")
  })
  .catch((error) => {
    event.target.removeAttribute("disabled")
    showPopUp("No se pudo borrar el video")
  })
}

function sameVideoUrl(url){
  // Validar que la URL ingresada no es igual a la URL del video
  let currentVideoUrl = getCurrentVideoUrl()
  return url == currentVideoUrl
}

function validateUrl(event){
  // Valida que la url escrita pueda ser almacenada
  let url = event.target.value
  let videoUrlInfo = document.getElementById("videoUrlInfo")
  let submitBtn = document.getElementById("uploadVideoBtn")
  if (!isValidYoutubeUrl(url)){
    videoUrlInfo.textContent = "Solamente se aceptan videos de youtube.com"
    videoUrlInfo.className = "text-danger mt-1 p-1"
    submitBtn.setAttribute("disabled", "disabled")
    return
  }
  if (sameVideoUrl(url)){
    videoUrlInfo.textContent = "No puede subir la misma URL"
    videoUrlInfo.className = "text-danger mt-1 p-1"
    submitBtn.setAttribute("disabled", "disabled")
    return
  }
  videoUrlInfo.textContent = "Video válido! (Antes de subir su video, siempre verifique que la url sea la correcta)"
  videoUrlInfo.className ="text-success mt-1 p-1"
  let uploadVideoBtn = document.getElementById("uploadVideoBtn")
  uploadVideoBtn.removeAttribute("disabled")
}

function cleanVideoModal(event){
  let videoUrlInfo = document.getElementById("videoUrlInfo")
  let iframe = event.target.querySelector("video-frame")
  let videoInput = document.getElementById("videoUrl")
  let submitBtn = document.getElementById("uploadVideoBtn")
  videoUrlInfo.innerText = ""
  videoUrlInfo.className = ""
  if (iframe){
    iframe.remove()
  }
  videoInput.value = ""
  changeBorder(videoInput, "var(--bs-border-width)", "var(--bs-border-color)")
  submitBtn.setAttribute("disabled", "disabled")
}

function uploadVideo(event){
  let videoModal = document.getElementById("videoModal")
  let videoInput = document.getElementById("videoUrl")
  let videoUrl = videoInput.value
  let currentVideoSection = videoModal.dataset.currentSection
  let videoFrame = videoModal.querySelector("video-frame")
  let videoUrlInfo = document.getElementById("videoUrlInfo")
  event.target.setAttribute("disabled", "disabled")
  let deleteVideoBtn = document.getElementById("deleteVideoBtn")
  let body = JSON.stringify({
    "sectionId": currentVideoSection,
    "URL": videoUrl
  })
  fetch("/api/teachers/controllers/addVideo.php", {
    method: "POST",
    body: body
  })
  .then(response => response.json())
  .then(data => {
    videoUrlInfo.innerText = ""
    if (data.status == "failure"){
      showPopUp("No puede guardar el mismo video activo")
    }
    let videoId = getYoutubeVideoId(videoUrl)
    if (videoFrame){
      videoFrame.remove()
    }
    createVideoIframe(videoId)
    setCurrentVideoUrl(videoUrl)
    deleteVideoBtn.removeAttribute("disabled")
    showPopUp("Se guardó el video exitosamente!", "success-popup", "/views/assets/img/checkmark.png")
  })
  .catch((error) =>{
    showPopUp("No se pudo guardar el video")
    event.target.removeAttribute("disabled")
  })
}

function downloadStudents(event){
  let tableId = "tablaAlumnos"
  let filename = "alumnos.csv"
  exportTableToCSV(tableId, filename)
}

function downloadGradesFormat(event){
  let tableId = "gradesFormat"
  let filename = "formatoNotas.csv"
  exportTableToCSV(tableId, filename)
} 

function hasGradeFormat(file){
  // Validar si un archivo csv tiene el formato adecuado
  function hasNanValues(rowContents){
    // Check if the row has NaN values where it shouldn't
    return isNaN(rowContents[0].trim()) || isNaN(rowContents[1].trim())
  }
  function scoreOutOfBounds(rowContents){
    // Check if the row has a score lower than 0 or greater than 100
    return rowContents[1] < 0 || rowContents[1] > 100
  }
  function obsScoreDiffer(rowContents){
    // Check if the given score and the OBS differ
    return (rowContents[2] == "apb" && rowContents[1] < 65) || (rowContents[2] == "rpb" && rowContents[1] >= 65) || 
    (rowContents[2] == "nsp" && rowContents[1] > 0 )
  }
  function unsupportedObs(rowContents, permittedObs){
    // Assert if an unsupported OBS was given
    return !permittedObs.includes(rowContents[2])
  }
  return readFileAsText(file).
  then((result) => {
    let response = {status: false, message: ""}
    let fileRows = result.split("\n")
    let permittedObs = ["apb", "rpb", "nsp", "abd"]
    let permittedHeader = ["numero de cuenta", "puntaje", "obs"]
    let incorrect_rows = []
    let header
    let content
    let count 
    if (fileRows.length == 0){
      response.message = "El archivo no puede estar vacío"
      return response
    }

    header = fileRows[0].split(",").map(val => val.trim().toLowerCase())
    if (JSON.stringify(header) !== JSON.stringify(permittedHeader)){
      response.message = "El archivo debe de incluir el encabezado del formato"
      return response
    }
    
    content = fileRows.slice(1)
    if (content.length == 0){
      response.message = "El archivo debe incluir los datos de su sección. Llene los datos por favor"
      return response
    }
    if (content[content.length -1] == ""){
      content = content.slice(0, -1)
    }
    
    count = 1
    incorrect_rows = []
    for (let row of content){
      let rowContents = row.split(",").map(val => val.trim().toLowerCase())
      if (
        hasNanValues(rowContents) || scoreOutOfBounds(rowContents) || obsScoreDiffer(rowContents) || 
        unsupportedObs(rowContents, permittedObs)
      ){
        incorrect_rows.push(count)
      }
      count++
    }
    if (incorrect_rows.length > 0){
      response.message = `Las filas ${incorrect_rows.join(", ")} no cumplen con el formato. Por favor, verifique los valores en estas filas. Recuerde, los números de cuenta deben ser válidos y las notas deben ser del 0 al 100. También, la observación solo puede ser APB, RPB, ABD o NSP y el puntaje y la observación deben de coincidir`
      return response
    }
    response.message = "Archivo válido"
    response.status = true
    return response
  })
  .catch(false)
}

function permitGradeUpload(event){
  // Habilitar la subida de las por clase si el archivo subido es el correcto
    let uploadGradesInput =event.target
    let file = uploadGradesInput.files[0]
    let uploadGradesTable = document.getElementById("uploadedGradesTable")
    hasGradeFormat(file)
    .then((result) => {
      if (!result.status){
        let uploadGradesInputInfo = document.getElementById("uploadGradesInputInfo")
        uploadGradesInputInfo.innerHTML = `<p>El archivo subido no cumple con el formato solicitado.</p><p>Motivo: ${result.message}   </p><span class="text-dark">Puede descargar el formato apretando el botón <b>Descargar Formato</b><span>`
        uploadGradesInputInfo.className = "text-danger p-1 mt-1"
        cleanTableBody(uploadGradesTable)
        uploadGradesTable.classList.add("d-none")
        return
      }
      uploadGradesInputInfo.innerHTML = `Formato aceptado! Siempre verifique los valores antes de enviar los resultados`
      uploadGradesInputInfo.className = "text-success p-1 mt-1"
      let uploadGradesBtn = document.getElementById("uploadGradesBtn")
      uploadGradesBtn.removeAttribute("disabled")
      uploadGradesTable.classList.remove("d-none")
      uploadGradesTable.classList.add("table-overflow")
      parseCsvToTable("uploadedGradesTable", file)
    })
}


function uploadGrades(event){
  let fileInput = document.getElementById("uploadGradesInput")
  let file = fileInput.files[0]
  let body = new FormData()
  let gradesModal = document.getElementById("notasModal")
  let sectionId = gradesModal.dataset.currentSection
  fileInput.setAttribute("disabled", "disabled")
  event.target.setAttribute("disabled", "disabled")
  body.append("sectionId", sectionId)
  body.append("csv", fileInput.files[0])
  fetch(`/api/teachers/controllers/uploadGrades.php?sectionId=${sectionId}`, {
    method: "POST",
    body: body
  })
  .then(response => response.json())
  .then(data => {
    if (data.status == "failure"){
      let uploadGradesInputInfo = document.getElementById("uploadGradesInputInfo")
      showPopUp("No se pudieron actualizar las notas")
      uploadGradesInputInfo.innerText = data.message
      uploadGradesInputInfo.className = "text-danger p-1 mt-1"
      return
    }
    showPopUp("Notas subidas con éxito", "success-popup", "/views/assets/img/checkmark.png")
    if (data.data){
      let notFoundStudents = data.data["notFoundStudents"]
      uploadGradesInputInfo.innerText = `Los estudiantes con los números de cuenta:\n ${notFoundStudents.join("\n")}
      no se encuentran asignados a su sección, por lo que sus notas no fueron actualizadas. Porfavor, revise estos valores.\nLas demás si calificaciones fueron actualizadas en el sistema`
      uploadGradesInputInfo.className = "text-danger p-1 mt-1"
      return
    }
    document.getElementById("closeGradesModal").click()
    }
  )
  .catch(
    showPopUp("Hubo un problema con el servidor")
  )
  .finally( () => {
    fileInput.removeAttribute("disabled")
    event.target.removeAttribute("disabled")
    }
  )
}

function cleanUploadGradesModal(event){
  let uploadGradesInput = document.getElementById("uploadGradesInput")
  let uploadGradesBtn = document.getElementById("uploadGradesBtn")
  let uploadGradesInputInfo = document.getElementById("uploadGradesInputInfo")
  let uploadTable = document.getElementById("uploadedGradesTable")
  uploadGradesInput.value = ""
  uploadGradesBtn.setAttribute("disabled", "disabled")
  uploadGradesInputInfo.innerText = ""
  uploadGradesInputInfo.classList = ""
  cleanTableBody(uploadTable)
  uploadTable.classList.add("d-none")
}

function initCoordinatorChairPrivileges(){
  // Check if a teacher is coordinator or department chair too
  let sidebarMain = document.getElementById("sidebarMain")
  fetch("/api/teachers/controllers/getTeacherRoles.php")
  .then(response => response.json())
  .then(data => {
    if (data.status === "failure"){
      return
    }
    let roles = data.roles;
    if (roles.includes("COORDINATOR")){
      // Dibujar dinámicamente el rol de coordinator
      let coordinatorAnchor = document.createElement("a")
      coordinatorAnchor.href = "/views/coordinator/index.php"
      coordinatorAnchor.innerText = "Ingresar como Coordinador"
      sidebarMain.appendChild(coordinatorAnchor)
    }
    else if (roles.includes("DEPARTMENT_CHAIR")){
      // Dibujar dinámicamente el anchor a la vista
      let chairAnchor = document.createElement("a")
      chairAnchor.href = "/views/department_chair/home/index.php"
      chairAnchor.innerText = "Ingresar como Jefe de Departamento"
      sidebarMain.appendChild(chairAnchor)
    }

  })
  .catch(
    // No se pudo verificar el privilegio 
  )
}


function main() {
  let deleteVideoBtn = document.getElementById("deleteVideoBtn")
  let videoInput = document.getElementById("videoUrl")
  let videoModal = document.getElementById("videoModal")
  let uploadGradesModal = document.getElementById("notasModal")
  let uploadVideoBtn = document.getElementById("uploadVideoBtn")
  let downloadStudentTableBtn = document.getElementById("downloadStudentTableBtn")
  let downloadGradesFormatBtn = document.getElementById("downloadGradesFormatBtn")
  let uploadGradesInput = document.getElementById("uploadGradesInput")
  let uploadGradesBtn = document.getElementById("uploadGradesBtn")
  loadTeacherProfile();
  getTeacherSections();
  initCoordinatorChairPrivileges();
  // Eventos a elementos de la página
  deleteVideoBtn.addEventListener("click", deleteVideo);
  videoInput.addEventListener("change", validateUrl)
  videoModal.addEventListener("hide.bs.modal", cleanVideoModal)
  uploadVideoBtn.addEventListener("click", uploadVideo)
  downloadStudentTableBtn.addEventListener("click", downloadStudents)
  downloadGradesFormatBtn.addEventListener("click", downloadGradesFormat)
  uploadGradesInput.addEventListener("change", permitGradeUpload)
  uploadGradesBtn.addEventListener("click", uploadGrades)
  uploadGradesModal.addEventListener("hide.bs.modal", cleanUploadGradesModal)
}

main()
