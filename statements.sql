-- Als de statement al bestaat word die eerst verwijderd. Dit gebruik ik om database veranderingen te inserten
DROP DATABASE IF EXISTS examen;

-- Statement voor het aanmaken van de database
CREATE DATABASE examen;

-- Word gebruikt om aan te geven in welke database je veranderingen in maakt, in dit geval word het gebruikt om tables en data in te voegen
USE examen;

-- Statements voor het aanmaken van een table
CREATE TABLE gerechtcategorien (
	ID INT(11) NOT NULL AUTO_INCREMENT UNIQUE,
	Code VARCHAR(3) UNIQUE,
	Naam VARCHAR(20),
	-- KEYS
	PRIMARY KEY (ID)
);

CREATE TABLE klanten (
	ID INT(11) NOT NULL AUTO_INCREMENT UNIQUE,
	Naam VARCHAR(20) NOT NULL,
	Telefoon VARCHAR(11) NOT NULL, 
	Email VARCHAR(128) NOT NULL,
	Birthday DATE NOT NULL,
	-- KEY 
	PRIMARY KEY (ID)
);

CREATE TABLE gerechtsoorten (
	ID INT(11) NOT NULL AUTO_INCREMENT UNIQUE,
	Code VARCHAR(3) UNIQUE,
	Naam VARCHAR(20),
	Gerechtcategorie_ID INT(11) NOT NULL,
	-- KEYS
	PRIMARY KEY (ID),
	FOREIGN KEY (Gerechtcategorie_ID) REFERENCES gerechtcategorien(ID)
);

CREATE TABLE menuitems (
	ID INT(11) NOT NULL AUTO_INCREMENT UNIQUE,
	Code VARCHAR(4) UNIQUE,
	Naam VARCHAR(30),
	Gerechtsoort_ID INT(11) NOT NULL,
	Prijs DECIMAL(5,2) NOT NULL,
	-- KEYS
	FOREIGN KEY (Gerechtsoort_ID) REFERENCES gerechtsoorten(ID)
);

CREATE TABLE reserveringen (
	ID INT(11) NOT NULL AUTO_INCREMENT UNIQUE,
	Tafel INT(11) NOT NULL,
	Datum DATE NOT NULL,
	Tijd TIME NOT NULL,
	Klant_ID INT(11) NOT NULL,
	Aantal INT(11) NOT NULL,
	Status TINYINT(4) NOT NULL DEFAULT '1', 
	Datum_toegevoegd TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ,
	Allergieen TEXT,
	Opmerkingen TEXT,
	-- KEYS 
	PRIMARY KEY (ID),
	FOREIGN KEY (Klant_ID) REFERENCES klanten(ID)
);

CREATE TABLE bestellingen (
	ID INT(11) NOT NULL AUTO_INCREMENT UNIQUE,
	Reservering_ID INT(11) NOT NULL,
	Menuitem_ID INT(11) NOT NULL,
	Aantal INT(11),
	Klaar TINYINT(4) DEFAULT '0',
	Gereserveerd TINYINT(4) DEFAULT '0',
	-- KEYS
	PRIMARY KEY (ID),
	FOREIGN KEY (Reservering_ID) REFERENCES reserveringen(ID),
	FOREIGN KEY (Menuitem_ID) REFERENCES menuitems(ID)
);

-- INSERT statements voor het invoeren van test gegevens
INSERT INTO `gerechtcategorien` (`ID`, `Code`, `Naam`) VALUES 
	(NULL, 'DK', 'Dranken'), 
	(NULL, 'HP', 'Hapjes'), 
	(NULL, 'HG', 'Hoofdgerechten'), 
	(NULL, 'NG', 'Nagerechten')
;

INSERT INTO `gerechtsoorten` (`ID`, `Code`, `Naam`, `Gerechtcategorie_ID`) VALUES 
	(NULL, 'BR', 'Bieren', '1'), 
	(NULL, 'WM', 'Warme hapjes', '2'), 
	(NULL, 'VL', 'Vlees', '3'), 
	(NULL, 'IS', 'Ijs', '4')
;

INSERT INTO `menuitems` (`ID`, `Code`, `Naam`, `Gerechtsoort_ID`, `Prijs`) VALUES 
	(NULL, 'PLS', 'Pilsner', '1', 6.50), 
	(NULL, 'BMM', 'Bitterballetjes met mosterd', '2', 6.50), 
	(NULL, 'WSL', 'Wienerschnitzel', '3', 6.50), 
	(NULL, 'VJS', 'Vruchtenijs', '4', 6.50)
;

INSERT INTO `klanten` (`ID`, `Naam`, `Telefoon`, `Email`, `Birthday`) VALUES 
	(NULL, 'Jansen', '0682901192', 'jansen@gmail.com',CURRENT_DATE )
;

INSERT INTO `reserveringen` (`ID`, `Tafel`, `Datum`, `Tijd`, `Klant_ID`, `Aantal`, `Status`, `Datum_toegevoegd`, `Allergieen`, `Opmerkingen`) VALUES 
	(NULL, '107', CURRENT_DATE, '11', '1', '4', '1', current_timestamp(), NULL, NULL)
;

INSERT INTO `bestellingen` (`ID`, `Reservering_ID`, `Menuitem_ID`, `Aantal`, `Klaar`,`Gereserveerd`) VALUES 
	(NULL, '1', '2', '2', '0','0')
;