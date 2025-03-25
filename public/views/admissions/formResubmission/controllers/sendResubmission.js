import { Request } from "../../../js/modules/request.mjs";
import { formFields } from "./formFields.mjs";
import { changeBorder, hideLoadingComponent, relocateWithErrorModal, relocateWithSuccessModal, showLoadingComponent, showPopUp, showFailModal } from "../../../js/modules/utlis.mjs";
import { validDNI, validPhoneNumber, validEmail } from "../../../js/modules/validator.mjs";

const submitBtn = document.getElementById('submit-button')
function cleanFormStyle(form) {
  // Devuelve el formulario a estilo original
  for (let control of form) {
    changeBorder(control, "var(--bs-border-width)", "var(--bs-border-color)");
  }
}



function emptyFormControls(form) {
  // Retorna una lista con los controls que estén vacíos
  let empty = [];
  for (let control of form) {
    if (control.value === "" || control.value === null) empty.push(control);
  }
  return empty;
}


function emptyControlStyle(emptyControls) {
  // Darles en border rojo a los controles dados
  for (let control of emptyControls) {
    changeBorder(control, "var(--bs-border-width)", "red");
  }
}

function differentOptions(mainCareer, secondaryCareer) {
  // Verificar si se eligieron dos carreras diferentes
  return mainCareer.value !== secondaryCareer.value;
}
function checkFormCompletion() {
  // Se comprueba que cada campo tenga un valor válido
  let allFilled = formFields.every(
    (field) => field.el.value && field.el.value.trim() !== ""
  );
  submitBtn.disabled = !allFilled;
}

//Bloquear el botón Enviar
formFields.forEach((field) => {
  field.el.addEventListener("input", checkFormCompletion);
  field.el.addEventListener("change", checkFormCompletion);
});


function sendResubmissionForm() {
  const submitBtn = document.getElementById('submit-button')
  submitBtn.addEventListener('click', async (event) => {
    try {
      event.preventDefault()
      let firstName = document.getElementById("first-name");
      let lastName = document.getElementById("last-name");
      let dni = document.getElementById("user-id");
      let phone = document.getElementById("phone");
      let email = document.getElementById("email");
      let genderSelect = document.getElementById("gender");
      let regionalCentersSelect = document.getElementById("regional-center");
      let mainCareerSelect = document.getElementById("main-career");
      let secondaryCareerSelect = document.getElementById("secondary-career");
      let certificateFile = document.getElementById("file-upload");
      let form = [
        firstName,
        lastName,
        dni,
        phone,
        email,
        genderSelect,
        regionalCentersSelect,
        mainCareerSelect,
        secondaryCareerSelect,
        certificateFile,
      ];
      
      let emptyControls = emptyFormControls(form);
      cleanFormStyle(form);
      if (emptyControls.length != 0) {
        // Indicar que se debe de llenar los campos obligatorios
        emptyControlStyle(emptyControls);
        showPopUp("Porfavor, Llene todos los datos");
        return;
      }
      if (!validDNI(dni.value)) {
        emptyControlStyle([dni]);
        showPopUp("Ingrese un DNI válido");
        return;
      }
      if (!validPhoneNumber(phone.value)) {
        emptyControlStyle([phone]);
        showPopUp("Ingrese un número válido");
        return;
      }
      if (!validEmail(email.value)) {
        emptyControlStyle([email]);
        showPopUp("Ingrese un correo válido");
        return;
      }
      if (!differentOptions(mainCareerSelect, secondaryCareerSelect)) {
        console.log("MISMA CARRERA!");
        emptyControlStyle([mainCareerSelect, secondaryCareerSelect]);
        showPopUp("Elija dos carreras distintas");
        return;
      }


      const URL_TOKEN = new URLSearchParams(window.location.search).get('token')
      const URL_INFO = `/api/admissions/controllers/getSubmittedAdmission.php?token=${URL_TOKEN}`
      var data = await Request.fetch(URL_INFO, "GET")

      let formData = new FormData();
      formData.append("firstName", firstName.value.trim());
      formData.append("lastName", lastName.value.trim());
      formData.append("dni", dni.value.trim());
      formData.append("phoneNumber", phone.value.trim());
      formData.append("email", email.value.trim());
      formData.append("gender", genderSelect.value);
      formData.append("comment", "Reenvío de solicitud");
      formData.append("certificate", certificateFile.files[0]);
      formData.append("applicationID", data.applicant.APPLICATION_ID)
      formData.append("applicationCODE", data.applicant.APPLICATION_CODE)
      formData.append("token", URL_TOKEN)

      showLoadingComponent("loading")
      const URL = '/api/admissions/controllers/updateResubmittedAdmission.php'
      const res = Request.fetch(URL, 'POST', formData, false)
      hideLoadingComponent('loading')
      relocateWithSuccessModal('/', 'correction-success')
    } catch(err) {
      console.log(err)
      showFailModal('correction-fail')
    }
  }) 
}

sendResubmissionForm()