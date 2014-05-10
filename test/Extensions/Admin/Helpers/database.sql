CREATE TABLE store (
	id       INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	name     VARCHAR NOT NULL,
	location VARCHAR NOT NULL
);

INSERT INTO store(id, name, location) VALUES(1, 'Tesco Express', 'Sheffield');
INSERT INTO store(id, name, location) VALUES(2, 'Sainsburys', 'Sheffield');
INSERT INTO store(id, name, location) VALUES(3, 'Morrisons', 'Retford');

CREATE TABLE product (
	id        INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	name      VARCHAR NOT NULL,
	available BOOLEAN NOT NULL DEFAULT TRUE,
	image     BLOB
);

INSERT INTO product(id, name) VALUES(1, 'Milk');
INSERT INTO product(id, name) VALUES(2, 'Bread');
INSERT INTO product(id, name) VALUES(3, 'Sausage');
INSERT INTO product(id, name) VALUES(4, 'Crisps');
INSERT INTO product(id, name) VALUES(5, 'Cola');
INSERT INTO product(id, name) VALUES(6, 'Cornflakes');

CREATE TABLE employee (
	id         INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	first_name VARCHAR NOT NULL,
	surname    VARCHAR NOT NULL,
	store_id   INTEGER NOT NULL,
	
	FOREIGN KEY(store_id) REFERENCES store(id)
);

INSERT INTO employee(id, first_name, surname, store_id) VALUES(1, 'David', 'Birch', 1);
INSERT INTO employee(id, first_name, surname, store_id) VALUES(2, 'Sophie', 'Trent', 1);
INSERT INTO employee(id, first_name, surname, store_id) VALUES(3, 'Christine', 'Wilby', 2);
INSERT INTO employee(id, first_name, surname, store_id) VALUES(4, 'Paul', 'Williams', 2);