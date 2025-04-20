import { Request } from '../../js/modules/request.mjs'
import { showLoadingComponent, showModal, hideLoadingComponent, showFailModal, showPopUp, showSuccessModal} from '../../js/modules/utlis.mjs'


const TABLE_ROWS = {
  cancellation: ["Estudiante", "Número de cuenta", "GPA", "Fecha de solicitud", "Documento"],
  majorChange: ["Estudiante", "Número de cuenta", "GPA", "Carrera a cambiar", "Fecha de solicitud", "Documento"],
  campusTransfer: ["Estudiante", "Número de cuenta", "GPA", "Centro a cambiar", "Fecha de solicitud", "Documento"] 
}

const RESPONSE_DATA = {
  cancellation: ["REQUEST_ID", "STUDENT_NAME", "ACCOUNT_NUMBER", "GPA", "DATE_OF_REQUEST", "DOCUMENT"],
  majorChange: ["REQUEST_ID", "STUDENT_NAME", "ACCOUNT_NUMBER", "GPA", "DATE_OF_REQUEST", "MAJOR_TO_CHANGE", "DOCUMENT"],
  campusTransfer: ["REQUEST_ID", "STUDENT_NAME", "ACCOUNT_NUMBER", "GPA", "DATE_OF_REQUEST", "CAMPUS_TO_CHANGE", "DOCUMENT"] 
}

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('cancellations').addEventListener('click', () => getRequests('cancellation'))
  document.getElementById('majorChange').addEventListener('click', () => getRequests('majorChange'))
  document.getElementById('campusTransfer').addEventListener('click', () => getRequests('campusTransfer'))
})

/**
 * 
 * @param {number} requestID 
 * @param {string} type 
 */
async function modalContent(requestID, type) {
  const modal = document.getElementById('request-modal')
  const modalBody = document.getElementById('modal-body')
  const acceptButton = document.createElement('button')
  const rejectButton = document.createElement('button')
  const div = document.createElement('div')

  modalBody.innerHTML = ''

  if (type != 'cancellation') {
    acceptButton.onclick = () => acceptRequest(requestID, type)
    acceptButton.textContent = "ACEPTAR"
  }
  else {
    acceptButton.onclick = (event) => showStudentCurrentClasses(event, requestID)
    acceptButton.textContent = 'Ver Clases'
  }
  
  rejectButton.onclick = () => rejectRequest(requestID, type)
  rejectButton.textContent = 'RECHAZAR'

  if (type == 'cancellation') {
    // iframe to see pdf
    const iframe = document.createElement('iframe')
    iframe.width = '500'
    iframe.height = '500'
    iframe.src = `/api/coordinator/controllers/getCancellationPDF.php?q=${requestID}`
    iframe.classList.add('m-2')
    modalBody.appendChild(iframe)
  } else {
    const URL = `/api/coordinator/controllers/getRequestDescription.php?q=${requestID}`
    showLoadingComponent('loading')
    const description = await Request.fetch(URL, 'GET')
    hideLoadingComponent('loading')
    const parseData = JSON.parse(description.data.CONTENT)
    const div = document.createElement('div')
    const p = document.createElement('p')
    const h6 = document.createElement('h6')
    h6.innerHTML = 'Descripción'
    p.innerHTML = parseData.DESCRIPTION
    div.appendChild(h6)
    div.appendChild(p)
    div.classList.add('d-flex', 'flex-column', 'justify-content-between', 'align-items-center')
    modalBody.appendChild(div)
  }
  

  div.appendChild(acceptButton)
  div.appendChild(rejectButton)
  acceptButton.classList.add('btn', 'btn-sm', 'b')
  rejectButton.classList.add('btn', 'btn-sm', 'btn-danger', 'ms-2')
  modalBody.appendChild(div)
  modalBody.classList.add('d-flex', 'flex-column', 'justify-content-center', 'align-items-center')
  modal.show()
}

/**
 * 
 * @param {Event} event 
 * @param {number} requestID 
 */
async function showStudentCurrentClasses(event, requestID) {
  event.target.disabled = true
  const modal = document.getElementById('current-classes-modal')
  const previousModal = document.getElementById('request-modal')

  // table to show Ongoing classes for student
  const table = document.getElementById('c-table') 
  const tableBody = document.getElementById('table-body-student-classes')
  table.style.display = 'block'
  previousModal.hide()

  const URL = `/api/coordinator/controllers/getCurrentStudentClasses.php?request=${requestID}`
  try {
    showLoadingComponent('loading')
    const response = await Request.fetch(URL, 'GET')
    hideLoadingComponent('loading') 
    renderStudentCurrentClasses(response.data, tableBody, requestID)
    modal.show()
  
    event.target.disabled = false
  } catch(err) {
    console.log(err)
    hideLoadingComponent('loading')
    showPopUp('No se pudo obtener clases...')
  }
}

