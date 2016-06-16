/* POKEMON Page
	Show All Pokemon */
	 SELECT p.pokemonID, p.sname, s.typeName, p.gender, p.locationName
	 FROM Pokemon p, Species s
	 WHERE p.sname = s.sname
	 ORDER BY p.pokemonID ASC;

/* POKEMON Page
	 Sort By {Attribute} */
	 SELECT p.pokemonID, p.sname, s.typeName, p.gender, p.locationName
	 FROM Pokemon p, Species s
	 WHERE p.sname = s.sname
	 ORDER BY {$sortbyoption} ASC;

/* POKEMON Page
	Search By PokemonID */
	 SELECT p.pokemonID, p.sname, s.typeName, p.gender, p.locationName
	 FROM Pokemon p, Species s
	 WHERE p.sname = s.sname AND p.pokemonID = {$pokemonID};

/* POKEMON Page
	Sort by Type */
	 SELECT distinct s.sname, s.preevo, s.postevo, s.typename, t.strength, t.weakness
	 FROM  Species s, Type t
	 WHERE s.typename = t.typename AND s.typeName = {$typepressed};


/* TRAINER Page
	Select * from trainers */

	SELECT *
	FROM TRAINERS
	WHERE trainerId = {$trainerId};

/* TRAINER Page
	Show the quantity of each item that a trainer owns */

	SELECT {$itemName}, count(*) AS {$ItemCount}
	FROM {$TrainerTable} INNER JOIN {$itemsTable}
	WHERE trainerId = {$trainerId};

/* TRAINER Page
	Show the most expensive item a trainer owns */

	SELECT unique {$itemName}, {$itemCost}
	FROM {$TrainerTable} INNER JOIN {$itemsTable}
	WHERE trainerId = {$trainerId} AND {$itemCost} >= ALL(select {$itemCost} from {$itemsTable});

/* TRAINER Page
	Show total cost of items for a trainer */

	SELECT SUM({$itemCost})
	FROM {$TrainerTable} INNER JOIN {$itemsTable}
	WHERE trainerId = {$trainerId};

/* TRAINER Page
	 Show Pokemon info for a trainer */

	SELECT *
	FROM {$TrainerTable}, pokemon
	WHERE {$trainerTable}.trainerId = pokemon.trainerId AND {trainerTable}.trainerId = {$trainerId};

/* ADMIN Page
   Find trainers that own at least one pokemon of each type */

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
   Find region with most pokemon */
SELECT region, count(*) as POKEMONPOP
FROM Birthplace b, Pokemon p
WHERE b.locationName = p.locationName
GROUP BY region
ORDER BY POKEMONPOP DESC;

/* ADMIN Page
   Find region with most trainers */
SELECT region, count(*) AS TrainerNumber
FROM Birthplace b, Trainers t
WHERE b.locationName = t.locationName
GROUP BY region
ORDER BY TrainerNumber desc;

/* ADMIN Page
   Update Field */

update Pokemon set {$updatefield}='{$updatevalue}' where pokemonId='{$pid}');

/* ADMIN Page
   Insert Field */

insert into SPECIES values ('{$sname}', '{$postEvo}', '{$preEvo}', '{$type}');

insert into TRAINERS values ('{$tid}', '{$tname}', '{$gender}', '{$lname}');

insert into POKEMON values ('{$pid}', '{$species}', '{$gender}', '{$name}', '{$location}', '{$tid}');

/* ADMIN Page
   Delete Field */
delete from POKEMON where pokemonId='{$pid}';

delete from SPECIES where sname='{$sname}';

delete from TRAINERS where trainerId = '{$tid}';



/* ADMIN Page
	Number of Trainers with More than 1 Pokemon (Trainers who can battle each other) */

SELECT count(*)
FROM Trainers t
WHERE EXISTS (SELECT p.trainerID, count(*) from Pokemon p WHERE p.trainerID = t.trainerID GROUP BY p.trainerID HAVING count(*) > 1);

/* ADMIN Page
	Total cost of items for each Trainer who can battle (more than 1 Pokemon) */

SELECT t.trainerName, sum(cost)
FROM Trainers t, Items i
WHERE t.trainerId = i.trainerId AND EXISTS (SELECT p.trainerID, count(*) from Pokemon p WHERE p.trainerID = t.trainerID GROUP BY p.trainerID HAVING count(*) > 1)
GROUP BY t.trainerName;

/* ADMIN Page
	Avg cost of items for each trainer who can battle (more than 1 Pokemon) */

SELECT t.trainerName, avg(cost)
FROM Trainers t, Items i
WHERE t.trainerId = i.trainerId AND EXISTS (SELECT p.trainerID, count(*) from Pokemon p WHERE p.trainerID = t.trainerID GROUP BY p.trainerID HAVING count(*) > 1)
GROUP BY t.trainerName;