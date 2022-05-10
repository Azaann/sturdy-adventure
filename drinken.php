<?php 

// Maakt een koppeling naar de aangegeven bestand. Bij deze word het gebruikt voor de database connectie en de bijhorende functies
include 'database.php';

$db = new database();

$drinken = $db->select(" SELECT menuitems.ID, menuitems.Code, menuitems.Naam, menuitems.Prijs ,gerechtsoorten.ID AS soort_ID ,gerechtcategorien.Naam AS soort, gerechtsoorten.Naam AS soort_naam FROM menuitems 
INNER JOIN gerechtsoorten ON menuitems.Gerechtsoort_ID = gerechtsoorten.ID
INNER JOIN gerechtcategorien ON gerechtsoorten.Gerechtcategorie_ID = gerechtcategorien.ID
WHERE Gerechtcategorie_ID = 1",[]);
$columns = array_keys($drinken[0]);
$row_data = array_values($drinken);

$soorten = $db->select("SELECT * FROM gerechtsoorten WHERE Gerechtcategorie_ID = 1",[]);
$row_data_soorten = array_values($soorten);

// Code word uitegevoerd wanneer er word geklikt op de form button, de button moet wel een name en een type "submit" bevatten
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {

	$item_id = htmlspecialchars(trim($_POST['item_id']));
	$code = htmlspecialchars(trim($_POST['code']));
	$naam = htmlspecialchars(trim($_POST['naam']));
	$soort_id = htmlspecialchars(trim($_POST['soort_id']));
	$prijs = htmlspecialchars(trim($_POST['prijs']));

	$db->edit_items($item_id, $code, $naam, $soort_id, $prijs);
}

// Code word uitegevoerd wanneer er word geklikt op de form button, de button moet wel een name en een type "submit" bevatten
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {

	$item_id = htmlspecialchars(trim($_POST['item_id']));
	$code = htmlspecialchars(trim($_POST['code']));
	$naam = htmlspecialchars(trim($_POST['naam']));
	$soort_id = htmlspecialchars(trim($_POST['soort_id']));
	$prijs = htmlspecialchars(trim($_POST['prijs']));

	$db->add_items($item_id, $code, $naam, $soort_id, $prijs);
}

// Code word uitegevoerd wanneer er word geklikt op de form button, de button moet wel een name en een type "submit" bevatten
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {

	$item_id = htmlspecialchars(trim($_POST['item_id']));

	$db->select("DELETE FROM menuitems WHERE ID = :item_id",[':item_id' => $item_id]);
	header("refresh:2;");
	echo "Drank succesfoll verwijderd";
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Drinken</title>
</head>
<body>
	<?php include 'navigatie.php'; ?>
	<table>
		<thead>
			<tr>
				<th>Code</th>
				<th>Naam</th>
				<th>Prijs</th>
				<th>Valt onder</th>
				<th>Actie</th>
			</tr>
		</thead>
		<tbody><h1>Drankenlijst wijzigen/deleten</h1>
			<?php foreach ($row_data AS $data) { ?>
				<form method="post">
					
					<tr>
						<input type="hidden" name="item_id" value="<?php echo $data["ID"] ?>">

						<td><input type="text" maxlength="3" name="code" value="<?php echo $data["Code"] ?>"></td>
						<td><input type="text" name="naam" value="<?php echo $data["Naam"] ?>"></td>
						<td><input type="number" min="1" step=".01" name="prijs" value="<?php echo $data["Prijs"] ?>"></td>

						<input type="hidden" name="soort_id" value="<?php echo $data["soort_ID"] ?>">

						<td><input disabled type="text" name="gerechtsoort" value="<?php echo $data["soort_naam"] ?>"></td>
						<td><button type="sumbit" name="edit" onclick="return confirm('Are you sure you want to edit this item?');">Edit</button></td>
						<td><button type="sumbit" name="delete" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button></td>
					</tr>
				</form>

			<?php } ?>	
		</tbody>
	</table>

	<form method="post">

		<h1>Drank toevoegen</h1>
	
		<input type="hidden" name="item_id">

		<h3>Voeg toe in</h3>
	    <select name="soort_id">
	      <option></option>
	      <?php foreach($row_data_soorten AS $data_soorten) {?>
	        <option value="<?php echo $data_soorten["ID"]?>"><?php echo $data_soorten["Naam"] ?></option>
	      <?php } ?>
	    </select>

		<h3>Code</h3>
		<input type="text" maxlength="3" name="code">

		<h3>Naam</h3>
		<input type="text" name="naam">

		<h3>Prijs</h3>
		<input type="number" min="1" step=".01" name="prijs">

		<button type="sumbit" name="add">Add</button>
	
	</form>


</body>
</html>