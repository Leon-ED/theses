<?php
require_once("./config/config.php");
require_once("./include/html.header.inc.php");
$erreur = "";
if (isset($_GET['error'])) {
    $erreur = "Une erreur est survenue lors de l'inscription, un compte avec ce nom d'utilisateur ou cette adresse courriel existe peut-être  déjà";
}
?>

<body>
    <?php require_once("./include/nav.inc.php") ?>
    <h1 class="text-center" style="margin-top:1%;">S'inscrire</h1>
    <p class="text-center" style="margin-top:1%;"><?php echo $erreur ?></p>

    <form action="controller/authController.php" method="POST" class="authForm">
        <div class="form-container">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="login" id="login" required>
        </div>
        <div class="form-container">
            <label for="username">Courriel</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-container">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{12,36}">
        </div>
        <small>
            Le mot de passe doit contenir au moins 12 caractères,<br> dont une majuscule, une minuscule et un chiffre.
        </small>
        </div>
        <div class="form-container">
            <label for="password">Confirmation du mot de passe</label>
            <input type="password" name="password_confirm" id="password_confirm" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{12,36}">
        </div>
        </div>
        <input type="hidden" name="action" value="register">
        <div class="form-container">
            <input type="submit" value="S'inscrire">
        </div>
    </form>
</body>