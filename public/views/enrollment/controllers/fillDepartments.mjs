import { Request } from "../../js/modules/request.mjs"
import { hideLoadingComponent, showLoadingComponent, showPopUp } from "../../js/modules/utlis.mjs"

/**
 * 
 * @param {Object} major 
 */
export async function fillDepartments(major) {
  showLoadingComponent('loading')
  try {
    console.log('FILL DEPARTMENT: ', major)
    const URL = `/api/enrollment/controllers/getAvailableDepartments.php?majorId=${major.MAJOR_ID}`
    const res = await Request.fetch(URL, 'GET')

    const departmentSelect = document.getElementById('departments')
    departmentSelect.innerHTML = ''
    departmentSelect.innerHTML += '<option value="0" selected>Seleccione un departamento</option>'
    res.data.forEach((department) => {
      departmentSelect.innerHTML += 
      `
        <option value=${department.DEPARTMENT_ID}>${ department.DEPARTMENT_NAME }</option> 
      `
    })

    setClasses(major)

    hideLoadingComponent('loading')
  } catch(err) {
    console.log(err)
    hideLoadingComponent('loading')
    showPopUp("Error en el servidor")
  }
}

/**
 * 
 * @param {Object} major 
 */
function setClasses(major) {
  const departmentsSelect = document.getElementById('departments')
  departmentsSelect.addEventListener('change', async (event) => {
    const departmentID = event.target.value
    const CLASSES_URL = `/api/enrollment/controllers/getStudentClassesByDepartment.php?majorId=${major.MAJOR_ID}&departmentId=${departmentID}`
    const classes = await Request.fetch(CLASSES_URL, 'GET')
    const classesSelect = document.getElementById('classes')
    classesSelect.innerHTML = ''
    classesSelect.innerHTML += '<option value="">Seleccione una clase</option>'
    classes.data.forEach((el) => {
      classesSelect.innerHTML += `<option value=${el.ID_CLASS}>${el.CLASS_NAME}</option>`
    })
  })
}