class failModal extends HTMLElement {
  constructor() {
    super()
    this.modalID = ''
    this.ariaLabelLedBy = ''
    this.arialLabel = ''
    this.hidden = "true"
    this.headerTitle = ''
  }

  connectedCallback(){
    this.modalID = this.getAttribute("modal-id")
    this.arialLabel = this.getAttribute("aria-label")
    this.ariaLabelLedBy = this.getAttribute("aria-label-led-by")
    this.hidden = this.getAttribute("hidden")
    this.headerTitle = this.getAttribute("header-title")
    this.render()


    setTimeout(() => {
      const modalElement = document.getElementById(this.modalID);
      if (modalElement) {
        const modalInstance = new bootstrap.Modal(modalElement);
        modalInstance.show();  // Show modal on render
      }
    }, 0);
  }

  attributeChangedCallback(name, oldValue, newValue){
    this.connectedCallback()
  }

  render() {
    this.innerHTML = `
      <d-modal modal-id=${ this.modalID } aria-label-led-by=${ this.ariaLabelLedBy } aria-label=${ this.arialLabel } hidden=${ this.hidden } header-title=${ this.headerTitle } style="z-index: 20;">
        <i class="fa-solid fa-circle-xmark" style="color: #ff0033; font-size: 7rem"></i>
      </d-modal> 
    `
  }
}

customElements.define('modal-error', failModal)

