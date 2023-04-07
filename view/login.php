<?php
require_once("./config/config.php");
require_once("./include/html.header.inc.php");
$erreur = "";
if (isset($_GET['error'])) {
    $erreur = "Les identifiants sont incorrects ou le compte n'existe pas";
}
?>

<body>
    <?php require_once("./include/nav.inc.php") ?>
    <h1 class="text-center" style="margin-top:1%;">Se connecter</h1>
    <p class="text-center" style="margin-top:1%;"><?php echo $erreur ?></p>
    <form action="controller/authController.php" method="POST" class="authForm">
        <div class="form-container">
            <label for="login">Nom d'utilisateur</label>
            <input type="text" name="login" id="login" required>
        </div>
        <div class="form-container">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>
        <input type="hidden" name="action" value="login">
        </div>
        <div class="form-container">
            <input type="submit" value="Se connecter">
        </div>





    </form>





</body>