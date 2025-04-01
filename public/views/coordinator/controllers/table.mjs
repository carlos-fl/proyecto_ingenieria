import { Request } from "../../js/modules/request.mjs"
import { showLoadingComponent, hideLoadingComponent, showPopUp } from "../../js/modules/utlis.mjs"

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
    row.innerHTML = `
    <td>${el.FIRST_NAME + ' ' + el.LAST_NAME}</td>
    <td>${el.ACCOUNT_NUMBER}</td>
    <td>${el.GPA}</td>
    `
    const td = document.createElement('td')
    const btn = document.createElement('button')
    btn.textContent = 'Ver'
    btn.classList.add('btn', 'btn-sm', 'b')
    btn.onclick = () => showModalWithHistory(el.ACCOUNT_NUMBER, el.FIRST_NAME + ' ' + el.LAST_NAME)
    td.appendChild(btn)
    row.appendChild(td)
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
    console.log(err)
    hideLoadingComponent('loading')
    showPopUp(`No se pudo obtener historial del estudiante ${name}`)
  }
}


/**
 * 
 * @param {string} name
 * @param {Object} data
 */
/*function setModalContent(name, data) {
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
}*/

function setModalContent(name, data) {
  try {

    const modal = document.getElementById('history-modal')
    const modalBody = document.getElementById('modal-body')
    modal.setAttribute('header-title', `Historial del estudiante ${ name }`)
    modalBody.classList.add('d-flex', 'flex-column', 'justify-content-center', 'align-items-center')    

    modalBody.innerHTML = ''

    const table = document.createElement('table')
    table.classList.add('table')
    const tbody = document.createElement('tbody') 
    const years = Object.keys(data)
    years.forEach(year => {
      const yeartr = document.createElement('tr')
      yeartr.innerHTML = `<td colspan="6">${year}</td>`
      tbody.appendChild(yeartr)
      const history = data[year]
      const thead = document.createElement('thead')
      const tr = document.createElement('tr')
      tr.innerHTML = `<th scope="col">Código</th>
        <th scope="col">Clase</th>
        <th scope="col">UV</th>
        <th scope="col">Año</th>
        <th scope="col">PAC</th>
        <th scope="col">Calificacion</th>
        <th scope="col">OBS</th>
        `
      thead.appendChild(tr)
      table.appendChild(thead)
      history.forEach(item => {
        const tr = document.createElement('tr')
        tr.innerHTML = `
        <td scope="col">${item.CODE}</td>
        <td scope="col">${item.CLASS_NAME}</td>
        <td scope="col">${item.UV}</td>
        <td scope="col">${item.PERIOD}</td>
        <td scope="col">${item.PAC}</td>
        <td scope="col">${item.CALIFICATION}</td>
        <td scope="col">${item.OBS}</td>
        `
        tbody.appendChild(tr)
      })
      table.appendChild(tbody)
      modalBody.appendChild(table)
      console.log(table)
      console.log(modalBody)
    })
    table.style.display = 'block'
    modal.show()
  } catch(err) {
    console.log(err)
  }
}

