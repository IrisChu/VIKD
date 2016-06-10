
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


/* ADMIN Page
   AGGREGATION QUERY: Find the number of each type of pokeball owned by each trainer*/

SELECT trainerName, itemName as Poke_Ball, count(itemName) as Number_of_Balls
FROM Trainers t, Items i
WHERE i.trainerId = t.trainerId
GROUP BY trainerName, itemName
ORDER BY trainerName;


/* TRAINERS PAGE
	NESTED AGGREGATION: Find locations with more trainers than pokemon */

SELECT locationName
FROM Birthplace b
WHERE (SELECT count(trainerId) AS countT
			 FROM Birthplace b1, Trainers t1
		 	 WHERE b1.locationName = t1.locationName
			 GROUP BY b1.locationName
			 having counT < ( select count(pokemonID)
		 	 from Birthplace b2, Pokemon p1
		   where b2.locationName = p1.locationName AND b2.locationName = b1.locationName
		   GROUP BY b2.locationName));
