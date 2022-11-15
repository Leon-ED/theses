# Projet Thèses
Les fichiers
<code>credentials.php , .htpasswd et .htaccess </code>ne sont pas push sur le Git<br>
URL : https://perso-etudiant.u-pem.fr/~leon.edmee/theses/<br>
LOGIN : dans le sujet<br>
PASSWORD : dans le sujet<br>

Fonctionnalités : <br>
- Recherche par le titre(exact), auteur (nom,prenom, prenom nom, nom prenom, discipline (exacte) et le nnt<br>
- Dashboard qui affiche le nombre de thèses, auteur,directeur et établissement au total ou selon la recherche<br>
- Bouton "J'ai de la chance" qui affiche 10 thèses aléatoires (possibilité de changer le nombre dans la barre URL (max 100 thèses))<br>
- Design Responsive

<br>
Pour utiliser le projet :
créer un fichier <code>credentials.php</code> ou utiliser le fichier template (à renommer) dans le dossier <code>config</code>
comme ceci :<br>

<code>
$servername = "URL_SERVEUR";<br>
$username = "NOM_UTILISATEUR";<br>
$password = "MOT_DE_PASSE";<br>
$dbname = "NOM_BASE_DE_DONNES";<br>
</code>
<br>
Testé en PHP 7.0.33 et 8.1.10
