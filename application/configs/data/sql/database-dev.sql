-- Create database
DROP DATABASE IF EXISTS edeco;
CREATE DATABASE edeco 
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

-- Create user
GRANT ALL PRIVILEGES on edeco.* TO edeco_user@localhost IDENTIFIED BY '3d3c0_us3r';