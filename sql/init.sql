-- schema name: lastterm

CREATE TABLE users(
    Id int PRIMARY KEY AUTO_INCREMENT,
    Username varchar(200),
    Email varchar(200),
    Password varchar(200)
);

CREATE TABLE lists{
    Id int PRIMARY KEY AUTO_INCREMENT,
    UserId int,
    Title varchar(200),
    Content text,
    Created datetime,
    
    FOREIGN KEY(UserId) REFERENCES users(Id)
};