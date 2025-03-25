import { Request } from "../../../js/modules/request.mjs";
import { relocateWithSuccessModal, showFailModal } from "../../../js/modules/utlis.mjs";
import { formFields } from "./formFields.mjs";

function checkFormCompletion() {
  // Se comprueba que cada campo tenga un valor válido
  let allFilled = formFields.every(
    (field) => field.value && field.value.trim() !== ""
  );
  submitBtn.disabled = !allFilled;
}

//Bloquear el botón Enviar
formFields.forEach((field) => {
  field.addEventListener("input", checkFormCompletion);
  field.addEventListener("change", checkFormCompletion);
});

// Needs application code to update which data has
function fillBodyRequest() {
  const requestBody = {}
  formFields.forEach(obj => {
    if (obj.name == 'certificate') {
      requestBody["certificate"] = obj.el.files[0]
    } else {
      requestBody[obj.name] = obj.el.value
    }
  })
  return requestBody
}

function sendResubmissionForm() {
  const submitBtn = document.getElementById('submit-button')
  submitBtn.addEventListener('click', async () => {
    try {
      const URL = '/api/admissions/controllers/updateAdmission.php'
      const body = fillBodyRequest()
      const res = Request.fetch(URL, 'PUT', body)
      if (res.status == 'success') {
        relocateWithSuccessModal('/', 'correction-success')
      }
    } catch(err) {
      showFailModal('correction-fail')
    }
  }) 
}

sendResubmissionForm()