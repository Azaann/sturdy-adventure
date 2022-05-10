<?php 

// Maakt een koppeling naar de aangegeven bestand. Bij deze word het gebruikt voor de database connectie en de bijhorende functies
include 'database.php';

// Word gebruikt voor de database connectie, zonder dit kan je geen data ophalen uit de database
$db = new database();

// Pakt de huidige datum en stopt hem in een variabel
$current_date = date('Y-m-d');

// Select statement om data te verzamelen uit de database
$reserveringen = $db->select("SELECT * FROM reserveringen INNER JOIN klanten on reserveringen.Klant_ID = klanten.ID WHERE reserveringen.Datum = :lala AND Status = 1",[':lala' => $current_date]);

if (!empty($reserveringen)) {
	$columns = array_keys($reserveringen[0]);
	$row_data = array_values($reserveringen);
}

// Code word uitegevoerd wanneer er word geklikt op de form button, de button moet wel een name en een type "submit" bevatten
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {

	// Stopt de form gegevens in variablelen
	$reservering_id = htmlspecialchars(trim($_POST['reservering_id']));
	$klant_id = htmlspecialchars(trim($_POST['klant_id']));
	$naam = htmlspecialchars(trim($_POST['naam']));
	$telefoon = htmlspecialchars(trim($_POST['telefoon']));
	$email = htmlspecialchars(trim($_POST['email']));
	$bday = htmlspecialchars(trim($_POST['bday']));
	$tafel = htmlspecialchars(trim($_POST['tafel']));
	$datum = htmlspecialchars(trim($_POST['datum']));
	$tijd = htmlspecialchars(trim($_POST['tijd']));
	$aantal = htmlspecialchars(trim($_POST['aantal']));
	$status = htmlspecialchars(trim($_POST['status']));
	$datum_toegevoegd = htmlspecialchars(trim($_POST['datum_toegevoegd']));
	$allergieen = htmlspecialchars(trim($_POST['allergieen']));
	$opmerkingen = htmlspecialchars(trim($_POST['opmerkingen']));

	// Na het defineren van de variablelen, de variableen sturen naar de functie

	// Na het defineren van de variablelen, de variableen sturen naar de functie
	$db->edit_reserveringen($reservering_id, $klant_id, $naam, $telefoon, $email,$bday, $tafel, $datum, $tijd, $aantal, $status, $datum_toegevoegd, $allergieen, $opmerkingen);
}

// Code word uitegevoerd wanneer er word geklikt op de form button, de button moet wel een name en een type "submit" bevatten
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['not_welcome'])) {

	// Stopt de form gegevens in variablelen
	$reservering_id = htmlspecialchars(trim($_POST['reservering_id']));

	// Update functie met behulp van de aangewezen variabelen
	$db->select("UPDATE reserveringen SET Status = :not_welcome WHERE ID = :reservering_id",[':not_welcome' => 0, ':reservering_id' => $reservering_id]);

	// Pagina word herstart om zo de wijzigingen te laten zien, daarvoor krijgt de user een message te zien
	header("refresh:2;");
	echo "Reservering succesfoll gewijzigd";
}

// Code word uitegevoerd wanneer er word geklikt op de form button, de button moet wel een name en een type "submit" bevatten
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {

	// Stopt de form gegevens in variablelen
	$naam = htmlspecialchars(trim($_POST['naam']));
	$telefoon = htmlspecialchars(trim($_POST['telefoon']));
	$email = htmlspecialchars(trim($_POST['email']));
	$bday = htmlspecialchars(trim($_POST['bday']));
	$tafel = htmlspecialchars(trim($_POST['tafel']));
	$datum = htmlspecialchars(trim($_POST['datum']));
	$tijd = htmlspecialchars(trim($_POST['tijd']));
	$aantal = htmlspecialchars(trim($_POST['aantal']));
	$allergieen = htmlspecialchars(trim($_POST['allergieen']));
	$opmerkingen = htmlspecialchars(trim($_POST['opmerkingen']));

  	// Na het defineren van de variablelen, de variableen sturen naar de functie

	// Na het defineren van de variablelen, de variableen sturen naar de functie
	$db->add_reservering($naam, $telefoon, $email, $bday, $tafel, $datum, $tijd, $aantal, $allergieen, $opmerkingen);
}

