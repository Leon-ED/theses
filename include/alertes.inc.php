<?php
$sql = "SELECT * FROM alertes WHERE idCompte = :idCompte";
$stmt = $conn->prepare($sql);
$stmt->execute(array(":idCompte" => $_SESSION['id']));
$alertes = $stmt->fetchAll(PDO::FETCH_ASSOC);

function afficherAlertes($alertes)
{
    if(count($alertes) == 0) {
        echo "<p>Vous n'avez pas d'alertes... pour le moment.</p>";
    }
    foreach ($alertes as $alerte) {
        $motCle = $alerte['motCle'];
        $id = $alerte['id'];
        ?>
       <div class="alerte">
        <strong class="alerte_mot_cle"><?php echo $motCle ?></strong>
        <a href="./controller/alertesController.php?alert_id=<?php echo $id ?>" class="alerte_delete">Supprimer</a>
       </div>
        <?php



    }
}