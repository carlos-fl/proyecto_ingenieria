// Module to validate form input

export function validEmail(email){
    let pattern = /[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
    let re = new RegExp(pattern)
    return re.test(email)
}

export function validDNI(dni){
    let pattern = /\d{4}-\d{4}-\d{5}$/
    let re = new RegExp(pattern)
    return re.test(dni)
}

export function validPhoneNumber(phone){
    let pattern = /\d{4}-\d{4}$/
    let re = new RegExp(pattern)
    return re.test(phone)
}

export function validApplicantCode(applicantCode) {
    const PATTERN = /^\d{4}[a-zA-Z0-9_.-]{23}$/
    const re = new RegExp(PATTERN)
    return re.test(applicantCode)
}