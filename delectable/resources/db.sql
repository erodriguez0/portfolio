SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema delectable
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `delectable` DEFAULT CHARACTER SET utf8 ;
USE `delectable` ;

-- -----------------------------------------------------
-- Table `delectable`.`administrator`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`administrator` ;

CREATE TABLE IF NOT EXISTS `delectable`.`administrator` (
  `admin_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_first_name` VARCHAR(64) NOT NULL,
  `admin_last_name` VARCHAR(64) NOT NULL,
  `admin_username` VARCHAR(64) NOT NULL,
  `admin_password` VARCHAR(255) NOT NULL,
  `admin_email` VARCHAR(255) NOT NULL,
  `admin_access` INT UNSIGNED NOT NULL,
  `admin_status` INT UNSIGNED NOT NULL,
  `admin_last_login` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_gender` VARCHAR(32) NOT NULL,
  `admin_birth_date` DATE NOT NULL,
  `admin_address_1` VARCHAR(64) NOT NULL,
  `admin_address_2` VARCHAR(64) NULL,
  `admin_city` VARCHAR(64) NOT NULL,
  `admin_state` VARCHAR(64) NOT NULL,
  `admin_postal_code` VARCHAR(64) NOT NULL,
  `admin_phone` VARCHAR(64) NOT NULL,
  `admin_hire_date` DATE NOT NULL,
  `admin_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`admin_id`))
ENGINE = InnoDB;

CREATE UNIQUE INDEX `admin_username_UNIQUE` ON `delectable`.`administrator` (`admin_username` ASC) VISIBLE;

