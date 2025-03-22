import { Request } from '../../../js/modules/request.mjs'
import { hideLoadingComponent, relocateWithErrorModal, showFailModal, showLoadingComponent } from '../../../js/modules/utlis.mjs'

export var data

document.addEventListener('DOMContentLoaded', async () => {
  showLoadingComponent('loading')
  const URL_TOKEN = new URLSearchParams(window.location.search).get('token')

  try {
    const URL = `/api/admissions/controllers/getSubmittedAdmission.php?${URL_TOKEN}`
    data = await Request.fetch(URL, "GET")

    if (!data || data == null) {
      hideLoadingComponent('loading')
      relocateWithErrorModal('/', 'correction-fail')
    }

    hideLoadingComponent('loading')

  } catch (err) {
    hideLoadingComponent('loading')
    relocateWithErrorModal('/', 'correction-fail')
  }
})
