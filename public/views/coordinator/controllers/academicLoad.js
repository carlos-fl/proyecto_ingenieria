import { Request } from "../../js/modules/request.mjs"
import { downloadFile, hideLoadingComponent, showLoadingComponent, showModal, showPopUp } from "../../js/modules/utlis.mjs"

document.addEventListener('DOMContentLoaded', async () => {
  const URL = "/api/coordinator/controllers/academicLoad.php"
  try {
    showLoadingComponent('loading')
    const response = await Request.fetch(URL, 'GET')
    hideLoadingComponent('loading')
    fillAcademicLoadTable(response.data)

  } catch(err) {
    console.log(err)
    hideLoadingComponent('loading')
    showPopUp("No se pudo obtener carga académica")
  }
})

/**
 * 
 * @param {Array} data 
 */
function fillAcademicLoadTable(data) {
  const table = document.getElementById('academic-load-table')
  const tableBody = document.getElementById('table-body-results')

  if (data.length == 0) {
    tableBody.innerHTML = `<tr><td colspan="4">No se encontraron cargas académicas.</td></tr>`
    return;
  } 

  data.forEach(el => {
    const row = document.createElement('tr')
    row.setAttribute('id', el.ACADEMIC_LOAD_ID)
    row.innerHTML = `
    <td>${el.DATE}</td> 
    <td>${el.PAC}</td> 
    `
    const pdfButton = document.createElement('button')
    const csvButton = document.createElement('button')
    pdfButton.classList.add('btn', 'btn-sm', 'b')
    pdfButton.textContent = 'Descargar PDF'
    pdfButton.onclick = (event) => downloadAsPdf(event, el.ACADEMIC_LOAD_ID)

    csvButton.classList.add('btn', 'btn-sm', 'b')
    csvButton.textContent = 'Descargar CSV'
    csvButton.onclick = (event) => downloadAsCsv(event, el.ACADEMIC_LOAD_ID)
    const pdfTd = document.createElement('td')
    const csvTd = document.createElement('td')
    pdfTd.appendChild(pdfButton)
    csvTd.appendChild(csvButton)
    row.appendChild(pdfTd)
    row.appendChild(csvTd)
    tableBody.appendChild(row)
  })
}

/**
 * 
 * @param {Event} event 
 * @param {number} loadID 
 */
async function downloadAsCsv(event, loadID) {
  event.preventDefault()
  event.target.disabled = true
  const URL = `/api/resources/controllers/downloadCsvLoad.php?load=${loadID}`
  try {
    showLoadingComponent('loading')
    const response = await fetch(URL)
    const blob = await response.blob()
    downloadFile('carga', blob)
    hideLoadingComponent('loading')

    event.target.disabled = false
  } catch(err) {
    console.log(err)
    event.target.disabled = false
    hideLoadingComponent('loading')
    showPopUp("No se pudo descargar carga académica... Intente más tarde")
  }
}

/**
 * 
 * @param {Event} event 
 * @param {number} loadID 
 */
async function downloadAsPdf(event, loadID) {
  event.preventDefault()
  event.target.disabled = true
  const URL = `/api/coordinator/controllers/downloadPdfLoad.php?load=${loadID}`
  try {
    showLoadingComponent('loading')
    const response = await Request.fetch(URL, 'GET')
    const blob = await response.blob()
    downloadFile('carga', blob)
    hideLoadingComponent('loading')
    event.target.disabled = false
  } catch(err) {
    event.target.disabled = false
    hideLoadingComponent('loading')
    showPopUp("No se pudo descargar carga académica... Intente más tarde")
  }
}
