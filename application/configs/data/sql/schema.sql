-- -----------------------------------------------------
-- Table `role`
-- -----------------------------------------------------

DROP TABLE IF EXISTS role;
CREATE  TABLE IF NOT EXISTS role(
  `name` VARCHAR(15) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Role\'s name' ,
  parentRole VARCHAR(15) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL COMMENT 'Parent role (inherits permissions)',
  PRIMARY KEY (`name`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'Stores authorization roles for this application';
CREATE INDEX fk_role_role1 ON role(parentRole ASC) ;

-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user`(
  username VARCHAR(120) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'The username will be a valid email address' ,
  `password` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL COMMENT 'The sha1 hashed user\'s password' ,
  `state` ENUM('active', 'unconfirmed', 'inactive', 'banned') CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'active' COMMENT 'The state of the user account: active, unconfirmed, inactive and banned' ,
  roleName VARCHAR(15) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Authorization role\'s name',
  confirmationKey VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL COMMENT 'Confirmation key hashed using sha1' ,
  creationDate DATE NULL COMMENT 'Date of creation of confirmation key',
  PRIMARY KEY (username),
  CONSTRAINT fk_user_role1
    FOREIGN KEY (roleName)
    REFERENCES role(`name`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE 
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'Stores the user account information';

CREATE INDEX idx_user_role1 ON `user` (roleName ASC) ;

-- -----------------------------------------------------
-- Table property
-- -----------------------------------------------------
DROP TABLE IF EXISTS property;
CREATE TABLE IF NOT EXISTS property(
  id INT NOT NULL AUTO_INCREMENT COMMENT 'Property\'s id' ,
  `name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'The name to be displayed for this property' ,
  `url` VARCHAR(55) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'The name of the property filtered as a friendly URL' ,
  description TEXT CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'The description of the property' ,
  price TEXT CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'It can specify details in a descriptive way' ,
  address TEXT CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL COMMENT 'The address of the property\nStreet and number, neighborhood, zip code, city, state, country' ,
  addressReference TEXT CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL COMMENT 'Comment to give a better idea of the address location' ,
  latitude FLOAT NULL COMMENT 'Googles map latitude' ,
  longitude FLOAT NULL COMMENT 'Googles map longitude' ,
  category ENUM('premises', 'warehouses', 'lands') CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Field we use to filter properties in categories' ,
  totalSurface FLOAT NOT NULL COMMENT 'Total surface in meters' ,
  metersOffered FLOAT NOT NULL COMMENT 'Offered meters' ,
  metersFront FLOAT NOT NULL COMMENT 'Amount of frontal meters ' ,
  landUse ENUM('housing', 'commercial', 'industrial', 'mixed') CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Legal land use' ,
  creationDate DATE NOT NULL COMMENT 'Creation date',
  availabilityFor ENUM('rent', 'sale') CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Field used to know if property can be rented or sold' ,
  showOnWeb TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Flag to determine if property should be shown on Web or not',
  contactName VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL COMMENT 'Contact name for the property' ,
  contactPhone VARCHAR(10) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL COMMENT 'Phone number for contact' ,
  contactCellphone VARCHAR(13) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL COMMENT 'Cell phone number for contact' ,
  active TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (id) 
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'Stores information of the properties to be rented or sold';

-- -----------------------------------------------------
-- Table picture
-- -----------------------------------------------------
DROP TABLE IF EXISTS picture;
CREATE  TABLE IF NOT EXISTS picture(
  id INT NOT NULL AUTO_INCREMENT COMMENT 'The picture\'s id' ,
  shortDescription VARCHAR(80) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'A short description to be used with the title attribute of the img element' ,
  filename VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'File name of the picture' ,
  propertyId INT NOT NULL COMMENT 'Id of the property to which this picture belongs to' ,
  PRIMARY KEY (id),
  CONSTRAINT fk_picture_property
    FOREIGN KEY (propertyId)
    REFERENCES property(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'Store the properties\' pictures information';

CREATE INDEX idx_picture_property ON picture (propertyId ASC) ;

-- -----------------------------------------------------
-- Table `state`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `state` ;
CREATE  TABLE IF NOT EXISTS `state` (
    id INT NOT NULL AUTO_INCREMENT COMMENT 'State\'s identifier',
    `name` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'State\'s name\n' ,
    PRIMARY KEY (id) 
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'Store the list of Mexico\'s states';

-- -----------------------------------------------------
-- Table `city`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `city` ;
CREATE  TABLE IF NOT EXISTS `city` (
    id INT NOT NULL AUTO_INCREMENT COMMENT 'City\'s identifier',
    `name` VARCHAR(130) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Name of the city' ,
    stateId INT NOT NULL COMMENT 'State to which this city belongs to' ,
    PRIMARY KEY (id) ,
    CONSTRAINT fk_city_state
        FOREIGN KEY (stateId)
        REFERENCES `state` (id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'Store the list of Mexico\'s cities';
CREATE INDEX idx_city_state ON city (stateId ASC);

-- -----------------------------------------------------
-- Table `resource`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resource` ;
CREATE  TABLE IF NOT EXISTS `resource` (
  `name` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Resource\'s name' ,
  PRIMARY KEY (`name`) 
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'Application\'s resources';

-- -----------------------------------------------------
-- Table `permission`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `permission` ;
CREATE  TABLE IF NOT EXISTS `permission` (
  `name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Permission\'s name',
  roleName VARCHAR(15) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Role\'s name',
  resourceName VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Resource\'s name',
  PRIMARY KEY (`name`, roleName, resourceName),
  CONSTRAINT fk_permission_role1
    FOREIGN KEY (roleName)
    REFERENCES role(`name`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT fk_permission_resources1
    FOREIGN KEY (resourceName)
    REFERENCES `resource` (`name`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'Stores the permissions allowed over a resource given to a particular role';
CREATE INDEX idx_permission_role1 ON `permission`(roleName ASC);
CREATE INDEX idx_permission_resources1 ON `permission` (resourceName ASC);

-- -----------------------------------------------------
-- Table `project`
-- -----------------------------------------------------
DROP TABLE IF EXISTS project;
CREATE  TABLE IF NOT EXISTS project(
  id INT NOT NULL AUTO_INCREMENT COMMENT 'Project\'s id' ,
  `name` VARCHAR(120) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Project\'s name' ,
  attachment VARCHAR(120) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Project\'s attachment (PP file)' ,
  PRIMARY KEY (id) 
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'Investment projects information';
