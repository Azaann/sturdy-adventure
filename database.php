<?php 

class database{
    
    private $db_server;
    private $db_username;
    private $db_password;
    private $db_name;
    private $db;

    // Functie voor het connecteren met de database
    function __construct(){

        $this->db_server = 'localhost';
        $this->db_username = 'root';
        $this->db_password = '';
        $this->db_name = 'examen';

        $dsn = "mysql:host=$this->db_server;dbname=$this->db_name;charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
             $this->db = new PDO($dsn, $this->db_username, $this->db_password, $options);
        } catch (\PDOException $e) {
             throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    // Functie voor het opzoeken van data
	public function select($statement, $named_placeholder){

        // prepared statement (Stuurt statement naar de server + checks syntax)
        $statement = $this->db->prepare($statement);

        $statement->execute($named_placeholder);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    // Functie voor het editen van de klanten
    public function edit_klanten($klant_id,$naam,  $telefoon, $email, $bday) {
    	try{
            // Zoekt data op uit de database
    		$sql = "UPDATE klanten SET Naam = :naam, Telefoon = :telefoon, Email = :email, Birthday = :bday WHERE ID = :klant_id";

    		$this->db->beginTransaction();

            //Zet data in de varibelen
    		$statement = $this->db->prepare($sql);
    		$statement->execute([
    		'naam' => $naam,
    		'telefoon' => $telefoon, 
    		'email' => $email,
            'bday' => $bday,
    		'klant_id' => $klant_id
    		]);

            // Als de code is uitgevoerd word de message uitgevoerd
    		if ($this->db->commit()) {
    			echo "Reservering is gewijzigd";
    			header("refresh:2;");
    		}
    	}catch (Exception $e){
    		$this->db->rollback();
    		throw $e;
    	}
    }

    // Functie voor het editen van de reserveringen
    public function edit_reserveringen($reservering_id, $klant_id, $naam, $telefoon, $email, $bday, $tafel, $datum, $tijd, $aantal, $status, $datum_toegevoegd, $allergieen, $opmerkingen) {
        try{
            // Zoekt data op uit de database
            $sql = "UPDATE reserveringen INNER JOIN klanten ON reserveringen.Klant_ID = klanten.ID SET reserveringen.Tafel = :tafel, reserveringen.Datum = :datum, reserveringen.Tijd = :tijd, reserveringen.Aantal = :aantal, reserveringen.Status = :status, reserveringen.Datum_toegevoegd = :datum_toegevoegd, reserveringen.Allergieen = :allergieen, reserveringen.opmerkingen = :opmerkingen, klanten.Naam = :naam, klanten.Telefoon = :telefoon, klanten.Email = :email, klanten.Birthday = :bday WHERE reserveringen.ID = :reservering_id";

            $this->db->beginTransaction();

            //Zet data in de varibelen
            $statement = $this->db->prepare($sql);
            $statement->execute([
            'tafel' => $tafel,
            'datum' => $datum,
            'tijd' => $tijd,
            'aantal' => $aantal,
            'status' => $status,
            'datum_toegevoegd' => $datum_toegevoegd,
            'allergieen' => $allergieen,
            'opmerkingen' => $opmerkingen,
            'naam' => $naam,
            'telefoon' => $telefoon, 
            'email' => $email,
            'bday' => $bday,
            'reservering_id' => $reservering_id
            ]);

            // Als de code is uitgevoerd word de message uitgevoerd
            if ($this->db->commit()) {
                echo "Klant is gewijzigd";
                header("refresh:2;");
            }
        }catch (Exception $e){
            $this->db->rollback();
            throw $e;
        }
    }

    public function edit_items($item_id, $code, $naam, $soort_id, $prijs) {
    	try{
            // Zoekt data op uit de database
    		$sql = "UPDATE menuitems SET code = :code, naam = :naam, prijs = :prijs WHERE ID = :item_id";

    		$this->db->beginTransaction();

            //Zet data in de varibelen
    		$statement = $this->db->prepare($sql);
    		$statement->execute([
    		'code' => $code,
    		'naam' => $naam, 
    		'prijs' => $prijs,
    		'item_id' => $item_id
    		]);

            // Als de code is uitgevoerd word de message uitgevoerd
    		if ($this->db->commit()) {
    			echo '<script>alert("Wijziging voltooid")</script>';
    			header("refresh:2;");
    		}
    	}catch (Exception $e){
    		$this->db->rollback();
    		throw $e;
    	}
    }

    public function add_items($item_id, $code, $naam, $soort_id, $prijs) {
    	try{
            // Zoekt data op uit de database
    		$sql = "INSERT INTO menuitems VALUES (NULL, :code, :naam, :soort_ID, :prijs)";

    		$this->db->beginTransaction();

            //Zet data in de varibelen
    		$statement = $this->db->prepare($sql);
    		$statement->execute([
    		'code' => $code,
    		'naam' => $naam,
    		'soort_ID' => $soort_id,
    		'prijs' => $prijs
    		]);

            // Als de code is uitgevoerd word de message uitgevoerd
    		if ($this->db->commit()) {
    			echo '<script>alert("Toevoeging voltooid")</script>';
    			header("refresh:2;");
    		}
    	}catch (Exception $e){
    		$this->db->rollback();
    		throw $e;
    	}
    }

    // Functie voor het toevoegen van de bestellingen
    public function add_bestelling($reservering_id, $item_id, $aantal) {
        try{
            $sql = "INSERT INTO bestellingen VALUES (NULL, :reservering_id, :item_id, :aantal,:klaar, :gereserveerd)";

            $this->db->beginTransaction();

            //Zet data in de varibelen
            $statement = $this->db->prepare($sql);
            $statement->execute([
            'reservering_id' => $reservering_id,
            'item_id' => $item_id,
            'aantal' => $aantal,
            'klaar' => 0,
            'gereserveerd' => 0
            ]);

            // Als de code is uitgevoerd word de message uitgevoerd
            if ($this->db->commit()) {
                echo '<script>alert("Toevoeging voltooid")</script>';
                header("refresh:2;");
            }
        }catch (Exception $e){
            $this->db->rollback();
            throw $e;
        }
    }

    // Fumctie voor het toevoegen van de subgroepen
    public function add_subcategorie($code, $naam, $soort_id) {
        try{
            $sql = "INSERT INTO gerechtsoorten VALUES (NULL, :code, :naam, :soort_id)";

            $this->db->beginTransaction();

            //Zet data in de varibelen
            $statement = $this->db->prepare($sql);
            $statement->execute([
            'code' => $code,
            'naam' => $naam,
            'soort_id' => $soort_id
            ]);
            
            if ($this->db->commit()) {
                echo '<script>alert("Toevoeging voltooid")</script>';
                header("refresh:2;");
            }
        }catch (Exception $e){
            $this->db->rollback();
            throw $e;
        }
    }

    // Functie voor het toevoegen van reserveringen
    public function add_reservering($naam, $telefoon, $email, $bday, $tafel, $datum, $tijd, $aantal, $allergieen, $opmerkingen) {
    	try{

            $this->db->beginTransaction();

            $klanten_check = "SELECT * FROM klanten WHERE Naam = :naam AND Telefoon = :telefoon";

            $klanten_statement = $this->db->prepare($klanten_check);

            $klanten_statement->execute(['naam' => $naam, 'telefoon' => $telefoon]);

            $result = $klanten_statement->fetch();

            // Als de klant al eerder een reservering heeft gemaakt en er geen gebruik van heeft gemaakt dan krijgt de medewerker een melding
            if (is_array($result) && count($result) > 0) { 
                echo '<script>alert("user heeft vorige keer geen gebruikt gemaakt van de reservering")</script>';
            }

    		$sql = "INSERT INTO klanten VALUES (NULL, :naam, :telefoon, :email, :bday)";

            //Zet data in de varibelen
    		$statement = $this->db->prepare($sql);
    		$statement->execute([
    		'naam' => $naam,
    		'telefoon' => $telefoon,
    		'email' => $email,
            'bday' => $bday
    		]);

            // pakt de laatst ingevoerde ID uit de database
    		$klant_id = $this->db->lastInsertId();

    		if ($this->db->commit()) {

    			$sql = "INSERT INTO reserveringen VALUES (NULL, :tafel, :datum, :tijd, :klant_id, :aantal, :status, :datum_toegevoegd, :allergieen, :opmerkingen)";
    			$statement = $this->db->prepare($sql);
	    		$statement->execute([
	    		'tafel' => $tafel,
	    		'datum' => $datum,
	    		'tijd' => $tijd,
	    		'klant_id' => $klant_id,
	    		'aantal' => $aantal,
	    		'status' => 1,
	    		'datum_toegevoegd' => date('Y-m-d H:i:s'),
	    		'allergieen' => $allergieen,
	    		'opmerkingen' => $opmerkingen,
	    		]);

    			echo '<script>alert("Toevoeging voltooid")</script>';
    			header("refresh:2;");
    		}   

    	}catch (Exception $e){

    		$this->db->rollback();
    		echo $e->getMessage();

    	}
    }
}

?>