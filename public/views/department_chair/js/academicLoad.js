// Lógica para la vista academicLoad.php

import { changeBorder, cleanTableBody, disableElement, enableElement, exportTableToCSV, newDangerBtn, newPrimaryBtn, newTableData, showLoadingIcon, showPopUp } from "../../js/modules/utlis.mjs"
import { isInt } from "../../js/modules/validator.mjs";

function isFull(domElement){
    return domElement.value !== ""
}

function emptyControl(formControl){
    // Handle empty control logic
    changeBorder(formControl, "var(--bs-border-width)", "red");
    showPopUp("El campo es obligatorio")
}

function enableFormSubmission(){
    // Habilitar el envío del formulario si todos los campos están correctos y llenos
    let form = document.querySelector(".load-form")
    let formControls = form.querySelectorAll("input,select")
    let submit = document.getElementById("submit")
    for (let control of formControls){
        if (control.value === ""){
            disableElement(submit)
            return
        }
    }
    if (!validQuota()){
        
        return
    }
    // TODO: Add logic to check if the schedule is enabled
    enableElement(submit)
}

function fetchDeparmentMajors(selectMajor){
    selectMajor.innerHTML = `<option value="">Cargando Carreras...</option>` 
    selectMajor.setAttribute("disabled", "disabled")
    fetch("/api/department_chair/controllers/getDepartmentMajors.php")
    .then(response => response.json())
    .then(data => {
        if (data.status == "failure"){
            showPopUp("No se pudieron traer las carreras")
            return
        }
        let majors = data.majors
        if (majors.length == 0){
            selectMajor.innerHTML = `<option value="">Departamento Vacío...</option>`
            showPopUp("El departamento no cuenta con carreras") 
            return
        }
        selectMajor.innerHTML = `<option value="">Elija la carrera...</option>`
        majors.forEach(major => {
            let option = document.createElement("option")
            option.value = major["ABREVIATION"]
            option.innerText = major["MAJOR_NAME"]
            selectMajor.appendChild(option)
        });
        selectMajor.removeAttribute("disabled")
    })
    .catch( error => {
        showPopUp("Hubo un error al cargar las carreras")
    }
    )
}

function editAcademicLoadSection(row, event){
    console.log("Editando sección!")
}

function deleteAcademicLoadSection(row, event){
    console.log("borrando sección!")
}
function fetchMajorAcademicLoad(majorSelect, academicLoadTable, newSectionBtn, academicLoadTableInfo, excelImportBtn, filterInput){
    let majorAbr = majorSelect.value
    let academicLoadTableBody = academicLoadTable.querySelector("tbody")
    showLoadingIcon(academicLoadTableInfo)
    fetch(`/api/department_chair/controllers/getMajorAcademicLoad.php?major=${majorAbr}&page=1`)
    .then(response => response.json())
    .then(data => {
        if (data.status == "failure"){
            showPopUp("Hubo un error al traer la carga académica")
            academicLoadTableInfo.innerHTML = "Hubo un error al cargar la carga académica"
            return
        }
        if (data.load.length == 0){
            academicLoadTableInfo.innerHTML = "Aún no hay secciones para esta carga académica"
            disableElement(excelImportBtn)
            disableElement(filterInput)
            enableElement(newSectionBtn)
            return
        }
        let load = data.load
        let counter = 1
        load.forEach(section => {
            let newRow = document.createElement("tr")
            let counterTd = newTableData(counter)
            let classCode = newTableData(section["CLASS_CODE"])
            let className = newTableData(section["CLASS_NAME"])
            let uv = newTableData(section["UV"])
            let teacherName = newTableData(section["TEACHER_NAME"])
            let startTime = newTableData(section["START_TIME"])
            let endTime = newTableData(section["END_TIME"])
            let daysWeek = newTableData(section["DAYS_OF_WEEK"])
            let building = newTableData(section["BUILDING_NAME"])
            let classroom = newTableData(section["CLASSROOM_ID"])
            let cupos = newTableData(section["CUPOS"])
            let pac = newTableData(section["PAC"])
            let actions = newTableData("")
            let editBtn = newPrimaryBtn("Editar")
            let deleteBtn = newDangerBtn("Eliminar")
            actions.appendChild(editBtn)
            actions.appendChild(deleteBtn)
            newRow.appendChild(counterTd)
            newRow.appendChild(classCode)
            newRow.appendChild(className)
            newRow.appendChild(uv)
            newRow.appendChild(teacherName)
            newRow.appendChild(startTime)
            newRow.appendChild(endTime)
            newRow.appendChild(daysWeek)
            newRow.appendChild(building)
            newRow.appendChild(classroom)
            newRow.appendChild(cupos)
            newRow.appendChild(pac)
            newRow.appendChild(actions)
            // TODO: data set to row
            editBtn.addEventListener("click", editAcademicLoadSection.bind(newRow))
            deleteBtn.addEventListener("click", deleteAcademicLoadSection.bind(newRow))
            academicLoadTableBody.appendChild(newRow)
            counter++
        })
        enableElement(excelImportBtn)
        enableElement(filterInput)
        enableElement(newSectionBtn)
        academicLoadTableInfo.innerHTML = ""
    })
    .catch( (error) => {
        showPopUp("Hubo un error con el servidor")
        academicLoadTableInfo.innerHTML = "Hubo un error al cargar la carga académica"    
        console.log(error)
    }
        
    )
}

