//Para manejar la carga del archivo:
import { showPopUp } from "./modules/utlis.mjs"

function hasCorrectHeader(csvArray){
  return csvArray[0].toString().trim() === ["APPLICATION_CODE", "TIPO_EXAMEN", "CALIFICATION"].toString().trim()
}

function hasCorrectValues(csvArray){
  let values = csvArray.slice(1, csvArray.length)
  for (let register of values){
    if (register.length != 3 || isNaN(register[2])){
      return false
    }
  }
  return true
}

document.getElementById("csvFile").addEventListener("change", function (e) {
  const file = e.target.files[0];
  console.log(file)
  file.text()
  .then(fileText => {
    let csvLines = fileText.split("\n").map(lines => lines.split(","))
    return csvLines
  })
  .then(csvLines => {
    // Revisar que el formato del csv sea el correcto
    if (!hasCorrectHeader(csvLines)){
      showPopUp("Headers del CSV son incorrectos")
      return
    }
    if (!hasCorrectValues(csvLines)){
      showPopUp("Verifique datos en el csv")
      return
    }
    return csvLines
  })
  .then(csvLines => {
    if (!csvLines) return 
    // Realizar petición al backend subiendo el archivo
    let formData = new FormData()
    formData.append("file", file)
    fetch("/api/reviewers/controllers/reviewersExams.php", {
      method: "POST",
      headers: {
        "Content-Type": "multipart/form-data"
      },
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      // TODO: Mostar que se subieron las notas adecuadamente
      // TODO: Mostar que no se pudieron subir las notas
    })
    .catch(error => {
      showPopUp("Hubo un error con el servidor")
    })
  })
});
