<?php 

// Maakt een koppeling naar de aangegeven bestand. Bij deze word het gebruikt voor de database connectie en de bijhorende functies
include 'database.php';

$db = new database();

$reserveringen = $db->select("SELECT * FROM reserveringen WHERE ID IN (SELECT Reservering_ID FROM bestellingen)
",[]);

$columns = array_keys($reserveringen[0]);
$row_data = array_values($reserveringen);

// Code word uitegevoerd wanneer er word geklikt op de form button, de button moet wel een name en een type "submit" bevatten
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['afdrukken'])) {

	$reservering_id = htmlspecialchars(trim($_POST['reservering_id']));

	$bon_details = $db->select("SELECT menuitems.Naam, bestellingen.Aantal, menuitems.Prijs * bestellingen.Aantal AS Prijs FROM bestellingen INNER JOIN menuitems ON bestellingen.Menuitem_ID = menuitems.ID WHERE bestellingen.Reservering_ID = :reservering_id",['reservering_id' => $reservering_id]);

	$totaal = 0;
	foreach($bon_details AS $details){
		$totaal += $details["Prijs"];
	};
	

	$filename = "bon-datum-".date('Y-m-d')."";

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$filename\"");
	$print_header = false;

	if (!empty($bon_details)) {
		foreach($bon_details AS $details){
			if (!$print_header) {
				echo implode("\t", array_keys($details)) . "\n";
				$print_header = true;
			}
			echo implode("\t", array_values($details)) . "\n";
		}
		echo "Totaal = € " .$totaal;
	}

	exit;

}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Bonetjes</title>
</head>
<body>
	<?php include 'navigatie.php'; ?>

	<h1>Bon afdrukken voor</h1>

	<form method="post">
		<h3>Tafel</h3>
		<select required type="number" name="reservering_id">
			<option>-----</option>
			<?php foreach($row_data AS $data) { ?>
				<option value="<?php echo $data["ID"] ?>"><?php echo $data["Tafel"] ?></option>
			<?php } ?>
		</select>

		<button type="submit" name="afdrukken">Selecteer</button>
	</form>
</body>
</html>