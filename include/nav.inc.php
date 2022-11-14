<!--Teamplate de la barre de navigation-->
<?php
if (isset($file) && $file != "index.php") { // Si on est pas sur la page d'accueil (index.html) on affiche en plus une barre de recherche
    ?>
    <nav class="nav-bar">
        <a href="./index.php">
            <h3>Theses.fr</h3>
        </a>
        <div class="d-flex w-20 align-items-center justify-content-space-evenly">
            <div class="d-flex">
                <?php
                if (isset($_SESSION['user'])) {  // Si on est connecté on affiche la page du profil et de déconnexion
                    echo '<a href="./profile.php" class="nav-link">Profil</a>';
                    echo '<a href="./logout.php" class="nav-link">Deconnexion</a>';
                } else {
                    echo '<a href="./login.php" class="nav-link">Connexion</a>';
                    echo '<a href="./login.php" class="nav-link">Inscription</a>';
                }
                ?>
            </div>
            <form method="GET" action="./search.php">
                <input type="text" name="search" placeholder="Rechercher">
                <input type="submit" value="Rechercher">
            </form>
        </div>
    </nav>

    <?php
} else {
?>
    <nav class="nav-bar">
        <a href="./index.php">
            <h3>Theses.fr</h3>
        </a>
        <div class="d-flex w-20 align-items-center justify-content-space-evenly">
            <div class="d-flex">
                <?php
                if (isset($_SESSION['user'])) { // Si on est connecté on affiche la page du profil et de déconnexion
                    echo '<a href="./profile.php" class="nav-link">Profil</a>';
                    echo '<a href="./logout.php" class="nav-link">Deconnexion</a>';
                } else {
                    echo '<a href="./login.php" class="nav-link">Connexion</a>';
                    echo '<a href="./login.php" class="nav-link">Inscription</a>';
                }
                ?>
            </div>
        </div>
    </nav>


    <?php
}