/**
 * 
 * @param {Array} classes 
 * @param {HTMLTableSectionElement}
 * @param {number} requestID
 */
function renderStudentCurrentClasses(classes, tableBody, requestID) {
  tableBody.innerHTML = ''
  classes.forEach(el => {
    const row = document.createElement('tr')
    row.innerHTML = `
      <td>${el.CLASS_NAME}</td> 
      <td>${el.UV}</td> 
      <td>${el.SECTION_CODE}</td> 
      <td>${el.START_TIME}</td> 
      <td>${el.END_TIME}</td> 
      <td>${el.TEACHER_NAME}</td> 
    `
    const button = document.createElement('button')
    const td = document.createElement('td')
    button.textContent = 'Cancelar'
    button.classList.add('btn', 'btn-primary')
    button.onclick = (event) => cancellStudentClass(event, el.ID_SECTION, requestID)
    td.appendChild(button)
    row.appendChild(td)

    tableBody.appendChild(row)
  })
}

/**
 * 
 * @param {Event} event 
 * @param {number} sectionID 
 * @param {number} requestID
 */
async function cancellStudentClass(event, sectionID, requestID) {
  event.target.disabled = true
  const URL = '/api/coordinator/controllers/cancelStudentCurrentClass.php'
  const body = { sectionID, requestID }
  try {
    showLoadingComponent('loading')
    await Request.fetch(URL, 'POST', body)
    hideLoadingComponent('loading') 
    showPopUp('Clase cancelada', 'success-popup', '/views/assets/img/checkmark.png')
  } catch(err) {
    hideLoadingComponent('loading')
    console.log(err)
    event.target.disabled = false
    showPopUp('No se pudo cancelar clase')
  }
}

/**
 * 
 * @param {number} requestID 
 * @param {string} type 
 */
async function acceptRequest(requestID, type) {
  const URL = `/api/coordinator/controllers/acceptRequest.php?q=${requestID}&type=${type}`
  try {
    showLoadingComponent('loading')
    await Request.fetch(URL, 'PUT')
    hideLoadingComponent('loading')
    showPopUp("Solicitud actualizada correctamente", 'success-popup', '/views/assets/img/checkmark.png')

    const modal = document.getElementById('request-modal')
    modal.hide()

    getRequests(type)


  } catch (err) {
    console.log(err)
    hideLoadingComponent('loading')
    showPopUp('Error en servidor. No se pudo hacer solicitud')
  }
}

/**
 * 
 * @param {number} requestID 
 * @param {string} type 
 */
async function rejectRequest(requestID, type) {
  const URL = `/api/coordinator/controllers/rejectRequest.php?q=${requestID}` 
  try {
    showLoadingComponent('loading')
    await Request.fetch(URL, 'PUT')
    hideLoadingComponent('loading')
    showSuccessModal('request-success')

    const modal = document.getElementById('request-modal')
    modal.hide()

    getRequests(type)
  } catch(err) {
    console.log(err)
    hideLoadingComponent('loading')
    showPopUp("Error del Servidor... No se pudo realizar acción")
  }
}

/**
 * 
 * @param {number} requestID 
 * @returns {Blob}
 */
/*async function getPdf(requestID) {
  const URL = `/api/coordinator/controllers/getCancellationPDF.php?q=${requestID}`
  try {
    showLoadingComponent('loading')
    const response = await Request.fetch(URL, 'GET')
    hideLoadingComponent('loading')

    return await response.data.blob()
  } catch(err) {
    console.log(err)
    hideLoadingComponent('loading')
    showPopUp("No se pudo Obtener PDF")
    return new Blob([])
  }
}
*/

