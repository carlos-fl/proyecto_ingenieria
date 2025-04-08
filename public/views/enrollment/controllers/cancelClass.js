import { Request } from "../../js/modules/request.mjs"
import { hideLoadingComponent, showLoadingComponent, showPopUp, showSuccessModal } from "../../js/modules/utlis.mjs"

document.addEventListener('DOMContentLoaded', async () => {
  
  updateTable()

})

/**
 * 
 * @param {Event} event
 * @param {number} sectionID 
 */
async function cancelClass(event, sectionID) {
  event.target.disabled = true
  const URL = '/api/enrollment/controllers/cancelClass.php'
  try {
    showLoadingComponent('loading')
    const body = { section: sectionID }
    await Request.fetch(URL, 'POST', body)
    hideLoadingComponent('loading')
    showPopUp('Clase cancelada')
    updateTable()
  } catch(err) {
    console.log(err)
    hideLoadingComponent('loading')
    showPopUp('Error en el servidor... No se canceló clase')
    event.target.disabled = false
  }
}


async function updateTable() {
  const tableBody = document.getElementById('table-body-results')

  showLoadingComponent('loading')
  const URL = '/api/enrollment/controllers/enrolledClasses.php'
  try {
    const res = await Request.fetch(URL, 'GET')
    hideLoadingComponent('loading')
    if (res.data.length == 0) {
      tableBody.innerHTML = `<tr><td colspan="5">No ha matriculado clases.</td></tr>` 
      return;
    }

    tableBody.innerHTML = ''
    res.data.forEach(el => {
      const row = document.createElement('tr')
      row.innerHTML = 
      `
        <td>${el.UV}</td>
        <td>${el.CLASS_NAME}</td>
        <td>${el.START_TIME}</td>
        <td>${el.END_TIME}</td>
      `
      const td = document.createElement('td')
      const button = document.createElement('button')
      button.textContent = 'Cancelar'
      button.classList.add('btn', 'btn-primary')
      button.addEventListener('click', (event) => cancelClass(event, el.ID_SECTION))

      td.appendChild(button)
      row.appendChild(td)
      tableBody.appendChild(row)
    })

  } catch(err) {
    console.log(err)
    hideLoadingComponent('loading')
    showPopUp('Error en el servidor')
  } 
}