// Lógica para la carga de la vista inscripciones.php
import { changeBorder, showPopUp } from "./modules/utlis.mjs"
import { validDNI, validEmail, validPhoneNumber } from "./modules/validator.mjs"

function fetchMajorsByRegionalCenter(event){
    let selectedRegionalCenter = event.target
    let centerId = selectedRegionalCenter.value
    let urlParams = new URLSearchParams({"center": centerId})
    let mainMajorSelect = document.getElementById("main-career")
    let secondaryMajorSelect = document.getElementById("secondary-career")
    fetch(`/api/resources/controllers/majorsByCenter.php?${urlParams}`, {METHOD:"GET"})
    .then(response =>{ return response.json()})
    .then(data => {
        mainMajorSelect.innerHTML = ""
        secondaryMajorSelect.innerHTML = ""
        for (let major of data["data"]){
            let option = document.createElement("option")
            let majorId = major["MAJOR_ID"]
            let majorName = major["MAJOR_NAME"]
            option.innerText = majorName
            option.value = majorId
            mainMajorSelect.appendChild(option)
            let clone = option.cloneNode(true)
            secondaryMajorSelect.appendChild(clone)
        }

    })
    // TODO: Implement pop up to show error message
    .catch(console.log("No se puedieron obtener los datos de este centro"))
    
    
}

function fetchRegionalCenters(){
    // Fetch los centros regionales de la universidad
    let regionalCentersSelect = document.getElementById("regional-center")
    fetch("/api/resources/controllers/regionalCenters.php", {METHOD:"GET"})
    .then(response => response.json())
    .then(data => {
        for (let center of data["data"]){
            let option = document.createElement("option")
            option.innerText = center["CENTER_NAME"]
            option.value = center["CENTER_ID"]
            option.onclick = fetchMajorsByRegionalCenter
            regionalCentersSelect.appendChild(option)
        }
    })
    // TODO: Implementar una vista por errores con el servidor
    .catch(error => {
        console.log("No se pudo conectar con el servidor, intente mas tarde")
        console.log(error)
    })
}

function cleanFormStyle(form){
    // Devuelve el formulario a estilo original
    for (let control of form){
        changeBorder(control, "var(--bs-border-width)", "var(--bs-border-color)")
    }
}

function emptyFormControls(form){
    // Retorna una lista con los controls que estén vacíos
    let empty = []
    for (let control of form ){
        if (control.value === "" || control.value === null) empty.push(control)
    }
    return empty
}

function emptyControlStyle(emptyControls){
    // Darles en border rojo a los controles dados
    for (let control of emptyControls){
        changeBorder(control, "var(--bs-border-width)", "red")
    }
}

function differentOptions(mainCareer, secondaryCareer){
    // Verificar si se eligieron dos carreras diferentes
    return mainCareer.value !== secondaryCareer.value
}

function submitForm(event){
    let firstName = document.getElementById("first-name")
    let lastName = document.getElementById("last-name")
    let dni = document.getElementById("user-id")
    let phone = document.getElementById("phone")
    let email = document.getElementById("email")
    let genderSelect = document.getElementById("gender")
    let regionalCentersSelect = document.getElementById("regional-center")
    let mainCareerSelect = document.getElementById("main-career")
    let secondaryCareerSelect = document.getElementById("secondary-career")
    let certificateFile = document.getElementById("file-upload")
    let form = [firstName, lastName, dni, phone, email, genderSelect, regionalCentersSelect, mainCareerSelect,          secondaryCareerSelect, certificateFile]
    let emptyControls = emptyFormControls(form)
    cleanFormStyle(form)
    if (emptyControls.length != 0){
        // Indicar que se debe de llenar los campos obligatorios
        emptyControlStyle(emptyControls)
        showPopUp("Porfavor, Llene todos los datos")
        return
    }
    if(!validDNI(dni.value)){
        emptyControlStyle([dni])
        showPopUp("Ingrese un DNI válido")
        return 
    }
    if (!validPhoneNumber(phone.value)){
        emptyControlStyle([phone])
        showPopUp("Ingrese un número válido")
        return
    }
    if (!validEmail(email.value)){
        emptyControlStyle([email])
        showPopUp("Ingrese un correo válido")
        return
    }
    if (!differentOptions(mainCareerSelect, secondaryCareerSelect)){
        console.log("MISMA CARRERA!")
        emptyControlStyle([mainCareerSelect, secondaryCareerSelect])
        showPopUp("Elija dos carreras distintas")
        return
    }
    let formData = new FormData()
    formData.append("firstName", firstName.value.trim())
    formData.append("lastName", lastName.value.trim())
    formData.append("dni", dni.value.trim())
    formData.append("phoneNumber", phone.value.trim())
    formData.append("email", email.value.trim())
    formData.append("gender", gender.value)
    formData.append("primaryMajor", mainCareerSelect.value)
    formData.append("secondaryMajor", secondaryCareerSelect.value)
    formData.append("comment", "Envio inscripcion")
    formData.append("certificate", certificateFile.files[0])
    fetch("/api/admissions/controllers/createAdmission.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log("Se guardó la solicitud exitosamente!")
    })
    .catch(console.log("No se pudo guardar la solicitud"))


}


function main(){
    fetchRegionalCenters()
    let submitBtn = document.getElementById("submit-button")
    submitBtn.addEventListener("click", submitForm)
}


main()