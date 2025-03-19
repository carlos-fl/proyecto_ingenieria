// Lógica de control par loginRevisorSolicitudesAd.php
import { loginFailHandler} from "./modules/utlis.mjs"

function loginSuccessHandler(){
    // Manejar el evento login-form:success
    let loginForm = document.getElementById("login-form")
    loginForm.addEventListener("login-form:success", event => {
        window.location.href = "solicitudesAdmision.php"
    })
    
}

function main(){
    loginFailHandler()
    loginSuccessHandler()
}

main()