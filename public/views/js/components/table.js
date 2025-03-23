class Table extends HTMLElement {
  constructor() {
    super()
    this.tableRow = [] // this is an array with the elements inside thead. Basically the column names
  }

  connectedCallback(){
    if (this.hasAttribute('table-row')) {
      this.tableRow = JSON.parse(this.getAttribute("table-row"))
    }

    this.bodyID = 'table-body-results'// this is the id of tbody tag 
    this.render()
  }

  attributeChangedCallback(name, oldValue, newValue){
    if (name == "table-row") {
      this.tableRow = JSON.parse(newValue);
    } 
    this.connectedCallback()
  }

  render() {
    this.innerHTML = `
    <table class="table table-bordered text-center align-middle">
      <thead class="table-light">
        <tr>
          ${ this.tableRow.map(rowName => `<th>${rowName}</th>`) } 
        </tr>
      </thead>
      <tbody id=${ this.bodyID }>
        <!-- Aquí se cargarán los resultados -->
      </tbody>
    </table>`
  }
}


customElements.define("d-table", Table)