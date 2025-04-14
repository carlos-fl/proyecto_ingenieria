import { Request } from '../../js/modules/request.mjs'
import { hideLoadingComponent, showLoadingComponent, showPopUp } from '../../js/modules/utlis.mjs'

document.addEventListener('DOMContentLoaded', async () => {
  showLoadingComponent('loading') 
  const URL = '/api/enrollment/controllers/isActive.php'
  try {
    const res = await Request.fetch(URL, 'GET')
    hideLoadingComponent('loading')

    if (res.status == 'failure') {
      showPopUp('No se puede comprobar estado de matricula')
      setTimeout(() => {
      window.location.replace('/')
      }, 3000)
    }

    if (res.status == 'success' && !res.isActive) {
      showPopUp('matricula no activa')
      console.log('no se encontraron matricula activas', res)
    }

  } catch(err) {
    console.log(err)
    hideLoadingComponent('loading')
    showPopUp('Error en el servidor')
    
  }
})
