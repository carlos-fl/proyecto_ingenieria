// Lógica de control par loginRevisorSolicitudesAd.php
import { showPopUp } from "./modules/utlis.mjs"

function loginFailHandler(){
    // Manejar el event login-form:fail junto con el popUp
    let loginForm = document.getElementById("login-form")
    loginForm.addEventListener("login-form:fail", event => {
        showPopUp(event.detail.message)
    })
}

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