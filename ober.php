<?php 

// Maakt een koppeling naar de aangegeven bestand. Bij deze word het gebruikt voor de database connectie en de bijhorende functies
include 'database.php';

$db = new database();

$overzicht_barman = $db->select("SELECT bestellingen.ID AS bestelid, reserveringen.Tafel, bestellingen.Aantal, menuitems.Naam, bestellingen.Gereserveerd, bestellingen.Klaar FROM `bestellingen` INNER JOIN reserveringen ON bestellingen.Reservering_ID = reserveringen.ID INNER JOIN menuitems ON bestellingen.Menuitem_ID = menuitems.ID INNER JOIN gerechtsoorten ON menuitems.Gerechtsoort_ID = gerechtsoorten.ID INNER JOIN gerechtcategorien ON gerechtsoorten.Gerechtcategorie_ID = gerechtcategorien.ID WHERE bestellingen.Klaar = 1 AND bestellingen.Gereserveerd = 0",[]);

if (!empty($overzicht_barman)) {
	$columns = array_keys($overzicht_barman[0]);
	$row_data = array_values($overzicht_barman);
}

// Code word uitegevoerd wanneer er word geklikt op de form button, de button moet wel een name en een type "submit" bevatten
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['gereed'])) {

	$bestelid = htmlspecialchars(trim($_POST['bestelid']));
	$db->select("UPDATE `bestellingen` SET `Gereserveerd` = '1' WHERE ID = :bestelid",[':bestelid' => $bestelid]);

	header("refresh:2;");
	echo "Menuitem succesfoll gereserveerd";
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Ober omgeving</title>
</head>
<body>

	<!-- Kopieert de navigatie en stopt deze in de bestand -->
	<?php include 'navigatie.php'; ?>

	<h1>Overzicht voor de Ober</h1>

	<!-- Als er geen data word gevonden word het niet uitgevoerd -->
	<?php if (!empty($overzicht_barman)) { ?>
		<table>
			<thead>
				<tr>
					<th>Tafel</th>
					<th>Aantal</th>
					<th>Gerecht</th>
					<th>Gereed</th>
				</tr>
			</thead>
			<tbody>
				<!-- Kopieert de navigatie en stopt deze in de bestand -->
				<?php foreach ($row_data AS $data) { ?>
					<tr>
						<td><?php echo $data["Tafel"]?></td>
						<td><?php echo $data["Aantal"]?></td>
						<td><?php echo $data["Naam"]?></td>
						<?php if ($data["Gereserveerd"] == 0) { ?>
							<form method="post">
								<input type="hidden" name="bestelid" value="<?php echo $data['bestelid'] ?>">
								<td><button type="sumbit" name="gereed">Ja</button><td>
							</form>
						<?php }?>
					</tr>
				<?php } ?>		
			</tbody>
		</table>
	<?php }else{
		echo "<h3>Gerchten zijn nog niet klaar</h3>";
	} ?>
</body>
</html>