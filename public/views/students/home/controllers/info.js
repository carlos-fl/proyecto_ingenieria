// Función para actualizar informacion del perfil
function updateInfo() {
  var name = document.getElementById("editName").value;
  var email = document.getElementById("editEmail").value;
  var phone = document.getElementById("editPhone").value;

  document.getElementById("name").innerText = name;
  document.getElementById("email").innerText = email;
  document.getElementById("phone").innerText = phone;

  var editModal = bootstrap.Modal.getInstance(
    document.getElementById("editInfoModal")
  );
  editModal.hide();
}

document.getElementById("uploadBtn").addEventListener("click", function () {
  document.getElementById("fileInput").click();
});

// Función para actualizar la imagen de perfil con la imagen seleccionada
document
  .getElementById("fileInput")
  .addEventListener("change", function (event) {
    var file = event.target.files[0];
    if (file) {
      var reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById("profileImg").src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
