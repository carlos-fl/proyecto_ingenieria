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
        // TODO: Make fetch to log out in back-end
        window.location.href = "/"
    }
}


customElements.define("log-out", LogOut)