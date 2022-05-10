<?php 

// Maakt een koppeling naar de aangegeven bestand. Bij deze word het gebruikt voor de database connectie en de bijhorende functies
include 'database.php';

$db = new database();

$reserveringen = $db->select("SELECT * FROM reserveringen
",[]);

$columns = array_keys($reserveringen[0]);
$row_data = array_values($reserveringen);

$menuitems = $db->select("SELECT * FROM menuitems",[]);
$columns_menuitems = array_keys($menuitems[0]);
$row_data_menuitems = array_values($menuitems);

// Code word uitegevoerd wanneer er word geklikt op de form button, de button moet wel een name en een type "submit" bevatten
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bestellen'])) {

	$reservering_id = htmlspecialchars(trim($_POST['reservering_id']));
	$item_id = htmlspecialchars(trim($_POST['item_id']));
	$aantal = htmlspecialchars(trim($_POST['aantal']));

	$db->add_bestelling($reservering_id, $item_id, $aantal);
}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Bestellen</title>
</head>
<body>
	<?php include 'navigatie.php'; ?>

	<h1>Bestelling plaatsen</h1>

	<form method="post">
		<h3>Tafel</h3>
		<select required type="number" name="reservering_id">
			<?php foreach($row_data AS $data) { ?>
				<option value="<?php echo $data["ID"] ?>"><?php echo $data["Tafel"] ?></option>
			<?php } ?>
		</select>

		<h3>Bestelling</h3>
		<select required type="text" name="item_id">
			<?php foreach($row_data_menuitems AS $data_menuitems) { ?>
				<option value="<?php echo $data_menuitems['ID'] ?>"><?php echo $data_menuitems["Naam"] ?></option>
			<?php } ?>
		</select>

		<h3>Aantal</h3>
		<input required type="number" name="aantal">

		<button type="submit" name="bestellen">bestellen</button>

	</form>
</body>
</html>