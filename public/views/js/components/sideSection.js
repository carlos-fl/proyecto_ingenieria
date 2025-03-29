class SideSection extends HTMLElement {
  constructor() {
    super()
  }

  connectedCallback(){
    this.render()
  }

  attributeChangedCallback(name, oldValue, newValue){
    this.connectedCallback()
  }

  render() {
    const children = this.childNodes
    let elements = Array.from(children).map((child) => {
      if (child.tagName) {
        return child.outerHTML
      }
    }).join('')
    this.innerHTML = `
    <div class="d-flex flex-column justify-content-between align-items-center h-100 py-5 side-section-c">
      <div>
        <h1 class="text-black">UNAH</h1> 
      </div>
      <div id="side-buttons" class="d-flex flex-column justify-content-center align-items-center h-50">${ elements }</div>
      <div>
        <log-out></log-out> 
      </div>
    </div>
    `
  }
}

customElements.define('side-section', SideSection)