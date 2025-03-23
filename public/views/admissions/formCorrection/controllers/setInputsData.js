import { data } from './getFormData.mjs'
import { hideLoadingComponent, relocateWithErrorModal, showFailModal } from '../../../js/modules/utlis.mjs'

const formFields = [
  document.getElementById("first-name"),
  document.getElementById("last-name"),
  document.getElementById("user-id"),
  document.getElementById("phone"),
  document.getElementById("email"),
  document.getElementById("gender"),
  document.getElementById("regional-center"),
  document.getElementById("main-career"),
  document.getElementById("secondary-career"),
  document.getElementById("file-upload"),
]

const dataKeys = Object.keys(data)

if (!data) {
  hideLoadingComponent('loading')
  setTimeout(() => {
    relocateWithErrorModal('/', 'correction-fail')
  }, 3000)
}

document.addEventListener('DOMContentLoaded', () => {
  formFields.forEach((input, index) => {
    input.value = data[dataKeys[index]]
  })
})