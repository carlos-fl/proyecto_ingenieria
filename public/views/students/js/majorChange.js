// Lógica para cargar la vista majorChange.php
import {showPopUp, changeBorder} from "../../js/modules/utlis.mjs"

function validateInput(event){
    let value = event.target.value.trim()
    let sendBtn = document.getElementById("sendRequestBtn")
    let inputChangeEvent = new CustomEvent("input-change")
    if (value === ""){
        changeBorder(event.target, "var(--bs-border-width)", "red");
        showPopUp("No puede dejar el campo vacío")
        sendBtn.dispatchEvent(inputChangeEvent)
        return
    }
    changeBorder(event.target, "var(--bs-border-width)", "var(--bs-border-color)")
    sendBtn.dispatchEvent(inputChangeEvent)
}

function checkInputFull(event){
    let majorSelect = document.getElementById("newMajorSelect")
    let changeReason = document.getElementById("changeReason")
    if (majorSelect.value !== "" && changeReason.value !== ""){
        event.target.removeAttribute("disabled")
    }else{
        event.target.setAttribute("disabled", "disabled")
    }
}

function cleanRequestModal(event){
    let majorSelect = document.getElementById("newMajorSelect")
    let changeReason = document.getElementById("changeReason")
    let sendBtn = document.getElementById("sendRequestBtn")
    changeReason.value = ""
    sendBtn.setAttribute("disabled", "disabled")
    majorSelect.removeAttribute("disabled")
    changeReason.removeAttribute("disabled")
    changeBorder(majorSelect, "var(--bs-border-width)", "var(--bs-border-color)")
    changeBorder(changeReason, "var(--bs-border-width)", "var(--bs-border-color)")
}

function fetchMajors(){
    // Traer las carreras a las que no pertenece un usuario
    let majorSelect = document.getElementById("newMajorSelect")
    majorSelect.innerHTML = "<option>Cargando Carreras...</option>"
    majorSelect.setAttribute("disabled", "disabled")
    fetch("/api/students/controllers/getDifferentMajors.php", {method:"GET"})
    .then(response => response.json())
    .then(data => {
        if (data.status == "failure"){
            showPopUp("Hubo un error al traer las carreras")
            return
        }
        let majors = data.majors
        majorSelect.innerHTML = '<option value="">Seleccione la carrera</option>'
        majors.forEach((major) =>{
            let option = document.createElement("option")
            option.value = major["ABREVIATION"]
            option.innerText = major["MAJOR_NAME"]
            majorSelect.appendChild(option)
        })
        majorSelect.removeAttribute("disabled")
    })
    .catch( (error) =>{
        showPopUp("Hubo un error al traer las carreras")
    })
}

function fetchMajorChangeRequest(){
    let requestsTableBody = document.getElementById("studentRequestsTableBody")
    requestsTableBody.innerHTML = ""
    let requestTableInfo = document.getElementById("majorChangeTblInfo")
    requestTableInfo.innerText = "Cargando solicitudes..."
    fetch("/api/students/controllers/getMajorChangeRequests.php")
    .then(response => response.json())
    .then(data => {
        if (data.status === "failure"){
            showPopUp("Hubo un fallo al traer sus solicitudes")
            return
        }
        let count = 1
        if (data.requests.length == 0){
            // El estudiante no tiene solicitudes pendientes
            requestTableInfo.innerText = "No tienes solicitudes de cambio de carrera"
            requestTableInfo.removeAttribute("hidden")
            return
        }
        requestTableInfo.setAttribute("hidden", "hidden")
        for (let request of data.requests){
            let row = document.createElement("tr")
            let major = request["MAJOR_NAME"]
            let requestDate = request["CREATE_DATE"]
            let status = request["STATUS"]
            let countTd = document.createElement("td")
            countTd.innerText = count
            let majorTd = document.createElement("td")
            majorTd.innerText = major
            let requestDateTd = document.createElement("td")
            requestDateTd.innerText = requestDate
            let statusTd = document.createElement("td")
            statusTd.innerText = status
            switch (status){
                case "PENDIENTE":
                    statusTd.classList.add("text-warning")
                    break
                case "RECHAZADA":
                    statusTd.classList.add("text-danger")
                    break
                case "APROBADA":
                    statusTd.classList.add("text-success")
                    break
            }
            row.appendChild(countTd)
            row.appendChild(majorTd)
            row.appendChild(requestDateTd)
            row.appendChild(statusTd)
            requestsTableBody.appendChild(row)
            count++
        }
    })
    .catch((error) => {
        showPopUp("Hubo un error al traer sus solicitudes")
    })
}

function sendRequest(event){    
    let modal = document.getElementById("newRequestModal")
    let majorSelect = document.getElementById("newMajorSelect")
    let changeReason = document.getElementById("changeReason")
    let sendBtn = document.getElementById("sendRequestBtn")
    sendBtn.setAttribute("disabled", "disabled")
    let body = new FormData()
    modal = bootstrap.Modal.getInstance(modal);
    body.append("major", majorSelect.value)
    body.append("content", changeReason.value)
    body.append("requestType", "MAJORCHANGE")
    majorSelect.setAttribute("disabled", "disabled")
    changeReason.setAttribute("disabled", "disabled")
    fetch("/api/students/controllers/newRequest.php", {method: "POST", body: body})
    .then(response => response.json())
    .then(data => {
        if (data.status == "failure"){
            showPopUp("No pudo realizarse la solicitud")
            majorSelect.removeAttribute("disabled")
            changeReason.removeAttribute("disabled")
            sendBtn.removeAttribute("disabled")

            return 
        }
        showPopUp("Solicitud realizada con éxito", "success-popup", "/views/assets/img/checkmark.png")
        modal.hide()
        fetchMajorChangeRequest()
    })
    .catch((error) => {
        showPopUp("Hubo un error con el servidor")
        majorSelect.removeAttribute("disabled")
        changeReason.removeAttribute("disabled")
    })
}

function main(){
    let newRequestBtn = document.getElementById("newRequestBtn")
    let majorSelect = document.getElementById("newMajorSelect")
    let changeReason = document.getElementById("changeReason")
    let sendBtn = document.getElementById("sendRequestBtn")
    let modal = document.getElementById("newRequestModal")
    newRequestBtn.addEventListener("click", fetchMajors)
    majorSelect.addEventListener("change", validateInput)
    changeReason.addEventListener("input", validateInput)
    sendBtn.addEventListener("input-change", checkInputFull)
    sendBtn.addEventListener("click", sendRequest)
    modal.addEventListener("hide.bs.modal", cleanRequestModal)
    fetchMajorChangeRequest() 
}

main()