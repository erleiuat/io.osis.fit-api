
-- CREATE DATABASE IF NOT EXISTS `osis_db` CHARACTER SET `utf8`;
-- USE `osis_db`;

-- ______________________________________________________________________________________
-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- ACCOUNT GENERAL

-- ------------------------------------------ account
DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
    account_id          VARCHAR(50) NOT NULL,
    mail                VARCHAR(90) NOT NULL,
    username            VARCHAR(90) NOT NULL,
    password            VARCHAR(255) NOT NULL,
    level               ENUM('user', 'premium', 'moderator', 'admin') NOT NULL DEFAULT 'user',
    status              ENUM('unverified', 'verified', 'locked', 'deleted') NOT NULL DEFAULT 'unverified',

    UNIQUE INDEX unique_mail (mail),
    UNIQUE INDEX unique_username (username),

    PRIMARY KEY (account_id)
);

-- ------------------------------------------ acc_verification
DROP TABLE IF EXISTS `acc_verification`;
CREATE TABLE `acc_verification` (
    account_id          VARCHAR(50) NOT NULL,
    
    mail                VARCHAR(90) NOT NULL,
    code                VARCHAR(255),
    created             TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verified            TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (account_id),
    FOREIGN KEY (account_id) REFERENCES account(account_id)
);

-- ------------------------------------------ acc_image
DROP TABLE IF EXISTS `acc_image`;
CREATE TABLE `acc_image` (
    acc_image_id        VARCHAR(50) NOT NULL,
    account_id          VARCHAR(50) NOT NULL,

    folder              VARCHAR(255) NOT NULL,
    name                VARCHAR(150) NOT NULL,
    mime                VARCHAR(10) NOT NULL,

    created             TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    accessed            TIMESTAMP NULL DEFAULT NULL,

    PRIMARY KEY (acc_image_id, account_id),
    FOREIGN KEY (account_id) REFERENCES account(account_id)
);


-- ------------------------------------------ acc_detail
DROP TABLE IF EXISTS `acc_detail`;
CREATE TABLE `acc_detail` (
    account_id          VARCHAR(50) NOT NULL,

    firstname           VARCHAR(150),
    lastname            VARCHAR(150),
    birthdate           DATE,
    locale              ENUM('en', 'de') DEFAULT 'en',
    avatar              VARCHAR(50),      

    PRIMARY KEY (account_id),
    FOREIGN KEY (account_id) REFERENCES account(account_id),
    FOREIGN KEY (avatar) REFERENCES acc_image(acc_image_id)
);

-- ------------------------------------------ acc_preferences
DROP TABLE IF EXISTS `acc_preferences`;
CREATE TABLE `acc_preferences` (
    account_id          VARCHAR(50) NOT NULL,

    app_mode            ENUM('light', 'dark') NOT NULL DEFAULT 'light',
    

    PRIMARY KEY (account_id),
    FOREIGN KEY (account_id) REFERENCES account(account_id)
);





-- ______________________________________________________________________________________
-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- AUTHENTICATION / SESSIONS

-- ------------------------------------------ authentication
DROP TABLE IF EXISTS `authentication`;
CREATE TABLE `authentication` (
    account_id          VARCHAR(50) NOT NULL,
    
    latest_login        TIMESTAMP NULL DEFAULT NULL,
    total_logins        INT,

    PRIMARY KEY (account_id),
    FOREIGN KEY (account_id) REFERENCES account(account_id)
);

-- ------------------------------------------ auth_session
DROP TABLE IF EXISTS `auth_session`;
CREATE TABLE `auth_session` (
    account_id          VARCHAR(50) NOT NULL,
    
    id                  INT NOT NULL AUTO_INCREMENT,
    keep                BOOLEAN NOT NULL DEFAULT 0,

    operating_system    VARCHAR(80) NOT NULL,
    browser             VARCHAR(80) NOT NULL,

    created             TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    latest_refresh      TIMESTAMP NULL DEFAULT NULL,
    total_refreshes     INT,

    auth_token_id       VARCHAR(50) NOT NULL,
    refresh_token_id    VARCHAR(50) NOT NULL,
    refresh_phrase      VARCHAR(255) NOT NULL,

    UNIQUE INDEX unique_auth_token_id (auth_token_id),
    UNIQUE INDEX unique_refresh_token_id (refresh_token_id),

    PRIMARY KEY (id, account_id),
    FOREIGN KEY (account_id) REFERENCES account(account_id)
);

-- ------------------------------------------ auth_passreset
DROP TABLE IF EXISTS `auth_passreset`;
CREATE TABLE `auth_passreset` (
    account_id          VARCHAR(50) NOT NULL,
    
    created             TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    phrase              VARCHAR(255) NOT NULL,

    PRIMARY KEY (account_id),
    FOREIGN KEY (account_id) REFERENCES account(account_id)
);







-- ______________________________________________________________________________________
-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- APP/API SYSTEM

-- ------------------------------------------ system (TODO?)
-- DROP TABLE IF EXISTS `system`;
-- CREATE TABLE `system` ();

-- ------------------------------------------ sys_log
DROP TABLE IF EXISTS `sys_log`;
CREATE TABLE `sys_log` (

    id                  INT NOT NULL AUTO_INCREMENT,
    level               ENUM('trace','debug','info', 'warn', 'error', 'fatal') NOT NULL DEFAULT 'trace',

    identifier          VARCHAR(255),
    process             VARCHAR(50),
    information         TEXT,
    stamp               TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    trace               TEXT,

    PRIMARY KEY (id)

);


-- ------------------------------------------ sys_error_codes
DROP TABLE IF EXISTS `sys_error_codes`;
CREATE TABLE `sys_error_codes` (

    code                VARCHAR(20) NOT NULL,
    http_code           VARCHAR(20),
    concerns            VARCHAR(255),
    description         VARCHAR(255),
    notes               TEXT,

    PRIMARY KEY (code)
);







-- ______________________________________________________________________________________
-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- VIEWS


-- ------------------------------------------ v_acc_detailed
DROP VIEW IF EXISTS `v_acc_detailed`;
CREATE VIEW `v_acc_detailed` AS

    SELECT

        acc.account_id,
        acc.mail,
        acc.username,
        acc.level,
        acc.status,
        acc_de.firstname,
        acc_de.lastname,
        acc_de.birthdate,
        acc_de.locale,
        acc_de.avatar

    FROM account AS acc
    LEFT JOIN acc_detail AS acc_de ON acc_de.account_id = acc.account_id;


-- ------------------------------------------ v_auth_deep
DROP VIEW IF EXISTS `v_auth_deep`;
CREATE VIEW `v_auth_deep` AS

    SELECT

        ause.id,
        ause.account_id,
        acc.status,
        acc.level,
        ause.keep,
        ause.auth_token_id,
        ause.refresh_token_id

    FROM auth_session AS ause
    LEFT JOIN account AS acc ON acc.account_id = ause.account_id;