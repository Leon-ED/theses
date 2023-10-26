# Projet Thèses VIZ
Les fichiers
<code>credentials.php , .htpasswd et .htaccess </code>ne sont pas push sur le Git<br>
URL : https://theses.edmeeleon.fr/<br>
LOGIN : dans le sujet<br>
PASSWORD : dans le sujet<br>

Fonctionnalités : <br>
- Recherche (min 3 caractères) : par le titre,resume, auteur, discipline (exacte) et le nnt, date (forme aaaa-mm-jj),<br>
- Dashboard qui affiche le nombre de thèses, auteur,directeur et établissement au total ou selon la recherche<br>
- Bouton "J'ai de la chance" qui affiche 10 thèses aléatoires (possibilité de changer le nombre dans la barre URL (max 100 thèses))<br>
- Design Responsive
- NOUVEAU :
- Possibilité de créer un compte
- Possibilité de se connecter
- Possibilité de modifier son profil
- Possibilité de supprimer son compte
- Possibilité d'ajouter, supprimer, voir ses alertes
- Possibilité d'envoyer les thèses selon nos alertes par mail
- Graphiques généraux ET selon la recherche

<br>
Pour utiliser le projet :
Faire : <code>git clone https://github.com/Leon-ED/theses</code><br>
Puis : <code>cd theses</code><br>
Puis : <code>git submodule update --init --recursive</code> Afin que le script d'import fonctionne<br>
créer un fichier <code>credentials.php</code> ou utiliser le fichier template (à renommer) dans le dossier <code>config</code>
comme ceci :<br>
Script pour importer les données : <code>script/script_import.php</code>, fichier pour les thèses : <code>fichiers/extract_theses.json</code> <br>
Dump de la base (structure et structure + données): <code>bdd/ </code><br>
<code>
$servername = "URL_SERVEUR";<br>
$username = "NOM_UTILISATEUR";<br>
$password = "MOT_DE_PASSE";<br>
$dbname = "NOM_BASE_DE_DONNES";<br>
$password_mail = "MOT_DE_PASSE_MAIL";<br>
</code>
<br>
Testé en PHP 7.0.33 et 8.1.10
