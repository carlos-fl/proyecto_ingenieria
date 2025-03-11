// Manejo de la lógica para peticiones de admin login

// Cerrar sesión
let endSessionBtn = document.getElementById("endSession")
endSessionBtn.addEventListener("click", (event) => {
    sessionStorage.clear()
    localStorage.clear()
    window.location.href = "adminLogin.php"
})


function main(){
    // Preparar y cargar los datos de la vista
    let personalInfo = document.getElementById("informacionContainer")
    let adminName = personalInfo.querySelector("#adminName")
    let adminEmail = personalInfo.querySelector("#adminEmail")
    let adminPhone = personalInfo.querySelector("#adminPhone")
    let adminEmployeeNumber= personalInfo.querySelector("#adminEmployeeNumber")

    adminName.textContent = sessionStorage.getItem("userFirstName") + " " + sessionStorage.getItem("userLastName")
    adminEmail.textContent = sessionStorage.getItem("userInstEmail")
    adminPhone.textContent = sessionStorage.getItem("userPhoneNumber")
    adminEmployeeNumber.textContent = sessionStorage.getItem("userEmployeeNumber")
}

main()