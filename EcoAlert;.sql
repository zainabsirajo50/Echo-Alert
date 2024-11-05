-- Active: 1730773964334@@eco-alert.c1gg4gm00tau.us-east-2.rds.amazonaws.com@3306@EcoAlert
CREATE DATABASE IF NOT EXISTS EcoAlert;

USE EcoAlert;

CREATE TABLE IF NOT EXISTS Users (
    Email VARCHAR(100) PRIMARY KEY,
    Password VARCHAR(255) NOT NULL,
    Name VARCHAR(100) NOT NULL,
    UserLocation VARCHAR(100),
    ROLE ENUM(
        'communityMember',
        'organization'
    ) NOT NULL
);

CREATE TABLE IF NOT EXISTS CommunityMembers (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Email VARCHAR(100),
    FOREIGN KEY (Email) REFERENCES Users (Email) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Organizations (
    OrganizationID INT AUTO_INCREMENT PRIMARY KEY,
    Email VARCHAR(100),
    FOREIGN KEY (Email) REFERENCES Users (Email) ON DELETE CASCADE
);