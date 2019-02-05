-- dodaj lot

Insert into PASSANGERS
	(users
		USER_ID, FLIGHT_ID, WEIGHT_OF_LUGGAGE, PAYED
    )
    values
	((select user_id from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q where username = "noname10"), 1, 1, false);

-- przeglad lotow
select f.flight_id as fid, f.departure_date,
(select a.city from airports a right join flights f on a.airport_id=f.start_airport_id where f.flight_id = fid limit 1) as start,
(select a.city from airports a right join flights f on a.airport_id=f.finish_airport_id  where f.flight_id = fid limit 1) as finish,
f.ticket_price
from flights f
order by f.departure_date asc;

-- przeglad lotow z danym id
select f.flight_id as fid, f.departure_date,
(select a.city from airports a right join flights f on a.airport_id=f.start_airport_id where f.flight_id = fid limit 1) as start,
(select a.city from airports a right join flights f on a.airport_id=f.finish_airport_id  where f.flight_id = fid limit 1) as finish,
f.ticket_price
from flights f
where f.flight_id = 2
order by f.departure_date asc;

-- wyszukanie polaczenia przez podanie startu i finishu > W->L
select * from (select f.flight_id as fid, f.departure_date,
(select a.city from airports a right join flights f on a.airport_id=f.start_airport_id where f.flight_id = fid limit 1) as fstart,
(select a.city from airports a right join flights f on a.airport_id=f.finish_airport_id  where f.flight_id = fid limit 1) as ffinish,
f.ticket_price
from flights f
order by f.departure_date asc) as t
where fstart = 'Warsaw' and ffinish = 'London';

-- dane osobowe pasazerow, dla przykladu lot 2
select distinct p.flight_id, pr.first_name, pr.last_name, pr.address_for_letters, pr.telephone, pr.email_adres
from passangers p
join users u on u.user_id = p.user_id
join persons pr on u.person_id = pr.person_id
where p.flight_id = 2;

-- ktore samoloty sa dostepne
select distinct p.plane_id, p.name_of_plane, p.model, p.provider, p.capacity
from planes p
where p.plane_id not in (
	select f.plane_id from flights f);
    
-- przeglad danych kadry
select distinct p.first_name, p.last_name, pl.possition, p.address_for_letters, p.telephone, p.email_adres
from persons p
join pilots pl on pl.person_id=p.person_id;

Insert into PASSANGERS
			(USER_ID, FLIGHT_ID, WEIGHT_OF_LUGGAGE, PAYED)
			values 
			((select person_id from persons where USERNAME = "noname"), 2, 1, false);
            
            Insert into PASSANGERS
			(USER_ID, FLIGHT_ID, WEIGHT_OF_LUGGAGE, PAYED)
		    values
			((select user_id from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q where username = "noname2"), 2, 1, false);

            

-- ----------------
-- wyszykanie lotów na które dany user kupił bilet 

select flight_id 
from passangers
where
user_id = (select user_id from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q where username = "noname2");

(select count(p.user_id) from passangers p right join flights f  on p.FLIGHT_ID=f.FLIGHT_ID
	where p.user_id = (
		select user_id from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q where username = "noname2")
	GROUP BY p.flight_id
) as my_number,



select f.flight_id as fid, f.departure_date,
(select a.city from airports a right join flights f on a.airport_id=f.start_airport_id where f.flight_id = fid limit 1) as start,
(select a.city from airports a right join flights f on a.airport_id=f.finish_airport_id  where f.flight_id = fid limit 1) as finish,
(select p.passanger_id from passangers p right join flights f on p.flight_id=f.flight_id  where f.flight_id = fid limit 1) as gowno,
f.ticket_price
from flights f
where f.flight_id in 
	(select flight_id
	from passangers
	where
	user_id = (select user_id from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q where username = "noname")
    GROUP BY passanger_id
    ) 
order by f.departure_date asc
;
airports

select f.flight_id as fid, f.departure_date,
(select a.city from airports a right join flights f on a.airport_id=f.start_airport_id where f.flight_id = fid limit 1) as start,
(select a.city from airports a right join flights f on a.airport_id=f.finish_airport_id  where f.flight_id = fid limit 1) as finish,
f.ticket_price
from flights f
where f.flight_id in 
	(select flight_id
	from passangers
	where
	user_id = (select user_id from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q where username = "noname")
    GROUP BY passanger_id
    ) 
