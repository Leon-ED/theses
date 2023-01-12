<?php
error_reporting(E_ALL);

$file = "login.php";
require_once("config/config.php");
require_once("include/html.header.inc.php");
include_once("controller/authController.php");
try {

    $register = false;
    if (isset($_GET['register'])) {
        $register = true;
    }

    if (isset($_POST['email']) && isset($_POST['password'])) {

        if (isset($_POST['password_confirm'])) {
        } else {
        }
    }



?>

    <main class="auth-main">
        <section id="login" <?php if ($register) : echo 'style="display:none;"';
                            endif; ?>>
            <h1>Se connecter à votre espace</h1>
            <form class="auth-form">
                <input type="email" name="email" placeholder="email@exemple.fr" required>
                <input type="password" name="password" placeholder="Mot de passe" required minlength="<?= AuthController::MIN_PASSWORD_LENGTH ?>" maxlength="<?= AuthController::MAX_PASSWORD_LENGTH ?>">
                <input type="submit" value="CONNEXION">
            </form>
        </section>
        <section id="register" <?php if (!$register) : echo 'style="display:none;"';
                                endif; ?>>
            <h1>Créer votre espace</h1>
            <form class="auth-form">
                <input type="text" name="login" placeholder="Nom d'utilisateur" required>
                <input type="password" name="password" placeholder="Mot de passe" required minlength="<?= AuthController::MIN_PASSWORD_LENGTH ?>" maxlength="<?= AuthController::MAX_PASSWORD_LENGTH ?>">
                <input type="password" name="password_confirm" placeholder="Mot de passe" required minlength="<?= AuthController::MIN_PASSWORD_LENGTH ?>" maxlength="<?= AuthController::MAX_PASSWORD_LENGTH ?>">
                <input type="submit" value="Créer un compte">
            </form>
        </section>
    </main>
<?php
} catch (Exception $e) {
    echo $e->getMessage();
}
