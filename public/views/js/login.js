// Manejo de la lógica para peticiones de admin login
import { addBorder, disableBtn, showFailPopUp } from "./modules/utlis.mjs"
import { validEmail } from "./modules/validator.mjs"

function loadUserData(user){
    // Load the user's data to Session Storagex
    window.sessionStorage.setItem("userId", user.USER_ID)
    window.sessionStorage.setItem("userFirstName", user.FIRST_NAME) 
    window.sessionStorage.setItem("userLastName", user.LAST_NAME)
    window.sessionStorage.setItem("userPhoneNumber", user.PHONE_NUMBER) 
    window.sessionStorage.setItem("userInstEmail", user.INST_EMAIL) 
}

function login(email, password){
    let body = JSON.stringify({
        "email": email,
        "password": password
    })
    fetch("../src/services/auth/controllers/login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: body
    })
    .then(res => res.json())
    .then(data => {
        if (data){
            if (data.status == "failure"){
                // No se pudo loggear correctamente
                let emailInput = document.getElementById("email")
                let passwordInput = document.getElementById("password")
                addBorder(emailInput,"var(--bs-border-width)", "red")
                addBorder(passwordInput,"var(--bs-border-width)", "red")    
                showFailPopUp("popUp", "Usuario o Contraseña Incorrectos");
                return  
            }
            // Load user data to sessionStorage
            loadUserData(data.user)
            window.location.href ="login.php"
        }else{
            showFailPopUp("popUp", "Hubo un error con el servidor");
        }

    })
    .catch(error => {
        // Mostrar el error que ocurrió con el server
        console.log(error)
    })
}

// submitBtn login event
let submitBtn = document.getElementById("submitBtn")
submitBtn.addEventListener("click", (event) => {
    //Bloquear botón
    disableBtn(event.target, 2500)
    let emailInput = document.getElementById("email")
    let passwordInput = document.getElementById("password")
    let email = emailInput.value.trim()
    let password = passwordInput.value.trim()
    if (!email || !password){
        // Pedirle al usuario que ponga un usuario y una contraseña
        addBorder(emailInput,"var(--bs-border-width)", "red")
        addBorder(passwordInput,"var(--bs-border-width)", "red")
        showFailPopUp("popUp", "Ingrese su usuario y contraseña");
        return
    }
    if (!validEmail(email)){
        // Pedirle al usuario que ingrese un correo válido
        addBorder(emailInput,"var(--bs-border-width)", "red")
        addBorder(passwordInput,"var(--bs-border-width)", "var(--bs-border-color)")
        showFailPopUp("popUp", "Ingrese un correo válido");
        return
    }
    // Realizar petición
    login(email, password)
})