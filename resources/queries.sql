/* POKEMON Page
	JOIN QUERY: Show All Pokemon */
	 SELECT p.pokemonID, p.sname, s.typeName, p.gender, p.locationName
	 FROM Pokemon p, Species s
	 WHERE p.sname = s.sname
	 ORDER BY p.pokemonID ASC;

/* POKEMON Page
	JOIN QUERY: Sort By {Attribute} */
	 SELECT p.pokemonID, p.sname, s.typeName, p.gender, p.locationName
	 FROM Pokemon p, Species s
	 WHERE p.sname = s.sname
	 ORDER BY {$sortbyoption} ASC;

/* POKEMON Page
	JOIN QUERY: Search By PokemonID */
	 SELECT p.pokemonID, p.sname, s.typeName, p.gender, p.locationName
	 FROM Pokemon p, Species s
	 WHERE p.sname = s.sname AND p.pokemonID = {$pokemonID};

/* POKEMON Page
	JOIN QUERY: Sort by Type */
	 SELECT p.pokemonID, p.sname, s.typeName, p.gender, p.locationName
	 FROM Pokemon p, Species s
	 WHERE p.sname = s.sname AND s.typeName = {$typepressed};


/* TRAINER Page
	Select * from trainers */

	SELECT *
	FROM TRAINERS
	WHERE trainerId = {$trainerId};

/* TRAINER Page
	AGGREGATION: Show the quantity of each item that a trainer owns */

	SELECT {$itemName}, count(*) AS {$ItemCount}
	FROM {$TrainerTable} INNER JOIN {$itemsTable}
	WHERE trainerId = {$trainerId};

/* TRAINER Page
	Show the most expensive item a trainer owns */

	SELECT unique {$itemName}, {$itemCost}
	FROM {$TrainerTable} INNER JOIN {$itemsTable}
	WHERE trainerId = {$trainerId} AND {$itemCost} >= ALL(select {$itemCost} from {$itemsTable});

/* TRAINER Page
	AGGREGATION: Show total cost of items for a trainer */

	SELECT SUM({$itemCost})
	FROM {$TrainerTable} INNER JOIN {$itemsTable}
	WHERE trainerId = {$trainerId};

/* TRAINER Page
	JOIN: Show Pokemon info for a trainer */

	SELECT *
	FROM {$TrainerTable}, pokemon
	WHERE {$trainerTable}.trainerId = pokemon.trainerId AND {trainerTable}.trainerId = {$trainerId};

/* ADMIN Page
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


/* ADMIN PAGE
	NESTED AGGREGATION: Number of Trainers with More than 1 Pokemon (Trainers who can battle each other) */

SELECT count(*)
FROM Trainers t
WHERE EXISTS (SELECT p.trainerID, count(*) from Pokemon p WHERE p.trainerID = t.trainerID GROUP BY p.trainerID HAVING count(*) > 1);

/* ADMIN PAGE
	NESTED AGGREGATION Total cost of items for each Trainer who can battle (more than 1 Pokemon) */

SELECT t.trainerName, sum(cost)
FROM Trainers t, Items i
WHERE t.trainerId = i.trainerId AND EXISTS (SELECT p.trainerID, count(*) from Pokemon p WHERE p.trainerID = t.trainerID GROUP BY p.trainerID HAVING count(*) > 1)
GROUP BY t.trainerName;
