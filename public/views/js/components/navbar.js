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
                    <a href="/index.php"><img style="width: 45%;" src="assets/img/logo-unah.png" alt="Logo UNAH" /></a>
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
                            <a class="nav-link" href="/views/login.php">Estudiantes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/views/login.php">Docentes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#reviewerModal">Revisores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/views/login.php">Matricula</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/views/login.php">Biblioteca</a>
                        </li>
                        </ul>
                    </div>
                    </div>
                </div>
            </nav>

        <!-Ventana modal-->
        <div class="modal fade" id="reviewerModal" tabindex="-1" aria-labelledby="reviewerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="reviewerModalLabel">Revisores</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center align-items-center">
            <div class="btn-group" role="group" aria-label="Select reviewer type">
                <a href="/views/loginSolicitudesAdmision.php"><button type="button" class="btn btn-warning mx-3" id="admissionReviewer">Revisor de Solicitud de Admision</button></a>
                <a href="/views/construccion.php"><button type="button" class="btn btn-warning mx-3" id="examReviewer">Revisor de Examen de Admision</button></a>  
            </div>
            </div>
        </div>
        </div>
    </div>  
    `
    }
}


customElements.define("navbar-unah", Navbar)