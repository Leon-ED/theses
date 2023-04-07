<?php
$file = "index.php";
require_once("./config/config.php");
require_once("./include/html.header.inc.php");
require("./include/alertes.inc.php");
$sql = "SELECT * FROM compte WHERE id = :id";
$req = $conn->prepare($sql);
$req->execute(array(":id" => $_SESSION['id']));
$result = $req->fetch(PDO::FETCH_ASSOC);

?>

<body>
    <?php require_once("./include/nav.inc.php") ?>
    <main class="p-5">
        <h1>Mon compte</h1>
        <p>
            Bienvenue sur votre compte, <?php echo $result['login'] ?><br>
            Votre email : <?php echo $result['email'] ?><br>
            Votre login : <?php echo $result['login'] ?>
        </p>
        <hr>
        <details>
            <summary>
                <h2>Changer mon mot de passe</h2>
                <hr>
            </summary>
            <small>
                Le mot de passe doit contenir au moins 12 caractères, dont une majuscule, une minuscule et un chiffre.
            </small>
            <form action="controller/authController.php" method="POST" class="authForm">
                <div class="form-container">
                    <label for="old_password">Ancien mot de passe</label>
                    <input type="password" name="old_password" id="old_password" required>
                </div>
                <div class="form-container">
                    <label for="new_password">Nouveau mot de passe</label>
                    <input type="password" name="new_password" id="new_password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{12,36}">
                </div>

                </div>
                <div class="form-container">
                    <label for="new_password_confirm">Confirmation du nouveau mot de passe</label>
                    <input type="password" name="new_password_confirm" id="new_password_confirm" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{12,36}">
                </div>
                </div>
                <input type="hidden" name="action" value="changePassword">
                <div class="form-container">
                    <input type="submit" value="Changer de mot de passe">
                </div>
            </form>
        </details>
        <details>
            <summary>
                <h2>Changer mon courriel<h2>
                        <hr>
            </summary>
            <form action="controller/authController.php" method="POST" class="authForm">
                <div class="form-container">
                    <label for="old_password">Mot de passe</label>
                    <input type="password" name="old_password" id="old_password" required>
                </div>
                <div class="form-container">
                    <label for="new_email">Nouveau courriel</label>
                    <input type="email" name="new_email" id="new_email">
                </div>

                </div>
                <div class="form-container">
                    <label for="email_confirm">Confirmation du nouveau courriel</label>
                    <input type="email" name="email_confirm" id="email_confirm" required>
                </div>
                </div>
                <input type="hidden" name="action" value="changeEmail">
                <div class="form-container">
                    <input type="submit" value="Changer mon courriel">
                </div>
            </form>

        </details>
        <details>
            <summary>
                <h2>Changer mon nom d'utilisateur</h2>
                <hr>
            </summary>
            <form action="controller/authController.php" method="POST" class="authForm">
                <div class="form-container">
                    <label for="old_password">Mot de passe</label>
                    <input type="password" name="old_password" id="old_password" required>
                </div>
                <div class="form-container">
                    <label for="new_login">Nouveau login</label>
                    <input type="text" name="new_login" id="new_login">
                </div>

                </div>
                <div class="form-container">
                    <label for="login_confirm">Confirmation du nouveau login </label>
                    <input type="text" name="login_confirm" id="login_confirm" required>
                </div>
                </div>
                <input type="hidden" name="action" value="changeLogin">
                <div class="form-container">
                    <input type="submit" value="Changer mon login">
                </div>
            </form>
        </details>
        <details>
            <summary>
                <h2>Gérer mes alertes</h2>
                <?php
                if (isset($_GET["error"]) && $_GET["error"] == 2) {
                    echo "<p style='color:red'>Les alertes par email ne sont pas disponibles.</p>";
                }
                ?>
                <hr>
            </summary>
            <h3>Ajouter une alerte</h3>
            <form action="controller/alertesController.php" method="POST" class="authForm">
                <div class="form-container">
                    <label for="motCle">Mot clé</label>
                    <input type="text" name="motCle" id="motCle">
                </div>
                <div class="form-container">
                    <input type="hidden" name="action" value="add">
                    <input type="submit" value="Créer une alerte">
                </div>
            </form>
            <h3>M'envoyer les thèses en fonction des alertes</h3>
            <form action="controller/alertesController.php" method="POST">
                <input type="hidden" name="action" value="send">
                <input type="submit" value="Envoyer les thèses">

            </form>
            <h3>Supprimer une alerte</h3>
            <div class="alertes">
                <?php
                afficherAlertes($alertes);
                ?>
            </div>
        </details>
        <details class="pt-5">
            <summary>
                <h2 style="color:red">Supprimer mon compte</h2>
                <hr>
            </summary>
            <!-- Formulaire suppression compte -->
            <form action="controller/authController.php" method="POST" class="authForm">
                <div class="form-container">
                    <label for="old_password">Mot de passe</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <input type="hidden" name="action" value="deleteAccount">
                <div class="form-container">
                    <input type="submit" value="Supprimer mon compte">
                </div>
        </details>
    </main>
</body>

</html>