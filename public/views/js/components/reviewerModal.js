class ReviewerModal extends HTMLElement {
  constructor() {
    super();
  }

  connectedCallback(){
    this.tagID = this.getAttribute('tag-id')
    this.application = this.getAttribute("application")
    this.exam = this.getAttribute("exam")
    this.render()

  }

  show() {
    const modalElement = document.getElementById(this.modalID);
    if (modalElement) {
      const modalInstance = new bootstrap.Modal(modalElement);
      modalInstance.show();
    }
  }


  attributeChangedCallback(name, oldValue, newValue){
    this.connectedCallback()
  }

  render() {
    this.setAttribute('id', this.tagID)
    this.innerHTML = `
    <div class="modal fade" id="reviewerModal" tabindex="-1" aria-labelledby="reviewerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewerModalLabel">Revisores</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="btn-group" role="group" aria-label="Select reviewer type">
                        <a href="${ this.application }"><button type="button" class="btn btn-warning mx-3" id="admissionReviewer">Revisor de Solicitud de Admision</button></a>
                        <a href="${ this.exam }"><button type="button" class="btn btn-warning mx-3" id="examReviewer">Revisor de Examen de Admision</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>`
  }
}


customElements.define('reviewer-modal', ReviewerModal)