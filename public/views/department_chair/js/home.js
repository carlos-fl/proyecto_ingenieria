// Lógica para la pantalla home the department_chair
function loadChairmanData(){
    // Load the data from localStorage to the chairman's profile
    let employeeNumber = document.getElementById("employeeNumber")
    let name = document.getElementById("name")
    let email = document.getElementById("email")
    let phone = document.getElementById("phone")
    employeeNumber.innerText = localStorage.getItem("employeeNumber")
    name.innerText = localStorage.getItem("userFirstName") + " " + localStorage.getItem("userLastName")
    email.innerText = localStorage.getItem("userInstEmail")
    phone.innerText = localStorage.getItem("userPhoneNumber")
}


function main(){
    loadChairmanData()
}

main()