order by f.departure_date asc
;

count(p.passanger_id)

select q.passanger_id, q.flight_id, q.DEPARTURE_DATE, q.TICKET_PRICE, q.PAYED
from 
(select * from passangers p right join flights f on p.FLIGHT_ID = f.FLIGHT_ID) q;

select q.passanger_id, q.flight_id, q.DEPARTURE_DATE, q.TICKET_PRICE, q.PAYED
from 
(select p.PASSANGER_ID, p.FLIGHT_ID, p.USER_ID, f.START_AIRPORT_ID, f.FINISH_AIRPORT_ID f.DEPARTURE_DATE from passangers p right join flights f on p.FLIGHT_ID = f.FLIGHT_ID) q;


select q.passanger_id, q.user_id, q.flight_id, q.departure_date, q.ticket_price, q.payed, q.START_AIRPORT_ID, q.FINISH_AIRPORT_ID 
from
(
	select p.passanger_id, p.user_id, p.flight_id, f.departure_date, f.ticket_price, p.payed, f.START_AIRPORT_ID, f.FINISH_AIRPORT_ID
	from passangers p join flights f on p.flight_id = f.flight_id
	where p.user_id = (select user_id from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q where username = "noname")
)q;

select q.passanger_id, q.user_id, q.flight_id, q.departure_date, q.ticket_price, q.payed, q.START_AIRPORT_ID, q.FINISH_AIRPORT_ID 
from
(
	select p.passanger_id, p.user_id, p.flight_id, f.departure_date, f.ticket_price, p.payed, f.START_AIRPORT_ID, f.FINISH_AIRPORT_ID
	from passangers p join flights f on p.flight_id = f.flight_id
	where p.user_id = (select user_id from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q where username = "noname")
) mquery right join mquery q;


-- ----------------------------------------------
select 
(select ps.passanger_id from passangers ps right join flights f on ps.flight_id=f.flight_id where ps.flight_id=f.flight_id limit 1) as passangerID,

select
 f.flight_id as fid, f.departure_date,
(select a.city from airports a right join flights f on a.airport_id=f.start_airport_id where f.flight_id = fid limit 1) as start,
(select a.city from airports a right join flights f on a.airport_id=f.finish_airport_id  where f.flight_id = fid limit 1) as finish,
f.ticket_price,
(select ps.payed from passangers ps right join flights f on ps.flight_id=f.flight_id where ps.flight_id=f.flight_id limit 1) as payed
from flights f
where f.flight_id in 
	(select flight_id
	from passangers
	where
	user_id = (select user_id from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q where username = "noname")
    GROUP BY passanger_id
    )
order by f.departure_date asc;
-- ----------------------------------------------

UPDATE passangers SET payed = 1 where passanger_id = 7;

-- ----------------------------------------------

Select * from FLIGHTS;

DELETE FROM planes WHERE PLANE_ID=4;
DELETE FROM planes WHERE PLANE_ID=1;
DELETE FROM planes WHERE PLANE_ID=5;

Select * from planes;

Select * from passangers;

DELETE FROM PASSANGERS WHERE PASSANGER_ID=5;

DELETE FROM FLIGHTS WHERE FLIGHT_ID = 3;

-- -------------------------------------------------
-- check if user have bought ticket on this flight

Select * from passangers
	where user_id =  (select user_id from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q where username = "noname")
    and
	flight_id = 3;

-- -------------------------------------------------

select * from flight;

select * from passangers;

delete from passangers where  user_id = 4 and flight_id = 3;
-- ---------------------------------------------------
Select * from persons;
Select * from users;

DELETE FROM PERSONS WHERE USERNAME = "misia123";

Select * from PILOTS;

DELETE FROM pilots WHERE pilot_id = 1;

Select * from PASSANGERS;

DELETE FROM PASSANGERS WHERE PASSANGER_ID=5;

DELETE FROM planes WHERE PLANE_ID=4;

