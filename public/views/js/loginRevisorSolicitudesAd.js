// Lógica de control par loginRevisorSolicitudesAd.php
import { loginFailHandler} from "./modules/utlis.mjs"

function loginSuccessHandler(){
    // Manejar el evento login-form:success
    let loginForm = document.getElementById("login-form")
    loginForm.addEventListener("login-form:success", event => {
        const data = event.detail.data
        const roles = data.roles
        if (roles.includes('REVIEWER')) {
            window.location.href = "solicitudesAdmision.php"
        }
       //TODO add href for add reviewer for admin 
    })
}

function main(){
    loginSuccessHandler()
    loginFailHandler()
}


main()