function majorSelected(filterInput, filterSearchBtn, newSectionBtn, excelImportBtn, academicLoadTable, academicLoadTableInfo, event){
    let selectMajor = event.target
    let majorName = selectMajor.options[selectMajor.selectedIndex].text
    let academicLoadTitle = document.getElementById("academicLoadMajor")
    cleanTableBody(academicLoadTable)
    if (selectMajor.value === ""){
        disableElement(filterInput)
        disableElement(newSectionBtn)
        disableElement(excelImportBtn)
        // Empty the academic load table
        showPopUp("Por favor Seleccione una carrera")
        academicLoadTableInfo.innerText = "No se ha seleccionado una carrera"
        academicLoadTitle.innerText = ""
        return
    }
    enableElement(newSectionBtn)
    academicLoadTitle.innerText = majorName
    fetchMajorAcademicLoad(event.target, academicLoadTable, newSectionBtn, academicLoadTableInfo, excelImportBtn, filterInput)
}

function exportLoadToExcel(majorSelect, event){
    let exportTable = document.getElementById("exportTable")
    let exportTableBody = exportTable.querySelector("tbody")
    let majorAbr = majorSelect.value
    fetch(`/api/department_chair/controllers/getMajorAcademicLoadAll.php?major=${majorAbr}`)
    .then(response => response.json())
    .then(data =>{
        if (data.status == "failure"){
            showPopUp("No se pudo cargar toda la carga académica")
            return
        }
        if (data.load.length == 0){
            showPopUp("La carrera aún no tiene planificación")
            return
        }
        let load = data.load
        let counter = 1
        load.forEach(section => {
            let newRow = document.createElement("tr")
            let counterTd = newTableData(counter)
            let classCode = newTableData(section["CLASS_CODE"])
            let className = newTableData(section["CLASS_NAME"])
            let uv = newTableData(section["UV"])
            let teacherName = newTableData(section["TEACHER_NAME"])
            let startTime = newTableData(section["START_TIME"])
            let endTime = newTableData(section["END_TIME"])
            let daysWeek = newTableData(section["DAYS_OF_WEEK"])
            let building = newTableData(section["BUILDING_NAME"])
            let classroom = newTableData(section["CLASSROOM_ID"])
            let cupos = newTableData(section["CUPOS"])
            let pac = newTableData(section["PAC"])
            newRow.appendChild(counterTd)
            newRow.appendChild(classCode)
            newRow.appendChild(className)
            newRow.appendChild(uv)
            newRow.appendChild(teacherName)
            newRow.appendChild(startTime)
            newRow.appendChild(endTime)
            newRow.appendChild(daysWeek)
            newRow.appendChild(building)
            newRow.appendChild(classroom)
            newRow.appendChild(cupos)
            newRow.appendChild(pac)
            exportTableBody.appendChild(newRow)
            counter++
        })
        exportTableToCSV("exportTable", "carga-academica.csv")
        exportTableBody.innerHTML = ""
    })
    .catch((error) => {
        showPopUp("No se pudo cargar toda la carga académica")
    })
}

