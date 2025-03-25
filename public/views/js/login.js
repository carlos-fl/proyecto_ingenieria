// Lógica de control par loginRevisorExamenesAd.php
import { loginFailHandler} from "./modules/utlis.mjs"

function loginSuccessHandler(){
    // Manejar el evento login-form:success
    let loginForm = document.getElementById("login-form")
    loginForm.addEventListener("login-form:success", event => {
        const data = event.detail.data
        const roles = data.roles
        if (roles.includes('ADMINISTRATOR')) {
            window.location.href = "../../admissions/uploadExamResults/index.php"
        }
    })
}

function main(){
    loginSuccessHandler()
    loginFailHandler()
}


main()