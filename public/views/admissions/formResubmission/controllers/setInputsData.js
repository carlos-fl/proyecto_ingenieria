import { formFields } from './formFields.mjs'
import { Request } from '../../../js/modules/request.mjs'
import { hideLoadingComponent, relocateWithErrorModal, showFailModal, showLoadingComponent, showPopUp } from '../../../js/modules/utlis.mjs'

document.addEventListener('DOMContentLoaded', async () => {
  showLoadingComponent('loading')
  const URL_TOKEN = new URLSearchParams(window.location.search).get('token')

  try {
    const URL = `/api/admissions/controllers/getSubmittedAdmission.php?token=${URL_TOKEN}`
    var data = await Request.fetch(URL, "GET")



    if (!data) {
      hideLoadingComponent('loading')
      relocateWithErrorModal('/', 'correction-fail', 2000)
    }

    hideLoadingComponent('loading')


    for (const obj of formFields) {
      if (obj.name == 'MAJOR_CODE' || obj.name == 'SECOND_MAJOR_CODE') {
        const majors = await Request.fetch(`/api/resources/controllers/majorsByCenter.php?center=${data.applicant.CENTER_ID}`,'GET')
          majors.data.forEach(major => {
            obj.el.innerHTML += `<option value=${ major.MAJOR_ID }>${ major.MAJOR_NAME }</option>`
          })
      }
      if (obj.name == 'CENTER_ID') {
        const centers = await Request.fetch(`/api/resources/controllers/regionalCenters.php`, 'GET')
        centers.data.forEach(center => {
          obj.el.innerHTML += `<option value=${ center.CENTER_ID }>${ center.CENTER_NAME }</option>`
        })
      }
      obj.el.value = data.applicant[obj.name]
    }
  } catch (err) {
    hideLoadingComponent('loading')
    console.log(err)
    showPopUp('Token ya fue utilizado...')
    setTimeout(() => {
      window.location.replace('/')
    }, 3000) 
  }
})


