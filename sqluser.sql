CREATE USER 'secureuser'@'localhost' IDENTIFIED BY 'test++';
GRANT ALL PRIVILEGES ON * . * TO 'secureuser'@'localhost';
FLUSH PRIVILEGES;
