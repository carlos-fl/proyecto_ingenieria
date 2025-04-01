class Modal extends HTMLElement {
  constructor() {
    super()
    this.dModalID = ''
    this.modalID = ''
    this.ariaLabelLedBy = ''
    this.arialLabel = ''
    this.hidden = "false"
    this.headerTitle = ''
  }

  static modalInstance = null

 connectedCallback(){
    this.dModalID = this.getAttribute('tag-id')
    this.modalID = this.getAttribute("modal-id")
    this.arialLabel = this.getAttribute("arial-label")
    this.ariaLabelLedBy = this.getAttribute("arial-label-led-by")
    this.hidden = this.getAttribute("hidden")
    this.headerTitle = this.getAttribute("header-title")
    this.render()
  }

  attributeChangedCallback(name, oldValue, newValue){
    this.connectedCallback()
  }

  show() {
    if (this.modalInstance) {
      this.modalInstance.show()
    } else {
      const modalElement = document.getElementById(this.modalID);
      if (modalElement) {
        const modalInstance = new bootstrap.Modal(modalElement);
        this.modalInstance = modalInstance
        modalInstance.show();
      }
    }
  }

  hide() {
    this.modalInstance.hide()
  }

  render() {
    const children = this.childNodes
    let modalBody = Array.from(children).map((child) => {
      if (child.tagName) {
        return child.outerHTML
      }
    }).join('')
    this.setAttribute('id', this.dModalID)
    this.innerHTML = `
    <div class="modal fade" id=${ this.modalID } tabindex="-1" aria-labelledby=${ this.ariaLabelLedBy } aria-hidden=${ this.hidden }>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="width: 75vw">
                <div class="modal-header">
                    <h5 class="modal-title" id=${ this.ariaLabelLedBy }>${ this.headerTitle }</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="modal-body" class="modal-body d-flex justify-content-center align-items-center">
                    ${ modalBody } 
                </div>
            </div>
        </div>
    </div>`
  }
}

customElements.define("d-modal", Modal)

/*<div class="modal fade" id="reviewerModal" tabindex="-1" aria-labelledby="reviewerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewerModalLabel">Revisores</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="btn-group" role="group" aria-label="Select reviewer type">
                        <a href="loginRevisorSolicitudesAd.php"><button type="button" class="btn btn-warning mx-3" id="admissionReviewer">Revisor de Solicitud de Admision</button></a>
                        <a href="loginRevisorExamenes.php"><button type="button" class="btn btn-warning mx-3" id="examReviewer">Revisor de Examen de Admision</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    */