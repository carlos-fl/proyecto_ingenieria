import { Request } from "../../js/modules/request.mjs";
import { fillDepartments } from "./fillDepartments.mjs";

document.addEventListener('DOMContentLoaded', async () => {
  window.localStorage.removeItem('major-chosen-enrollment')
  const URL = `/api/enrollment/controllers/getStudentMajors.php`
  try {
    const res = await Request.fetch(URL, 'GET')
    if (res.data.length == 1) {
      setMajorInLocalstorage(res.data[0])
      return;
    }


    // has more majors...
    if (!window.localStorage.getItem('major-chosen-enrollment')) {
      setModalContent(res.data) 
    }

  } catch(err) {
    console.log(err)
  }
})

/**
 * @param {Array} majors
 */
function setModalContent(majors) {
  const modalBody = document.getElementById('content-major')
  const modal = document.getElementById('major-modal')
  const div = document.createElement('div')
  div.classList.add('d-flex', 'flex-column' ,'justify-content-between', 'align-items-center')
  majors.forEach(major => {
    const button = document.createElement('button')
    button.classList.add('btn', 'btn-primary', 'w-100', 'mb-1')
    button.onclick = () => setMajorInLocalstorage(major)
    button.textContent = major.MAJOR_NAME
    div.appendChild(button)
  })
  modalBody.appendChild(div)
  if (!window.localStorage.getItem('major-chosen-enrollment'))
    modal.show()
}

/**
 * 
 * @param {Object} major 
 */
async function setMajorInLocalstorage(major) {
  const modal = document.getElementById('major-modal')
  if (!window.localStorage.getItem('major-chosen-enrollment')) {
    window.localStorage.setItem('major-chosen-enrollment', JSON.stringify(major))
  }


  modal.hide()
  fillDepartments(major)
}