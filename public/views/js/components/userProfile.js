class UserProfile extends HTMLElement {
  constructor() {
    super()
  }

  static observedAttributes = ['profile-title', 'welcome-msg', 'profile-img', 'desc-img', 'user-number', 'user-email', 'user-name', 'user-phone']

  connectedCallback(){
    this.profileTitle = this.getAttribute('profile-title')
    this.welcomeMsg = this.getAttribute('welcome-msg')
    this.profileImg = this.getAttribute('profile-img')
    this.imgDescription = this.getAttribute('desc-img')
    this.employeeNumber = this.getAttribute('user-number')
    this.employeeEmail = this.getAttribute('user-email')
    this.employeeName = this.getAttribute('user-name')
    this.employeePhone = this.getAttribute('user-phone')
    this.render()

  }

  attributeChangedCallback(name, oldValue, newValue){
    this.connectedCallback()
  }

  render() {
    this.setAttribute('id', 'user-profile')
    this.innerHTML = `
                  <div class="container bg-white px-5">
                    <div class="profile-container-title">
                      <div class="profile-bar-h mb-5">
                        <h3>${ this.profileTitle }</h3>
                      </div>
                    </div>
                    <div class="profile-container-body">
                      <div class="profile-container-body-message">
                        <h4 class="profile-container-body-message-text">${ this.welcomeMsg }</h4>
                      </div>
                      <div class="d-flex flex-row justify-content-between align-items-center profile-container-body-info">
                        <div class="profile-container-body-info-img">
                          <img src=${"data:image/png;base64," + this.profileImg } alt=${ this.imgDescription ?? 'Foto perfil del usuario' }>
                        </div>
                        <div class="profile-container-body-info-personal">
                          <section>
                              <div class="info-card">
                                <div class="info-header">
                                  <span>Información Personal</span>
                                </div>
                                <hr />
                                <div class="info-row info-labels">
                                  <span>Número de Empleado</span>
                                  <span>Nombre Completo</span>
                                  <span>Correo</span>
                                  <span>Teléfono</span>
                                </div>
                                <div class="info-row">
                                  <span id="employeeNumber">${ this.employeeNumber }</span>
                                  <span id="name">${ this.employeeName }</span>
                                  <span id="email">${ this.employeeEmail }</span>
                                  <span id="phone">${ this.employeePhone }</span>
                                </div>
                              </div>
                            </section>
                        </div>
                      </div>
                    </div>
                  </div> 
    `
  }
}

customElements.define('user-profile', UserProfile)
