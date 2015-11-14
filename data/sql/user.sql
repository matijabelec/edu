CREATE TABLE user (
    id int(11) NOT NULL auto_increment,
    username varchar(20) UNIQUE NOT NULL,
    password char(32) NOT NULL,
    usergroup int NOT NULL DEFAULT 2,
    PRIMARY KEY (id)
);

INSERT INTO user (username, password, usergroup)
    VALUES ('admin', md5('admin'), 1);
