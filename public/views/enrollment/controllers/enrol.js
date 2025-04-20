import { Request } from "../../js/modules/request.mjs"
import { hideLoadingComponent, showLoadingComponent, showPopUp, showSuccessModal } from "../../js/modules/utlis.mjs"

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('enrol').addEventListener('click', async (event) => {
    showLoadingComponent('loading')
    event.target.disabled = true
    try {
      const VERIFY_URL = '/api/enrollment/controllers/sectionsValidateEnrollment.php'
      const URL = '/api/enrollment/controllers/enrol.php'
      const section = document.getElementById('sections')
      
      const body = { section: section.value }
      const bodyToVerify = { sectionId: section.value }
      const validEnrollmentResponse = await Request.fetch(VERIFY_URL, 'POST', bodyToVerify)
      console.log(validEnrollmentResponse)
      if (validEnrollmentResponse.status == 'success') {
        await Request.fetch(URL, 'POST', body)
        hideLoadingComponent('loading')
        showPopUp('Clase matriculada exitosamente', 'success-popup', '/views/assets/img/checkmark.png')
        event.target.disabled = false
      } else {
        hideLoadingComponent('loading')
        showPopUp('No se pudo matricular clase')
      }

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
          <tr>
            <td>${el.UV}</td>
            <td>${el.CLASS_NAME}</td>
            <td>${el.START_TIME}</td>
            <td>${el.END_TIME}</td>
            <td>${el.DAYS}</td>
          </tr>
          `
        })


    } catch(err) {
      console.log(err)
      event.target.disabled = false
      hideLoadingComponent('loading')
      showPopUp(err.message)
    }
  })
})