// Code word uitegevoerd wanneer er word geklikt op de form button, de button moet wel een name en een type "submit" bevatten
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {

	// Stopt de form gegevens in variablelen
	$reservering_id = htmlspecialchars(trim($_POST['reservering_id']));

	// Delete functie met behulp van de aangewezen variabelen
	$db->select("DELETE FROM reserveringen WHERE ID = :reservering_id",[':reservering_id' => $reservering_id]);

	// Pagina word herstart om zo de wijzigingen te laten zien, daarvoor krijgt de user een message te zien
	header("refresh:2;");
	echo "Reservering succesfoll verwijderd";
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
	<!-- Kopieert de navigatie en stopt deze in de bestand -->
	<?php include 'navigatie.php'; ?>

	<!-- Tabel ontwerp opstellen -->
	<?php if (!empty($reserveringen)) { ?>
	<table>
		<thead>
			<tr>
				<th>Tafel</th>
				<th>Datum</th>
				<th>Tijd</th>
				<th>Klant</th>
				<th>Aantal</th>
				<th>Status</th>
				<th>Datum toegevoegd</th>
				<th>Allergien</th>
				<th>Opmerkingen</th>
			</tr>
		</thead>
		<tbody>
			<!-- Zet de huidige datum op de front page om aan de medewerkers te laten zien welke datum de reserveringen op gebaseert is -->
			<h1>Reserveringen wijzigen/deleten van <?php echo $current_date ?></h1>

			<!-- Menukaart opstellen met de subcategorien van de hoofdgroepen -->
			
			<!-- Herhaalt de code wanneer hij meerdere data tekenkomt uit de select statement -->
			<?php foreach ($row_data AS $data) { ?>
				<form method="post">
					<tr>
						<input type="hidden" name="reservering_id" value="<?php echo $data["ID"] ?>">

						<td><input type="number" name="tafel" value="<?php echo $data["Tafel"] ?>"></td>

						<td><input type="date" name="datum" value="<?php echo $data["Datum"] ?>"></td>

						<td><input type="time" name="tijd" value="<?php echo $data["Tijd"] ?>"></td>

						<input type="hidden" name="klant_id" value="<?php echo $data["Klant_ID"] ?>">

						<td><input type="text" name="naam" value="<?php echo $data["Naam"] ?>"></td>

						<input type="hidden" name="telefoon" value="<?php echo $data["Telefoon"] ?>">
						<input type="hidden" name="bday" value="<?php echo $data["Birthday"] ?>">

						<input type="hidden" name="email" value="<?php echo $data["Email"] ?>">

						<td><input type="number" name="aantal" value="<?php echo $data["Aantal"] ?>"></td>

						<td><input type="number" name="status" value="<?php echo $data["Status"] ?>"></td>

						<td><input type="datetime" name="datum_toegevoegd" value="<?php echo $data["Datum_toegevoegd"] ?>"></td>

						<td><input type="text" name="allergieen" value="<?php echo $data["Allergieen"] ?>"></td>

						<td><input type="text" name="opmerkingen" value="<?php echo $data["Opmerkingen"] ?>"></td>

						<td><button type="sumbit" name="edit" onclick="return confirm('Weet je zeker dat je de reservering wilt wijzigen?');">Edit</button></td>
						<td><button type="sumbit" name="not_welcome" onclick="return confirm('Weet je zeker dat de klant niet is op komen dagen?');">Niet op komen dagen</button></td>
						<td><button type="sumbit" name="delete" onclick="return confirm('Weet je zeker dat je de reservering wilt verwijderen?');">Delete</button></td>

						<?php if ($data['Birthday'] == $current_date) {
							echo "<td>deze persoon is jarig</td>";
						} ?>
					</tr>
				</form>
			<?php } ?>	
		</tbody>
	</table>
<?php }else{
	echo "<h1>Geen reserveringen op deze dag</h1>";
} ?>

	<!-- Form voor het toevoegen van de reserveringen -->
	<form method="post">

		<h1>Reserveringen toevoegen</h1>

		<h3>Naam</h3>
		<input required type="text" name="naam">

		<h3>Telefoonnummer</h3>
		<input required type="number" name="telefoon">

		<h3>Email</h3>
		<input required type="email" name="email">

		<h3>Geboortedatum</h3>
		<input required type="date" name="bday">

		<h3>Tafel</h3>
		<input required type="number" name="tafel">

		<h3>Datum</h3>
		<input required type="date" name="datum">

		<h3>Tijd</h3>
		<input required type="time" name="tijd">

		<h3>Aantal</h3>
		<input required type="number" name="aantal">

		<h3>Allergieen</h3>
		<input type="text" name="allergieen">

		<h3>Opmerkingen</h3>
		<input type="text" name="opmerkingen">

		<button type="sumbit" name="add" onclick="return confirm('Are you sure you want to add this person?');">Add</button>
	</form>
</body>
</html>