
/*******************************************************************************
   Chinook Database - Version 1.4
   Script: Chinook_Sqlite.sql
   Description: Creates and populates the Chinook database.
   DB Server: Sqlite
   Author: Luis Rocha
   License: http://www.codeplex.com/ChinookDatabase/license
********************************************************************************/

DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Item;
DROP TABLE IF EXISTS Transaction;
DROP TABLE IF EXISTS Cart;
DROP TABLE IF EXISTS ProductCategory;
DROP TABLE IF EXISTS Message;
DROP TABLE IF EXISTS ItemCategory;
DROP TABLE IF EXISTS ItemBrand;
DROP TABLE IF EXISTS ItemCondition;
DROP TABLE IF EXISTS ItemSize;
DROP TABLE IF EXISTS ItemModel;



/*******************************************************************************
   Create Tables
********************************************************************************/


/*


CREATE TABLE Customer
(
    CustomerId INTEGER  NOT NULL,
    FirstName NVARCHAR(40)  NOT NULL,
    LastName NVARCHAR(20)  NOT NULL,
    Company NVARCHAR(80),
    Address NVARCHAR(70),
    City NVARCHAR(40),
    State NVARCHAR(40),
    Country NVARCHAR(40),
    PostalCode NVARCHAR(10),
    Phone NVARCHAR(24),
    Fax NVARCHAR(24),
    Email NVARCHAR(60) NOT NULL,
    Password NVARCHAR(40) NOT NULL,
    CONSTRAINT PK_Customer PRIMARY KEY  (CustomerId)
);

CREATE TABLE Track
(
    TrackId INTEGER  NOT NULL,
    Name NVARCHAR(200)  NOT NULL,
    AlbumId INTEGER,
    MediaTypeId INTEGER  NOT NULL,
    GenreId INTEGER,
    Composer NVARCHAR(220),
    Milliseconds INTEGER  NOT NULL,
    Bytes INTEGER,
    UnitPrice NUMERIC(10,2)  NOT NULL,
    CONSTRAINT PK_Track PRIMARY KEY  (TrackId),
    FOREIGN KEY (AlbumId) REFERENCES Album (AlbumId) 
		ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (GenreId) REFERENCES Genre (GenreId) 
		ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (MediaTypeId) REFERENCES MediaType (MediaTypeId) 
		ON DELETE NO ACTION ON UPDATE NO ACTION
);

CREATE TABLE Album
(
    AlbumId INTEGER  NOT NULL,
    Title NVARCHAR(160)  NOT NULL,
    ArtistId INTEGER  NOT NULL,
    CONSTRAINT PK_Album PRIMARY KEY  (AlbumId),
    FOREIGN KEY (ArtistId) REFERENCES Artist (ArtistId)
		ON DELETE NO ACTION ON UPDATE NO ACTION
);
*/


CREATE TABLE User
(
    UserId INTEGER NOT NULL,
    FirstName NVARCHAR(40)  NOT NULL,
    LastName NVARCHAR(20)  NOT NULL,
    Username TEXT NOT NULL UNIQUE,
    Email NVARCHAR(60) NOT NULL,
    Password NVARCHAR(40) NOT NULL,
    JoinDate DATE DEFAULT CURRENT_DATE,
    Address NVARCHAR(70),
    City NVARCHAR(40),
    District NVARCHAR(40),
    Country NVARCHAR(40),
    PostalCode NVARCHAR(10),
    Phone NVARCHAR(24),
    ImageUrl NVARCHAR(255)
    Admin BOOLEAN NOT NULL,
    
    CONSTRAINT PK_User PRIMARY KEY  (UserId)
);
CREATE TABLE Item
(
   ItemId INTEGER  NOT NULL,
   SellerId INTEGER,
   Title NVARCHAR(160)  NOT NULL,
   Description NVARCHAR(200),
   Price NUMERIC(10,2)  NOT NULL,
   ImageUrl NVARCHAR(255),
   ListingDate DATE DEFAULT CURRENT_DATE,
   BrandId INTEGER,
   ConditionId INTEGER,
   SizeId INTEGER,
   ModelId INTEGER,
   CONSTRAINT PK_Item PRIMARY KEY  (ItemId),
   FOREIGN KEY (SellerId) REFERENCES User (UserId) 
      ON DELETE NO ACTION ON UPDATE NO ACTION,
   FOREIGN KEY (BrandId) REFERENCES ItemBrand (BrandId) 
        ON DELETE NO ACTION ON UPDATE NO ACTION,
   FOREIGN KEY (ConditionId) REFERENCES ItemCondition (ConditionId) 
        ON DELETE NO ACTION ON UPDATE NO ACTION,
   FOREIGN KEY (SizeId) REFERENCES ItemSize (SizeId) 
        ON DELETE NO ACTION ON UPDATE NO ACTION,
   FOREIGN KEY (ModelId) REFERENCES ItemModel (ModelId) 
        ON DELETE NO ACTION ON UPDATE NO ACTION,
   
    
);

