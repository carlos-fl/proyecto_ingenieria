class Footer extends HTMLElement{

    connectedCallback(){
        this.render()
    }

    attributeChangedCallback(){
        this.connectedCallback()
    }

    render(){
        this.innerHTML = `
           <footer>
                <div class="navbar bg-body-tertiary">
                    <div class="container-fluid" id="footer-cf">
                        <p>&copy; 2024 Universidad Nacional Autónoma de Honduras</p>
                    </div>
                </div>
            </footer>
    `
    }
}


customElements.define("footer-unah", Footer)