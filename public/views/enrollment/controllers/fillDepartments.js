import { Request } from "../../js/modules/request.mjs"
import { hideLoadingComponent, showLoadingComponent, showPopUp } from "../../js/modules/utlis.mjs"

document.addEventListener('DOMContentLoaded', async () => {
  showLoadingComponent('loading')
  try {
    const URL = '/api/enrollment/controllers/studentDepartment.php'
    const res = await Request.fetch(URL, 'GET')

    const departmentSelect = document.getElementById('departments')
    departmentSelect.innerHTML = 
    `
      <option value=${res.data[0].DEPARTMENT_ID} selected>${ res.data[0].DEPARTMENT_NAME }</option> 
    `

    const CLASSES_URL = `/api/enrollment/controllers/classesByDepartment.php?department=${res.data[0].DEPARTMENT_ID}`
    const classes = await Request.fetch(CLASSES_URL, 'GET')
    const classesSelect = document.getElementById('classes')
    classesSelect.innerHTML = ''
    classesSelect.innerHTML += '<option value="">Seleccione una clase</option>'
    classes.data.forEach((el) => {
      classesSelect.innerHTML += `<option value=${el.ID_CLASS}>${el.CLASS_NAME}</option>`
    })

    hideLoadingComponent('loading')
  } catch(err) {
    console.log(err)
    hideLoadingComponent('loading')
    showPopUp("Error en el servidor")
  }
})
