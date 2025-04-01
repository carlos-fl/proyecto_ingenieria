import { loginFailHandler, showPopUp } from "./modules/utlis.mjs"

function loginSuccessHandler(){
    let loginForm = document.querySelector("login-form")
    loginForm.addEventListener("login-form:success", event => {
        let data = event.detail.data
        localStorage.setItem("idStudent", data.idStudent)
        let roles = data.roles
        if (roles.includes("STUDENTS")){
            window.location.href = "students/home/index.php";
        }
        else if (roles.includes("ADMINISTRATOR")){
            window.location.href= "construccion.php"
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