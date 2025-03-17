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
  document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const searchValue = document.getElementById('numSolicitud').value.trim();
    const resultsTableBody = document.getElementById('resultsTableBody');
    // Si el campo está vacío, se limpia la tabla
    if (searchValue === "") {
      resultsTableBody.innerHTML = "";
      return;
    }
    const examResults = JSON.parse(localStorage.getItem('examResults')) || [];
    const filteredResults = examResults.filter(result => result.numSolicitud === searchValue);
    loadResults(filteredResults);
  });