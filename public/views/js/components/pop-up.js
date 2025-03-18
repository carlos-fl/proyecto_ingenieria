class PopUp extends HTMLElement{

    constructor(){
        super()
        this.message = ""
        this.imgSource = ""
        this.duration = ""
    }

    static observedAttributes = ["message", "imgSource", "popUpClass"]

    connectedCallback(){
        this.message = this.getAttribute("message")
        this.imgSource = this.getAttribute("imgSource")
        this.popUpClass = this.getAttribute("popUpClass") ?? "fail-popup"
        // Default pop Up duration time is 4s
        this.duration = this.getAttribute("duration") ?? 4000
        this.render()
    }

    attributeChangedCallback(){
        this.connectedCallback()
    }

    render(){
        this.innerHTML = `
            <div id="popUpContainer" class="d-flex align-items-center ps-2 pop-up ${this.popUpClass}" disabled>
                <img id="popUpImg"src="${this.imgSource}" alt="">
                <span class="ms-2" id="popUpMessage">${this.message}</span>
            </div>
    `
    }

    show(){
        // Show the popUp
        let keyframes = [
            {display: "block", bottom: "20px", opacity: 1, offset: .01},
            {display: "block", bottom: "20px", opacity: 1, offset: .9},
            {display: "block", bottom: "0px", opacity: 0, offset: 1},
        ]
        let timing = {
            duration: this.duration, 
            easing: "ease-in",
            iterations: 1
        }
        let container = this.querySelector("#popUpContainer")
        let animation = container.animate(keyframes, timing)
        animation.play()
    }
}


customElements.define("pop-up", PopUp)