function fetchMajorClasses(){
    // Fetch las clases de un Major
    let classesSelect = document.getElementById("class")
    let majorAbr = document.getElementById("departmentMajor").value
    classesSelect.innerHTML = `<option value="">Cargando Clases...</option>`
    disableElement(classesSelect)
    fetch(`/api/department_chair/controllers/getMajorClasses.php?major=${majorAbr}`)
    .then(response => response.json())
    .then(data => {
        if (data.status == "failure"){
            showPopUp("La carrera no tiene clases")
            classesSelect.innerHTML = `<option value="">La carrera no tiene clases</option>`
            return
        }
        classesSelect.innerHTML = `<option value="">Seleccione una clase</option>`
        data.classes.forEach(elem => {
            let option = document.createElement("option")
            option.value = elem["CLASS_CODE"]
            option.innerText = elem["CLASS_NAME"]
            option.dataset.uv = elem["UV"]
            classesSelect.appendChild(option)
        })
        enableElement(classesSelect)
    })
    .catch(error => {
        showPopUp("No se pudieron cargar las clases")
        classesSelect.innerHTML = `<option value="">No se pudieron cargar las clases...</option>`
        console.log(error)
    })
}

function fetchMajorTeachers(){
    // Fetch las clases de un Major
    let teacherSelect = document.getElementById("teacher")
    let majorAbr = document.getElementById("departmentMajor").value
    teacherSelect.innerHTML = `<option value="">Cargando Docentes...</option>`
    disableElement(teacherSelect)
    fetch(`/api/department_chair/controllers/getMajorTeachers.php?major=${majorAbr}`)
    .then(response => response.json())
    .then(data => {
        if (data.status == "failure"){
            showPopUp("La carrera no tiene docentes")
            teacherSelect.innerHTML = `<option value="">La carrera no tiene docentes</option>`
            return
        }
        teacherSelect.innerHTML = `<option value="">Seleccione un docente</option>`
        data.teachers.forEach(elem => {
            let option = document.createElement("option")
            option.value = elem["TEACHER_NUMBER"]
            option.innerText = elem["NAME"]
            teacherSelect.appendChild(option)
        })
        enableElement(teacherSelect)
    })
    .catch(error => {
        showPopUp("No se pudieron cargar los docentes")
        teacherSelect.innerHTML = `<option value="">No se pudieron cargar los docentes...</option>`
        console.log(error)
    })
}

function fetchDepartmentBuildings(){
    // Fetch los edificios en los que tiene aulas un departamento
    let buildingSelect = document.getElementById("building")
    buildingSelect.innerHTML = `<option value="">Cargando edificios...</option>`
    disableElement(buildingSelect)
    fetch(`/api/department_chair/controllers/getDepartmentBuildings.php`)
    .then(response => response.json())
    .then(data => {
        if (data.status == "failure"){
            showPopUp("El departamento no tiene aulas en ningún edificio")
            buildingSelect.innerHTML = `<option value="">El departamento no tiene aulas en ningún edificio</option>`
            return
        }
        buildingSelect.innerHTML = `<option value="">Seleccione un edificio</option>`
        data.buildings.forEach(elem => {
            let option = document.createElement("option")
            option.value = elem["BUILDING_ID"]
            option.innerText = elem["BUILDING_NAME"]
            buildingSelect.appendChild(option)
        })
        enableElement(buildingSelect)
    })
    .catch(error => {
        showPopUp("No se pudieron cargar los edificios")
        buildingSelect.innerHTML = `<option value="">No se pudieron cargar los edificios...</option>`
        console.log(error)
    })
}

