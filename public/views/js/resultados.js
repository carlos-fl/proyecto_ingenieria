import { Request } from "./modules/request.mjs";
import { disableBtn, showFailPopUp, changeBorder, showPopUp } from "./modules/utlis.mjs";
import { validApplicantCode } from "./modules/validator.mjs";

// Función para cargar y mostrar resultados; se utiliza el arreglo filtrado (si existe)
function loadResults(filteredResults) {
    const resultsTableBody = document.getElementById('resultsTableBody');
    resultsTableBody.innerHTML = "";
    let resultsToShow = filteredResults || [];
    if (resultsToShow.length > 0) {
      resultsToShow.forEach(result => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${result.examTitle}</td>
          <td>${result.examDate}</td>
          <td>${result.nota}</td>
          <td>${result.numSolicitud}</td>
        `;
        resultsTableBody.appendChild(row);
      });
    } else {
      resultsTableBody.innerHTML = '<tr><td colspan="4">No se han subido resultados.</td></tr>';
    }
  }
  
  // Manejo del formulario de búsqueda por número de solicitud
  async function searchForm() {
    const btn = document.getElementById('result-btn')
    btn.addEventListener('click', async function(e) {
      e.preventDefault();
      disableBtn(btn, 1800)
      const searchValue = document.getElementById('numSolicitud');

      if (!validApplicantCode(searchValue.value)) {
        changeBorder(searchValue, 'var(--bs-border-width)', "red")
        showPopUp("algo")
        console.log('no valido')
        return;
      }

      const resultsTableBody = document.getElementById('tb');
      // Si el campo está vacío, se limpia la tabla
      if (searchValue.value.trim() === "") {
        resultsTableBody.innerHTML = "";
        return;
      }
      try {
        const ENDPOINT = "/api/auth/controllers/applicantAuth.php"
        const body = { applicantCode: searchValue.value.trim() }
        const examResults = JSON.parse(await Request.fetch(ENDPOINT, 'POST', JSON.stringify(body)));
        if (examResults.success === "failure") {
          console.log('error en fetch')
          showFailPopUp("fail-results-data", "Fallo al buscar datos")
        }

        loadResults(examResults);

        console.log('llega aqui')
      } catch(err) {
        const modalError = document.getElementById('fail-results')
        console.log(modalError)
        modalError.style.display = 'block'
        console.log('llega aqui error')
      }

      searchValue.value = ''
    });
  }

await searchForm()