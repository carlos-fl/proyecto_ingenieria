// Lógica de control par loginDocentes.php
import { loginFailHandler, showPopUp } from "./modules/utlis.mjs"

function loginSuccessHandler(){
    // Manejar el evento login-form:success
    let loginForm = document.querySelector("login-form")
    loginForm.addEventListener("login-form:success", event => {
        // Datos del login
        let roles = event.detail.roles
        if (roles.includes("TEACHERS")){
            // Reenviar a la vista de docentes
            window.location.href ="docentes.php"
        }
        else if (roles.includes("ADMINISTRATOR")){
            // Reenviar a la vista de creación de un nuevo docente
            window.location.href= "nuevoDocente.php"
        }
        else{
            showPopUp("Acceso Denegado")
        }
    }) 
}

function main(){
    loginFailHandler()
    loginSuccessHandler()
}

main()