async function transformPDFBlobToUrl(blob) {
  console.log('BLOB: ', blob);

  if (!blob) {
    const base64String =
      "JVBERi0xLjMKJZOMi54gUmVwb3J0TGFiIEdlbmVyYXRlZCBQREYgZG9jdW1lbnQgaHR0cDovL3d3dy5yZXBvcnRsYWIuY29t...";
    
    // Decode Base64 into binary data
    const byteCharacters = atob(base64String);
    const byteNumbers = new Uint8Array(byteCharacters.length);
    for (let i = 0; i < byteCharacters.length; i++) {
      byteNumbers[i] = byteCharacters.charCodeAt(i);
    }

    // Create a valid PDF Blob
    blob = new Blob([byteNumbers], { type: "application/pdf" });
  }

  return URL.createObjectURL(blob);
}


/**
 * 
 * @param {Blob} blob 
 * @returns {URL}
 */
/*function transformPDFBlobToUrl(blob) {
  console.log(blob)
  if (!blob) {
    var base64String = "JVBERi0xLjMKJZOMi54gUmVwb3J0TGFiIEdlbmVyYXRlZCBQREYgZG9jdW1lbnQgaHR0cDovL3d3dy5yZXBvcnRsYWIuY29tCjEgMCBvYmoKPDwKL0YxIDIgMCBSCj4+CmVuZG9iagoyIDAgb2JqCjw8Ci9CYXNlRm9udCAvSGVsdmV0aWNhIC9FbmNvZGluZyAvV2luQW5zaUVuY29kaW5nIC9OYW1lIC9GMSAvU3VidHlwZSAvVHlwZTEgL1R5cGUgL0ZvbnQKPj4KZW5kb2JqCjMgMCBvYmoKPDwKL0NvbnRlbnRzIDcgMCBSIC9NZWRpYUJveCBbIDAgMCA1OTUuMjc1NiA4NDEuODg5OCBdIC9QYXJlbnQgNiAwIFIgL1Jlc291cmNlcyA8PAovRm9udCAxIDAgUiAvUHJvY1NldCBbIC9QREYgL1RleHQgL0ltYWdlQiAvSW1hZ2VDIC9JbWFnZUkgXQo+PiAvUm90YXRlIDAgL1RyYW5zIDw8Cgo+PiAKICAvVHlwZSAvUGFnZQo+PgplbmRvYmoKNCAwIG9iago8PAovUGFnZU1vZGUgL1VzZU5vbmUgL1BhZ2VzIDYgMCBSIC9UeXBlIC9DYXRhbG9nCj4+CmVuZG9iago1IDAgb2JqCjw8Ci9BdXRob3IgKGFub255bW91cykgL0NyZWF0aW9uRGF0ZSAoRDoyMDI1MDMzMTE1Mzk0OSswMCcwMCcpIC9DcmVhdG9yIChSZXBvcnRMYWIgUERGIExpYnJhcnkgLSB3d3cucmVwb3J0bGFiLmNvbSkgL0tleXdvcmRzICgpIC9Nb2REYXRlIChEOjIwMjUwMzMxMTUzOTQ5KzAwJzAwJykgL1Byb2R1Y2VyIChSZXBvcnRMYWIgUERGIExpYnJhcnkgLSB3d3cucmVwb3J0bGFiLmNvbSkgCiAgL1N1YmplY3QgKHVuc3BlY2lmaWVkKSAvVGl0bGUgKHVudGl0bGVkKSAvVHJhcHBlZCAvRmFsc2UKPj4KZW5kb2JqCjYgMCBvYmoKPDwKL0NvdW50IDEgL0tpZHMgWyAzIDAgUiBdIC9UeXBlIC9QYWdlcwo+PgplbmRvYmoKNyAwIG9iago8PAovRmlsdGVyIFsgL0FTQ0lJODVEZWNvZGUgL0ZsYXRlRGVjb2RlIF0gL0xlbmd0aCA1OQo+PgpzdHJlYW0KR2FwUWgwRT1GLDBVXEgzVFxwTllUXlFLaz90Yz5JUCw7VyNVMV4yM2loUEVNX1BQJE8hM14sQzVRfj5lbmRzdHJlYW0KZW5kb2JqCnhyZWYKMCA4CjAwMDAwMDAwMDAgNjU1MzUgZiAKMDAwMDAwMDA3MyAwMDAwMCBuIAowMDAwMDAwMTA0IDAwMDAwIG4gCjAwMDAwMDAyMTEgMDAwMDAgbiAKMDAwMDAwMDQxNCAwMDAwMCBuIAowMDAwMDAwNDgyIDAwMDAwIG4gCjAwMDAwMDA3NzggMDAwMDAgbiAKMDAwMDAwMDgzNyAwMDAwMCBuIAp0cmFpbGVyCjw8Ci9JRCAKWzxlZmEyNDE3NzUwOTRmN2U2OTQyMzQzMWZlYzM2NzAxYz48ZWZhMjQxNzc1MDk0ZjdlNjk0MjM0MzFmZWMzNjcwMWM+XQolIFJlcG9ydExhYiBnZW5lcmF0ZWQgUERGIGRvY3VtZW50IC0tIGRpZ2VzdCAoaHR0cDovL3d3dy5yZXBvcnRsYWIuY29tKQoKL0luZm8gNSAwIFIKL1Jvb3QgNCAwIFIKL1NpemUgOAo+PgpzdGFydHhyZWYKOTg1CiUlRU9GCg=="
    var defaultPdfBlob = await base64String.blob()
  }
  const blobURL = URL.createObjectURL(blob)
  return blobURL
}

*/