function fetchDeparmentBuildingClassrooms(classRoomSelect, event){
    let buildingSelect = event.target
    let building = buildingSelect.value
    if (building === ""){
        emptyControl(event.target)
        disableElement(classRoomSelect)
        classRoomSelect.innerHTML = ``
        return
    }
    changeBorder(event.target, "var(--bs-border-width)", "var(--bs-border-color)")
    classRoomSelect.innerHTML = `<option value="">Cargando Aulas...</option>`
    disableElement(classRoomSelect)
    fetch(`/api/department_chair/controllers/getDepartmentClassrooms.php?building=${building}`)
    .then(response => response.json())
    .then(data => {
        if (data.status == "failure"){
            showPopUp("El departamento no tiene aulas en este edificio")
            classRoomSelect.innerHTML = `<option value="">El departamento no tiene aulas en este edificio</option>`
            return
        }
        classRoomSelect.innerHTML = `<option value="">Seleccione un aula</option>`
        data.classrooms.forEach(elem => {
            let option = document.createElement("option")
            option.value = elem["CLASSROOM_ID"]
            option.innerText = elem["ROOM_NAME"]
            classRoomSelect.appendChild(option)
        })
        enableElement(classRoomSelect)
        enableFormSubmission()
    })
    .catch(error => {
        showPopUp("No se pudieron cargar las aulas de este edificio")
        classRoomSelect.innerHTML = `<option value="">No se pudieron cargar las aulas de este edificio...</option>`
        console.log(error)
    })
}

function newSectionModal(academicLoadMajorTitle, event){
    let newSectionModal = document.getElementById("newSectionModal")
    let modalTitle = newSectionModal.querySelector(".titleSuffix")
    modalTitle.innerText = academicLoadMajorTitle.innerText
    newSectionModal = new bootstrap.Modal(newSectionModal);
    newSectionModal.show();
    fetchMajorClasses();
    fetchMajorTeachers();
    fetchDepartmentBuildings();
}

function validQuota(){
    // Check if the quota is valid
    let quota = document.getElementById("quota")
    let quotaInfo = document.getElementById("quota-info")
    if (isNaN(quota.value) || !isInt(quota)){
        changeBorder(quota, "var(--bs-border-width)", "red");
        showPopUp("Los cupos deben de ser un número entero")
        quotaInfo.innerText = "Los cupos deben de ser un número entero"
        return false
    }
    if (parseInt(quota.value) > 40){
        changeBorder(quota, "var(--bs-border-width)", "red");
        showPopUp("Cantidad de cupos máxima superada")
        quotaInfo.innerText = "Cantidad de cupos máxima superada (Cantidad máxima: 40)"
        return false
    }
    changeBorder(quota, "var(--bs-border-width)", "var(--bs-border-color)")
    quotaInfo.innerText = ""
    return true
}

function getEndTime(startTime, classDays, selectClass, endTime, event){
    if (!isFull(event.target)){
        emptyControl(event.target)
        return
    }
    changeBorder(event.target, "var(--bs-border-width)", "var(--bs-border-color)")
    // Check to change schedule for the weekend schedule
    let weekDayOnly = startTime.querySelectorAll(".weekday-only")
    if (event.target.value === "SAT"){
        weekDayOnly.forEach(time => {disableElement(time)})
        startTime.value = ""
        endTime.value = ""
        return
    }
    weekDayOnly.forEach(time => {enableElement(time)})
    let selectedClassDays  = classDays[0].checked ? classDays[0] : classDays[1]
    let selectedClassUv = selectClass.options[selectClass.selectedIndex].dataset.uv
    let startTimeValue = startTime.value
    if (startTimeValue === "" || !selectedClassUv){
        return
    }
    startTimeValue = parseInt(startTimeValue)
    // Calculate the section's end time
    let classHours = 100 * parseInt(selectedClassUv)
    // Calculate the endtime according to the UVs
    let end = selectedClassDays.value === "SAT" ? startTimeValue + classHours : startTimeValue + 100
    let endOption = document.createElement("option")
    endOption.setAttribute("selected", true)
    // Add a zero to the start if the number has less than four digits
    endOption.innerText = end < 1000 ? "0".concat(end): end
    endOption.value = end
    endTime.innerHTML = ""
    endTime.appendChild(endOption)
    enableFormSubmission()
}

