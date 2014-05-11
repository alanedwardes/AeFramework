PRAGMA foreign_keys = ON;

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

CREATE TABLE store_product (
	id         INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	store_id   INTEGER NOT NULL,
	product_id INTEGER NOT NULL,
	
	CONSTRAINT store_product_store_id   FOREIGN KEY(store_id)   REFERENCES store(id)   ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT store_product_product_id FOREIGN KEY(product_id) REFERENCES product(id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO store_product(store_id, product_id) VALUES(1, 1);
INSERT INTO store_product(store_id, product_id) VALUES(1, 2);
INSERT INTO store_product(store_id, product_id) VALUES(1, 3);
INSERT INTO store_product(store_id, product_id) VALUES(2, 3);
INSERT INTO store_product(store_id, product_id) VALUES(2, 4);
INSERT INTO store_product(store_id, product_id) VALUES(2, 5);
INSERT INTO store_product(store_id, product_id) VALUES(3, 6);

CREATE TABLE employee (
	id         INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	first_name VARCHAR NOT NULL,
	surname    VARCHAR NOT NULL,
	store_id   INTEGER NOT NULL,
	
	CONSTRAINT employee_store_id FOREIGN KEY(store_id) REFERENCES store(id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO employee(id, first_name, surname, store_id) VALUES(1, 'David', 'Birch', 1);
INSERT INTO employee(id, first_name, surname, store_id) VALUES(2, 'Sophie', 'Trent', 1);
INSERT INTO employee(id, first_name, surname, store_id) VALUES(3, 'Christine', 'Wilby', 2);
INSERT INTO employee(id, first_name, surname, store_id) VALUES(4, 'Paul', 'Williams', 2);