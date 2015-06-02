CREATE SCHEMA IF NOT EXISTS `creditSintesi` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `creditSintesi` ;

-- -----------------------------------------------------
-- Table `mydb`.`teachersValidations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `creditSintesi`.`teachersValidations` (
  `orderNum` INT UNSIGNED NOT NULL,
  `name` VARCHAR(100) NULL,
  `lastName` VARCHAR(100) NULL,
  `used` VARCHAR(100) NULL DEFAULT 'no',
  PRIMARY KEY (`orderNum`),
  UNIQUE INDEX `orderNum_UNIQUE` (`orderNum` ASC)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`users`
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `creditSintesi`.`users` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100) NOT NULL,
	`lastName` VARCHAR(100) NOT NULL,
	`email` VARCHAR(50) NOT NULL,
	`birthday` DATE NOT NULL,
	`profileImg` VARCHAR(200) NOT NULL,
	`username` VARCHAR(50) NOT NULL,
	`password` VARCHAR(45) NOT NULL,
	`role` TINYINT(3) UNSIGNED NOT NULL COMMENT '1 - Alumnes\\n2 - Professors\\n3 - Admin',
	`orderNum` INT(10) UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `idx_orderNum` (`orderNum`),
	UNIQUE INDEX `orderNum` (`orderNum`),
	UNIQUE INDEX `email` (`email`),
	UNIQUE INDEX `username` (`username`),
	CONSTRAINT `users_teachersValidations` FOREIGN KEY (`orderNum`) REFERENCES `teachersValidations` (`orderNum`)
)
ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`centers`
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `creditSintesi`.`centers` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `city` VARCHAR(30) NOT NULL,
  `zipCode` VARCHAR(10) NOT NULL,
  `address` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`teams`
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `creditSintesi`.`teams` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,	
  `startDate` DATE NOT NULL,
  `endDate` DATE NOT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`projects`
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `creditSintesi`.`projects` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(50) NOT NULL,	
	`startDate` DATE NOT NULL,
	`endDate` DATE NOT NULL,
	`description` TEXT NOT NULL,
	`outdated` INT(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
)
ENGINE=InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`connections`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `creditSintesi`.`connections` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idcenter1` INT(11) UNSIGNED NOT NULL,
  `idcenter2` INT(11) UNSIGNED NOT NULL,
  `name` VARCHAR(50) NOT NULL,  
  `startDate` DATE NOT NULL,
  `endDate` DATE NOT NULL,
  `outdated` INT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_connections_centers1_idx` (`idcenter1`),
  INDEX `fk_connections_centers2_idx` (`idcenter2`),
  CONSTRAINT `fk_connections_centers1` FOREIGN KEY (`idcenter1`) REFERENCES `centers` (`id`),
  CONSTRAINT `fk_connections_centers2` FOREIGN KEY (`idcenter2`) REFERENCES `centers` (`id`)
  )
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`connectionsProjects`
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `creditSintesi`.`connectionsProjects` (
  `connections_id` INT(11) UNSIGNED NOT NULL,
  `projects_id` INT(11) UNSIGNED NOT NULL,
  INDEX `fk_centers_projects_idx` (`connections_id`),
  INDEX `fk_centers_projects1_idx` (`projects_id`),
  CONSTRAINT `fk_centers_projects` FOREIGN KEY (`connections_id`) REFERENCES `connections` (`id`),
  CONSTRAINT `fk_centers_projects1` FOREIGN KEY (`projects_id`) REFERENCES `projects` (`id`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`inscriptions`
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `creditSintesi`.`inscriptions` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`users_id` INT(10) UNSIGNED NOT NULL,
	`centers_id` INT(10) UNSIGNED NOT NULL,
	`startYear` INT(4) UNSIGNED NOT NULL,
	`endYear` INT(4) UNSIGNED NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `fk_inscription_user_idx` (`centers_id`),
	INDEX `fk_inscription_centers_idx` (`users_id`),
	CONSTRAINT `fk_inscription_centers` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
	CONSTRAINT `fk_inscription_user` FOREIGN KEY (`centers_id`) REFERENCES `centers` (`id`)
)
ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`inscriptionsTeams`
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `creditSintesi`.`inscriptionsTeams` (
 	`teams_id` INT(11) UNSIGNED NOT NULL,
	`inscription_id` INT(11) UNSIGNED NOT NULL,
	INDEX `fk_inscriptions_inscriptions_idx` (`inscription_id`),
	INDEX `fk_inscription_teams_idx` (`teams_id`),
	CONSTRAINT `fk_inscription_teams` FOREIGN KEY (`teams_id`) REFERENCES `teams` (`id`),
	CONSTRAINT `fk_inscriptions_inscriptions` FOREIGN KEY (`inscription_id`) REFERENCES `inscriptions` (`id`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`teamsProjects`
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `creditSintesi`.`teamsProjects` (
	`projects_id` INT(11) UNSIGNED NOT NULL,
	`teams_id` INT(11) UNSIGNED NOT NULL,  
    INDEX `fk_teams_projects_idx` (`projects_id`),
    INDEX `fk_teams_projects1_idx` (`teams_id`),
  	CONSTRAINT `fk_teams_projects` FOREIGN KEY (`projects_id`) REFERENCES `projects` (`id`),
  	CONSTRAINT `fk_teams_projects1` FOREIGN KEY (`teams_id`) REFERENCES `teams` (`id`)  
)
ENGINE = InnoDB;
