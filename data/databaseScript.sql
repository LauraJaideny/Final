CREATE DATABASE LauraAdrian;

USE lauraAdrian;

CREATE TABLE Users (
	fName VARCHAR(30) NOT NULL,
    lName VARCHAR(30) NOT NULL,
    username VARCHAR(50) NOT NULL PRIMARY KEY,
    passwrd VARCHAR(50) NOT NULL,
    email VARCHAR(320) NOT NULL
);

INSERT INTO Users(fName, lName, username, passwrd, email)
VALUES  ('Alfredo', 'Salazar', 'alfredo08', 'alfred90', 'alfred@gmail.com'),
		('Pamela', 'Rodriguez', 'thePam22', '22mapeht', 'pam@gmail.com');

CREATE TABLE Posts (
    postID int NOT NULL AUTO_INCREMENT,
    username VARCHAR(50),
    comment VARCHAR(2000),
    postDate DATE,
    PRIMARY KEY (postID),
    FOREIGN KEY (username) REFERENCES Users(username)
);

CREATE TABLE Replies (
    replyID int NOT NULL AUTO_INCREMENT,
    username VARCHAR(50),
    postID int,
    reply VARCHAR(2000),
    PRIMARY KEY (replyID),
    FOREIGN KEY (username) REFERENCES Users(username),
    FOREIGN KEY (postID) REFERENCES Posts(postID) ON DELETE CASCADE
);

CREATE TABLE Images (
    imageId int NOT NULL AUTO_INCREMENT,
    username VARCHAR(50),
    image VARCHAR(50),
    postDate DATE,
    PRIMARY KEY (imageId),
    FOREIGN KEY (username) REFERENCES Users(username)
);

CREATE TABLE Favorites (
    favoriteID int NOT NULL AUTO_INCREMENT,
    username VARCHAR(50),
    postID int,
    PRIMARY KEY (favoriteID),
    FOREIGN KEY (username) REFERENCES Users(username),
    FOREIGN KEY (postID) REFERENCES Posts(postID)
    ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE FavoriteImages (
    favoriteID int NOT NULL AUTO_INCREMENT,
    username VARCHAR(50),
    imageId int,
    PRIMARY KEY (favoriteID),
    FOREIGN KEY (username) REFERENCES Users(username),
    FOREIGN KEY (imageId) REFERENCES Images(imageId)
    ON DELETE CASCADE ON UPDATE CASCADE
);

/* 
INSERT INTO Images(username, image, postDate)
VALUES ('laura04', 'images/cover.jpg', CURRENT_DATE());

INSERT INTO Posts(username, comment, postDate)
VALUES  ('AdrianP94', 'HOLA SOY EL PRIMER POST XD', CURRENT_DATE());

INSERT INTO Posts(username, comment, postDate)
VALUES  ('AdrianP94', 'OTRO POST', CURRENT_DATE());

INSERT INTO Posts(username, comment, postDate)
VALUES  ('AdrianP94', 'TERCER POST', CURRENT_DATE());

INSERT INTO Posts(username, comment, postDate)
VALUES  ('AdrianP94', 'CUARTO POST', CURRENT_DATE());

INSERT INTO `replies` (`replyID`, `username`, `postID`, `reply`) VALUES (NULL, 'AdrianP94', '1', 'ESTE ES UN REPLY :)');
*/
