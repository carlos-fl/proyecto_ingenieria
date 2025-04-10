// Lógica para la vista academicLoad.php

import { changeBorder, cleanTableBody, disableElement, enableElement, exportTableToCSV, newDangerBtn, newPrimaryBtn, newTableData, showLoadingIcon, showPopUp } from "../../js/modules/utlis.mjs"

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
        showPopUp("Por favor elija una carrera")
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
        showPopUp("Elija un edificio porfavor")
        classRoomSelect.innerHTML = ``
        disableElement(classRoomSelect)
        return
    }
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

function main(){
    let selectMajor = document.getElementById("departmentMajor")
    let filterInput = document.getElementById("academicLoadFilter")
    let filterSearchBtn = document.getElementById("academicLoadFilterSearchBtn")
    let newSectionBtn = document.getElementById("newSectionBtn")
    let excelImportBtn = document.getElementById("excelImportBtn")
    let academicLoadTable = document.getElementById("academicLoadTable")
    let academicLoadTableInfo = document.getElementById("table-info")
    let academicLoadMajorTitle = document.getElementById("academicLoadMajor")
    let buildingSelect = document.getElementById("building")
    let classRoomSelect = document.getElementById("classroom")
    fetchDeparmentMajors(selectMajor)
    selectMajor.addEventListener("change", majorSelected.bind(null, filterInput, filterSearchBtn, newSectionBtn, excelImportBtn, academicLoadTable, academicLoadTableInfo))
    excelImportBtn.addEventListener("click", exportLoadToExcel.bind(null, selectMajor))
    newSectionBtn.addEventListener("click", newSectionModal.bind(null, academicLoadMajorTitle))
    buildingSelect.addEventListener("change", fetchDeparmentBuildingClassrooms.bind(null, classRoomSelect))
}

main()