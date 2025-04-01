import { showFailModal, showLoadingComponent, hideLoadingComponent, showPopUp } from "../../js/modules/utlis.mjs"

/**
 * 
 * @param {Array} data 
 */
export function fillTable(data) {
  if (!data)
    return

  const tableBody = document.getElementById('table-body-results')
  tableBody.innerHTML = ''
  data.forEach(el => {
    const row = document.createElement('tr')
    row.onclick = () => showModalWithHistory(el.ACCOUNT_NUMBER, el.FIRST_NAME + ' ' + el.LAST_NAME)
    row.innerHTML = `
    <td>${el.FIRST_NAME + ' ' + el.LAST_NAME}</td>
    <td>${el.ACCOUNT_NUMBER}</td>
    <td>${el.GPA}</td>
    `
    tableBody.append(row)
  })
}


/**
 * 
 * @param {string} accountNumber 
 * @param {string} name
 */
export async function showModalWithHistory(accountNumber, name) {
  const URL = `/api/coordinator/controllers/studentHistory.php?account=${accountNumber}`
  try {
    showLoadingComponent('loading')
    const response = await Request.fetch(URL, 'GET')
    hideLoadingComponent('loading')

    setModalContent(name, response.data)
    
  } catch(err) {
    hideLoadingComponent('loading')
    showPopUp(`No se pudo obtener historial del estudiante ${name}`)
  }
}


/**
 * 
 * @param {string} name
 * @param {Object} data
 */
function setModalContent(name, data) {
  const modal = document.getElementById('history-modal')
  modal.setAttribute('header-title', `Historial del estudiante ${ name }`)
  
  const years = Object.keys(data)
  years.forEach((year, index) => {
    const yearTitle = document.createElement('h2')
    yearTitle.innerHTML = year
    modal.appendChild(yearTitle)
    const table = document.createElement('d-table')
    table.setAttribute('table-row', JSON.stringify(["Código", "Nombre", "UV", "Periodo", "Nota", "OBS"]))
    table.setAttribute('body-id', `body-${index}`)

    const bodyTable = document.getElementById(`body-${index}`)
    data[year].forEach(el => {
      const row = document.createElement(tr)
      row.innerHTML = `
      <td>${el.CODE}</td> 
      <td>${el.CLASS_NAME}</td> 
      <td>${el.UV}</td> 
      <td>${el.PERIOD}</td> 
      <td>${el.CALIFICATION}</td> 
      <td>${el.OBS}</td> 
      `
      bodyTable.appendChild(row)
    })
    modal.appendChild(table)
  })
}


