
/* POKEMON Page
   DIVISION QUERY: Find trainers that own at least one pokemon of each type */

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


/* ADMINISTRATION Page
   AGGREGATION QUERY: Find the number of each type of pokeball owned by each trainer*/

SELECT trainerName, itemName as Poke_Ball, count(itemName) as Number_of_Balls
FROM Trainers t, Items i
WHERE i.trainerId = t.trainerId
GROUP BY trainerName, itemName
ORDER BY trainerName;


/* TRAINERS PAGE
	NESTED AGGREGATION: Find locations with more trainers than pokemon */

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
