class LoginForm extends HTMLElement{

    constructor(){
        super()
        this.action = ""
        this.imgSource = ""
        this.imgAlt = ""
        this.heading =""
        this.message = ""
    }

    handleEvent(event){
        this.login(event)
    }

    connectedCallback(){
        this.action = this.getAttribute("action")
        this.imgSource = this.getAttribute("imgSource")
        this.imgAlt = this.getAttribute("imgAlt") ?? ""
        this.heading = this.getAttribute("heading")
        this.message = this.getAttribute("message") ?? "Debes autenticarte para usar este servicio"
        this.render()
        this.querySelector("#submitBtn").addEventListener("click", this)
    }

    render(){
        this.innerHTML = `
            <section id="loginSection" class="container d-flex justify-content-center">
                <div id="loginComponent" class="outline-grey">
                <img id="heroUnah" src="${this.imgSource}" alt="${this.imgAlt}" >
                <div id="loginBox" class="pt-3 ps-3 pe-5">
                    <h3 class="fw-bold">${this.heading}</h3>
                    <small>${this.message}</small>
                    <p class="mt-5">Ingrese su correo y contraseña</p>
                    <hr>
                    <div id="loginForm" class="mt-5">
                        <div>
                            <label for="email">Correo</label>
                            <input id="email" placeholder="example@unah.edu.hn" type="email" class="form-control">
                        </div>
                        <div class="mt-2">
                            <label for="password">Contraseña</label>
                            <input id="password" placeholder="Contraseña" type="password" class="form-control">
                        </div>
                        <div class="d-flex justify-content-end">
                            <button id="submitBtn" class="btn btn-primary mt-3">Ingresar</button>
                        </div>
                        <small class="float-end mt-2"><a href="" class="text-danger no-underline">Olvidé mi contraseña</a></small>
                    </div>
                </div>
            </div>
        </section>
    `
    }
    

    login(event){
        //Bloquear botón
        let submitBtn = event.target
        let emailInput = this.querySelector("#email")
        let passwordInput = this.querySelector("#password")
        let email = emailInput.value.trim()
        let password = passwordInput.value.trim()
        this.disable(submitBtn, 2000)
        if (!email || !password){
            // Pedirle al usuario que ponga un usuario y una contraseña
            this.changeBorder(emailInput,"var(--bs-border-width)", "red")
            this.changeBorder(passwordInput,"var(--bs-border-width)", "red")
            this.emit("fail", {message: "Ingrese su usuario y contraseña"});
            return
        }
        if (!this.validEmail(email)){
            // Pedirle al usuario que ingrese un correo válido
            this.changeBorder(emailInput,"var(--bs-border-width)", "red")
            this.changeBorder(passwordInput,"var(--bs-border-width)", "var(--bs-border-color)")
            this.emit("fail", {message: "Ingrese un correo válido"});
            return
        }
        // Realizar petición
        this.requestLogin(emailInput, passwordInput)
    }

    disable(htmlElement, time){
        htmlElement.setAttribute("disabled", "disabled")
        setTimeout(() => {htmlElement.removeAttribute("disabled")}, time)
    }

    changeBorder(input, borderWidth, color){
        // Add red border to an input in the form
        input.style.border = borderWidth + ' solid ' + color 

    }

    emit(eventName, detail={}){
        // Emit an event
        let event = new CustomEvent(`login-form:${eventName}`, {
            bubbbles: true,
            cancelable: true,
            detail: detail
            })
        
        this.dispatchEvent(event)
    }

    validEmail(email){
        let pattern = /[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
        let re = new RegExp(pattern)
        return re.test(email)
    }

    async requestLogin(email, password){
        let body = JSON.stringify({
            "email": email.value,
            "password": password.value
        })
        fetch(this.action, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: body
        })
        .then(res => res.json())
        .then(data => {
            if (!data){
                this.emit("fail", {message: "Hubo un error con el servidor"});
                return
            }
            if (data.status === "failure"){
                // No se pudo loggear correctamente
                this.changeBorder(email,"var(--bs-border-width)", "red")
                this.changeBorder(password,"var(--bs-border-width)", "red")
                this.emit("fail", {message: "Usuario o contraseña incorrectos"});
                return  
            }
            // Successful login
            // Load user data to sessionStorage
            this.#loadUserData(data.sessionData.user)
            this.emit("success", {data: data.sessionData})
        })
        .catch(error => {
            // Unsuccessful login
            console.log("ERROR")
            console.log(error.message)
            this.emit("fail", {message: "Hubo un error con el servidor"});
        })
    }

    #loadUserData(data){
        data = JSON.parse(data)
        window.localStorage.setItem("userId", data["USER_ID"])
        window.localStorage.setItem("userFirstName", data["FIRST_NAME"]) 
        window.localStorage.setItem("userLastName", data["LAST_NAME"])
        window.localStorage.setItem("userPhoneNumber", data["PHONE_NUMBER"]) 
        window.localStorage.setItem("userInstEmail", data["INST_EMAIL"])
    }
}


customElements.define("login-form", LoginForm)