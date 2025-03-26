// Module to validate form input

export function validEmail(email) {
  let pattern = /[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  let re = new RegExp(pattern);
  return re.test(email);
}

export function validDNI(dni) {
  let pattern = /\d{4}-\d{4}-\d{5}$/;
  let re = new RegExp(pattern);
  return re.test(dni);
}

export function validPhoneNumber(phone) {
  let pattern = /^((3[0-9])|([89][0-9]))(?!0{6}$)\d{2}(?:[-\s]?)\d{4}$/;
  let re = new RegExp(pattern);
  return re.test(phone);
}

export function validApplicantCode(applicantCode) {
  const PATTERN = /^\d{4}[a-zA-Z0-9_.-]{23}$/;
  const re = new RegExp(PATTERN);
  return re.test(applicantCode);
}

/**
 *
 * @param {string} dni
 */
export function isValidDni(dni) {
  const PATTERN = /\d{4}\d{4}\d{5}$/;
  const PATTERN_2 = /\d{4}-\d{4}-\d{5}$/;
  const PATTERN_3 = /^[A-Z]{1,2}[0-9]{6,9}$/;
  const re = new RegExp(PATTERN);
  const re_2 = new RegExp(PATTERN_2);
  const re_3 = new RegExp(PATTERN_3);
  return re.test(dni) || re_2.test(dni) || re_3.test(dni);
}

/**
 *
 * @param {string} name
 */
export function isValidExamName(name) {
  const PATTERN = /[A-Za-z0-9]$/;
  const re = new RegExp(PATTERN);
  return re.test(name);
}

/**
 *
 * @param {string} calification
 */
export function isValidCalification(calification) {
  const PATTERN = /^(?:1[0-7][0-9]{2}|[1-9][0-9]{2}|1800)$/;
  const re = new RegExp(PATTERN);
  return re.test(calification);
}

export function isValidYoutubeUrl(url) {
  const PATTERN = /^https:\/\/www\.youtube.com\/watch\?v=\w+/;
  const re = new RegExp(PATTERN);
  return re.test(url);
}
