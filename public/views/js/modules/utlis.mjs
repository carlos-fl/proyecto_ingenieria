// General utilities for views management

// Disable a button n milliseconds
export function disableBtn(btn, time){
    btn.setAttribute("disabled", "disabled")
    setTimeout(() =>{
        btn.removeAttribute("disabled")
    }, time)
}


export function showPopUp(message, popUpClass="fail-popup", imgSource="/views/assets/img/crossmark.png"){
    let popUp = document.getElementById("popUp")
    popUp.setAttribute("message", message)
    popUp.setAttribute("imgSource", imgSource)
    popUp.setAttribute("pop-up-class", popUpClass)
    popUp.show()
    
}

export function showFailPopUp(popUpId, content){
    let popUp = document.getElementById(popUpId)
    let popUpMessage = popUp.querySelector("#popUpMessage") 
    popUpMessage.textContent = content
    popUp.style.animation = "showPopUp 2s ease-in-out forwards"
    setTimeout(() =>{
        popUp.style.animation = ""
    }, 2000)
}

export function changeBorder(domElement, borderWidth, color){
    domElement.style.border = `${borderWidth} solid ${color}`
}


export function loginFailHandler(){
    // Manejar el event login-form:fail junto con el popUp
    let loginForm = document.querySelector("login-form")
    loginForm.addEventListener("login-form:fail", event => {
        showPopUp(event.detail.message)
    })
}


/**
 * 
 * @param {string} modalErrorId 
 * this is the id of the custom element with tag-id
 */
export function showFailModal(modalErrorId, message="Hubo un error...") {
    const modal = document.getElementById(modalErrorId)
    modal.setAttribute('header-title', message)
    modal.show()
}

/**
 * 
 * @param {string} modalSuccessId 
 * this is the id of the custom element with tag-id
 */
export function showSuccessModal(modalSuccessId) {

    const modal = document.getElementById(modalSuccessId)
    modal.show()
}

/**
 * 
 * @param {string} modalID 
 */
export function showModal(modalID) {
    const modal = document.getElementById(modalID)
    modal.show()
}

/**
 * 
 * @param {string} URL 
 * URL where user will be relocated
 * @param {string} modalErrorId 
 * modal id. the one with tag-id
 * @param {int} modalDuration
 * this is for the error modal to show in milliseconds
 */
export function relocateWithErrorModal(URL, modalErrorId, modalDuration) {
    showFailModal(modalErrorId)
    setTimeout(() => {
        window.location.replace('/')
    }, modalDuration)
}

/**
 * 
 * @param {string} URL 
 * URL where user will be relocated
 * @param {string} modalSuccessId 
 * modal id. The one with tag-id
 * @param {int} modalDuration
 * in milliseconds default 3200
 */
export function relocateWithSuccessModal(URL, modalSuccessId, modalDuration = 2400) {
    showSuccessModal(modalSuccessId)
    setTimeout(() => {
        window.location.replace(URL)
    }, modalDuration)
}

/**
 * 
 * @param {string} loadingID 
 * this is the id in tag-id
 */
export function showLoadingComponent(loadingID) {
    const modal = document.getElementById(loadingID)
    modal.show()
}

/**
 * 
 * @param {string} loadingID 
 * this is the id in tag-id
 */
export function hideLoadingComponent(loadingID) {
    setTimeout(() => {
        const modal = document.getElementById(loadingID)
        modal.hide()
    }, 500)
}

function parseToCsv(tableId){
    let csv = []
    let rows = document.querySelectorAll(`#${tableId} tr`)
    let currentRow
    for (let row of rows){    
        let cols = row.querySelectorAll("td,th")
        cols = Array.from(cols)
        currentRow = cols.map((col) => {return col.innerText.trim()})
        csv.push(currentRow.join(","))
    }
    console.log(csv)
    return csv
  }
  
  
  // Función para exportar la tabla de alumnos a CSV (simulación de descarga Excel)
export function exportTableToCSV(tableId, filename) {
    // export a table to CSV format
    let csv = parseToCsv(tableId)
    // Crea un Blob y genera el enlace de descarga
    let csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
    let downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

export function readFileAsText(file){
    // Returns the contents of a file as text
    return new Promise((resolve) => {
        let fileReader = new FileReader()
        fileReader.onload = (e) => resolve(fileReader.result)
        fileReader.readAsText(file)
    })
}

export function parseCsvToTable(tableId, file, hasHeader=true){
    readFileAsText(file)
    .then((textFile) => {
        let rows = textFile.split("\n")
        let table = document.getElementById(tableId)
        let tableBody = table.querySelector("tbody")
        if (hasHeader){
            rows = rows.slice(1)
        }
        rows = rows.slice(0,-1)
        for (let row of rows){
            let tblRow = document.createElement("tr")
            let cols = row.split(",")
            for (let col of cols){
                let tblCol = document.createElement("td")
                tblCol.innerText = col
                tblRow.appendChild(tblCol)
            }
            tableBody.appendChild(tblRow)
        }
    })
}

export function cleanTableBody(table){
    let tableBody = table.querySelector("tbody")
    tableBody.innerHTML = ""
}


/**
 * 
 * @param {string} fileName 
 * this is how the file will be called when user downloads it
 * @param {blob} blob 
 * blob file
 */ 
export function downloadFile(fileName, blob) {
    let downloadLink = document.createElement("a");
    downloadLink.download = fileName;
    downloadLink.href = window.URL.createObjectURL(blob);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

/**
 * 
 * @param {HTMLInputElement} input 
 * @param {string} inputBorderWidth 
 * @param {string} changeToColor 
 * @param {string} normalColor 
 * @param {number} time
 * milliseconds
 */
export function changeBorderWithTiming(input, inputBorderWidth, changeToColor, normalColor, time) {
    changeBorder(input, inputBorderWidth, changeToColor)
    setTimeout(() => {
      changeBorder(input, inputBorderWidth, normalColor)
    }, time)
}

export function newTableData(content, name = "") {
    let data = document.createElement("td");
    data.className = name;
    data.innerText = content ?? "Manolo";
    return data;
}

export function newPrimaryBtn(content) {
    // Create a new Primary button HTML Element
    let btn = document.createElement("button");
    btn.innerText = content;
    btn.className = "btn btn-primary btn-sm mx-2";
    return btn;
}

export function newDangerBtn(content) {
    // Create a new Primary button HTML Element
    let btn = document.createElement("button");
    btn.innerText = content;
    btn.className = "btn btn-danger btn-sm mx-2";
    return btn;
}

export function disableElement(DOMElement){
    DOMElement.setAttribute("disabled", "disabled")
}

export function enableElement(DOMElement){
    DOMElement.removeAttribute("disabled")
}

export function showLoadingIcon(domObject){
    // Show a loading component in a dom object
    domObject.innerText = ""
    domObject.innerHTML = `
        <div class="spinner-border text-secondary">
            <span class="sr-only">Loading...</span>
        </div>
        <div>Cargado Registros...</div>
        `
}
