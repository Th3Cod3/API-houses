CREATE DATABASE IF NOT EXISTS dtt;
USE dtt;

CREATE TABLE IF NOT EXISTS persons (
    id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100) NULL,
    birthdate DATE NOT NULL,
    gender ENUM('F', 'M', 'U') NOT NULL DEFAULT 'U', -- U = undefined
    deleted_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS roles (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS permissions (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS role_permissions (
    id INT NOT NULL AUTO_INCREMENT,
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY u_role_permission (role_id, permission_id),
    CONSTRAINT fk_action_permission
        FOREIGN KEY (permission_id)
        REFERENCES permissions(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_action_role
        FOREIGN KEY (role_id)
        REFERENCES roles(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(15) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    person_id INT NOT NULL UNIQUE,
    role_id INT NOT NULL,
    jti VARCHAR(16) NULL,
    last_login TIMESTAMP NULL,
    last_fail TIMESTAMP NULL,
    fail_counter TINYINT(1) NULL,
    banned ENUM('Y', 'N') NULL DEFAULT 'N',
    deleted_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id, person_id),
    CONSTRAINT fk_user_person
        FOREIGN KEY (person_id)
        REFERENCES persons(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_user_role
        FOREIGN KEY (role_id)
        REFERENCES roles(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS room_types (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS houses (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    city VARCHAR(100) NOT NULL,
    street VARCHAR(100) NOT NULL,
    zip_code VARCHAR(10) NOT NULL,
    number INT NOT NULL,
    addition VARCHAR(20) NULL,
    deleted_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_house_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS rooms (
    id INT NOT NULL AUTO_INCREMENT,
    house_id INT NOT NULL,
    type_id INT NOT NULL,
    width INT NOT NULL,
    length INT NOT NULL,
    height INT NOT NULL,
    deleted_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_room_house
        FOREIGN KEY (house_id)
        REFERENCES houses(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_room_type
        FOREIGN KEY (type_id)
        REFERENCES room_types(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE = InnoDB;

INSERT INTO roles (id, name) VALUES
(1, "User"),
(2, "Admin");

INSERT INTO permissions (id, name) VALUES
(1, "edit_all_houses"),
(2, "delete_all_house");

INSERT INTO role_permissions (role_id, permission_id) VALUES
(2, 1),
(2, 2);

INSERT INTO room_types (id, name) VALUES
(1, "Living room"),
(2, "Bedroom"),
(3, "Toilet"),
(4, "Storage"),
(5, "Bathroom"),
(6, "Garage");

INSERT INTO persons (id, first_name, last_name, birthdate, gender) VALUES
(1, "User", "dtt", "1994-12-30", "M"),
(2, "Admin", "dtt", "1994-12-30", "F");

INSERT INTO users (id, username, password, email, person_id, role_id) VALUES
(1, "User", "$2y$10$XjQpZn8hO87C0FxN6pYCze9eq1ye1t7XcSV51yjaIi18fXuOAqAvS", "dtt1@dtt-nl.com", 1, 1),
(2, "Admin", "$2y$10$XjQpZn8hO87C0FxN6pYCze9eq1ye1t7XcSV51yjaIi18fXuOAqAvS", "dtt2@dtt-nl.com", 2, 2);

