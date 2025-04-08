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
    } catch(err) {
      console.log(err)
      event.target.disabled = false
      hideLoadingComponent('loading')
      showPopUp('Error en el servidor... No se guardó clase')
    }
  })
})