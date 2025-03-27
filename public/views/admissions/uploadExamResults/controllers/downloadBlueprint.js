import { Request } from "../../../js/modules/request.mjs"
import { downloadFile, hideLoadingComponent, showFailModal, showLoadingComponent } from "../../../js/modules/utlis.mjs"

const downloadBtn = document.getElementById('admission-blueprint')

downloadBtn.addEventListener('click', (event) => {
  event.preventDefault()
  downloadBtn.disable = true
  downloadBtn.classList.remove('hover')
  downloadBtn.style.backgroundColor = '#8A94A6'
  showLoadingComponent('loading')
  downloadBlueprint()
  hideLoadingComponent('loading')
  setTimeout(() => {
    downloadBtn.disable = false
    downloadBtn.classList.add('hover')
    downloadBtn.style.backgroundColor = '#000E33'
  }, 1300)
})


async function downloadBlueprint() {
  const URL = '/api/resources/controllers/admissionsBlueprint.php'
  try {
    const res = await fetch(URL) 
    const blob = await res.blob()
    downloadFile('blueprint', blob) 
  } catch(err) {
    console.log(err)
    showFailModal('csv-upload', "No se pudo descargar el archivo... Intente más tarde")
  }
}