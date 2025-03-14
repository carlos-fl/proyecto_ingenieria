const editButton = document.getElementById("editButton");
const saveButton = document.getElementById("saveButton");
const cancelButton = document.getElementById("cancelButton");

editButton.addEventListener("click", function () {
    let nameSpan = document.getElementById("name");
    let phoneSpan = document.getElementById("phone");

    // Guardar los valores actuales para cancelar si es necesario
    let currentName = nameSpan.innerText;
    let currentPhone = phoneSpan.innerText;

    // Reemplazar los spans con inputs
    nameSpan.innerHTML = `<input type="text" id="nameInput" value="${currentName}">`;
    phoneSpan.innerHTML = `<input type="text" id="phoneInput" value="${currentPhone}">`;

    // Mostrar botones de guardar y cancelar
    saveButton.style.display = "block";
    cancelButton.style.display = "block";

    // Agregar evento para cancelar edición
    cancelButton.onclick = function () {
        nameSpan.innerText = currentName;
        phoneSpan.innerText = currentPhone;
        saveButton.style.display = "none";
        cancelButton.style.display = "none";
    };
});

saveButton.addEventListener("click", function () {
    let nameInput = document.getElementById("nameInput");
    let phoneInput = document.getElementById("phoneInput");

    // Guardar los valores editados
    let newName = nameInput.value;
    let newPhone = phoneInput.value;

    // Reemplazar los inputs con los nuevos valores
    document.getElementById("name").innerText = newName;
    document.getElementById("phone").innerText = newPhone;

    // Ocultar botones de guardar y cancelar
    saveButton.style.display = "none";
    cancelButton.style.display = "none";
});

// Abre la modal y muestra el código de la clase seleccionado
function openModal(codigoClase) {
    document.getElementById('claseCodigo').textContent = codigoClase;
    // Aquí se podría actualizar la lista de alumnos según la clase.
    var alumnosModal = new bootstrap.Modal(document.getElementById('alumnosModal'));
    alumnosModal.show();
  }

  // Función para exportar la tabla de alumnos a CSV (simulación de descarga Excel)
  function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("#tablaAlumnos tr");
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        for (var j = 0; j < cols.length; j++) {
            row.push('"' + cols[j].innerText + '"');
        }
        csv.push(row.join(","));
    }

    // Crea un Blob y genera el enlace de descarga
    var csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
    var downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
  }

  // Variable global para almacenar la URL actual del video
  let currentVideoUrl = "";

  function uploadVideo() {
    const url = document.getElementById("videoUrl").value.trim();
    if (url) {
      currentVideoUrl = url;
      document.getElementById("videoIframe").src = currentVideoUrl;
      document.getElementById("videoPreview").style.display = "block";
      alert("Video subido exitosamente");
    } else {
      alert("Por favor ingresa una URL válida");
    }
  }

  function updateVideo() {
    if (!currentVideoUrl) {
      alert("No hay un video subido para actualizar. Usa 'Subir Video' primero.");
      return;
    }
    const url = document.getElementById("videoUrl").value.trim();
    if (url) {
      currentVideoUrl = url;
      document.getElementById("videoIframe").src = currentVideoUrl;
      alert("Video actualizado exitosamente");
    } else {
      alert("Por favor ingresa una URL válida para actualizar");
    }
  }

  function deleteVideo() {
    if (!currentVideoUrl) {
      alert("No hay video para borrar");
      return;
    }
    currentVideoUrl = "";
    document.getElementById("videoUrl").value = "";
    document.getElementById("videoIframe").src = "";
    document.getElementById("videoPreview").style.display = "none";
    alert("Video borrado exitosamente");
  }

  function uploadCSV() {
    const fileInput = document.getElementById("csvFile");
    if (fileInput.files.length === 0) {
      alert("Por favor selecciona un archivo CSV");
      return;
    }
    // Simulación de subida de CSV
    alert("Archivo CSV subido exitosamente");
    fileInput.value = "";
  }