-- DADES A USER

INSERT INTO users (name, lastName, email, birthday, profileImg, username, password, role) VALUES ('bartok', 'algun algun', 'reg.bartok@gmail.com', '2015-10-10', 'path', 'bartok', MD5('123456'), 2);
INSERT INTO users (name, lastName, email, birthday, profileImg, username, password, role) VALUES ('victor', 'algun algun', 'victor@gmail.com', '2015-10-10', 'path', 'bartok', MD5('1234'), 2);

-- DADES A CENTRES

INSERT INTO centers (name, city, zipCode, address) VALUES ('centre1', 'Barcelona', '0850', 'algun carrer');
INSERT INTO centers (name, city, zipCode, address) VALUES ('centre2', 'Barcelona', '0850', 'algun carrer');
INSERT INTO centers (name, city, zipCode, address) VALUES ('centre3', 'Barcelona', '0850', 'algun carrer');
INSERT INTO centers (name, city, zipCode, address) VALUES ('centre4', 'Barcelona', '0850', 'algun carrer');
INSERT INTO centers (name, city, zipCode, address) VALUES ('centre5', 'Barcelona', '0850', 'algun carrer');

--DADES A INSCRIPTIONS

INSERT INTO inscriptions (users_id, centers_id, startYear, endYear) VALUES (1, 2, 2015, 2016);
INSERT INTO inscriptions (users_id, centers_id, startYear, endYear) VALUES (1, 3, 2015, 2016);
INSERT INTO inscriptions (users_id, centers_id, startYear, endYear) VALUES (1, 4, 2015, 2016);

-- DADES A CONNECITONS

INSERT INTO connections (idcenter1, idcenter2, name, startDate, endDate) VALUES (1, 2, 'conn1', '2015-10-10', '2018-10-10');
INSERT INTO connections (idcenter1, idcenter2, name, startDate, endDate) VALUES (1, 2, 'conn2', '2015-10-10', '2018-10-10');
INSERT INTO connections (idcenter1, idcenter2, name, startDate, endDate) VALUES (1, 2, 'conn3', '2015-10-10', '2018-10-10');