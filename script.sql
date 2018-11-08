-- --------------------------------------------------------------
-- DELETE OLD BASE 
-- --------------------------------------------------------------
DROP TABLE PASSANGERS; 
DROP TABLE FLIGHTS;
DROP TABLE USERS;
DROP TABLE PEOPLES;
DROP TABLE AIR_PORT_LIST;
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
);
   
CREATE TABLE AIR_PORT_LIST 
(  
	AIRPORT_ID INT primary key not null auto_increment, 
	CITY VARCHAR(20) not null, 
	CAPACITY VARCHAR(25) not null -- how many planes on this airport in the same time 
);
   
CREATE TABLE PEOPLES 
(  
	PERSON_ID INT primary key not null auto_increment, 
    POSSITION ENUM("CAPITAN", "FIRST OFFICER")not null,
    SALARY DECIMAL(9,2)
);
        
CREATE TABLE USERS
(  
	USER_ID INT primary key not null auto_increment,
	FIRST_NAME VARCHAR(20), 
	LAST_NAME VARCHAR(25) not null,
    PERSON_ID INT DEFAULT NULL, -- set if user is worker
	ADDRESS_FOR_LETTERS VARCHAR(40), 
    TELEPHONE VARCHAR(9) not null,
    EMAIL_ADRES VARCHAR(30) not null,
    PERMISSION_TYPE ENUM("CLIENT", "WORKER", "ADMIN") not null,
    USER_PASSWORD VARCHAR(255) not null,
    
    FOREIGN KEY (PERSON_ID) REFERENCES PEOPLES(PERSON_ID)
);
    
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

	FOREIGN KEY (START_AIRPORT_ID) REFERENCES AIR_PORT_LIST(AIRPORT_ID),
    FOREIGN KEY (FINISH_AIRPORT_ID) REFERENCES AIR_PORT_LIST(AIRPORT_ID), 
    FOREIGN KEY (CAPITAN_ID) REFERENCES PEOPLES(PERSON_ID),
    FOREIGN KEY (FIRST_OFFICER_ID) REFERENCES PEOPLES(PERSON_ID),
    FOREIGN KEY (PLANE_ID) REFERENCES PLANES(PLANE_ID)
);
    
CREATE TABLE PASSANGERS
(  
	PASSANGER_ID INT primary key not null auto_increment,
	USER_ID INT not null,
    FLIGHT_ID INT not null, 	
    WEIGHT_OF_LUGGAGE DECIMAL(5,3),
    PAYED BOOL default false,
    
    FOREIGN KEY (USER_ID) REFERENCES USERS(USER_ID),
    FOREIGN KEY (FLIGHT_ID) REFERENCES FLIGHTS(FLIGHT_ID)
);

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
    (
		'name','797','Boeing', 250  -- I have no idea for name :( 
    );
    
-- AIR_PORT_LIST 

Insert into AIR_PORT_LIST
	(
		CITY, CAPACITY
    )
	values 
    (
		'Warsaw',50
    );
    
-- PEOPLES

Insert into PEOPLES
	(
		POSSITION, SALARY
    )
	values 
    (
		'CAPITAN', 5000.00
    );
    
-- FLIGHTS

Insert into FLIGHTS
	(
		DEPARTURE_DATE, START_AIRPORT_ID, FINISH_AIRPORT_ID, BOOKED_TICKETS, CAPITAN_ID, FIRST_OFFICER_ID, PLANE_ID, TICKET_PRICE
    )
	values 
    (
		'2013-02-11', 1, 1,  123, 1, 1, 1, 450.99
    );
    
-- USERS

Insert into USERS
	(
		FIRST_NAME, LAST_NAME, PERSON_ID, ADDRESS_FOR_LETTERS, TELEPHONE, EMAIL_ADRES, PERMISSION_TYPE, USER_PASSWORD
    )
	values 
    (
		 "Jan", "Kowalski", NULL,  "Warszawa Plac Wolnośći 1", "787456876", "JKowalski@gmail.com", "CLIENT", "2f3cf1102675a149392efd5bb6a12aa7"
    );

-- PASSANGERS

Insert into PASSANGERS
	(
		USER_ID, FLIGHT_ID, WEIGHT_OF_LUGGAGE, PAYED
    )
	values 
    (
		 1, 1,  12, false
    );

-- --------------------------------------------------------------
-- TARSHS
-- --------------------------------------------------------------

-- DELETE FROM Planes where plane_id = 1;


    
 
