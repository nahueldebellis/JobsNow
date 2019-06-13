CREATE TABLE Users(
	id int not null auto_increment,
    name varchar(50),
    email varchar(100),
    phone int,
    pass varchar(120),
    address varchar(60),
    rotulo varchar(100),
    profile_photo longblob not null,
    primary key (id)
);

CREATE TABLE Job(
	id int not null AUTO_INCREMENT,
    position varchar (50),
    description text,
    salary float,
    requeriments text,
    state varchar (30),
    PRIMARY KEY (id)
);

CREATE TABLE Employee(
	id int not null auto_increment,
    id_user int,
    experience text,
    education text,
    cv longblob,
    primary key(id),
	foreign key Employee(id_user) REFERENCES Users(id)
);

CREATE TABLE Company(
	id int not null AUTO_INCREMENT,
    id_user int,
    nif int,
    area varchar(50),
    PRIMARY KEY (id),
    FOREIGN KEY Company(id_user) REFERENCES Users(id)    
);

CREATE TABLE Administrator(
	id int not null auto_increment,
    id_user int,
    id_company int,
    position varchar(100),
    up_date date,
    primary key (id),
    FOREIGN KEY (id_user) REFERENCES Users(id),
    FOREIGN KEY (id_company) REFERENCES Company(id) 
);

CREATE TABLE Postulations(
	id int not null AUTO_INCREMENT,
    id_job int,
    id_employee int,
    id_company int,
    PRIMARY KEY (id),
    FOREIGN KEY (id_job) REFERENCES Job(id),
    FOREIGN KEY (id_employee) REFERENCES Employee(id),
    FOREIGN KEY (id_company) REFERENCES Company(id)
);