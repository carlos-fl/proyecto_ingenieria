// Lógica para la carga de la vista inscripciones.php
import { changeBorder, hideLoadingComponent, relocateWithErrorModal, relocateWithSuccessModal, showLoadingComponent, showPopUp } from "./modules/utlis.mjs";
import {
  validDNI,
  validEmail,
  validPhoneNumber,
} from "./modules/validator.mjs";

// Función para obtener las carreras según el centro regional seleccionado
function fetchMajorsByRegionalCenter(event) {
  let selectedRegionalCenter = event.target;
  let centerId = selectedRegionalCenter.value;
  let urlParams = new URLSearchParams({ center: centerId });
  let mainMajorSelect = document.getElementById("main-career");
  let secondaryMajorSelect = document.getElementById("secondary-career");

  // Bloquear los selects y mostrar mensaje de carga
  mainMajorSelect.disabled = true;
  secondaryMajorSelect.disabled = true;
  mainMajorSelect.innerHTML = "<option>Cargando carreras...</option>";
  secondaryMajorSelect.innerHTML = "<option>Cargando carreras...</option>";

  fetch(`/api/resources/controllers/majorsByCenter.php?${urlParams}`, {
    method: "GET",
  })
    .then((response) => response.json())
    .then((data) => {
      // Habilitar y actualizar los selects con los datos recibidos
      mainMajorSelect.disabled = false;
      secondaryMajorSelect.disabled = false;
      mainMajorSelect.innerHTML =
        "<option value=''>Seleccione una carrera</option>";
      secondaryMajorSelect.innerHTML =
        "<option value=''>Seleccione una carrera secundaria</option>";

      for (let major of data["data"]) {
        let option = document.createElement("option");
        let majorId = major["MAJOR_ID"];
        let majorName = major["MAJOR_NAME"];
        option.innerText = majorName;
        option.value = majorId;
        mainMajorSelect.appendChild(option);
        let clone = option.cloneNode(true);
        secondaryMajorSelect.appendChild(clone);
      }
    })
    .catch((error) => {
      console.log("No se pudieron obtener los datos de este centro", error);
    });
}

// En lugar de asignar el evento a cada <option>,
// se asigna el evento "change" al select de centros regionales
function fetchRegionalCenters() {
  let regionalCentersSelect = document.getElementById("regional-center");

  // Bloquear el select y mostrar mensaje de carga
  regionalCentersSelect.disabled = true;
  regionalCentersSelect.innerHTML =
    "<option>Cargando centros regionales...</option>";

  fetch("/api/resources/controllers/regionalCenters.php", { method: "GET" })
    .then((response) => response.json())
    .then((data) => {
      // Habilitar y actualizar el select con los datos recibidos
      regionalCentersSelect.disabled = false;
      regionalCentersSelect.innerHTML =
        "<option value=''>Seleccione un centro regional</option>";
      for (let center of data["data"]) {
        let option = document.createElement("option");
        option.innerText = center["CENTER_NAME"];
        option.value = center["CENTER_ID"];
        regionalCentersSelect.appendChild(option);
      }
    })
    .catch((error) => {
      console.log("No se pudo conectar con el servidor, intente más tarde");
      console.log(error.message);
    });

  // Asigna el evento "change" para detectar cuando se selecciona un centro
  regionalCentersSelect.addEventListener("change", fetchMajorsByRegionalCenter);
}

// Restaura el estilo original de todos los controles del formulario.
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

// Valida y envía el formulario, mostrando notificaciones en caso de errores y un modal de éxito al completarse.
function submitForm(event) {
  event.preventDefault();

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
  let formData = new FormData();
  formData.append("firstName", firstName.value.trim());
  formData.append("lastName", lastName.value.trim());
  formData.append("dni", dni.value.trim());
  formData.append("phoneNumber", phone.value.trim());
  formData.append("email", email.value.trim());
  formData.append("gender", genderSelect.value);
  formData.append("primaryMajor", mainCareerSelect.value);
  formData.append("secondaryMajor", secondaryCareerSelect.value);
  formData.append("centerId", regionalCentersSelect.value)
  formData.append("comment", "Envio inscripcion");
  formData.append("certificate", certificateFile.files[0]);

  showLoadingComponent("loading")
  fetch("/api/admissions/controllers/createAdmission.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      // Mostrar modal de éxito
      hideLoadingComponent('loading')
      if (data.status == 'success')
        relocateWithSuccessModal('/', 'submission-success') 
      else
        relocateWithErrorModal('/', 'error-modal', 3100)
    })
    .catch((error) => {
      hideLoadingComponent("loading")
      console.log("No se pudo guardar la solicitud", error);
      showPopUp("Error al enviar la solicitud, intente más tarde");
    });
}

