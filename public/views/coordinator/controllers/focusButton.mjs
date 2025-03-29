/**
 * 
 * @param {HTMLButtonElement} btn 
 */
export function changeFocusBtn(btn) {
  const btns = document.getElementsByClassName('b')
  Array.from(btns).forEach(button => {
    button.classList.remove('b-focus');
  })

  btn.classList.add('b-focus');
}