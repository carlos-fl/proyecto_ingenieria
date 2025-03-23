document.getElementById("csvFile").addEventListener("change", function (event) {
  const file = event.target.files[0]; // Obtener el archivo
  if (!file) {
    return;
  }

  const reader = new FileReader();

  reader.onload = function (e) {
    // TextDecoder para decodificar el archivo con ISO-8859-1 para evitar simbolos como �
    const content = new TextDecoder("ISO-8859-1").decode(e.target.result);

    // Dividir el contenido en líneas
    const lines = content.split(/\r\n|\n/);

    if (lines.length === 0 || lines[0].trim() === "") {
      alert("El archivo CSV parece estar vacío o tiene un formato incorrecto.");
      return;
    }

    // Cambiar el separador de columnas a ";"
    const headers = lines[0].split(";").map((h) => h.trim());

    // Encabezados esperados
    const expectedHeaders = [
      "ID_Candidato",
      "DNI",
      "Nombre",
      "Apellido",
      "Fecha de nacimiento",
      "Correo",
      "Teléfono",
    ];

    // Comparar los encabezados del archivo con los esperados
    if (JSON.stringify(headers) !== JSON.stringify(expectedHeaders)) {
      alert(
        "Formato incorrecto. Asegúrate de incluir las columnas: " +
          expectedHeaders.join(", ")
      );
      event.target.value = ""; // Limpiar el input
    } else {
      alert("Archivo válido. Procediendo con la carga...");
    }
  };

  reader.onerror = function () {
    alert("Error al leer el archivo.");
  };

  // Leer el archivo como ArrayBuffer para usar TextDecoder
  reader.readAsArrayBuffer(file);
});
