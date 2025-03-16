class LogOut extends HTMLElement{


    connectedCallback(){
        this.onclick = this.logOut
        this.render()
    }

    render(){
        this.innerHTML = `
            <button type="button" class="btn btn-danger">Cerrar Sesion</button>
    `
    }

    logOut(){
        localStorage.clear()
        sessionStorage.clear()
        fetch("/api/auth/controllers/logout.php", {METHOD: "GET"})
        .then(response => console.log("Se cerró la sesión"))
        .catch(error => console.log("No hubo conexión con el server"))
        window.location.href = "/"
    }
}


customElements.define("log-out", LogOut)