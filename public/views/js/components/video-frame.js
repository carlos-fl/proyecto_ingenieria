class VideoFrame extends HTMLElement{

    constructor(){
        super()
        this.videoSource = ""
        this.frameWidth = ""
        this.frameHeight = ""
    }

    connectedCallback(){
        this.videoSource= this.getAttribute("video-source")
        this.width= this.getAttribute("width")
        this.height =this.getAttribute("height")
        this.render()
    }

    render(){
        console.log(this.frameHeight)
        this.innerHTML = `
            <iframe width="${this.width}" height="${this.height}" class=".video-frame" src="${this.videoSource}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
            </iframe>
    `
    }
}


customElements.define("video-frame", VideoFrame)