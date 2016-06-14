
/* Administration Page Queries*/

SELECT distinct t.trainerName
FROM Trainers t, Pokemon p, Species s, Type type
WHERE t.trainerId = p.trainerId
		AND p.sname = s.sname
		AND s.typeName = type.typeName
		AND NOT EXISTS
		((SELECT type.typeName
			FROM Type)
			MINUS
			(SELECT type1.typeName
			FROM Trainers t1, Pokemon p1, Species s1, Type type1
			WHERE t1.trainerId = p1.trainerId
					AND p1.sname = s1.sname
					AND s1.typeName = type1.typeName
					AND t.trainerId = t1.trainerId));


SELECT region, count(*) as POKEMONPOP
FROM Birthplace b, Pokemon p
WHERE b.locationName = p.locationName
GROUP BY region
ORDER BY POKEMONPOP DESC;

SELECT region, count(*) AS TrainerNumber
FROM Birthplace b, Trainers t
WHERE b.locationName = t.locationName
GROUP BY region
ORDER BY TrainerNumber desc;

update Pokemon set {$updatefield}='{$updatevalue}' where pokemonId='{$pid}');

insert into SPECIES values ('{$sname}', '{$postEvo}', '{$preEvo}', '{$type}');

insert into TRAINERS values ('{$tid}', '{$tname}', '{$gender}', '{$lname}');

insert into POKEMON values ('{$pid}', '{$species}', '{$gender}', '{$name}', '{$location}', '{$tid}');

delete from POKEMON where pokemonId='{$pid}';

delete from SPECIES where sname='{$sname}';

delete from TRAINERS where trainerId = '{$tid}';



/*Pokemon Page Queries */

SELECT trainerName, itemName as Poke_Ball, count(itemName) as Number_of_Balls
FROM Trainers t, Items i
WHERE i.trainerId = t.trainerId
GROUP BY trainerName, itemName
ORDER BY trainerName;


/* Trainers Page Queries */
CREATE VIEW pokemon_in_area AS
(SELECT b.locationName, count(trainerId) AS TRAINERCOUNT
			 FROM Birthplace b, Trainers t
		 	 WHERE b.locationName = t.locationName
			 GROUP BY b.locationName);

CREATE VIEW trainer_in_area AS
(SELECT b.locationName, count(pokemonID) AS POKEMONCOUNT
		 	 FROM Birthplace b, Pokemon p
		   WHERE b.locationName = p.locationName
		   GROUP BY b.locationName);

SELECT locationName
FROM trainer_in_area NATURAL FULL OUTER JOIN pokemon_in_area
WHERE POKEMONCOUNT > TRAINERCOUNT;