const formFields = [
  document.getElementById("first-name"),
  document.getElementById("last-name"),
  document.getElementById("user-id"),
  document.getElementById("phone"),
  document.getElementById("email"),
  document.getElementById("gender"),
  document.getElementById("regional-center"),
  document.getElementById("main-career"),
  document.getElementById("secondary-career"),
  document.getElementById("file-upload"),
];

const formFieldsBlur = [
  document.getElementById("first-name"),
  document.getElementById("last-name"),
  document.getElementById("user-id"),
  document.getElementById("phone"),
  document.getElementById("email"),
  document.getElementById("gender"),
  document.getElementById("regional-center"),
  document.getElementById("main-career"),
  document.getElementById("secondary-career"),
];

const submitBtn = document.getElementById("submit-button");
submitBtn.disabled = true;

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

//Función para generar el número de solicitud:
function generateApplicationNumber(length = 10) {
  const chars =
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  let result = "";
  for (let i = 0; i < length; i++) {
    result += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  return result;
}

// Valida la identidad ingresada (DNI o Pasaporte) según su formato.
function validIdentity(value) {
  const dniRegex = /^(0[1-9]|1[0-8])\d{2}-(19\d{2}|20[0-9]{2})-\d{5}$/;
  const passportRegex = /^[A-Z]{1,2}[0-9]{6,9}$/;

  if (dniRegex.test(value)) {
    return { valid: true, type: "DNI" };
  } else if (passportRegex.test(value)) {
    return { valid: true, type: "Pasaporte" };
  } else {
    return { valid: false, type: null };
  }
}

// Asignación de evento para validar el campo de identidad
document.getElementById("user-id").addEventListener("blur", (e) => {
  let result = validIdentity(e.target.value.trim());
  let identityInfo = document.getElementById("identity-info");

  if (!result.valid) {
    changeBorder(e.target, "var(--bs-border-width)", "red");
    identityInfo.textContent = "Ingrese una Identidad válida (DNI o Pasaporte)";
    identityInfo.style.color = "red";
  } else {
    changeBorder(e.target, "var(--bs-border-width)", "var(--bs-border-color)");
    identityInfo.textContent = `Identidad válida detectada: ${result.type}`;
    identityInfo.style.color = "green";
  }
});

// Función genérica de validación para cada campo
function validateField(field) {
  const value = field.value.trim();
  switch (field.id) {
    case "first-name":
      if (value === "") {
        changeBorder(field, "var(--bs-border-width)", "red");
        showPopUp("El campo nombre es obligatorio.");
        return false;
      }
      break;
    case "last-name":
      if (value === "") {
        changeBorder(field, "var(--bs-border-width)", "red");
        showPopUp("El campo apellido es obligatorio.");
        return false;
      }
      break;
    case "user-id": {
      const result = validIdentity(value);
      if (!result.valid) {
        changeBorder(field, "var(--bs-border-width)", "red");
        showPopUp("Ingrese una Identidad válida (DNI o Pasaporte)");
        return false;
      }
      break;
    }
    case "phone":
      if (!validPhoneNumber(value)) {
        changeBorder(field, "var(--bs-border-width)", "red");
        showPopUp("Ingrese un número de teléfono válido");
        return false;
      }
      break;
    case "email":
      if (!validEmail(value)) {
        changeBorder(field, "var(--bs-border-width)", "red");
        showPopUp("Ingrese un correo válido");
        return false;
      }
      break;
    case "gender":
      if (value === "") {
        changeBorder(field, "var(--bs-border-width)", "red");
        showPopUp(`Seleccione un valor en genero`);
        return false;
      }
    case "regional-center":
      if (value === "") {
        changeBorder(field, "var(--bs-border-width)", "red");
        showPopUp(`Seleccione un valor en centro regional`);
        return false;
      }
    case "main-career":
      if (value === "") {
        changeBorder(field, "var(--bs-border-width)", "red");
        showPopUp(`Seleccione un valor en carrera principal`);
        return false;
      }
    case "secondary-career":
      if (value === "") {
        changeBorder(field, "var(--bs-border-width)", "red");
        showPopUp(`Seleccione un valor en carrera secundaria`);
        return false;
      }
      break;
    case "file-upload":
      if (field.files.length === 0) {
        changeBorder(field, "var(--bs-border-width)", "red");
        showPopUp("Adjunte el certificado de secundaria");
        return false;
      }
      break;
    default:
      break;
  }
  // Si la validación es correcta, restablecemos el borde
  changeBorder(field, "var(--bs-border-width)", "var(--bs-border-color)");
  return true;
}

formFieldsBlur.forEach((field) => {
  field.addEventListener("blur", () => {
    validateField(field);
  });
});

function main() {
  fetchRegionalCenters();
  let submitBtn = document.getElementById("submit-button");
  submitBtn.addEventListener("click", submitForm);
}

main();
