import { changeFocusBtn } from "./focusButton.mjs";
import { Request } from '../../js/modules/request.mjs'
import { showLoadingComponent, hideLoadingComponent, showFailModal} from '../../js/modules/utlis.mjs'


const TABLE_ROWS = {
  cancellation: ["Estudiante", "Número de cuenta", "GPA", "Fecha de solicitud", "Documento"],
  majorChange: ["Estudiante", "Número de cuenta", "GPA", "Carrera a cambiar", "Fecha de solicitud", "Documento"],
  campusTransfer: ["Estudiante", "Número de cuenta", "GPA", "Centro a cambiar", "Fecha de solicitud", "Documento"] 
}

const RESPONSE_DATA = {
  cancellation: ["STUDENT_NAME", "ACCOUNT_NUMBER", "GPA", "DATE_OF_REQUEST", "DOCUMENT"],
  majorChange: ["STUDENT_NAME", "ACCOUNT_NUMBER", "GPA", "DATE_OF_REQUEST", "MAJOR_TO_CHANGE", "DOCUMENT"],
  campusTransfer: ["STUDENT_NAME", "ACCOUNT_NUMBER", "GPA", "DATE_OF_REQUEST", "CAMPUS_TO_CHANGE", "DOCUMENT"] 
}

/**
 * 
 * @param {string} requestName 
 */
async function getRequests(requestName) {
  const URL = `/api/coordinator/requests.php?q=${requestName}`
 
  showLoadingComponent('loading')
  try {
    const requests = await Request.fetch(URL, 'GET')
    showRequests(requests)
    hideLoadingComponent('loading')
  } catch(err) {
    hideLoadingComponent('loading')
    showFailModal('request-error', 'Error al obtener solicitudes... Intente más tarde')
  }
}

/**
 * 
 * @param {Array} requests 
 * @param {string} requestName
 */
function showRequests(requests, requestName) {
  const table = document.getElementById('r-table')
  const body = document.getElementById('table-body-results')

  const responseData = getResponseDataFormat(requestName)

  body.innerHTML = ''
  requests.forEach((element, index) => {
    const row = document.createElement('tr')
    const data = []
    data.push(`<td>${element[responseData[index]]}</td>`)
    
    row.innerHTML = data.join('')
    body.appendChild(row)
  })
  table.setAttribute('table-row', JSON.stringify(TABLE_ROWS.cancellation))
  table.style.display = 'block'
}

/**
 *@param {string} requestName 
 *@returns {Array}
 */
function getResponseDataFormat(requestName) {
  if (requestName == 'cancellation') {
    table.setAttribute('table-row', TABLE_ROWS.cancellation)
    return RESPONSE_DATA.cancellation
  }
  if (requestName == 'majorChange') {
    table.setAttribute('table-row', TABLE_ROWS.majorChange)
    return RESPONSE_DATA.majorChange
  }
  if (requestName == 'campusTransfer') {
    table.setAttribute('table-row', TABLE_ROWS.campusTransfer)
    return RESPONSE_DATA.campusTransfer
  }
}