/**
 * 
 * @param {string} requestName 
 */
async function getRequests(requestName) {
  const URL = `/api/coordinator/controllers/requests.php?q=${requestName}`
  showLoadingComponent('loading')
  try {
    const requests = await Request.fetch(URL, 'GET')
    showRequests(requests.data, requestName)
    hideLoadingComponent('loading')
  } catch(err) {
    console.log(err)
    showRequests([], requestName);
    hideLoadingComponent('loading')
    showPopUp('Error al obtener solicitudes... Intente más tarde')
  }
}



/**
 * 
 * @param {Array} requests 
 * @param {string} requestName
 */
function showRequests(requests, requestName) {
  const table = document.getElementById('r-table')
  if (requestName == 'cancellation') {
    table.setAttribute('table-row', '["Estudiante", "Número de cuenta", "GPA", "Fecha de solicitud", "Documento", "Finalizado"]')
  } else {
    table.setAttribute('table-row', '["Estudiante", "Número de cuenta", "GPA", "Fecha de solicitud", "Documento"]')
  }

  const body = document.getElementById('table-body-results')


  document.getElementById('temp-text').style.display = 'none'
  if (requests.length == 0) {
    body.innerHTML = `<tr><td colspan="5">No se Encontraron Solicitudes.</td></tr>`
    table.style.display = 'block'
    return;
  }

  const responseData = getResponseDataFormat(requestName)

  body.innerHTML = ''
  requests.forEach((element, index) => {
    const row = document.createElement('tr')
    row.innerHTML = `
    <td>${element['STUDENT_NAME']}</td>
    <td>${element['ACCOUNT_NUMBER']}</td>
    <td>${element['GPA']}</td>
    <td>${element['DATE_OF_REQUEST']}</td>
    `
    const button = document.createElement('button');
    const td = document.createElement('td')
    button.textContent = 'VER';
    button.classList.add('btn', 'btn-sm', 'b');
    button.addEventListener('click', () => modalContent(element['REQUEST_ID'], requestName));
    
    td.appendChild(button)
    row.appendChild(td);

    if (requestName == 'cancellation') {
      const doneButton = document.createElement('button')
      const doneTd = document.createElement('td')
      doneButton.textContent = 'Completado'
      doneButton.classList.add('btn', 'btn-sm', 'b')
      doneButton.onclick = (event) => completedCancellationRequest(event, element['REQUEST_ID'])
      doneTd.appendChild(doneButton) 
      row.appendChild(doneTd)
    }

    body.appendChild(row)
  })
  table.style.display = 'block'
}

/**
 * 
 * @param {Event} event 
 * @param {number} requestID 
 */
async function completedCancellationRequest(event, requestID) {
  event.target.disabled = true
  const URL = '/api/coordinator/controllers/completedCancellationRequest.php'
  const body = { requestID }
  try {
    showLoadingComponent('loading')
    await Request.fetch(URL, 'POST', body)
    hideLoadingComponent('loading')
    showPopUp('Solicitud Actualizada', 'success-popup', '/views/assets/img/checkmark.png')

    getRequests('cancellation')

  } catch(err) {
    hideLoadingComponent('loading')
    console.log(err)
    showPopUp('No se pudo actualizar solicitud')
  }
}

/**
 *@param {string} requestName 
 *@returns {Array}
 */
function getResponseDataFormat(requestName) {
  if (requestName == 'cancellation') {
    return RESPONSE_DATA.cancellation
  }
  if (requestName == 'majorChange') {
    return RESPONSE_DATA.majorChange
  }
  if (requestName == 'campusTransfer') {
    return RESPONSE_DATA.campusTransfer
  }
}

