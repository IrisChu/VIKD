  drop table Pokemon;
  drop table Items;
  drop table Trainers;
  drop table Species;
  drop table Birthplace;
  drop table Type;

  CREATE TABLE Type
    (typeName varchar(20) PRIMARY KEY,
    weakness varchar(20),
    strength varchar(20));

  CREATE TABLE Birthplace
    (locationName varchar(30) PRIMARY KEY,
    region varchar(20));

  CREATE TABLE Species
    (sname varchar(20) PRIMARY KEY,
    postEvo varchar(20),
    preEvo varchar(20),
    typeName varchar(20),
    FOREIGN KEY (typeName) REFERENCES Type(typeName));

  CREATE TABLE Trainers
    (trainerId integer PRIMARY KEY,
    trainerName varchar(30),
    gender varchar(10),
    locationName varchar(30) NOT NULL,
    FOREIGN KEY (locationName) REFERENCES Birthplace(locationName));

  CREATE TABLE Items
    (itemId integer PRIMARY KEY,
    itemName varchar(20),
    cost integer,
    trainerId integer NOT NULL,
    FOREIGN KEY (trainerId) REFERENCES Trainers ON DELETE CASCADE);

  CREATE TABLE Pokemon
    (pokemonId integer PRIMARY KEY,
    sname varchar(20),
    gender varchar(6),
    pokemonName varchar(20),
    locationName varchar(30) NOT NULL,
    trainerId integer,
    FOREIGN KEY (locationName) REFERENCES Birthplace(locationName),
    FOREIGN KEY (trainerId) REFERENCES Trainers(trainerId) ON DELETE SET NULL,
    FOREIGN KEY (sname) REFERENCES Species(sname),
    CHECK (sname<>pokemonName));

  insert into type
    values ('Fire', 'Water', 'Grass');

  insert into type
    values ('Psychic', 'Psychic', 'Poison');

  insert into type
    values ('Grass', 'Fire', 'Water');

  insert into type
    values ('Lightning', 'Grass' ,'Water');

  insert into type
    values ('Water', 'Lightning', 'Fire');

  insert into type
    values ('Rock', 'Ground', 'Fire');

  insert into Birthplace
    values ('Azure Bay', 'Kalos');

  insert into Birthplace
    values ('Lagoon Town', 'Kalos');

  insert into Birthplace
    values ('Amity Square', 'Sinnoh');

  insert into Birthplace
    values ('Eterna City', 'Sinnoh');

  insert into Birthplace
    values ('Bell Tower', 'Hoenn');

  insert into Birthplace
    values ('Small Court', 'Unova');

  insert into Birthplace
    values ('Dreamyard', 'Unova');

  insert into Birthplace
    values ('Roshan City', 'Unova');

  insert into species
    values ('Pikachu', 'Raichu', NULL, 'Lightning');

  insert into species
    values ('Charmander', 'Charmeleon', NULL, 'Fire');

  insert into species
    values ('Geodude', 'Graveler', NULL, 'Rock');

  insert into species
    values ('Magikarp', 'Gyarados', NULL, 'Water');

  insert into species
    values ('Haunter', 'Gengar', 'Ghastly', 'Psychic');

  insert into species
    values ('Scyther',  'Scizor', NULL,'Grass');

  insert into trainers
    values (001, 'Red', 'Male', 'Azure Bay');

  insert into trainers
    values (002, 'Kris', 'Female', 'Amity Square');

  insert into trainers
    values (003, 'May', 'Female', 'Azure Bay');

  insert into trainers
    values (004, 'Nate', 'Male', 'Dreamyard');

  insert into trainers
    values (005, 'Helio', 'Male', 'Small Court');

  insert into Items
    values (001, 'Adamant Orb', 500, 001);

  insert into Items
    values (002, 'Cheri Berry', 30, 005);

  insert into Items
    values (003, 'Lava Cookie', 50, 003);

  insert into Items
    values (004, 'Love Ball', 400, 001);

  insert into Items
    values (005, 'Potion', 100, 002);

  insert into Items
    values (006, 'Love Ball', 400, 001);

  insert into Items
    values (007, 'Poke Ball', 200, 004);

  insert into Items
    values (008, 'Grass Ball', 500, 005);

  insert into Items
    values (009, 'Love Ball', 400, 005);

  insert into Items
    values (010, 'Poke Ball', 200, 003);

  insert into Items
    values (011, 'Master Ball', 800, 001);

  insert into Items
    values (012, 'Master Ball', 800, 004);

  insert into Items
    values (013, 'Ultra Ball', 700, 001);

  insert into Items
    values (014, 'Ultra Ball', 700, 001);

  insert into pokemon
    values (001, 'Pikachu', 'Female', 'Ryan', 'Azure Bay', 002);

  insert into pokemon
    values (002, 'Charmander', 'Male', 'Dan', 'Bell Tower', 003);

  insert into pokemon
    values (003, 'Charmander', 'Female', 'Chan', 'Roshan City', 003);

  insert into pokemon
    values (004, 'Geodude', 'Male', 'Hann', 'Small Court', 001);

  insert into pokemon
    values (005, 'Magikarp', 'Female', NULL, 'Dreamyard', NULL);

  insert into pokemon
    values (006, 'Scyther', 'Female', 'Dany', 'Eterna City', 001);

  insert into pokemon
    values (007, 'Charmander', 'Female', 'Char', 'Small Court', 001);

  insert into pokemon
    values (008, 'Pikachu', 'Male', 'Timothy', 'Bell Tower', 001);

  insert into pokemon
    values (009, 'Magikarp', 'Female', 'Amy', 'Azure Bay', 001);

  insert into pokemon
    values (010, 'Haunter', 'Male', 'Ryan', 'Small Court', 001);
