class successModal extends HTMLElement {
  constructor() {
    super()
    this.modalSuccessID
    this.modalID = ''
    this.ariaLabelLedBy = ''
    this.arialLabel = ''
    this.hidden = "false"
    this.headerTitle = ''
  }

 connectedCallback(){
    this.modalSuccessID = this.getAttribute('tag-id')
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
    const modalElement = document.getElementById(this.modalID);
    if (modalElement) {
      const modalInstance = new bootstrap.Modal(modalElement);
      modalInstance.show();
    }
  }

  render() {
    this.setAttribute('id', this.modalSuccessID)
    this.innerHTML = `
      <d-modal modal-id=${ this.modalID } arial-label-led-by=${ this.ariaLabelLedBy } arial-label=${ this.arialLabel } hidden=${ this.hidden } header-title="${ this.headerTitle }">
        <i class="fa-solid fa-circle-check" style="color: #4BB543; font-size: 7rem"></i> 
      </d-modal> 
    `
  }
}

customElements.define('modal-success', successModal)
