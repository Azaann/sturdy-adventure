<?php 

// Maakt een koppeling naar de aangegeven bestand. Bij deze word het gebruikt voor de database connectie en de bijhorende functies
include 'database.php';

$db = new database();

$categorien = $db->select("SELECT * FROM gerechtcategorien",[]);

$columns = array_keys($categorien[0]);
$row_data = array_values($categorien);
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
	<?php include 'navigatie.php' ?>

	<h1>Menukaart</h1>

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

					<th><?php echo $data_soorten["Naam"] ?></th>
					<tr>
						<?php foreach($row_data_menuitems AS $data_menuitems) {?>
							<tr><td><?php echo $data_menuitems["Naam"]." €<strong>".$data_menuitems['Prijs']."</strong>"?></td>
						<?php } ?>
					</tr>
				<?php } ?>
			</tr>			
		</table>
	<?php } ?>
</body>
</html>