import { loginFailHandler, showPopUp } from "./modules/utlis.mjs";

function loginSuccessHandler() {
  let loginForm = document.querySelector("login-form");

  loginForm.addEventListener("login-form:success", (event) => {
    let data = event.detail.data;
    let roles = data.roles;

    if (roles.includes("STUDENTS")) {
      localStorage.setItem("idStudent", data.idStudent);
      localStorage.setItem("studentAccountNumber", data.studentAccountNumber);
    }

    if (
      roles.includes("TEACHERS") ||
      roles.includes("COORDINATOR") ||
      roles.includes("DEPARTMENT_CHAIR")
    ) {
      localStorage.setItem("teacherNumber", data.teacherNumber);
    }

    if (roles.includes("STUDENTS")) {
      window.location.href = "../../library/home/index.php";
    } else if (
      roles.includes("COORDINATOR") ||
      roles.includes("DEPARTMENT_CHAIR")
    ) {
      window.location.href = "../../library/home/manageBooks.php";
    } else if (roles.includes("TEACHERS")) {
      window.location.href = "../../library/home/index.php";
    } else {
      showPopUp("Acceso Denegado");
    }
  });
}

function main() {
  loginFailHandler();
  loginSuccessHandler();
}

main();
