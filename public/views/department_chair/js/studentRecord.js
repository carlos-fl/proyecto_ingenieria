// Lógica para la vista studentRecord.php
import {showPopUp, changeBorder, newTableData, newPrimaryBtn, showLoadingIcon} from "../../js/modules/utlis.mjs"

function showAcademicRecordModal(accountNumber, event){
    // Show a modal with the acadmic record of the student
    let modal = document.getElementById("classRecordModal")
    let newClassModal = new bootstrap.Modal(modal);
    let tableBody = document.getElementById("classRecordTable").querySelector("tbody")
    let tableInfo = document.getElementById("classRecordTableInfo")
    showLoadingIcon(tableInfo)
    tableBody.innerHTML = ""
    newClassModal.show()
    fetch(`/api/department_chair/controllers/getStudentClassHistory.php?accountNumber=${accountNumber}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === "failure"){
            tableInfo.innerHTML = "No se encontró historial para este estudiante"
            return
        }
        let recordHistory = data.data
        for (let [key, value] of Object.entries(recordHistory)){
            let yearRecord = recordHistory[key]
            yearRecord.forEach(record => {
                let row = document.createElement("tr")
                let code = newTableData(record["CODE"])
                let className = newTableData(record["CLASS_NAME"])
                let uv = newTableData(record["UV"])
                let year = newTableData(record["PERIOD"])
                let pac = newTableData(record["PAC"])
                let grade = newTableData(record["CALIFICATION"])
                let obs = newTableData(record["OBS"])
                row.appendChild(code)
                row.appendChild(className)
                row.appendChild(uv)
                row.appendChild(year)
                row.appendChild(pac)
                row.appendChild(grade)
                row.appendChild(obs)
                tableBody.appendChild(row)
            })
        }
        tableInfo.innerHTML = ""
    })
    .catch(error =>{
        showPopUp("Hubo un error al cargar el historial de las clases")
        tableInfo.innerHTML = "Hubo un error al cargar los registros"
    })
}

function addStudentToTable(student, studentRecordTable){
    let row = document.createElement("tr")
    let accountNumber =newTableData(student["ACCOUNT_NUMBER"])
    let studentName = newTableData(`${student["FIRST_NAME"]} ${student["LAST_NAME"]}`)
    let studentGPA = newTableData(student["GPA"])
    let academicRecord = newTableData("")
    let actionBtn = newPrimaryBtn("Ver Historial")
    academicRecord.appendChild(actionBtn)
    actionBtn.addEventListener("click", showAcademicRecordModal.bind(null, student["ACCOUNT_NUMBER"]))
    row.appendChild(accountNumber)
    row.appendChild(studentName)
    row.appendChild(studentGPA)
    row.appendChild(academicRecord)
    studentRecordTable.querySelector("tbody").appendChild(row)
}

function searchByAccountNumber(accountNumberInput, studentRecordTable, tableInfo, event){
    studentRecordTable.querySelector("tbody").innerHTML = ""
    let accountNumber = accountNumberInput.value.trim()
    if (accountNumber === ""){
        showPopUp("Escriba un número de cuenta ")
        changeBorder(accountNumberInput, "var(--bs-border-width)", "red");
        return
    }
    showLoadingIcon(tableInfo)
    fetch(`/api/department_chair/controllers/studentRecordByAccountNumber.php?accountNumber=${accountNumber}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === "failure"){
            // No se encontró un estudiante con ese número de cuenta
            showPopUp("No se encontró un estudiante con esa cuenta")
            changeBorder(accountNumberInput, "var(--bs-border-width)", "red");
            tableInfo.innerText = "No se encontró un estudiante con ese número de cuenta"
            return
        }
        let students = data.students
        students.forEach(student => {
            addStudentToTable(student, studentRecordTable); 
        });
        tableInfo.innerText = ""
    })
    .catch(error => {
        showPopUp("Hubo un error al buscar esa cuenta")
        tableInfo.innerText = "Hubo un error al buscar el estudiante con esta cuenta"
        changeBorder(accountNumberInput, "var(--bs-border-width)", "red");
        console.log(error)
    })
}


function main(){
    let searchByAccountBtn = document.getElementById("searchByAccountBtn")
    let accountNumberInput = document.getElementById("studentAccountNumber")
    let studentRecordTable = document.getElementById("studentRecordTable")
    let tableInfo = document.getElementById("table-info")
    searchByAccountBtn.addEventListener("click", searchByAccountNumber.bind(null, accountNumberInput, studentRecordTable, tableInfo))
}

main()
