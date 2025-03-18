//Para manejar la carga del archivo:
document.getElementById("csvFile").addEventListener("change", function (e) {
  const file = e.target.files[0];
  console.log("Archivo seleccionado:", file);
  // Aquí podrías implementar la validación del CSV...
});
