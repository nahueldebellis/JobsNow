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
    user_id int,
    experience text,
    education text,
    cv longblob,
    primary key(id),
	foreign key Employee(user_id) REFERENCES Users(id)
);

CREATE TABLE Company(
    id int not null AUTO_INCREMENT,
    user_id int,
    nif int,
    area varchar(50),
    PRIMARY KEY (id),
    FOREIGN KEY Company(user_id) REFERENCES Users(id)    
);

CREATE TABLE Administrator(
	id int not null auto_increment,
    user_id int,
    company_id int,
    position varchar(100),
    up_date date,
    primary key (id),
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (company_id) REFERENCES Company(id) 
);

CREATE TABLE Postulations(
	id int not null AUTO_INCREMENT,
    job_id int,
    employee_id int,
    company_id int,
    PRIMARY KEY (id),
    FOREIGN KEY (job_id) REFERENCES Job(id),
    FOREIGN KEY (employee_id) REFERENCES Employee(id),
    FOREIGN KEY (company_id) REFERENCES Company(id)
);