CREATE UNIQUE INDEX `admin_email_UNIQUE` ON `delectable`.`administrator` (`admin_email` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `delectable`.`restaurant`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`restaurant` ;

CREATE TABLE IF NOT EXISTS `delectable`.`restaurant` (
  `res_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `res_name` VARCHAR(128) NOT NULL,
  `res_slogan` VARCHAR(128) NULL,
  `res_description` TEXT NULL,
  `res_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `res_updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`res_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `delectable`.`location`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`location` ;

CREATE TABLE IF NOT EXISTS `delectable`.`location` (
  `loc_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `loc_address_1` VARCHAR(64) NOT NULL,
  `loc_address_2` VARCHAR(64) NULL,
  `loc_city` VARCHAR(64) NOT NULL,
  `loc_state` VARCHAR(64) NOT NULL,
  `loc_postal_code` VARCHAR(64) NOT NULL,
  `loc_phone` VARCHAR(64) NOT NULL,
  `loc_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `loc_updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `loc_timeslot` INT UNSIGNED NOT NULL DEFAULT 60,
  `loc_reservation_fee` DECIMAL(4,2) UNSIGNED NULL,
  `loc_overtime` INT UNSIGNED NOT NULL DEFAULT 30,
  `loc_overtime_fee` DECIMAL(4,2) UNSIGNED NULL,
  `fk_res_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`loc_id`),
  CONSTRAINT `fk_location_res_id`
    FOREIGN KEY (`fk_res_id`)
    REFERENCES `delectable`.`restaurant` (`res_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_res_id_idx` ON `delectable`.`location` (`fk_res_id` ASC) VISIBLE;

-- -----------------------------------------------------
-- Table `delectable`.`location_hours`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`location_hours` ;

CREATE TABLE IF NOT EXISTS `delectable`.`location_hours` (
  `hours_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hours_day` INT UNSIGNED NOT NULL,
  `hours_open` TIME NOT NULL,
  `hours_close` TIME NOT NULL,
  `hours_valid_from` DATE DEFAULT NULL,
  `hours_valid_thru` DATE DEFAULT NULL,
  `fk_loc_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`hours_id`),
  CONSTRAINT `fk_location_hours_loc_id`
    FOREIGN KEY (`fk_loc_id`)
    REFERENCES `delectable`.`location` (`loc_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_loc_id_idx` ON `delectable`.`location_hours` (`fk_loc_id` ASC) VISIBLE;

-- -----------------------------------------------------
-- Table `delectable`.`employee`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`employee` ;

CREATE TABLE IF NOT EXISTS `delectable`.`employee` (
  `emp_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `emp_first_name` VARCHAR(64) NOT NULL,
  `emp_last_name` VARCHAR(64) NOT NULL,
  `emp_username` VARCHAR(64) NOT NULL,
  `emp_password` VARCHAR(255) NOT NULL,
  `emp_email` VARCHAR(255) NOT NULL,
  `emp_status` INT UNSIGNED NOT NULL DEFAULT 1,
  `emp_last_login` TIMESTAMP NULL DEFAULT NULL,
  `emp_gender` VARCHAR(32) NULL,
  `emp_birth_date` DATE NULL,
  `emp_address_1` VARCHAR(64) NULL,
  `emp_address_2` VARCHAR(64) NULL,
  `emp_city` VARCHAR(64) NULL,
  `emp_state` VARCHAR(64) NULL,
  `emp_postal_code` VARCHAR(64) NULL,
  `emp_phone` VARCHAR(64) NULL,
  `emp_pay_rate` VARCHAR(64) NULL,
  `emp_pay` DECIMAL(15,2) NULL,
  `emp_job` VARCHAR(64) NULL,
  `emp_manager` BIT(1) NOT NULL DEFAULT 0,
  `emp_hire_date` DATE NULL,
  `emp_dismissed` TIMESTAMP NULL,
  `emp_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `emp_updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `fk_loc_id` INT UNSIGNED NULL,
  PRIMARY KEY (`emp_id`),
  CONSTRAINT `fk_employee_loc_id`
    FOREIGN KEY (`fk_loc_id`)
    REFERENCES `delectable`.`location` (`loc_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `emp_email_UNIQUE` ON `delectable`.`employee` (`emp_email` ASC) VISIBLE;

CREATE INDEX `fk_loc_id_idx` ON `delectable`.`employee` (`fk_loc_id` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `delectable`.`salary`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`salary` ;

CREATE TABLE IF NOT EXISTS `delectable`.`salary` (
  `salary_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `salary_start_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `salary_end_date` TIMESTAMP NULL,
  `salary_pay_rate` DECIMAL(10,2) UNSIGNED NULL,
  `fk_emp_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`salary_id`),
  CONSTRAINT `fk_salary_emp_id`
    FOREIGN KEY (`fk_emp_id`)
    REFERENCES `delectable`.`employee` (`emp_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_emp_id_idx` ON `delectable`.`salary` (`fk_emp_id` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `delectable`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`category` ;

CREATE TABLE IF NOT EXISTS `delectable`.`category` (
  `cat_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cat_name` VARCHAR(64) NOT NULL,
  `cat_description` VARCHAR(64) NOT NULL,
  `cat_updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cat_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `delectable`.`menu_item_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`menu_item_category` ;

CREATE TABLE IF NOT EXISTS `delectable`.`menu_item_category` (
  `item_cat_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_cat_name` VARCHAR(64) NOT NULL,
  `item_cat_description` VARCHAR(255) NULL,
  `item_cat_status` BIT(1) NOT NULL DEFAULT 1,
  `fk_loc_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`item_cat_id`),
  CONSTRAINT `fk_menu_item_cat_loc_id`
    FOREIGN KEY (`fk_loc_id`)
    REFERENCES `delectable`.`location` (`loc_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `item_cat_name_UNIQUE` ON `delectable`.`menu_item_category` (`item_cat_name` ASC) VISIBLE;

CREATE INDEX `fk_loc_id_idx` ON `delectable`.`menu_item_category` (`fk_loc_id` ASC) VISIBLE;

-- -----------------------------------------------------
-- Table `delectable`.`menu_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`menu_item` ;

CREATE TABLE IF NOT EXISTS `delectable`.`menu_item` (
  `item_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_name` VARCHAR(64) NOT NULL,
  `item_description` VARCHAR(255) NULL,
  `item_price` DECIMAL(4,2) NOT NULL,
  `item_status` BIT(1) NOT NULL DEFAULT 1,
  `fk_item_cat_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`item_id`),
  CONSTRAINT `fk_menu_item_item_cat_id`
    FOREIGN KEY (`fk_item_cat_id`)
    REFERENCES `delectable`.`menu_item_category` (`item_cat_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_item_cat_id_idx` ON `delectable`.`menu_item` (`fk_item_cat_id` ASC) VISIBLE;

-- -----------------------------------------------------
-- Table `delectable`.`menu_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`menu_category` ;

CREATE TABLE IF NOT EXISTS `delectable`.`menu_category` (
  `menu_cat_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_loc_id` INT UNSIGNED NOT NULL,
  `fk_cat_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`menu_cat_id`),
  CONSTRAINT `fk_menu_category_res_id`
    FOREIGN KEY (`fk_loc_id`)
    REFERENCES `delectable`.`restaurant` (`res_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_category_cat_id`
    FOREIGN KEY (`fk_cat_id`)
    REFERENCES `delectable`.`category` (`cat_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_cat_id_idx` ON `delectable`.`menu_category` (`fk_cat_id` ASC) VISIBLE;

CREATE INDEX `fk_res_id_idx` ON `delectable`.`menu_category` (`fk_loc_id` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `delectable`.`customer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`customer` ;

CREATE TABLE IF NOT EXISTS `delectable`.`customer` (
  `cust_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cust_first_name` VARCHAR(64) NOT NULL,
  `cust_last_name` VARCHAR(64) NOT NULL,
  `cust_username` VARCHAR(64) NOT NULL,
  `cust_password` VARCHAR(255) NOT NULL,
  `cust_email` VARCHAR(255) NOT NULL,
  `cust_status` INT NOT NULL DEFAULT 1,
  `cust_last_login` TIMESTAMP NULL,
  `cust_address_1` VARCHAR(64) NULL,
  `cust_address_2` VARCHAR(64) NULL,
  `cust_city` VARCHAR(64) NULL,
  `cust_state` VARCHAR(64) NULL,
  `cust_postal_code` VARCHAR(64) NULL,
  `cust_country` VARCHAR(64) NULL,
  `cust_phone` VARCHAR(64) NULL,
  `cust_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cust_updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cust_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `delectable`.`reservation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`reservation` ;

CREATE TABLE IF NOT EXISTS `delectable`.`reservation` (
  `rsvn_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `rsvn_date` DATE NOT NULL,
  `rsvn_slot` TIME NOT NULL,
  `rsvn_length` INT NOT NULL DEFAULT 60,
  `rsvn_status` VARCHAR(64) NOT NULL DEFAULT 1,
  `rsvn_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rsvn_updated` TIMESTAMP NULL,
  `fk_loc_id` INT UNSIGNED NOT NULL,
  `fk_cust_id` INT UNSIGNED NOT NULL,
  `fk_table_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`rsvn_id`),
  CONSTRAINT `fk_reservation_loc_id`
    FOREIGN KEY (`fk_loc_id`)
    REFERENCES `delectable`.`location` (`loc_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reservation_cust_id`
    FOREIGN KEY (`fk_cust_id`)
    REFERENCES `delectable`.`customer` (`cust_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reservation_table_id`
    FOREIGN KEY (`fk_table_id`)
    REFERENCES `delectable`.`table` (`table_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_loc_id_idx` ON `delectable`.`reservation` (`fk_loc_id` ASC) VISIBLE;

CREATE INDEX `fk_cust_id_idx` ON `delectable`.`reservation` (`fk_cust_id` ASC) VISIBLE;

CREATE INDEX `fk_table_id_idx` ON `delectable`.`reservation` (`fk_table_id` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `delectable`.`reservation_staff`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`reservation_staff` ;

CREATE TABLE IF NOT EXISTS `delectable`.`reservation_staff` (
  `staff_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_rsvn_id` INT UNSIGNED NOT NULL,
  `fk_emp_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`staff_id`),
  CONSTRAINT `fk_reservation_staff_rsvn_id`
    FOREIGN KEY (`fk_rsvn_id`)
    REFERENCES `delectable`.`reservation` (`rsvn_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reservation_staff_emp_id`
    FOREIGN KEY (`fk_emp_id`)
    REFERENCES `delectable`.`employee` (`emp_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_rsvn_id_idx` ON `delectable`.`reservation_staff` (`fk_rsvn_id` ASC) VISIBLE;

CREATE INDEX `fk_emp_id_idx` ON `delectable`.`reservation_staff` (`fk_emp_id` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `delectable`.`order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`order` ;

CREATE TABLE IF NOT EXISTS `delectable`.`order` (
  `order_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_total` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `order_status` VARCHAR(64) NOT NULL DEFAULT 1,
  `order_message` VARCHAR(255) NULL,
  `order_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `fk_cust_id` INT UNSIGNED NOT NULL,
  `fk_rsvn_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`order_id`),
  CONSTRAINT `fk_order_cust_id`
    FOREIGN KEY (`fk_cust_id`)
    REFERENCES `delectable`.`customer` (`cust_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_rsvn_id`
    FOREIGN KEY (`fk_rsvn_id`)
    REFERENCES `delectable`.`reservation` (`rsvn_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_cust_id_idx` ON `delectable`.`order` (`fk_cust_id` ASC) VISIBLE;

CREATE INDEX `fk_rsvn_id_idx` ON `delectable`.`order` (`fk_rsvn_id` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `delectable`.`order_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`order_item` ;

CREATE TABLE IF NOT EXISTS `delectable`.`order_item` (
  `order_item_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_price` DECIMAL(10,2) NULL,
  `order_quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `fk_order_id` INT UNSIGNED NOT NULL,
  `fk_menu_item_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`order_item_id`),
  CONSTRAINT `fk_order_item_order_id`
    FOREIGN KEY (`fk_order_id`)
    REFERENCES `delectable`.`order` (`order_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_item_menu_item_id`
    FOREIGN KEY (`fk_menu_item_id`)
    REFERENCES `delectable`.`menu_item` (`item_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_order_id_idx` ON `delectable`.`order_item` (`fk_order_id` ASC) VISIBLE;

CREATE INDEX `fk_menu_item_id_idx` ON `delectable`.`order_item` (`fk_menu_item_id` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `delectable`.`table`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`table` ;

CREATE TABLE IF NOT EXISTS `delectable`.`table` (
  `table_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `table_uuid` VARCHAR(128) NOT NULL,
  `table_number` VARCHAR(128) NOT NULL,
  `table_seats` INT NOT NULL DEFAULT 1,
  `table_type` ENUM('circle', 'rectangle', 'square') NOT NULL,
  `table_height` INT NOT NULL,
  `table_width` INT NOT NULL,
  `table_left` INT NOT NULL,
  `table_top` INT NOT NULL,
  `table_angle` INT NOT NULL,
  `table_status` BIT(1) NOT NULL DEFAULT 1,
  `fk_loc_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`table_id`),
  CONSTRAINT `fk_table_loc_id`
    FOREIGN KEY (`fk_loc_id`)
    REFERENCES `delectable`.`location` (`loc_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_loc_id_idx` ON `delectable`.`table` (`fk_loc_id` ASC) VISIBLE;

-- -----------------------------------------------------
-- Table `delectable`.`review`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`review` ;

CREATE TABLE IF NOT EXISTS `delectable`.`review` (
  `review_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `review_text` TEXT NOT NULL,
  `review_rating` INT(1) UNSIGNED NOT NULL,
  `review_food_rating` INT(1) UNSIGNED NOT NULL,
  `review_service_rating` INT(1) UNSIGNED NOT NULL,
  `review_thumbs_up` INT NULL DEFAULT 0,
  `review_thumbs_down` INT NULL DEFAULT 0,
  `review_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `review_updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `fk_cust_id` INT UNSIGNED NOT NULL,
  `fk_rsvn_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`review_id`),
  CONSTRAINT `fk_review_cust_id`
    FOREIGN KEY (`fk_cust_id`)
    REFERENCES `delectable`.`customer` (`cust_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_review_rsvn_id`
    FOREIGN KEY (`fk_rsvn_id`)
    REFERENCES `delectable`.`reservation` (`rsvn_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_cust_id_idx` ON `delectable`.`review` (`fk_cust_id` ASC) VISIBLE;

CREATE INDEX `fk_rsvn_id_idx` ON `delectable`.`review` (`fk_rsvn_id` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `delectable`.`payment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`payment` ;

CREATE TABLE IF NOT EXISTS `delectable`.`payment` (
  `pay_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pay_token` VARCHAR(128) NOT NULL,
  `pay_transaction` VARCHAR(128) NOT NULL,
  `pay_card_type` VARCHAR(32) NOT NULL,
  `pay_brand` VARCHAR(32) NOT NULL,
  `pay_last_digits` VARCHAR(4) NOT NULL,
  `pay_expiration` DATE NOT NULL,
  `pay_status` VARCHAR(32) NOT NULL,
  `pay_amount` DECIMAL(6,2) UNSIGNED NOT NULL,
  `pay_refund_status` VARCHAR(32) NULL,
  `pay_refund` DECIMAL(6,2) UNSIGNED NULL,
  `fk_order_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`pay_id`),
  CONSTRAINT `fk_payment_order_id`
    FOREIGN KEY (`fk_order_id`)
    REFERENCES `delectable`.`order` (`order_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_order_id_idx` ON `delectable`.`payment` (`fk_order_id` ASC) VISIBLE;

CREATE UNIQUE INDEX `pay_token_UNIQUE` ON `delectable`.`payment` (`pay_token` ASC) VISIBLE;

-- -----------------------------------------------------
-- Table `delectable`.`location_hours`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`location_hours` ;

CREATE TABLE IF NOT EXISTS `delectable`.`location_hours` (
  `hours_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hours_day` INT UNSIGNED NOT NULL,
  `hours_open` TIME NOT NULL DEFAULT "8:00",
  `hours_close` TIME NOT NULL DEFAULT "20:00",
  `hours_valid_from` DATE NOT NULL,
  `hours_valid_thru` DATE DEFAULT NULL,
  `fk_loc_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`hours_id`),
  CONSTRAINT `fk_location_hours_loc_id`
    FOREIGN KEY (`fk_loc_id`)
    REFERENCES `delectable`.`location` (`loc_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_loc_id_idx` ON `delectable`.`location_hours` (`fk_loc_id` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `delectable`.`object`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `delectable`.`object` ;

CREATE TABLE IF NOT EXISTS `delectable`.`object` (
  `object_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `object_uuid` VARCHAR(64) NOT NULL,
  `object_type` ENUM('other', 'chair') NOT NULL,
  `object_height` INT NOT NULL,
  `object_width` INT NOT NULL,
  `object_left` INT NOT NULL,
  `object_top` INT NOT NULL,
  `object_angle` INT NOT NULL,
  `fk_loc_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`object_id`),
  CONSTRAINT `fk_object_loc_id`
    FOREIGN KEY (`fk_loc_id`)
    REFERENCES `delectable`.`location` (`loc_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `delectable`;

DELIMITER $$

USE `delectable`$$
DROP TRIGGER IF EXISTS `delectable`.`salary_BEFORE_INSERT` $$
USE `delectable`$$
CREATE DEFINER = CURRENT_USER TRIGGER `delectable`.`salary_BEFORE_INSERT` BEFORE INSERT ON `salary` FOR EACH ROW
BEGIN
SET NEW.salary_pay_rate = (SELECT emp_pay_rate FROM employee WHERE emp_id = NEW.fk_emp_id);
END$$


USE `delectable`$$
DROP TRIGGER IF EXISTS `delectable`.`order_item_BEFORE_INSERT` $$
USE `delectable`$$
CREATE DEFINER = CURRENT_USER TRIGGER `delectable`.`order_item_BEFORE_INSERT` BEFORE INSERT ON `order_item` FOR EACH ROW
BEGIN
SET NEW.order_price = (SELECT item_price FROM menu_item WHERE item_id = NEW.fk_menu_item_id);
END$$

USE `delectable`$$
DROP TRIGGER IF EXISTS `delectable`.`order_item_AFTER_INSERT` $$
USE `delectable`$$
CREATE DEFINER = CURRENT_USER TRIGGER `delectable`.`order_item_AFTER_INSERT` AFTER INSERT ON `order_item` FOR EACH ROW
BEGIN
UPDATE `order`
SET order_total = order_total + (NEW.order_price * NEW.order_quantity)
WHERE order_id = NEW.fk_order_id;
END$$

USE `delectable`$$
DROP TRIGGER IF EXISTS `delectable`.`hours_AFTER_INSERT` $$
USE `delectable`$$
CREATE DEFINER = CURRENT_USER TRIGGER `delectable`.`hours_AFTER_INSERT` AFTER INSERT ON `location` FOR EACH ROW
BEGIN
  DECLARE x INT;
  DECLARE d INT;
  SET x = 7;
  SET d = 1;
  WHILE x > 0 DO
    INSERT INTO location_hours(hours_day, hours_valid_from, fk_loc_id) VALUES (d, CURRENT_DATE, NEW.loc_id);
    SET x = x - 1;
    SET d = d + 1;
  END WHILE;
END$$

USE `delectable`$$
DROP TRIGGER IF EXISTS `delectable`.`rsvn_AFTER_INSERT` $$
USE `delectable`$$
CREATE DEFINER = CURRENT_USER TRIGGER `delectable`.`rsvn_AFTER_INSERT` AFTER INSERT ON `reservation` FOR EACH ROW
BEGIN
    INSERT INTO `order` (fk_cust_id, fk_rsvn_id) VALUES (NEW.fk_cust_id, NEW.rsvn_id);
END$$


DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- TEST DATA
INSERT INTO administrator VALUES 
(1, 'Esteban', 'Rodriguez', 'esteban', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'esteban@esteban.com', 1, 1, CURRENT_TIMESTAMP, 'Male', '1994-07-05', '123 Street Ave', NULL, 'Bakersfield', 'California', '93307', '661-123-4567', '2020-02-04', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);


-- RESTAURANT INSERT QUERIES 
INSERT INTO restaurant (res_name, res_slogan, res_description) VALUES 
('BANGABURGER', 'Deliciousness with a BANG!', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ultricies urna sed rutrum lobortis. Nullam imperdiet libero et dignissim placerat. Etiam nunc massa, elementum id dui et, mattis iaculis tellus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Suspendisse volutpat ante lectus, quis varius nisi tempus ac.'),
('BAMBOO!', 'Bamboo! Eat healthy!', 'Geat healthy alternative to the mainstream chinese food. Come eat at Bamboo for high quality food you will for sure love, Vegan food available.'),
('Dumpling House', 'Amazing Dumplings!', 'Come eat soft chewy dumplings that have an explosion of flavor! Many options available to suit anyones tastes buds!'),
('Lil` Italiano', 'Italian Food!', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ultricies urna sed rutrum lobortis. Nullam imperdiet libero et dignissim placerat. Etiam nunc massa, elementum id dui et, mattis iaculis tellus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Suspendisse volutpat ante lectus, quis varius nisi tempus ac.'),
('Steak House', 'All you can eat!', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ultricies urna sed rutrum lobortis. Nullam imperdiet libero et dignissim placerat. Etiam nunc massa, elementum id dui et, mattis iaculis tellus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Suspendisse volutpat ante lectus, quis varius nisi tempus ac.');


-- LOCATION INSERT QUERIES
INSERT INTO location (loc_address_1, loc_address_2, loc_city, loc_state, loc_postal_code, loc_phone, fk_res_id) VALUES 
('2550 California Ave', 'Suite #200', 'Bakersfield', 'California', '93308', '(661)-844-7071', 1),
('1701 New Stine Rd', '', 'Bakersfield', 'California', '93309', '(661)-832-1278', 2),
('8110 Rosedale Hwy', 'Suite #F', 'Seattle', 'Washington', '98101', '(206)-588-4879', 3),
('2217 Ashe Rd', '', 'Los Angeles', 'California', '93309', '(661)-473-1426', 4),
('5051 Stockdale Hwy', '', 'Bakersfield', 'California', '93309', '(661)-834-7850', 5);

-- EMPLOYEE INSERT QUERIES
INSERT INTO employee (emp_first_name, emp_last_name, emp_username, emp_password, emp_email, emp_job, emp_manager, fk_loc_id) VALUES 
('Esteban', 'Rodriguez', 'erodriguez', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'esteban@example.com', 'Owner/General Manager', 1, 1),
('Daven', 'Wilson', 'dwilson8', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'dwilson8@ning.com', 'Owner/General Manager', 1, 2),
('Cort', 'Haggas', 'chaggase', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'chaggase@unc.edu', 'Owner/General Manager', 1, 3),
('Manon', 'Jenks', 'mjenksi1', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'mjenksi@ed.gov', 'Owner/General Manager', 1, 4),
('Jasun', 'Holsey', 'jholsey0', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'jholsey0@bravesites.com', 'Owner/General Manager', 1, 5),
('Ilene', 'Caston', 'icastona', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'icastona@1und1.de', 'Kitchen Manager', 1, 1),
('Bram', 'De Gregorio', 'bdegregorio1', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'bdegregorio1@ft.com', null, 0, 1),
('Clayborn', 'Anger', 'clanger7', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'canger7@list-manage.com', null, 0, 1),
('Barbabas', 'Tomsu', 'btomsu9', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'btomsu9@tuttocitta.it', null, 0, 2),
('Breanne', 'Filyukov', 'bfilyukovd', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'bfilyukovd@mac.com', null, 0, 2),
('Giralda', 'Carlos', 'gcarlosh', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'gcarlosh@samsung.com', 'Kitchen Manager', 1, 2),
('Rodolfo', 'Gissing', 'rgissingj', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'rgissingj@epa.gov', null, 0, 2),
('Katti', 'Conen', 'kconen34', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'kconen3@histats.com', null, 0, 3),
('Anjela', 'Aveling', 'aavelingf', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'aavelingf@is.gd', 'Kitchen Manager', 1, 3),
('Misty', 'Southwell', 'msouthwellg', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'msouthwellg@cocolog-nifty.com',null, 0, 3),
('Shalom', 'Gillivrie', 'sgillivrie6', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'sgillivrie6@fastcompany.com', null, 0, 3),
('Audrye', 'Thonger', 'athonger2', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'athonger2@desdev.cn', null, 0, 4),
('Rich', 'Boliver', 'rboliver4', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'rboliver4@baidu.com', null, 0, 4),
('Gael', 'Caine', 'gcainec1', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'gcainec@vistaprint.com', null, 0, 4),
('Arlen', 'Narramore', 'anarramore5', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'anarramore5@technorati.com', null, 0, 5),
('Juliana', 'De Beneditti', 'jdebenedittib', '$2y$10$t2QL6MJRS7R81F/Uh9xW1eEs9JIW10Z9aMW/tT6WIdwOo4E6CtPIG', 'jdebenedittib@netvibes.com', null, 0, 5);


-- CUSTOMER INSERT QUERIES
INSERT INTO customer (cust_first_name, cust_last_name, cust_username, cust_password, cust_email, cust_address_1, cust_city, cust_state, cust_postal_code, cust_phone) VALUES 
("Example", "McExample", "example0", "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", "example0@example.com", '7 Judy Park', 'Yonkers', 'New York', '10705', '(914)-644-7367'),
('Issie', 'Flemmich', 'iflemmich00', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'iflemmich0@gov.uk', '19067 Roth Plaza', 'Atlanta', 'Georgia', '30340', '(770)-524-0542'),
('Ermentrude', 'Busek', 'ebusek10', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'ebusek1@oaic.gov.au', '2 Manufacturers Parkway', 'Raleigh', 'North Carolina', '27621', '(919)-434-3895'),
('Dolli', 'Petrie', 'dpetrie20', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'dpetrie2@hhs.gov', '66 Twin Pines Crossing', 'New York City', 'New York', '10203', '(212)-626-7497'),
('Les', 'Vernham', 'lvernham30', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'lvernham3@desdev.cn', '5901 Buell Point', 'Worcester', 'Massachusetts', '01605', '(508)-330-0231'),
('Allsun', 'Passingham', 'apassingham40', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'apassingham4@1und1.de', '203 Coleman Junction', 'Hollywood', 'Florida', '33028', '(305)-106-9690'),
('Ki', 'Colleton', 'kcolleton50', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'kcolleton5@ebay.co.uk', '8 Roth Lane', 'Inglewood', 'California', '90305', '(310)-970-7570'),
('Vilma', 'Davers', 'vdavers60', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'vdavers6@nifty.com', '179 Declaration Parkway', 'Philadelphia', 'Pennsylvania', '19131', '(215)-295-9026'),
('Any', 'Pashen', 'apashen70', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'apashen7@github.io', '33806 Charing Cross Circle', 'Warren', 'Ohio', '44485', '(330)-528-2217'),
('Dwight', 'Batters', 'dbatters80', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'dbatters8@xing.com', '5 Macpherson Park', 'Fresno', 'California', '93721', '(209)-694-9784'),
('Jennifer', 'Piccop', 'jpiccop90', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'jpiccop9@livejournal.com', '42 Fieldstone Alley', 'Montgomery', 'Alabama', '36134', '(334)-172-7759'),
('Mackenzie', 'McQuilliam', 'mmcquilliama0', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'mmcquilliama@macromedia.com', '99980 Rieder Place', 'Irvine', 'California', '92717', '(714)-394-9925'),
('Linette', 'Kiehne', 'lkiehneb0', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'lkiehneb@dyndns.org', '3 Superior Court', 'Bradenton', 'Florida', '34205', '(941)-355-7751'),
('Ferdinande', 'Housbey', 'fhousbeyc0', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'fhousbeyc@cisco.com', '106 Lerdahl Street', 'Sioux Falls', 'South Dakota', '57105', '(605)-484-0621'),
('Lynn', 'Dowker', 'ldowkerd0', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'ldowkerd@ted.com', '01 Lakewood Street', 'Saint Louis', 'Missouri', '63158', '(314)-477-4313'),
('Mylo', 'Hegge', 'mheggee0', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'mheggee@craigslist.org', '621 Morningstar Place', 'Mesquite', 'Texas', '75185', '(972)-583-8785'),
('Gamaliel', 'McCarlie', 'gmccarlief0', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'gmccarlief@ucoz.com', '47998 Stoughton Place', 'Washington', 'District of Columbia', '20319', '(202)-553-4706'),
('Vergil', 'Blaxill', 'vblaxillg0', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'vblaxillg@google.com.hk', '034 Redwing Junction', 'New York City', 'New York', '10131', '(212)-901-9750'),
('Debra', 'Ellerton', 'dellertonh0', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'dellertonh@edublogs.org', '3 Texas Place', 'Tacoma', 'Washington', '98447', '(253)-555-4764'),
('Frederique', 'Ruseworth', 'fruseworthi0', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'fruseworthi@independent.co.uk', '7 Dakota Court', 'Petaluma', 'California', '94975', '(707)-380-5074'),
('Sisely', 'Gange', 'sgangej0', "$2y$10$xqYrxNVkcBBAXyppmeKSJepHOmknTUBYJBER3niQgV8E/ueja.X2y", 'sgangej@hubpages.com', '90 Dorton Point', 'Peoria', 'Illinois', '61614', '(309)-458-7287');

-- MENU ITEM CATEGORY
INSERT INTO menu_item_category (item_cat_name, item_cat_description, fk_loc_id) VALUES 
("Burgers", null, 1),
("Sides", null, 1),
("Drinks", null, 1),
("Appetizers", null, 2),
("Soups", null, 2),
("Fried Rice", null, 2),
("Sushi", null, 3),
("Seafood", null, 3),
("Specialties", null, 3),
("Pasta", null, 4),
("Pizza", null, 4),
("Salad", null, 4),
("Starters", null, 5),
("Steaks", null, 5),
("Steakhouse Sides", null, 5);

-- MENU ITEM
INSERT INTO menu_item (item_name, item_price, fk_item_cat_id) VALUES 
("Cheeseburger", 8.99, 1),
("Veggie Burger", 6.99, 1),
("Angus Burger", 12.99, 1),
("French Fries", 4.99, 2),
("Onion Rings", 4.99, 2),
("House Salad", 3.99, 2),
("Tea", 2.99, 3),
("Soft Drink", 2.49, 3),
("Coffee", 2.49, 3),
("Egg Rolls", 2.99, 4),
("Pot Stickers", 4.25, 4),
("Crab Rangoon", 3.49, 4),
("Egg Drop Soup", 6.25, 5),
("Mixed Veggie Soup", 6.99, 5),
("Wor Wonton Soup", 7.25, 5),
("Pork", 11.99, 6),
("Vegetable", 8.95, 6),
("Beef", 10.95, 6),
("Tempura", 8.99, 7),
("Spicy Special Tuna", 10.99, 7),
("King Crab", 10.99, 7),
("Clams", 9.00, 8),
("Mussels", 4.00, 8),
("Calamari", 6.00, 8),
("Chirashi Zushi", 8.00, 9),
("Saikoro Steak", 15.00, 9),
("Fried Soft Shell Crab", 10.00, 9),
("Pasta Maria", 12.50, 10),
("Cajun Pasta", 13.50, 10),
("Pasta Francesco", 11.50, 10),
("Margherita", 5.69, 11),
("Meat Feast", 5.98, 11),
("Hot Sea", 7.13, 11),
("Blueberry and Chicken", 12.00, 12),
("Shrimp Cobb Salad", 11.00, 12),
("Classic Caesar", 8.00, 12),
("Shrimp Cocktail", 5.99, 13),
("Fried Mushrooms", 4.95, 13),
("Cheese and Fruit Plate", 7.95, 13),
("New York Strip", 20.49, 14),
("Prime Rib", 24.99, 14),
("Outlaw Ribeye", 25.99, 14),
("Sweet Potato", 2.99, 15),
("Fresh Steamed Broccoli", 2.99, 15),
("Mashed Potatoes", 2.99, 15);

-- LAYOUT TABLES
INSERT INTO `table` (`table_uuid`, `table_number`, `table_seats`, `table_type`, `table_height`, `table_width`, `table_left`, `table_top`, `table_angle`, `fk_loc_id`) VALUES
('xs41wsw2d-bcnxc7jnp-siyu13xpn-njocx59d0', '1', 1, 'rectangle', 76, 106, 600, 15, 45, 1),
('cljxm2nei-nfw8exn64-j197r2xlg-mkgs3tfwy', '2', 1, 'rectangle', 76, 106, 90, 390, 45, 1),
('d609kd8o9-zmp1ybq6b-9yt6nvzbi-8ws3ebbzi', '3', 1, 'rectangle', 76, 106, 315, 215, 0, 1),
('snjfyyaor-ua5jc4er7-2olrtlfl3-dn1ribmoe', '4', 1, 'rectangle', 76, 106, 45, 30, 0, 1),
('fkwg3w5iv-2siy5ynfw-ho7qkny26-onoppgwqu', '5', 1, 'rectangle', 76, 106, 570, 420, 0, 1),
('yfmwn38px-lg3cjj9i6-i37s33ra2-0ldjv9kx7', '1', 1, 'rectangle', 76, 106, 600, 15, 45, 2),
('okpvuo6th-k8p9zrnzg-os965b1xc-dciino147', '2', 1, 'rectangle', 76, 106, 90, 390, 45, 2),
('ctc5aimuu-zjcyo6r4m-rzalgt1uq-bdu3cofi4', '3', 1, 'rectangle', 76, 106, 315, 215, 0, 2),
('n7nk513pu-pgnyd741k-dyu6awndb-afqv5sym0', '4', 1, 'rectangle', 76, 106, 45, 30, 0, 2),
('n7oa1e8kl-g9q1ho3xi-h94i90za8-4d46a0duy', '5', 1, 'rectangle', 76, 106, 570, 420, 0, 2),
('95z3k8u3c-xcawuuxui-s2yofrc92-qaro15txl', '1', 1, 'rectangle', 76, 106, 600, 15, 45, 3),
('1vfrgqe83-lkr61hfx5-6mw1xsxkm-hehawldel', '2', 1, 'rectangle', 76, 106, 90, 390, 45, 3),
('de23uiqcp-aavasnta9-5cmtezczp-f99gh43z7', '3', 1, 'rectangle', 76, 106, 315, 215, 0, 3),
('6tcxjkufc-ynca7gja0-qc773wuvw-fc409fed1', '4', 1, 'rectangle', 76, 106, 45, 30, 0, 3),
('act99xi7d-yoc1w2ayk-bs4y5pxyc-6cn3pvjar', '5', 1, 'rectangle', 76, 106, 570, 420, 0, 3),
('du48j9goe-l83m2s3jx-7rqdn9kdm-cbqat0a96', '1', 1, 'rectangle', 76, 106, 600, 15, 45, 4),
('ljxl75kpd-z3dx9de4z-zbzrfhr71-v5p56y3or', '2', 1, 'rectangle', 76, 106, 90, 390, 45, 4),
('genr8ygyn-9xzauvdyp-apctzghmf-ghtii46lq', '3', 1, 'rectangle', 76, 106, 315, 215, 0, 4),
('3boms66om-6g2motujs-29oo18wx2-ou5n4hp2u', '4', 1, 'rectangle', 76, 106, 45, 30, 0, 4),
('yiuzzpl0i-v556brhai-svcw8lle3-bq6oruk37', '5', 1, 'rectangle', 76, 106, 570, 420, 0, 4),
('rg6tc4vp8-o7ikrlyc4-7ewp7vzul-9cxev8o2w', '1', 1, 'rectangle', 76, 106, 600, 15, 45, 5),
('tpj2w3kwg-6b8mwseox-t40g5b8zb-ccl2ej0v5', '2', 1, 'rectangle', 76, 106, 90, 390, 45, 5),
('b9wxhsda7-fswcva8q3-zr5sgb4xx-x6gq9g8dj', '3', 1, 'rectangle', 76, 106, 315, 215, 0, 5),
('ggq29yyez-kz510nqw5-dp5vz004u-a4jgt1ecf', '4', 1, 'rectangle', 76, 106, 45, 30, 0, 5),
('x0hr08yyt-ve68pdmqg-owzs9jz7u-kt2o537es', '5', 1, 'rectangle', 76, 106, 570, 420, 0, 5);

-- RESERVATION QUERY
INSERT INTO reservation (rsvn_date, rsvn_slot, fk_loc_id, fk_cust_id, fk_table_id) VALUES 
("2020-04-01", "16:00:00", 1, 1, 1),
("2020-04-01", "10:00:00", 1, 2, 5),
("2020-04-01", "12:00:00", 1, 3, 2),
("2020-04-01", "13:00:00", 1, 4, 4),
("2020-04-01", "19:00:00", 1, 5, 3),
("2020-04-02", "16:00:00", 1, 1, 1),
("2020-04-02", "10:00:00", 1, 2, 5),
("2020-04-02", "12:00:00", 1, 3, 2),
("2020-04-02", "13:00:00", 1, 4, 4),
("2020-04-02", "19:00:00", 1, 5, 3),
("2020-04-03", "16:00:00", 1, 1, 1),
("2020-04-03", "10:00:00", 1, 2, 5),
("2020-04-03", "12:00:00", 1, 3, 2),
("2020-04-03", "13:00:00", 1, 4, 4),
("2020-04-03", "19:00:00", 1, 5, 3),
("2020-04-27", "16:00:00", 1, 3, 2),
("2020-04-30", "16:00:00", 1, 4, 4),
("2020-05-01", "16:00:00", 1, 5, 3),
("2020-05-02", "18:00:00", 1, 6, 1),
("2020-05-02", "19:00:00", 1, 7, 5),
("2020-05-02", "14:00:00", 1, 8, 2),
("2020-05-02", "15:00:00", 1, 9, 4),
("2020-04-08", "18:00:00", 2, 10, 8),
("2020-04-08", "16:00:00", 2, 11, 6),
("2020-04-11", "16:00:00", 2, 12, 10),
("2020-04-27", "16:00:00", 2, 13, 7),
("2020-05-06", "16:00:00", 2, 14, 9),
("2020-05-24", "16:00:00", 2, 15, 8),
("2020-05-02", "18:00:00", 2, 16, 8),
("2020-05-02", "19:00:00", 2, 17, 6),
("2020-05-02", "14:00:00", 2, 18, 10),
("2020-05-02", "15:00:00", 2, 19, 7),
("2020-05-05", "18:00:00", 2, 20, 9),
("2020-04-08", "16:00:00", 3, 10, 11),
("2020-04-11", "16:00:00", 3, 9, 15),
("2020-04-27", "16:00:00", 3, 8, 12),
("2020-04-30", "16:00:00", 3, 7, 14),
("2020-05-01", "16:00:00", 3, 6, 13),
("2020-05-02", "18:00:00", 3, 5, 11),
("2020-05-02", "19:00:00", 3, 4, 15),
("2020-05-02", "14:00:00", 3, 3, 12),
("2020-05-02", "15:00:00", 3, 2, 14),
("2020-05-05", "18:00:00", 3, 1, 13),
("2020-04-08", "16:00:00", 4, 20, 16),
("2020-04-11", "16:00:00", 4, 19, 20),
("2020-04-27", "16:00:00", 4, 18, 18),
("2020-04-30", "16:00:00", 4, 17, 19),
("2020-05-01", "16:00:00", 4, 16, 18),
("2020-05-02", "18:00:00", 4, 15, 16),
("2020-05-02", "19:00:00", 4, 14, 20),
("2020-05-02", "14:00:00", 4, 13, 17),
("2020-05-02", "15:00:00", 4, 12, 19),
("2020-05-05", "18:00:00", 4, 11, 18),
("2020-04-08", "16:00:00", 5, 11, 21),
("2020-04-11", "16:00:00", 5, 1, 25),
("2020-04-27", "16:00:00", 5, 12, 22),
("2020-04-30", "16:00:00", 5, 2, 24),
("2020-05-01", "16:00:00", 5, 13, 23),
("2020-05-02", "18:00:00", 5, 3, 21),
("2020-05-02", "19:00:00", 5, 14, 25),
("2020-05-02", "14:00:00", 5, 4, 22),
("2020-05-02", "15:00:00", 5, 15, 24),
("2020-05-05", "18:00:00", 5, 5, 23);

-- RSVN STAFF
INSERT INTO reservation_staff (fk_emp_id, fk_rsvn_id) VALUES 
(7, 1),
(7, 2),
(7, 3),
(7, 22);

-- ORDER ITEM
INSERT INTO order_item (order_quantity, fk_order_id, fk_menu_item_id) VALUES 
(1, 1, 1);

INSERT INTO review(review_rating, review_service_rating, review_food_rating, fk_cust_id, fk_rsvn_id, review_text) VALUES 
(1, 4, 5, 5, 1, 'Morbi porttitor lorem id ligula. Suspendisse ornare consequat lectus. In est risus, auctor sed, tristique in, tempus sit amet, sem. Fusce consequat. Nulla nisl. Nunc nisl.'),
(5, 3, 5, 1, 2, 'Fusce consequat. Nulla nisl. Nunc nisl.'),
(1, 1, 3, 5, 3, 'In sagittis dui vel nisl. Duis ac nibh. Fusce lacus purus, aliquet at, feugiat non, pretium quis, lectus.'),
(2, 3, 1, 1, 4, 'Maecenas tristique, est et tempus semper, est quam pharetra magna, ac consequat metus sapien ut nunc. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris viverra diam vitae quam. Suspendisse potenti. Nullam porttitor lacus at turpis. Donec posuere metus vitae ipsum. Aliquam non mauris. Morbi non lectus. Aliquam sit amet diam in magna bibendum imperdiet. Nullam orci pede, venenatis non, sodales sed, tincidunt eu, felis.'),
(1, 2, 2, 4, 5, 'Fusce consequat. Nulla nisl. Nunc nisl. Duis bibendum, felis sed interdum venenatis, turpis enim blandit mi, in porttitor pede justo eu massa. Donec dapibus. Duis at velit eu est congue elementum. In hac habitasse platea dictumst. Morbi vestibulum, velit id pretium iaculis, diam erat fermentum justo, nec condimentum neque sapien placerat ante. Nulla justo.'),
(4, 2, 1, 5, 6, 'Sed ante. Vivamus tortor. Duis mattis egestas metus. Aenean fermentum. Donec ut mauris eget massa tempor convallis. Nulla neque libero, convallis eget, eleifend luctus, ultricies eu, nibh. Quisque id justo sit amet sapien dignissim vestibulum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla dapibus dolor vel est. Donec odio justo, sollicitudin ut, suscipit a, feugiat et, eros.'),
(1, 3, 4, 3, 7, 'Nam ultrices, libero non mattis pulvinar, nulla pede ullamcorper augue, a suscipit nulla elit ac nulla. Sed vel enim sit amet nunc viverra dapibus. Nulla suscipit ligula in lacus. Curabitur at ipsum ac tellus semper interdum. Mauris ullamcorper purus sit amet nulla. Quisque arcu libero, rutrum ac, lobortis vel, dapibus at, diam.'),
(5, 5, 4, 4, 8, 'Praesent blandit. Nam nulla. Integer pede justo, lacinia eget, tincidunt eget, tempus vel, pede. Morbi porttitor lorem id ligula. Suspendisse ornare consequat lectus. In est risus, auctor sed, tristique in, tempus sit amet, sem. Fusce consequat. Nulla nisl. Nunc nisl.'),
(5, 3, 3, 5, 9, 'Quisque porta volutpat erat. Quisque erat eros, viverra eget, congue eget, semper rutrum, nulla. Nunc purus. Phasellus in felis. Donec semper sapien a libero. Nam dui. Proin leo odio, porttitor id, consequat in, consequat ut, nulla. Sed accumsan felis. Ut at dolor quis odio consequat varius.'),
(2, 3, 4, 4, 10, 'Sed ante. Vivamus tortor. Duis mattis egestas metus. Aenean fermentum. Donec ut mauris eget massa tempor convallis. Nulla neque libero, convallis eget, eleifend luctus, ultricies eu, nibh.'),
(3, 2, 4, 5, 11, 'Nullam porttitor lacus at turpis. Donec posuere metus vitae ipsum. Aliquam non mauris. Morbi non lectus. Aliquam sit amet diam in magna bibendum imperdiet. Nullam orci pede, venenatis non, sodales sed, tincidunt eu, felis. Fusce posuere felis sed lacus. Morbi sem mauris, laoreet ut, rhoncus aliquet, pulvinar sed, nisl. Nunc rhoncus dui vel sem.'),
(4, 1, 2, 2, 12, 'Mauris enim leo, rhoncus sed, vestibulum sit amet, cursus id, turpis. Integer aliquet, massa id lobortis convallis, tortor risus dapibus augue, vel accumsan tellus nisi eu orci. Mauris lacinia sapien quis libero.'),
(3, 3, 3, 3, 13, 'Sed ante. Vivamus tortor. Duis mattis egestas metus. Aenean fermentum. Donec ut mauris eget massa tempor convallis. Nulla neque libero, convallis eget, eleifend luctus, ultricies eu, nibh. Quisque id justo sit amet sapien dignissim vestibulum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla dapibus dolor vel est. Donec odio justo, sollicitudin ut, suscipit a, feugiat et, eros.'),
(4, 1, 4, 5, 14, 'In hac habitasse platea dictumst. Morbi vestibulum, velit id pretium iaculis, diam erat fermentum justo, nec condimentum neque sapien placerat ante. Nulla justo. Aliquam quis turpis eget elit sodales scelerisque. Mauris sit amet eros. Suspendisse accumsan tortor quis turpis.'),
(5, 2, 5, 5, 15, 'Maecenas ut massa quis augue luctus tincidunt. Nulla mollis molestie lorem. Quisque ut erat. Curabitur gravida nisi at nibh. In hac habitasse platea dictumst. Aliquam augue quam, sollicitudin vitae, consectetuer eget, rutrum at, lorem. Integer tincidunt ante vel ipsum. Praesent blandit lacinia erat. Vestibulum sed magna at nunc commodo placerat.');