CREATE TABLE Transaction
(
    TransactionId INTEGER  NOT NULL,
    BuyerId INTEGER,
    SellerId INTEGER,
    ItemId INTEGER,
    TransactionDate DATE DEFAULT CURRENT_DATE,
    CONSTRAINT PK_Transaction PRIMARY KEY  (TransactionId),
    FOREIGN KEY (BuyerId) REFERENCES User (UserId) 
        ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (SellerId) REFERENCES User (UserId) 
        ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (ItemId) REFERENCES Item (ItemId) 
        ON DELETE NO ACTION ON UPDATE NO ACTION
);
CREATE TABLE Cart
(
    CartId INTEGER  NOT NULL,
    UserId INTEGER,
    ItemId INTEGER,
    Quantity INTEGER,
    CONSTRAINT PK_Cart PRIMARY KEY  (CartId),
    FOREIGN KEY (UserId) REFERENCES User (UserId) 
        ON DELETE CASCADE ON UPDATE NO ACTION,
    FOREIGN KEY (ItemId) REFERENCES Item (ItemId) 
        ON DELETE CASCADE ON UPDATE NO ACTION
);

CREATE TABLE Message
(
    MessageId INTEGER PRIMARY KEY,
    SenderId INTEGER,
    ReceiverId INTEGER,
    MessageText NVARCHAR(200),
    SendDate DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (SenderId) REFERENCES User (UserId) 
        ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (ReceiverId) REFERENCES User (UserId) 
        ON DELETE NO ACTION ON UPDATE NO ACTION
);

CREATE TABLE ProductCategory
(  
   CategoryId INTEGER PRIMARY KEY,
   CategoryName NVARCHAR(100) NOT NULL

   
);


CREATE TABLE ItemCategory
(
   ItemId INTEGER
   CategoryId INTEGER
   FOREIGN KEY (ItemId) REFERENCES Item (ItemId) 
        ON DELETE CASCADE ON UPDATE NO ACTION
   FOREIGN KEY (CategoryId) REFERENCES ProductCategory (CategoryId) 
        ON DELETE CASCADE ON UPDATE NO ACTION   
)

Create TABLE ItemBrand
(
   BrandId INTEGER PRIMARY KEY,
   BrandName NVARCHAR(100) NOT NULL
)
Create TABLE ItemCondition
(
   ConditionId INTEGER PRIMARY KEY,
   ConditionName NVARCHAR(100) NOT NULL
)

Create TABLE ItemSize
(
   SizeId INTEGER PRIMARY KEY,
   SizeName NVARCHAR(100) NOT NULL
)

Create TABLE ItemModel
(
   ModelId INTEGER PRIMARY KEY,
   ModelName NVARCHAR(100) NOT NULL
)

/*******************************************************************************
   Create Foreign Keys
********************************************************************************/
CREATE INDEX IFK_AlbumArtistId ON Album (ArtistId);

CREATE INDEX IFK_TrackAlbumId ON Track (AlbumId);

/*******************************************************************************
   Populate Tables
********************************************************************************/
