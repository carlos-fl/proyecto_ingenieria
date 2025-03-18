class Navbar extends HTMLElement{
    static get observedAttributes() {
        return [
          'home-href', // para el enlace del logo
          'logo', 'logo-alt',
          'inicio-href',
          'estudiantes-href',
          'docentes-href',
          'revisores-href',
          'matricula-href',
          'admisiones-href',
          'biblioteca-href'
        ];
      }

    connectedCallback(){
        this.render()
    }

    attributeChangedCallback(){
        this.connectedCallback()
    }

    render(){
        // valores por defecto.
       // Se definen los valores por defecto si no se especifican los atributos.
    const homeHref = this.getAttribute('home-href') || '/index.php';
    const logo = this.getAttribute('logo') || '/views/assets/img/logo-unah.png';
    const logoAlt = this.getAttribute('logo-alt') || 'Logo UNAH';
    const estudiantesHref = this.getAttribute('estudiantes-href') || '/views/loginEstudiantes.php';
    const docentesHref = this.getAttribute('docentes-href') || '/views/loginDocentes.php';
    const matriculaHref = this.getAttribute('matricula-href') || '/views/login.php';
    const admisionesHref = this.getAttribute('admisiones-href') || '/views/admisiones.php';

        this.innerHTML = `
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <div>
                    <a href="/index.php"><img style="width: 45%;" src="${logo}" alt="${logoAlt}" /></a>
                    </div>
                    <div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                        aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarText">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="${homeHref}">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="${estudiantesHref}">Estudiantes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="${docentesHref}">Docentes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#reviewerModal">Revisores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="${matriculaHref}">Matricula</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="${admisionesHref}">Admisiones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="${estudiantesHref}">Biblioteca</a>
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