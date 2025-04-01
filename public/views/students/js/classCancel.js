// Lógica para cargar la vista majorChange.php
import {showPopUp, changeBorder} from "../../js/modules/utlis.mjs"

function validateFileInput(event){
    let file =  event.target.files[0]
    let sendBtn = document.getElementById("sendRequestBtn")
    let inputChangeEvent = new CustomEvent("input-change")
    if (!file){
        changeBorder(event.target, "var(--bs-border-width)", "red");
        showPopUp("No puede dejar el campo vacío")
        sendBtn.dispatchEvent(inputChangeEvent)
        return
    }
    changeBorder(event.target, "var(--bs-border-width)", "var(--bs-border-color)")
    sendBtn.dispatchEvent(inputChangeEvent)
}

function checkInputFull(event){
    let classCancel = document.getElementById("classCancel")
    if (classCancel.files.length != 0){
        event.target.removeAttribute("disabled")
    }else{
        event.target.setAttribute("disabled", "disabled")
    }
}

function cleanRequestModal(event){
    let classCancel = document.getElementById("classCancel")
    let sendBtn = document.getElementById("sendRequestBtn")
    classCancel.value = ""
    sendBtn.setAttribute("disabled", "disabled")
    classCancel.removeAttribute("disabled")
    changeReason.removeAttribute("disabled")
    changeBorder(classCancel, "var(--bs-border-width)", "var(--bs-border-color)")
    changeBorder(changeReason, "var(--bs-border-width)", "var(--bs-border-color)")
}


function fetchClassCancelRequest(){
    let requestsTableBody = document.getElementById("studentRequestsTableBody")
    requestsTableBody.innerHTML = ""
    let requestTableInfo = document.getElementById("majorChangeTblInfo")
    requestTableInfo.innerText = "Cargando solicitudes..."
    fetch("/api/students/controllers/getClassCancelRequests.php")
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
            let requestDate = request["CREATE_DATE"]
            let status = request["STATUS"]
            let countTd = document.createElement("td")
            countTd.innerText = count
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
    let classCancel = document.getElementById("classCancel")
    let sendBtn = document.getElementById("sendRequestBtn")
    sendBtn.setAttribute("disabled", "disabled")
    let body = new FormData()
    modal = bootstrap.Modal.getInstance(modal);
    body.append("cancel", classCancel.files[0])
    body.append("requestType", "CANCELLATION")
    classCancel.setAttribute("disabled", "disabled")
    fetch("/api/students/controllers/newRequest.php", {method: "POST", body: body})
    .then(response => response.json())
    .then(data => {
        if (data.status == "failure"){
            showPopUp("No pudo realizarse la solicitud")
            classCancel.removeAttribute("disabled")
            sendBtn.removeAttribute("disabled")
            return 
        }
        showPopUp("Solicitud realizada con éxito", "success-popup", "/views/assets/img/checkmark.png")
        modal.hide()
        fetchClassCancelRequest()
    })
    .catch((error) => {
        showPopUp("Hubo un error con el servidor")
        classCancel.removeAttribute("disabled")
        changeReason.removeAttribute("disabled")
    })
}

function main(){
    let classCancel = document.getElementById("classCancel")
    let sendBtn = document.getElementById("sendRequestBtn")
    let modal = document.getElementById("newRequestModal")
    classCancel.addEventListener("change", validateFileInput)
    sendBtn.addEventListener("input-change", checkInputFull)
    sendBtn.addEventListener("click", sendRequest)
    modal.addEventListener("hide.bs.modal", cleanRequestModal)
    fetchClassCancelRequest() 
}

main()