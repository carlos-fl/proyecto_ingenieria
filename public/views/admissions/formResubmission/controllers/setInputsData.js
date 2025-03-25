import { data } from './getFormData.mjs'
import { hideLoadingComponent, relocateWithErrorModal, showFailModal } from '../../../js/modules/utlis.mjs'
import { formFields } from './formFields.mjs'

const dataKeys = Object.keys(data)

if (!data) {
  hideLoadingComponent('loading')
  setTimeout(() => {
    relocateWithErrorModal('/', 'correction-fail')
  }, 3000)
}

document.addEventListener('DOMContentLoaded', () => {
  formFields.forEach((obj, index) => {
    obj.el.value = data[dataKeys[index]]
  })
})