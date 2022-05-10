<?php 

// Maakt een koppeling naar de aangegeven bestand. Bij deze word het gebruikt voor de database connectie en de bijhorende functies
include 'database.php';

$db = new database();

$drinken = $db->select("SELECT * FROM klanten",[]);

$columns = array_keys($drinken[0]);
$row_data = array_values($drinken);

// Code word uitegevoerd wanneer er word geklikt op de form button, de button moet wel een name en een type "submit" bevatten
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {

	$klant_id = htmlspecialchars(trim($_POST['klant_id']));
	$naam = htmlspecialchars(trim($_POST['naam']));
	$telefoon = htmlspecialchars(trim($_POST['telefoon']));
	$email = htmlspecialchars(trim($_POST['email']));
	$bday = htmlspecialchars(trim($_POST['bday']));
	
	$db->edit_klanten($klant_id, $naam, $telefoon, $email, $bday);
}

// Code word uitegevoerd wanneer er word geklikt op de form button, de button moet wel een name en een type "submit" bevatten
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {

	$klant_id = htmlspecialchars(trim($_POST['klant_id']));

	$db->select("DELETE FROM klanten WHERE ID = :klant_id",[':klant_id' => $klant_id]);
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
				<th>Naam</th>
				<th>Telefoon</th>
				<th>Email</th>
			</tr>
		</thead>
		<tbody><h1>Klantenlijst wijzigen/deleten</h1>
			<?php foreach ($row_data AS $data) { ?>
				<form method="post">				
					<tr>
						<input type="hidden" name="klant_id" value="<?php echo $data["ID"] ?>">

						<td><input type="text" name="naam" value="<?php echo $data["Naam"] ?>"></td>
						<td><input type="text" name="email" value="<?php echo $data["Email"] ?>"></td>
						<td><input type="number" name="telefoon" value="<?php echo $data["Telefoon"] ?>"></td>
						<td><input type="date" name="bday" value="<?php echo $data["Birthday"] ?>"></td>

						
						<td><button type="sumbit" name="edit" onclick="return confirm('Are you sure you want to edit this person?');">Edit</button></td>
						<td><button type="sumbit" name="delete" onclick="return confirm('Are you sure you want to delete this person?');">Delete</button></td>
					</tr>
				</form>
			<?php } ?>	
		</tbody>
	</table>
</body>
</html>