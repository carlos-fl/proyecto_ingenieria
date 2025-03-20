class failModal extends HTMLElement {
  constructor() {
    super()
    this.modalID = ''
    this.ariaLabelLedBy = ''
    this.arialLabel = ''
    this.hidden = "false"
    this.headerTitle = ''
  }

 connectedCallback(){
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

  render() {
    this.innerHTML = `
      <d-modal modal-id=${ this.modalID } arial-label-led-by=${ this.ariaLabelLedBy } arial-label=${ this.arialLabel } hidden=${ this.hidden } header-title=${ this.headerTitle }>
        <i class="fa-solid fa-circle-xmark" style="color: #ff0033;"></i>
      </d-modal> 
    `
  }
}

customElements.define('modal-error', failModal)

