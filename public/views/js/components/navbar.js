class Navbar extends HTMLElement{

    connectedCallback(){
        this.render()
    }

    attributeChangedCallback(){
        this.connectedCallback()
    }

    render(){
        this.innerHTML = `
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <div>
                    <a href="index.php"><img style="width: 45%;" src="assets/img/logo-unah.png" alt="Logo UNAH" /></a>
                    </div>
                    <div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                        aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarText">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="/index.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./loginEstudiantes.php">Estudiantes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./loginDocentes.php">Docentes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#reviewerModal">Revisores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./login.php">Matricula</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./admisiones.php">Admisiones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./loginEstudiantes.php">Biblioteca</a>
                        </li>
                        </ul>
                    </div>
                    </div>
                </div>
            </nav>
    `
    }
}


customElements.define("navbar-unah", Navbar)