function controlOnChangeHandler(event){
    if (!isFull(event.target)){
        emptyControl(event.target)
        console.log("EJECUCIÓN")
        return
    }
    changeBorder(event.target, "var(--bs-border-width)", "var(--bs-border-color)")
    enableFormSubmission()
}

function submitForm(event){
    // Enviar la carga académica
    let selectedClass = document.getElementById("class")
    let teacherSelect = document.getElementById("teacher")
    let classDays = document.getElementsByName("classDays")
    let startTime = document.getElementById("start-time")
    let endTime = document.getElementById("end-time")
    let buildingSelect = document.getElementById("building")
    let classRoomSelect = document.getElementById("classroom")
    let quota = document.getElementById("quota")
    let selectedClassDays  = classDays[0].checked ? classDays[0] : classDays[1]
    let form = new FormData()
    form.append("class-code", selectedClass.value)
    form.append("teacher-number", teacherSelect.value)
    form.append("class-days", selectedClassDays.value)
    form.append("start-time", startTime.value)
    form.append("end-time", endTime.value)
    form.append("building", buildingSelect.value)
    form.append("classroom", classRoomSelect.value)
    form.append("quota", quota.value)
    fetch(`/api/department_chair/controllers/uploadSectionToAcademicLoad.php`, {method: "POST", body: form})
    .then(response => response.json())
    .then(data => {
        if (data.status === "failure"){
            showPopUp("No se pudo subir la sección")
            return
        }
        showPopUp("Se guardó la sección exitosamente!", "success-popup", "/views/assets/img/checkmark.png")
        document.getElementById("closeNewSectionModal").click()
    })
    .catch(error => {
        showPopUp("Hubo un error al subir la sección")
        console.log(error)
    })
}

function main(){
    let selectMajor = document.getElementById("departmentMajor")
    let filterInput = document.getElementById("academicLoadFilter")
    let filterSearchBtn = document.getElementById("academicLoadFilterSearchBtn")
    let newSectionBtn = document.getElementById("newSectionBtn")
    let excelImportBtn = document.getElementById("excelImportBtn")
    let academicLoadTable = document.getElementById("academicLoadTable")
    let academicLoadTableInfo = document.getElementById("table-info")
    let academicLoadMajorTitle = document.getElementById("academicLoadMajor")
    // New Section Form Controls
    let buildingSelect = document.getElementById("building")
    let classRoomSelect = document.getElementById("classroom")
    let teacherSelect = document.getElementById("teacher")
    let startTime = document.getElementById("start-time")
    let classDays = document.getElementsByName("classDays")
    let selectClass = document.getElementById("class")
    let endTime = document.getElementById("end-time")
    let quota = document.getElementById("quota")
    let submit = document.getElementById("submit")
    fetchDeparmentMajors(selectMajor)
    selectMajor.addEventListener("change", majorSelected.bind(null, filterInput, filterSearchBtn, newSectionBtn, excelImportBtn, academicLoadTable, academicLoadTableInfo))
    excelImportBtn.addEventListener("click", exportLoadToExcel.bind(null, selectMajor))
    newSectionBtn.addEventListener("click", newSectionModal.bind(null, academicLoadMajorTitle))
    // New Section Modal
    buildingSelect.addEventListener("change", fetchDeparmentBuildingClassrooms.bind(null, classRoomSelect))
    startTime.addEventListener("change", getEndTime.bind(null, startTime, classDays, selectClass, endTime));
    classDays.forEach(elem => {elem.addEventListener("change", getEndTime.bind(null, startTime, classDays, selectClass, endTime))})
    selectClass.addEventListener("change", getEndTime.bind(null, startTime, classDays, selectClass, endTime));
    teacherSelect.addEventListener("change", controlOnChangeHandler)
    classRoomSelect.addEventListener("change", controlOnChangeHandler)
    quota.addEventListener("change", controlOnChangeHandler)
    submit.addEventListener("click", submitForm)
}

main()