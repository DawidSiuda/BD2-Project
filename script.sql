-- --------------------------------------------------------------
-- DELETE OLD BASE 
-- --------------------------------------------------------------
DROP VIEW booked;
DROP VIEW view_person_onUserId;

DROP TABLE PASSANGERS; 
DROP TABLE FLIGHTS;
DROP TABLE USERS;
DROP TABLE PILOTS;
DROP TABLE PERSONS;
DROP TABLE AIRPORTS;
DROP TABLE PLANES;


-- --------------------------------------------------------------
-- CREATE TABLES
-- --------------------------------------------------------------
CREATE TABLE PLANES 
(  
	PLANE_ID int primary key not null auto_increment, 
	NAME_OF_PLANE VARCHAR(20) not null, 
	MODEL VARCHAR(25), 
	PROVIDER VARCHAR(25) not null, 
	CAPACITY int(20) not null -- how many ppassangers can be inside plane 
)ENGINE=InnoDB;


   
CREATE TABLE AIRPORTS 
(  
	AIRPORT_ID INT primary key not null auto_increment, 
	CITY VARCHAR(20) not null, 
	CAPACITY VARCHAR(25) not null -- how many planes on this airport in the same time 
)ENGINE=InnoDB;

/*
CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
*/
CREATE TABLE PERSONS
(
	PERSON_ID INT primary key not null auto_increment, 
    USERNAME VARCHAR(50) NOT NULL UNIQUE,
	FIRST_NAME VARCHAR(20), 
	LAST_NAME VARCHAR(25) not null,
	ADDRESS_FOR_LETTERS VARCHAR(40), 
    TELEPHONE VARCHAR(9) not null,
    EMAIL_ADRES VARCHAR(30) not null,
	USER_PASSWORD VARCHAR(255) not null,
    CREATED_AT DATETIME DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB;

CREATE TABLE PILOTS
(  
	PILOT_ID INT primary key not null auto_increment,
    PERSON_ID INT not null,
	POSSITION ENUM("CAPITAN", "FIRST OFFICER")not null,
    SALARY DECIMAL(9,2),
    
	FOREIGN KEY (PERSON_ID) REFERENCES PERSONS(PERSON_ID) ON DELETE RESTRICT
)ENGINE=InnoDB;
        
CREATE TABLE USERS
(  
	USER_ID INT primary key not null auto_increment,
    PERSON_ID INT not null,
    PERMISSION_TYPE ENUM("CLIENT","ADMIN", "EMPLOYEE") not null,
    
    FOREIGN KEY (PERSON_ID) REFERENCES PERSONS(PERSON_ID) ON DELETE RESTRICT
)ENGINE=InnoDB;
    
CREATE TABLE FLIGHTS 
(  
	FLIGHT_ID INT primary key not null auto_increment, 
	DEPARTURE_DATE DATE not null, 
	START_AIRPORT_ID INT not null,
    FINISH_AIRPORT_ID INT not null,
    BOOKED_TICKETS INT not null,
    -- AVAILBLE_seats  VARCHAR(20), <-- we need to get it from planes:capacity
    CAPITAN_ID INT not null,
    FIRST_OFFICER_ID INT not null,
    PLANE_ID INT not null,
    TICKET_PRICE DECIMAL(5,2) not null,

	FOREIGN KEY (START_AIRPORT_ID) REFERENCES AIRPORTS(AIRPORT_ID) ON DELETE RESTRICT,
    FOREIGN KEY (FINISH_AIRPORT_ID) REFERENCES AIRPORTS(AIRPORT_ID) ON DELETE RESTRICT, 
    FOREIGN KEY (CAPITAN_ID) REFERENCES PILOTS(PILOT_ID) ON DELETE RESTRICT,
    FOREIGN KEY (FIRST_OFFICER_ID) REFERENCES PILOTS(PILOT_ID) ON DELETE RESTRICT
    -- FOREIGN KEY (PLANE_ID) REFERENCES PLANES(PLANE_ID) ON DELETE RESTRICT
)ENGINE=InnoDB;

ALTER TABLE FLIGHTS ADD CONSTRAINT FLIGHTS_PLANE_ID_foreign FOREIGN KEY (PLANE_ID) REFERENCES PLANES (PLANE_ID) ON DELETE RESTRICT;
-- ALTER TABLE PLANES  ADD CONSTRAINT PLANES_PLANE_ID_foreign FOREIGN KEY (PLANE_ID) REFERENCES FLIGHTS (PLANE_ID) ON DELETE RESTRICT;
    
CREATE TABLE PASSANGERS
(  
	PASSANGER_ID INT primary key not null auto_increment,
	USER_ID INT not null,
    FLIGHT_ID INT not null, 	
    WEIGHT_OF_LUGGAGE DECIMAL(5,3),
    PAYED BOOL default false,
    
    FOREIGN KEY (USER_ID) REFERENCES USERS(USER_ID) ON DELETE RESTRICT,
    FOREIGN KEY (FLIGHT_ID) REFERENCES FLIGHTS(FLIGHT_ID) ON DELETE RESTRICT
)ENGINE=InnoDB;

-- ----------------------------------------------------------------------------------------
-- CREATE VIEW
-- ----------------------------------------------------------------------------------------

CREATE VIEW booked AS
select p.passanger_id, p.user_id, p.flight_id, f.departure_date, f.ticket_price, p.payed, f.START_AIRPORT_ID, f.FINISH_AIRPORT_ID
	from passangers p join flights f on p.flight_id = f.flight_id;
    
CREATE VIEW view_person_onUserId AS
SELECT u.user_id, p.USERNAME, p.first_name, p.last_name, p.address_for_letters, p.telephone, p.email_adres
FROM PERSONS p  right join users u on p.person_id=u.person_id;
/*
CREATE OR REPLACE FORCE VIEW my_view (id_transakcji, nazwa_produktu, cena_produktu, id_platnosci, kwota_do_zaplaty, id_osoby, nazwa_stanowiska, data_zamowienia) AS 
  SELECT
  z.id_transakcji,
  pr.nazwa_produktu,
  pr.cena_produktu,
  pl.id_platnosci,
  pl.kwota_do_zaplaty,
  o.id_osoby,
  s.nazwa_stanowiska,
  z.data_zamowienia
FROM
  zamowienia z,
  Produkty pr,
  platnosci pl,
  Osoby o,
  Stanowiska s
WHERE 	z.id_produktu = pr.id_produktu
	AND z.id_platnosci = pl.id_platnosci
	AND z.id_osoby_obsl_zamowienie = o.id_osoby
	AND o.id_stanowiska = s.id_stanowiska
WITH READ ONLY;

*/
-- --------------------------------------------------------------
-- TRANSAKCJE
-- --------------------------------------------------------------

-- to do 
/*
START TRANSACTION
   INSERT INTO faktura (...) VALUES (...);
   $faktura_id = mysql_inserted_id();
   foreach (koszyk_pozycje){
      INSERT INTO faktura_pozycje (...,faktura,..) VALUES (...,$faktura_id,... );
   }
   DELETE FROM koszyk_pozycje WHERE koszyk_id = ?
POTWIERDZ_TRANSAKCJA;
*/

-- --------------------------------------------------------------
-- INSERT TABLES
-- --------------------------------------------------------------

-- PLANES

Insert into PLANES
	(
		NAME_OF_PLANE, MODEL, PROVIDER, CAPACITY
    )
	values 
		('Maciej','Tu-134','Tupolew', 130),
        ('Dreamliner','787','Boeing', 110),
        ('Unicorn','Il-114','Iljuszyn', 96),
        ('Worldliner','777','Boeing', 124),
        ('Grzaniec','Il-96','Iljuszyn', 96),
        ('Elvis','An-24','Antonow', 52);
    
-- AIRPORTS 

Insert into AIRPORTS
		(CITY, CAPACITY)
	values 
		('Warsaw',50),
		('Berlin',60),
		('Liverpol',10),
		('London',32),
		('LosAngeles',50),
		('Praga',50);

-- PERSONS

Insert into PERSONS
		(USERNAME, FIRST_NAME, LAST_NAME, ADDRESS_FOR_LETTERS, TELEPHONE, EMAIL_ADRES, USER_PASSWORD)
	values
		('misia123', 'Michalina', 'Ciapciajka', 'Kolejowa 12 Wroclaw', '123456789', 'zalson@gmail.com', '$2y$10$bC3xJ9M8v9NfoiZi57c.X.YukBWXoFPAtgFe3b6I20dBSpCF4y1j2'),
        ('sbabol', 'Stefan', 'Babol', 'Kluczborska 666 Sosnowiec', '987654321', 'stefanos@buziaczek.com', '$2y$10$bC3xJ9M8v9NfoiZi57c.X.YukBWXoFPAtgFe3b6I20dBSpCF4y1j2'),
        ('dziku569', 'Ryszard', 'Dziki', 'Powodzie 18 Radom', '456123789', 'dziku569@wp.pl', '$2y$10$bC3xJ9M8v9NfoiZi57c.X.YukBWXoFPAtgFe3b6I20dBSpCF4y1j2'),
        ('dziku568', 'Dawid', 'Siuda', 'Powodzie 18 Radom', '456123789', 'dziku569@wp.pl', '$2y$10$bC3xJ9M8v9NfoiZi57c.X.YukBWXoFPAtgFe3b6I20dBSpCF4y1j2'),
        ('czarekzegarek', 'Cezary', 'Orzeszek', 'Sliczna 5 Pawelki', '213546879', 'elo320@gmail.pl', '$2y$10$bC3xJ9M8v9NfoiZi57c.X.YukBWXoFPAtgFe3b6I20dBSpCF4y1j2'),
        ('Jankow', 'Jan', 'Kowalski', 'Sliczna 5 Pawelki', '213546879', 'elo320@gmail.pl', '$2y$10$bC3xJ9M8v9NfoiZi57c.X.YukBWXoFPAtgFe3b6I20dBSpCF4y1j2'),
        ( 'noname', 'Dominika', 'Dominika', 'Wybrzeże Wyspiańskiego 27 Wrocław', '654456345', 'ddominika@nic.pl', '$2y$10$bC3xJ9M8v9NfoiZi57c.X.YukBWXoFPAtgFe3b6I20dBSpCF4y1j2'),
        ( 'worker1', 'Jan', 'Pomorski', 'Powodzie 18 Radom', '456123789', 'dziku5669@wp.pl', '$2y$10$bC3xJ9M8v9NfoiZi57c.X.YukBWXoFPAtgFe3b6I20dBSpCF4y1j2'),
        ( 'admin', 'Janusz', 'Pomorski', 'Powodzie 18 Radom', '456123789', 'dziku529@wp.pl', '$2y$10$bC3xJ9M8v9NfoiZi57c.X.YukBWXoFPAtgFe3b6I20dBSpCF4y1j2');

        
-- PILOTS

Insert into PILOTS
		(PERSON_ID, POSSITION, SALARY)
	values 
		(1, "CAPITAN", 5000.00),
        (2, "CAPITAN", 5000.00),
        (3, "FIRST OFFICER", 9999.00),
        (4, "FIRST OFFICER", 13.50);
    

-- USERS

Insert into USERS
	(PERSON_ID, PERMISSION_TYPE)
	values 
    -- (3,"CLIENT"),
	((select person_id from persons where USERNAME = "misia123"), "EMPLOYEE"),
    ((select person_id from persons where USERNAME = "sbabol"), "EMPLOYEE"),
    ((select person_id from persons where USERNAME = "dziku569"), "EMPLOYEE"),
    ((select person_id from persons where USERNAME = "dziku568"), "EMPLOYEE"),
    ((select person_id from persons where USERNAME = "czarekzegarek"), "CLIENT"),
	((select person_id from persons where USERNAME = "Jankow"), "CLIENT"),
    ((select person_id from persons where USERNAME = "noname"), "CLIENT"),
    ((select person_id from persons where USERNAME = "worker1"), "EMPLOYEE"),
    ((select person_id from persons where USERNAME = "admin"), "ADMIN");
   
    
    

    
-- FLIGHTS
       
Insert into FLIGHTS
	(DEPARTURE_DATE, START_AIRPORT_ID, FINISH_AIRPORT_ID, BOOKED_TICKETS, CAPITAN_ID, FIRST_OFFICER_ID, PLANE_ID, TICKET_PRICE)
	values 
		('2019-05-11', 1, 3,  123, 2, 3, 4, 450.99),
        ('2019-04-23', 2, 3,  30, 2, 4, 2, 15.60),
        ('2019-12-10', 1, 4,  69, 3, 2, 5, 920.36),
        ('2019-03-11', 1, 3,  123, 1, 4, 1, 450.99),
        ('2019-05-23', 2, 3,  30, 2, 4, 2, 15.60),
        ('2019-09-12', 6, 4,  69, 3, 2, 5, 920.36),
        ('2019-11-15', 5, 3,  123, 1, 4, 1, 450.99),
        ('2019-11-13', 2, 3,  30, 2, 4, 2, 15.60),
        ('2019-12-19', 1, 4,  69, 3, 2, 5, 920.36);
    
-- PASSANGERS

Insert into PASSANGERS
	(
		USER_ID, FLIGHT_ID, WEIGHT_OF_LUGGAGE, PAYED
    )
	values 
		(5, 2, 3, false),
        (6, 2, 3, true),
        (7, 2, 9, false),
        (6, 1, 2, true),
        (7, 3, 40, true);
        



