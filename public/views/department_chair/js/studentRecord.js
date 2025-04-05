// Lógica para la vista studentRecord.php
import {showPopUp, changeBorder, newTableData, newPrimaryBtn} from "../../js/modules/utlis.mjs"

function showLoadingIcon(domObject){
    // Show a loading component in a dom object
    domObject.innerText = ""
    domObject.innerHTML = `
        <div class="spinner-border text-secondary">
            <span class="sr-only">Loading...</span>
        </div>
        <div>Cargado Registros...</div>
        `
}

function showAcademicRecordModal(accountNumber, event){
    // Show a modal with the acadmic record of the student

}

function addStudentToTable(student, studentRecordTable){
    let row = document.createElement("tr")
    let accountNumber =newTableData(student["ACCOUNT_NUMBER"])
    let studentName = newTableData(`${student["FIRST_NAME"]} ${student["LAST_NAME"]}`)
    let studentGPA = newTableData(student["GPA"])
    let academicRecord = newTableData("")
    academicRecord.innerHTML = newPrimaryBtn("Enviar")
    academicRecord.addEventListener("click", showAcademicRecordModal.bind(student["accountNumber"]))
    row.appendChild(accountNumber)
    row.appendChild(studentName)
    row.appendChild(studentGPA)
    row.appendChild(academicRecord)
    studentRecordTable.querySelector("tbody").appendChild(row)
}

function searchByAccountNumber(accountNumberInput, studentRecordTable, tableInfo, event){
    let accountNumber = accountNumberInput.value.trim()
    if (accountNumber === ""){
        showPopUp("Escriba un número de cuenta ")
        changeBorder(accountNumberInput, "var(--bs-border-width)", "red");
        return
    }
    showLoadingIcon(tableInfo)
    fetch(`/api/department_chair/controllers/studentRecordByAccountNumber?acountNumber="${accountNumber}"`)
    .then(response => response.json())
    .then(data => {
        if (data === "failure"){
            // No se encontró un estudiante con ese número de cuenta
            showPopUp("No se encontró un estudiante con esa cuenta")
            changeBorder(accountNumberInput, "var(--bs-border-width)", "red");
            tableInfo.innerText = "No se encontró un estudiante con ese número de cuenta"
            return
        }
        let students = data.students
        students.array.forEach(student => {
            addStudentToTable(student, studentRecordTable); 
        });

    })
    .catch(error => {
        showPopUp("Hubo un error al buscar esa cuenta")
        tableInfo.innerText = "Hubo un error al buscar el estudiante con esta cuenta"
        changeBorder(accountNumberInput, "var(--bs-border-width)", "red");
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
