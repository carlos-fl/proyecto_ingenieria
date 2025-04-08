import { Request } from "../../js/modules/request.mjs"
import { hideLoadingComponent, showLoadingComponent, showPopUp, showSuccessModal } from "../../js/modules/utlis.mjs"

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('enrol').addEventListener('click', async (event) => {
    showLoadingComponent('loading')
    event.target.disabled = true
    try {
      const URL = '/api/enrollment/controllers/enrol.php'
      const section = document.getElementById('sections')
      
      const body = { section: section.value }
      await Request.fetch(URL, 'POST', body)
      hideLoadingComponent('loading')
      showSuccessModal('success-modal')
      event.target.disabled = false


      const tableBody = document.getElementById('table-body-results')

      showLoadingComponent('loading')
      const URL_ENROLLED = '/api/enrollment/controllers/enrolledClasses.php'
        const res = await Request.fetch(URL_ENROLLED, 'GET')
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
      event.target.disabled = false
      hideLoadingComponent('loading')
      showPopUp('Error en el servidor... No se guardó clase')
    }
  })
})