import { Request } from "../../js/modules/request.mjs"
import { hideLoadingComponent, showLoadingComponent, showPopUp } from "../../js/modules/utlis.mjs"

document.addEventListener('DOMContentLoaded', async () => {
  const tableBody = document.getElementById('table-body-results')

  showLoadingComponent('loading')
  const URL = '/api/enrollment/controllers/enrolledClasses.php'
  try {
    const res = await Request.fetch(URL, 'GET')
    hideLoadingComponent('loading')
    if (res.data.length == 0) {
      tableBody.innerHTML = `<tr><td colspan="4">No ha matriculado clases.</td></tr>` 
      return;
    }

    tableBody.innerHTML = ''
    res.data.forEach(el => {
      tableBody.innerHTML += `
      <tr><td>${el.UV}</td>
      <td>${el.CLASS_NAME}</td>
      <td>${el.START_TIME}</td>
      <td>${el.END_TIME}</td></tr>
      `
    })

  } catch(err) {
    console.log(err)
    hideLoadingComponent('loading')
    showPopUp('Error en el servidor')
  }

})