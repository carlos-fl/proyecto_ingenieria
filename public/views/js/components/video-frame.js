class VideoFrame extends HTMLElement{

    constructor(){
        super()
        this.videoId = ""
        this.frameWidth = ""
        this.frameHeight = ""
    }

    static observedAttributes = ["videoId"]

    connectedCallback(){
        this.videoId= this.getAttribute("video-id")
        this.width= this.getAttribute("width")
        this.height =this.getAttribute("height")
        this.render()
    }

    attributeChangedCallback(){
        this.videoId= this.getAttribute("video-id")
        this.render()
    }

    render(){
        console.log(this.frameHeight)
        this.innerHTML = `
            <iframe width="${this.width}" height="${this.height}" class=".video-frame" src="https://www.youtube.com/embed/${this.videoId}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
            </iframe>
    `
    }
}


customElements.define("video-frame", VideoFrame)