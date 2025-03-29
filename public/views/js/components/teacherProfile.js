class TeacherProfile extends HTMLElement {
  constructor() {
    super()
  }

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
    this.innerHTML = `
      <div>
        <div>
          <side-section></side-section> 
        </div> 
        <div>
          <user-profile></user-profile> 
        </div> 
        <div></div> 
      </div>
    `
  }
}