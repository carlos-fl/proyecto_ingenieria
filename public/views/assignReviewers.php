<?php
    session_start();
    if (empty($_SESSION)) {
        header('Location: loginRevisorSolicitudesAd.php');
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignacion de Revisores</title>
    <link rel="icon" type="image/png" href="assets/img/UNAH-escudo.png">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/userProfileComponent.css">
    <link rel="stylesheet" href="css/assignReviewers.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/6130fb0810.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <navbar-unah logo="assets/img/logo-unah.png"
            home-href="index.php"
            estudiantes-href="loginEstudiantes.php"
            docentes-href="loginDocentes.php"
            matricula-href="login.php"
            admisiones-href="admisiones.php"
            biblioteca-href="LoginEstudiantes.php"></navbar-unah>
        <div class="top-bar">
            <div>
                <nav class="navbar bg-body-tertiary" id="header-bar">
                    <div class="container-fluid">
                        <h1 class="navbar-brand">
                            Asignacion de Revisores
                        </h1>
                    </div>
                </nav>
            </div>
    </header>
    <main>
        <div class="container bg-white"> 
            <user-profile
                profile-title="Asignacion de Revisores"
                profile-img="assets/img/default-profile.png"
                welcome-msg="Bienvenido Administrador"
                user-number="<?php echo $_SESSION['DNI'] ?>"
                user-name="<?php echo $_SESSION['FIRST_NAME'] . " " . $_SESSION["LAST_NAME"] ?>"
                user-phone="<?php echo $_SESSION['PHONE'] ?>"
                user-email="<?php echo $_SESSION['INST_EMAIL'] ?>">
            </user-profile>
            <div class="container center-container">
                <div class="upload-wrapper">
                    <label for="csvFile" class="upload-btn">Asignar Revisores</label>
                    <input type="file" id="csvFile" name="csvFile" accept=".csv">
                </div>
            </div>
            <log-out id="logOut"></log-out>
        </div>
    </main>
    <footer-unah></footer-unah>

    <!-- Ventana modal -->
    <div class="modal fade" id="reviewerModal" tabindex="-1" aria-labelledby="reviewerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewerModalLabel">Revisores</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center">
                    <div class="btn-group" role="group" aria-label="Select reviewer type">
                        <a href="loginRevisorSolicitudesAd.php"><button type="button" class="btn btn-warning mx-3" id="admissionReviewer">Revisor de Solicitud de Admision</button></a>
                        <a href="loginRevisorExamenes.php"><button type="button" class="btn btn-warning mx-3" id="examReviewer">Revisor de Examen de Admision</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="js/components/navbar.js"></script>
    <script src="js/components/footer.js"></script>
    <script src="js/components/log-out.js"></script>
    <script src="js/components/userProfile.js"></script>
    <script src="js/assingReviewers.js"></script>

</body>

</html>