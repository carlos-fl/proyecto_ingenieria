import { Request } from "../../js/modules/request.mjs";
import { changeBorderWithTiming, hideLoadingComponent, showFailModal, showLoadingComponent, showPopUp } from "../../js/modules/utlis.mjs";
import { isValidAccountNumber } from "../../js/modules/validator.mjs";
import { getCenters, getMajors } from "./data.mjs";
import { fillTable } from "./table.mjs";

document.addEventListener('DOMContentLoaded', async () => {
  const majors = await getMajors()
  const centers = await getCenters()

  const majorsList = document.getElementById('majors')
  const campusList = document.getElementById('campus')

  majorsList.innerHTML = ''
  majors.forEach(major => {
    majorsList.innerHTML += `<option value='${major.MAJOR_NAME}'></option>`
  })

  campusList.innerHTML = ''
  centers.forEach(campus => {
    campusList.innerHTML += `<option value='${campus.CENTER_NAME}'></option>`
  })
})


document.getElementById('apply').addEventListener('click', async () => {
  const account = document.getElementById('account')
  const major = document.getElementById('major-input')
  const campus = document.getElementById('center-input')

  if (account.value != '' && !isValidAccountNumber(account.value)) {
    changeBorderWithTiming(account, 'var(--bs-border-width)', 'red', '#ced4da', 3000)
    showPopUp("Escriba un número de cuenta correcto")
    return;
  }
   
  if (account.value && isValidAccountNumber(account.value)) {
    const student = await getStudent(account.value)
    if (!student)
      return;
    fillTable([student.data])
    return;
  }

  const majors = await getMajors()

  if (major.value && !exists(majors, major.value, 'MAJOR_NAME')) {
    changeBorderWithTiming(major, 'var(--bs-border-width)', 'red', '#ced4da', 3000)
    showPopUp("Escriba una carrera existente")
    return;
  }

  const centers = await getCenters()

  if (campus.value && !exists(centers, campus.value, "CENTER_NAME")) {
    changeBorderWithTiming(campus, 'var(--bs-border-width)', 'red', '#ced4da', 3000)
    showPopUp("Escriba un centro existente")
    return;
  }

  try {
    const students = await getStudentsByFilter(getIdByName(centers, campus.value, 'CENTER_NAME', "CENTER_ID"), getIdByName(majors, major.value, 'MAJOR_NAME', 'MAJOR_ID'))
    fillTable(students.data)
  } catch(err) {
    console.log(err)
  }
})


/**
 * 
 * @param {string} accountNumber 
 */
async function getStudent(accountNumber) {
  showLoadingComponent('loading')
  const URL = `/api/coordinator/controllers/students.php?account=${accountNumber}` 
  try {
    const data = await Request.fetch(URL, 'GET')
    if (!data)
      return null
    hideLoadingComponent('loading')
    return data
  } catch(err) {
    console.log(err)
    hideLoadingComponent('loading')
    showFailModal('history-error', 'No se pudo obtener historiales... Intente más tarde')
    return null
  }
}

/**
 * @param {int} campus 
 * @param {int} major
 */
async function getStudentsByFilter(campus, major) {
  showLoadingComponent('loading')
  const URL = `/api/coordinator/controllers/studentsByFilter.php?campus=${campus}&major=${major}`
  try {
    const data = await Request.fetch(URL, 'GET')
    if (!data)
      return []
    hideLoadingComponent('loading')
    return data
  } catch(err) {
    hideLoadingComponent('loading')
    showPopUp("No se pudo obtener historial... Intente más tarde")
    return []
  }
}

/**
 * 
 * @param {Array} data 
 * @param {string} value 
 * @param {string} valueToSearch
 */
function exists(data, value, valueToSearch) {
  return data.some(el => el[valueToSearch] == value)
}

/**
 * 
 * @param {Array} data 
 * @param {string} valueToSearch
 * @param {string} value 
 * @param {string} valueToGet 
 * @returns {Object}
 */
function getIdByName(data, valueToSearch, value, valueToGet) {
  const item = data.find(el => el[value] == valueToSearch)
  return item ? item[valueToGet] ?? 0 : 0
}
