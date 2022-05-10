<?php

// Maakt een koppeling naar de aangegeven bestand. Bij deze word het gebruikt voor de database connectie en de bijhorende functies- 
include 'database.php';

// Word gebruikt voor de database connectie, zonder dit kan je geen data ophalen uit de database
$db = new database();

// Select statement om data te verzamelen uit de database
$categorien = $db->select("SELECT * FROM gerechtcategorien",[]);
$columns = array_keys($categorien[0]);
$row_data = array_values($categorien);

// Code word uitegevoerd wanneer er word geklikt op de form button, de button moet wel een name en een type "submit" bevatten
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {

  // Stopt de form gegevens in variablelen
  $soort_id = htmlspecialchars(trim($_POST['soort_id']));

  // Na het defineren van de variablelen, de variableen sturen naar de functie

  // Delete functie met behulp van de aangewezen variabelen
  $db->select("DELETE FROM gerechtsoorten WHERE ID = :soort_id",[':soort_id' => $soort_id]);

  // Pagina word herstart om zo de wijzigingen te laten zien, daarvoor krijgt de user een message te zien
  header("refresh:2;");
  echo "Drank succesfoll verwijderd";
}

// Code word uitegevoerd wanneer er word geklikt op de form button, de button moet wel een name en een type "submit" bevatten
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {

  // Stopt de form gegevens in variablelen
  $code = htmlspecialchars(trim($_POST['code']));
  $naam = htmlspecialchars(trim($_POST['naam']));
  $soort_id = htmlspecialchars(trim($_POST['categorie_id']));
  
  // Na het defineren van de variablelen, de variableen sturen naar de functie
  $db->add_subcategorie($code, $naam, $soort_id);
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="style.css">
  <title>Kok omgeving</title>
</head>
<body>
  <!-- Kopieert de navigatie en stopt deze in de bestand -->
  <?php include 'navigatie.php' ?>

  <h1>Menukaart</h1>
  <p>* Kan subcategorien kunnen alleen verwijderd worden als de menuitems leeg zijn</p>

  <?php foreach ($row_data AS $data) { ?>

    <?php 
    $categorie_id = $data['ID'];

    $soorten = $db->select("SELECT * FROM gerechtsoorten WHERE Gerechtcategorie_ID = :categorie_id",[':categorie_id' => $categorie_id]);
    $row_data_soorten = array_values($soorten);

    
    ?>

    <h3><?php echo $data["Naam"]?></h3>

    <table style="text-align: left;">
      <tr>
        <?php foreach($row_data_soorten AS $data_soorten) {?>
          <?php 
            $soorten_id = $data_soorten['ID'];
            $menuitems = $db->select("SELECT * FROM menuitems WHERE Gerechtsoort_ID = :soorten_id",[':soorten_id' => $soorten_id]);
            $row_data_menuitems = array_values($menuitems);
          ?>
          <form method="post">
            <input type="hidden" name="soort_id" value="<?php echo $data_soorten['ID']; ?>">
            <th><?php echo $data_soorten["Naam"] ?></th>
            <th><button type="sumbit" name="delete">Delete</button></th>
          </form>
          <tr>
          <?php foreach($row_data_menuitems AS $data_menuitems) {?>
            <tr><td><?php echo $data_menuitems["Naam"]." €<strong>".$data_menuitems['Prijs']."</strong>"?></td>
          <?php } ?>
          </tr>
        <?php } ?>
      </tr>
    </table>
  <?php } ?>

  <form method="post">
    <h1>Voeg Sub categorien toe</h1>

    <h3>Voeg toe in</h3>
    <select required name="categorie_id">
      <option></option>
      <?php foreach($row_data AS $data) {?>
        <option value="<?php echo $data["ID"]?>"><?php echo $data["Naam"] ?></option>
      <?php } ?>
    </select>

    <h3>Code</h3>
    <input type="text" name="code">

    <h3>Naam</h3>
    <input type="text" name="naam">

    <button type="submit" name="add">Voeg toe</button>
  </form>
</body>
</html>