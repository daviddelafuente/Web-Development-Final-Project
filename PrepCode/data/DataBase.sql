Create database PrepCode;


Create Table Users(
  Username VARCHAR(30) NOT NULL,
  Password varchar(100) NOT NULL,
  Fname varchar(30) not null,
  Lname varchar(30) not null,
  Email varchar(50) not null,
  Gender char(1) not null,
  Country varchar(30) not null,
  Organization varchar(50) not null,
  PRIMARY Key(UserName,Email));
/*encrypt password*/

Create Table Follows(
  UserFrom varchar(30) not null,
  UserTo varchar(30) not null,
  primary key(UserFrom,UserTo),
  FOREIGN key(UserFrom) REFERENCES Users(Username),
  FOREIGN key(UserTo) REFERENCES Users(Username)
);

Create Table Admin(
  id varchar(30) Not null primary key,
  Password varchar(100)
);
/*We could encrypt both id and password*/
Create Table Problems(
  id int not null AUTO_INCREMENT PRIMARY Key,
  title varchar(50) not null,
  OrgFrom varchar(30) not null,
  link varchar(150) not null unique
);
/*Going to be slow because its need to get the id*/
Insert into Problems (OrgFrom,title ,link)
values ('LeetCode','https://leetcode.com/problems/add-two-numbers/description/');

Create Table Tags(
  id int not null,
  tag varchar(50) not null,
  primary key(id,tag),
  FOREIGN KEY (id) REFERENCES Problems(id)
);
/*el tag puede ser un nombre o un link para una imagen que esta
en una carpeta adentro del proyecto*/
Insert into Tags(id,tag)
values (1,"Math"),
        (1,"Linked List");


Create Table Solved(
  username varchar(30) not null,
  id int,
  timePosted DateTime not null,
  primary key(username,id),
  FOREIGN KEY (id) REFERENCES Problems(id),
  FOREIGN key(username) REFERENCES Users(Username)
);
/*At moment of getting all problems to show we will need to find if the
given problem is solved maybe with a subquery inside the sql or another query
for every entrey of database (veeery slow) maybe dont show problems solved*/

Create Table News(
  id int not null AUTO_INCREMENT PRIMARY Key,
  OrgFrom varchar(30) not null,
  title varchar(50) not null,
  link varchar(150) not null,
  timePosted DateTime not null
);
