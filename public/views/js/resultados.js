import { Request } from "./modules/request.mjs";
import { disableBtn, showModal, changeBorder, showPopUp, showLoadingComponent, hideLoadingComponent } from "./modules/utlis.mjs";
import { validApplicantCode } from "./modules/validator.mjs";

// Función para cargar y mostrar resultados; se utiliza el arreglo filtrado (si existe)
function loadResults(filteredResults) {
    const resultsTableBody = document.getElementById('table-body-results');
    resultsTableBody.innerHTML = "";
    let resultsToShow = filteredResults || [];
    if (resultsToShow.data.length > 0) {
      resultsToShow.data.forEach(result => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${result.EXAM_NAME}</td>
          <td>${result.CREATED_AT}</td>
          <td>${result.CALIFICATION}</td>
          <td>${result.APPLICATION_CODE}</td>
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
        showLoadingComponent('loading')
        const ENDPOINT = "/api/auth/controllers/applicantAuth.php"
        const body = { applicantCode: searchValue.value.trim() }
        const examResults = await Request.fetch(ENDPOINT, 'POST', body);
        hideLoadingComponent('loading')
        if (examResults.status === "failure") {
          showPopUp("No se encontraron Resultados")
          return;
        }

        loadResults(examResults);
        showModal('d-modal')

      } catch(err) {
        showPopUp("No se encontraron Resultados")
        console.log(err)
      }

      searchValue.value = ''
    });
  }

await searchForm()
