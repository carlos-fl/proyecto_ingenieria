import { Request } from "../../js/modules/request.mjs"
import { hideLoadingComponent, showFailModal, showLoadingComponent, showPopUp } from "../../js/modules/utlis.mjs"
import { fillTable } from "./table.mjs"

document.addEventListener('DOMContentLoaded', getStudents)


async function getStudents() {
  const URL = `/api/coordinator/controllers/studentsAll.php`
  try {
    showLoadingComponent('loading')
    const data = await Request.fetch(URL, 'GET')
    hideLoadingComponent('loading')

    fillTable(data.data)
  } catch(err) {
    hideLoadingComponent('loading')
    showPopUp("No se encontraron historiales...")
    fillTable([])
  }
}


/**
 * 
 * @param {Array} data 
 */
function setTableBody(data) {
  const tableBody = document.getElementById('table-body-results')
  tableBody.classList.add('h-75')
  if (data.length === 0) {
    tableBody.innerHTML = `<tr><td colspan="4">No se encontraron historiales.</td></tr>`
    return;
  }

  tableBody.innerHTML = ''
  data.forEach(element => {
    const row = document.createElement('tr')
    row.innerHTML = `
      <td>${ element.FIRST_NAME + ' ' + element.LAST_NAME }</td> 
      <td>${ element.ACCOUNT_NUMBER }</td> 
      <td>${ element.GPA }</td> 
    `
    tableBody.append(row)
  })
}
