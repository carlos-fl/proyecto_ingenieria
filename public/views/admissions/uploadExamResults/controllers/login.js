// Lógica de control par loginRevisorSolicitudesAd.php
import { loginFailHandler} from "../../../js/modules/utlis.mjs"

function loginSuccessHandler(){
    // Manejar el evento login-form:success
    let loginForm = document.getElementById("login-form")
    loginForm.addEventListener("login-form:success", event => {
        const data = event.detail.data
        const roles = data.roles
        if (roles.includes('ADMINISTRATOR')) {
          if (!window.localStorage.getItem('upload-info')) {
            window.localStorage.setItem("upload-info", JSON.stringify(data))
          }
            window.location.href = "index.php"
        }
    })
}

function main(){
    loginSuccessHandler()
    loginFailHandler()
}


main()