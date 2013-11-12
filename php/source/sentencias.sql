create table if not exists users (
	name varchar(25) not null,
	password varchar(25) not null,
	email varchar(25) not null,
	gcm_id text not null,
	isEnable boolean default true not null, -- This app does not want delete accounts because always is better avoid multiple nicks
	user_created_at timestamp not null default CURRENT_TIMESTAMP,
	user_updated_at timestamp not null,
	primary key (name)
) ENGINE=InnoDB default CHARSET=latin1;

create table if not exists friends (
	player_name varchar(25) not null,
	friend_name varchar(25) not null,
	friendship_added_at timestamp not null default CURRENT_TIMESTAMP,
	friendship_updated_at timestamp not null,
	state enum('waiting_for_response','accepted', 'rejected') not null default 'waiting_for_response',
	primary key (player_name,friend_name)	
) ENGINE=InnoDB default CHARSET=latin1;


create table if not exists games (
	game_id int not null AUTO_INCREMENT,
	player1_name varchar(25) not null,
	player2_name varchar(25) not null,
	game_created_at timestamp not null default CURRENT_TIMESTAMP,
	game_updated_at timestamp not null,
	level smallint unsigned not null default 4,
	score1 smallint unsigned not null,
	score2 smallint unsigned not null,
	state enum('waiting_for_response','waiting_player1', 'waiting_player2', 'refused','finished') not null default 'waiting_for_response',
	primary key (game_id)	
) ENGINE=InnoDB default CHARSET=latin1 AUTO_INCREMENT=1;

create table if not exists moves (
	move_id int not null AUTO_INCREMENT,
	game_id int not null,
	player_name varchar(25) not null, -- The player who makes the move
	move varchar(60) not null,
	move_created_at timestamp not null default CURRENT_TIMESTAMP,
	primary key (move_id)	
) ENGINE=InnoDB default CHARSET=latin1 AUTO_INCREMENT=1;



-- Se recomienda escribir los triggers mediante la interfaz de phpMyAdmin
create trigger user_updated before update on users
	for each row
	begin
            if new.gcm_id != old.gcm_id then  
                    set new.user_updated_at = NOW();
            end if;
	end;

	
create trigger friendship_updated before update on friends
	for each row
	begin
            if new.state != old.state then  
                    set new.friendship_updated_at = NOW();
            end if;
	end;
	
create trigger game_updated before update on games
	for each row
	begin
            if new.state != old.state then  
                    set new.game_updated_at = NOW();
            end if;
	end;	