DELETE FROM PERSONS WHERE PERSON_ID = 1;
DELETE FROM PERSONS WHERE PERSON_ID = 5;
DELETE FROM PERSONS WHERE PERSON_ID = 6;



SET FOREIGN_KEY_CHECKS=1; -- to re-enable them
SET FOREIGN_KEY_CHECKS=0; -- to re-enable them

--  ------------------------------------------------------------------------

DROP VIEW widok;
CREATE VIEW widok AS
select p.passanger_id, p.user_id, p.flight_id, f.departure_date, f.ticket_price, p.payed, f.START_AIRPORT_ID, f.FINISH_AIRPORT_ID
	from passangers p join flights f on p.flight_id = f.flight_id;
    
    
select w.passanger_id, w.flight_id
 from widok;
 
select  w.passanger_id, w.flight_id as fid, w.user_id, w.departure_date, w.ticket_price, w.payed,
(select a.city from airports a right join widok w on a.airport_id=w.start_airport_id where w.flight_id = fid limit 1) as start,
(select a.city from airports a right join widok w on a.airport_id=w.finish_airport_id  where w.flight_id = fid limit 1) as finish
 from widok w
 where user_id =  (select user_id from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q where username = "noname");
 
 select b.passanger_id, b.flight_id as fid, b.user_id, b.departure_date, b.ticket_price, b.payed,
(select a.city from airports a right join booked b on a.airport_id=b.start_airport_id where b.flight_id = fid limit 1) as start,
(select a.city from airports a right join booked b on a.airport_id=b.finish_airport_id  where b.flight_id = fid limit 1) as finish
 from booked b
 where user_id = 
 (select user_id from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q where username = "noname");


--  ------------------------------------------------------------------------

select	f.flight_id as fid, 
		f.departure_date,
        (select a.city from airports a where a.airport_id =
			(select START_AIRPORT_ID from flights where FLIGHT_ID = fid)) as start,
        (select a.city from airports a where a.airport_id =
			(select FINISH_AIRPORT_ID from flights where FLIGHT_ID = fid)) as finish,
		f.TICKET_PRICE
from	flights f
where	f.departure_date > curdate()
 order by f.departure_date asc;   
 
 SELECT PERSON_ID, USERNAME, USER_PASSWORD FROM PERSONS WHERE USERNAME ="noname";
 
  
 SELECT * FROM PERSONS WHERE USERNAME ="noname";
 
SELECT p.PERSON_ID, p.USERNAME, p.USER_PASSWORD, u.PERMISSION_TYPE 
FROM PERSONS p  right join users u on p.person_id=u.person_id
WHERE USERNAME ="noname";

SELECT p.USERNAME, p.first_name, p.last_name, p.address_for_letters, p.telephone, p.email_adres
FROM PERSONS p  right join users u on p.person_id=u.person_id
WHERE PERMISSION_TYPE ="EMPLOYEE";


Insert into PILOTS
		(PERSON_ID, POSSITION, SALARY)
	values 
		(1, "CAPITAN", 5000.00),
        (2, "CAPITAN", 5000.00),
        (3, "FIRST OFFICER", 9999.00),
        (4, "FIRST OFFICER", 13.50);
        
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

SELECT u.user_id, p.USERNAME, p.first_name, p.last_name, p.address_for_letters, p.telephone, p.email_adres
FROM PERSONS p  right join users u on p.person_id=u.person_id;

WHERE u.FLIGHT_ID =1;


select p.USERNAME, p.first_name, p.last_name, p.address_for_letters, p.telephone, p.email_adres
from view_person_onUserId p right join FLIGHTS f

select p.USERNAME, p.first_name, p.last_name, p.address_for_letters, p.telephone, p.email_adres 
from PASSANGERS u right join view_person_onUserId p on p.user_id = u.user_id
where FLIGHT_ID = 2;


CREATE TABLE PASSANGERS
(  
	PASSANGER_ID INT primary key not null auto_increment,
	USER_ID INT not null,
    FLIGHT_ID INT not null, 	
    WEIGHT_OF_LUGGAGE DECIMAL(5,3),
    PAYED BOOL default false,