<?php

$query1 = "CREATE table IF NOT EXISTS company_info (
	id int unsigned not null auto_increment,
	primary key (id),
	name varchar(250) null,
	slogan varchar(250) null,
	logo_ref text null,
	website varchar(250) null,
	email varchar(250) null,
	phone varchar(15) null,
	address varchar(250) null,
	other text null,
	slider text DEFAULT NULL,
	branches text
)";

$query2 = "CREATE table if not exists roles (
	roleid int unsigned not null auto_increment,
	primary key (roleid),
	transcid varchar(50),
	rolename varchar(255),
	roledesc text DEFAULT NULL,
	clientid int
)";

$query3 = "CREATE table if not exists activitylog (
	id int(11) unsigned not null auto_increment,
	primary key (id),
	user_id int(50),
	action varchar(250),
	type varchar(10) default 'admin',
	location varchar(250),
	location_id varchar(50),
	description varchar(250),
	date DATETIME NULL DEFAULT CURRENT_TIMESTAMP
)";

$query4 = "CREATE table if not exists users (
	id int unsigned not null auto_increment,
	primary key (id),
	first_name varchar(50) default null,
	last_name varchar(50) default null,
	email varchar(50) default null,
	picture_ref varchar(250) default null,
	username varchar(50) default null,
	password text not null,
	pin int(4) not null default 0,
	type tinyint default 0,
	status tinyint default 0,
	roleId tinyint default 0,
	accessLevel tinyint default 0,
	date DATETIME NULL DEFAULT CURRENT_TIMESTAMP
)";



$query5  = "CREATE table if not exists coin (
	id int unsigned not null auto_increment,
	primary key (id),
	`price` decimal(50,2) NOT NULL default 0,
 `rate` decimal(30,2) NOT NULL default 0,
 `charge` decimal(30,2) NOT NULL default 0,
	team_id int(11) not null default 0,
	date DATETIME NULL DEFAULT CURRENT_TIMESTAMP
)";

$query6  = "CREATE table if not exists account (
	id int unsigned not null auto_increment,
	primary key (id),
	user_id int(11) not null default 0,
	balance decimal(60,2) NOT NULL default 0,
	 bonus decimal(60,2) NOT NULL default 0,
	bank_code varchar(5) not null default 0,
	account_name varchar(100) default null,
	account_no varchar(11) not null default 0,
	recipient varchar(100) not null default 0,
	date DATETIME NULL DEFAULT CURRENT_TIMESTAMP
)";

$query7  = "CREATE table if not exists team (
	id int unsigned not null auto_increment,
	primary key (id),
	name varchar(20) not null default 0,
	type varchar(4) not null default 'buy',
	status tinyint default 0,
	`coin_price` decimal(50,2) NOT NULL default 0,
	`rate` decimal(30,2) NOT NULL default 0,
	date DATETIME NULL DEFAULT CURRENT_TIMESTAMP
)";

$query8  = "CREATE table if not exists contributions (
	id int unsigned not null auto_increment,
	primary key (id),
	user_id int(11) not null default 0,
	team_id int(11) not null default 0,
	`amount` decimal(50,2) NOT NULL default 0,
	`charge` decimal(30,2) NOT NULL default 0,
	date DATETIME NULL DEFAULT CURRENT_TIMESTAMP
)";

$query9 = "CREATE TABLE IF NOT EXISTS transaction (
 id int(10) NOT NULL AUTO_INCREMENT,
 PRIMARY KEY (id),
 user_id int(11) NOT NULL DEFAULT 0,
 tx_no varchar(20) NOT NULL,
 tx_type varchar(3) NOT NULL,
 `amount` decimal(50,2) NOT NULL default 0,
 description varchar(100) NOT NULL,
 paid_into varchar(100) DEFAULT NULL,
 evidence varchar(100) DEFAULT NULL,
 status int(10) NOT NULL DEFAULT 0,
 date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
)";


$query10 = "CREATE TABLE IF NOT EXISTS referral (
 id int unsigned not null auto_increment,
 primary key (id),
 referral_id int(50) default 0,
 referred_id int(50) default 0,
 status tinyint NOT NULL DEFAULT 0,
 `amount` decimal(30,2) NOT NULL default 0,
 date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
)";

$query11 = "CREATE TABLE IF NOT EXISTS cash_tokens (
 id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 creator_id int(11) NOT NULL,
 user_id int(11) NOT NULL,
 token varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
 amount decimal(50,2) NOT NULL,
 status tinyint NOT NULL DEFAULT 0,
 created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	UNIQUE KEY `cash_tokens_token_unique` (`token`)
 )";


?>
