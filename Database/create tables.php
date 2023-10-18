create table login (
    id int not null primary key auto_increment,
    name varchar(50) not null unique,
    password varchar(255) not null,
    created_at datetime default current_timestamp
);

create table registration (
    firstName varchar(50) not null,
    lastName varchar(50) not null,
    email varchar(255),
    address varchar(255),
    phone varchar(255),
    DOB DATE,
);