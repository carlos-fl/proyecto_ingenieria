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
    domElement.style.border = borderWidth + 'solid ' + color
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
export function showFailModal(modalErrorId) {
    const modal = document.getElementById(modalErrorId)
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
 * in milliseconds
 */
export function relocateWithSuccessModal(URL, modalSuccessId, modalDuration) {
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
    const modal = document.getElementById(loadingID)
    modal.hide()
}