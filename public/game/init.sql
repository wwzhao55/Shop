create database balloon_game;
use balloon_game;
create table game_user
(
	id int unsigned not null auto_increment primary key,
	openid char(40) not null,
	phone char(20) not null default 0,
	count int unsigned default 0,
	count_lim int unsigned default 5,
	share_time int unsigned default 0,
	max_money float(4,2) not null default 0
);
create table single_game
(
	id int unsigned not null auto_increment primary key,
	openid char(40) not null,
	break_point float(4,2) not null,
	push_time int unsigned not null,
	money float(4,2) not null default 0,
	date char(16) not null
);
create table game_time
(
	id int unsigned not null auto_increment primary key,
	openid char(40) not null,
	ym char(20) not null,
	phone_count int unsigned not null default 0,
	game_count int unsigned not null default 0
);
create table share_table
(
	id int unsigned not null auto_increment primary key,
	openid char(40) not null,
	date char(16) not null,
	share int unsigned not null default 0,
	count int unsigned not null default 0
);
create table add_times
(
	times int unsigned not null primary key default 0
);
insert into add_times (times) values (0);

create table open(open_state int unsigned default 1);
insert into open (open_state) values (1);

