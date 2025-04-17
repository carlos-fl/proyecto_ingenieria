import { Request } from "../../js/modules/request.mjs"
import { hideLoadingComponent, showLoadingComponent, showPopUp } from "../../js/modules/utlis.mjs"

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('classes').addEventListener('change', async (event) => {
    event.target.disabled = true
    showLoadingComponent('loading')
    try {
      const URL = `/api/enrollment/controllers/sectionsByClass.php?class=${event.target.value}`
      const res = await Request.fetch(URL, 'GET')

      const sections = document.getElementById('sections')
      sections.innerHTML = ''
      sections.innerHTML += `<option value="">Seleccione una sección</option>`
      res.data.forEach(el => {
        sections.innerHTML += `<option value=${el.ID_SECTION}>${el.TIMING}-${el.NAME}</option>`
      })
      event.target.disabled = false
      hideLoadingComponent('loading')
    } catch(err) {
      event.target.disabled = false
      hideLoadingComponent('loading')
      if (err.message == 'Not Sections Found') {
        showPopUp('No hay secciones disponibles')
        return
      }
      showPopUp('Error en el servidor')
    }
  })
})