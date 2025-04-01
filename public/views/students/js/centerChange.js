// Lógica para cargar la vista centerChange.php
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
    let centerSelect = document.getElementById("newCenterSelect")
    let changeReason = document.getElementById("changeReason")
    if (centerSelect.value !== "" && changeReason.value !== ""){
        event.target.removeAttribute("disabled")
    }else{
        event.target.setAttribute("disabled", "disabled")
    }
}

function cleanRequestModal(event){
    let centerSelect = document.getElementById("newCenterSelect")
    let changeReason = document.getElementById("changeReason")
    let sendBtn = document.getElementById("sendRequestBtn")
    changeReason.value = ""
    sendBtn.setAttribute("disabled", "disabled")
    centerSelect.removeAttribute("disabled")
    changeReason.removeAttribute("disabled")
    changeBorder(centerSelect, "var(--bs-border-width)", "var(--bs-border-color)")
    changeBorder(changeReason, "var(--bs-border-width)", "var(--bs-border-color)")
}

function fetchCenters(){
    // Traer las carreras a las que no pertenece un usuario
    let centerSelect = document.getElementById("newCenterSelect")
    centerSelect.innerHTML = "<option>Cargando Centros...</option>"
    centerSelect.setAttribute("disabled", "disabled")
    fetch("/api/students/controllers/getDifferentRegionalCenters.php", {method:"GET"})
    .then(response => response.json())
    .then(data => {
        if (data.status == "failure"){
            showPopUp("Hubo un error al traer las carreras")
            return
        }
        console.log(data)
        let centers = data.centers
        centerSelect.innerHTML = '<option value="">Seleccione el centro regional</option>'
        centers.forEach((center) =>{
            let option = document.createElement("option")
            option.value = center["ABREVIATION"]
            option.innerText = center["CENTER_NAME"]
            centerSelect.appendChild(option)
        })
        centerSelect.removeAttribute("disabled")
    })
    .catch( (error) =>{
        showPopUp("Hubo un error al traer los centros regionales")
    })
}

function fetchCenterChangeRequest(){
    let requestsTableBody = document.getElementById("studentRequestsTableBody")
    requestsTableBody.innerHTML = ""
    let requestTableInfo = document.getElementById("centerChangeTblInfo")
    requestTableInfo.innerText = "Cargando solicitudes..."
    fetch("/api/students/controllers/getCenterChangeRequests.php")
    .then(response => response.json())
    .then(data => {
        if (data.status === "failure"){
            showPopUp("Hubo un fallo al traer sus solicitudes")
            return
        }
        let count = 1
        if (data.requests.length == 0){
            // El estudiante no tiene solicitudes pendientes
            requestTableInfo.innerText = "No tienes solicitudes de cambio de centro"
            requestTableInfo.removeAttribute("hidden")
            return
        }
        requestTableInfo.setAttribute("hidden", "hidden")
        for (let request of data.requests){
            let row = document.createElement("tr")
            let center = request["CENTER_NAME"]
            let requestDate = request["CREATE_DATE"]
            let status = request["STATUS"]
            let countTd = document.createElement("td")
            countTd.innerText = count
            let centerTd = document.createElement("td")
            centerTd.innerText = center
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
            row.appendChild(centerTd)
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
    let centerSelect = document.getElementById("newCenterSelect")
    let changeReason = document.getElementById("changeReason")
    let body = new FormData()
    modal = bootstrap.Modal.getInstance(modal);
    body.append("center", centerSelect.value)
    body.append("content", changeReason.value)
    body.append("requestType", "CAMPUSTRANSFER")
    centerSelect.setAttribute("disabled", "disabled")
    changeReason.setAttribute("disabled", "disabled")
    fetch("/api/students/controllers/newRequest.php", {method: "POST", body: body})
    .then(response => response.json())
    .then(data => {
        if (data.status == "failure"){
            showPopUp("No pudo realizarse la solicitud")
            centerSelect.removeAttribute("disabled")
            changeReason.removeAttribute("disabled")
            return 
        }
        showPopUp("Solicitud realizada con éxito", "success-popup", "/views/assets/img/checkmark.png")
        modal.hide()
        fetchCenterChangeRequest()
    })
    .catch((error) => {
        showPopUp("Hubo un error con el servidor")
        centerSelect.removeAttribute("disabled")
        changeReason.removeAttribute("disabled")
    })
}

function main(){
    let newRequestBtn = document.getElementById("newRequestBtn")
    let centerSelect = document.getElementById("newCenterSelect")
    let changeReason = document.getElementById("changeReason")
    let sendBtn = document.getElementById("sendRequestBtn")
    let modal = document.getElementById("newRequestModal")
    newRequestBtn.addEventListener("click", fetchCenters)
    centerSelect.addEventListener("change", validateInput)
    changeReason.addEventListener("input", validateInput)
    sendBtn.addEventListener("input-change", checkInputFull)
    sendBtn.addEventListener("click", sendRequest)
    modal.addEventListener("hide.bs.modal", cleanRequestModal)
    fetchCenterChangeRequest() 
}

main()