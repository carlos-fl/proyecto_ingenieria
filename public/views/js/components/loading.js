class Loading extends HTMLElement {
  constructor() {
    super()
    this.modalID = ''
    this.loadingID = ''
  }

  connectedCallback() {
    this.loadingID = this.getAttribute('tag-id')
    this.modalID = this.getAttribute('modal-id')
    this.render()
  }

  show() {
    const modalElement = document.getElementById(this.modalID);
    if (modalElement) {
      const modalInstance = new bootstrap.Modal(modalElement);
      modalInstance.show();
    }
  }

  hide() {
    const modalElement = document.getElementById(this.modalID)
    if (modalElement) {
      const modalInstance = new bootstrap.Modal(modalElement);
      modalInstance.hide();
    }
  }

  attributeChangedCallback(name, oldValue, newValue){
    this.connectedCallback()
  }

  render() {
    this.setAttribute('id', this.loadingID)
    this.innerHTML = `
    <div class="modal fade" id=${ this.modalID } tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="width: 20vw; height: 20vh; margin: 0 auto;">
                <div class="modal-body d-flex justify-content-center align-items-center">
                  <div class="d-loader"></div>
                </div>
            </div>
        </div>
    </div>
    `
  }
}

customElements.define('loading-modal', Loading)
/*    <d-modal modal-id="${ this.modalID }" arial-label-led-by="getting-content" header-title="..." arial-label="content" hidden="false">
    </d-modal>
    */