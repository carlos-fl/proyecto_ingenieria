import { Request } from "./modules/request.mjs";
import { disableBtn, showModal, showFailPopUp, changeBorder, showPopUp, showFailModal } from "./modules/utlis.mjs";
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
        showPopUp("Ingrese un número de solicitud válido")
        searchValue.value = ''
        return;
      }

      try {
        const ENDPOINT = "/api/auth/controllers/applicantAuth.php"
        const body = { applicantCode: searchValue.value.trim() }
        const examResults = JSON.parse(await Request.fetch(ENDPOINT, 'POST', body));
        if (examResults.status === "failure") {
          showFailPopUp("fail-results-data", "Fallo al buscar datos")
          return;
        }

        loadResults(examResults);
        showModal('results')

      } catch(err) {
        showPopUp(err.message)
      }

      searchValue.value = ''
    });
  }

await searchForm()