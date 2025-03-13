// Module to validate form input

export function validEmail(email){
    let pattern = /[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
    let re = new RegExp(pattern)
    return re.test(email)
}