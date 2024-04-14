

DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Item;
DROP TABLE IF EXISTS Payment;
DROP TABLE IF EXISTS Cart;
DROP TABLE IF EXISTS ProductCategory;
DROP TABLE IF EXISTS Communication;
DROP TABLE IF EXISTS ItemCategory;
DROP TABLE IF EXISTS ItemBrand;
DROP TABLE IF EXISTS ItemCondition;
DROP TABLE IF EXISTS ItemSize;
DROP TABLE IF EXISTS ItemModel;



/*******************************************************************************
   Create Tables
********************************************************************************/



-- User Table
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
    ImageUrl NVARCHAR(255),
    Admin BOOLEAN NOT NULL,
    
    CONSTRAINT PK_User PRIMARY KEY  (UserId)
);

-- Item Table
CREATE TABLE Item
(
   ItemId INTEGER  NOT NULL,
   SellerId INTEGER,
   Title NVARCHAR(160)  NOT NULL,
   Description NVARCHAR(200),
   Price NUMERIC(10,2)  NOT NULL,
   ListingDate DATE DEFAULT CURRENT_DATE,
   ImageId INTEGER,
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
   FOREIGN KEY (ImageId) REFERENCES ItemImage (ImageId) 
        ON DELETE NO ACTION ON UPDATE NO ACTION
    
);

-- Payment Table
CREATE TABLE Payment
(
    PaymentId INTEGER  NOT NULL,
    BuyerId INTEGER,
    SellerId INTEGER,
    ItemId INTEGER,
    PaymentDate DATE DEFAULT CURRENT_DATE,
    CONSTRAINT PK_Payment PRIMARY KEY  (PaymentId),
    FOREIGN KEY (BuyerId) REFERENCES User (UserId) 
        ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (SellerId) REFERENCES User (UserId) 
        ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (ItemId) REFERENCES Item (ItemId) 
        ON DELETE NO ACTION ON UPDATE NO ACTION
);

-- Shopping Cart Table
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

-- Communication Table
CREATE TABLE Communication
(
    CommunicationId INTEGER PRIMARY KEY,
    SenderId INTEGER,
    ReceiverId INTEGER,
    CommunicationText NVARCHAR(200),
    SendDate DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (SenderId) REFERENCES User (UserId) 
        ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (ReceiverId) REFERENCES User (UserId) 
        ON DELETE NO ACTION ON UPDATE NO ACTION
);


-- Product-Category Table
CREATE TABLE ProductCategory
(  
   CategoryId INTEGER PRIMARY KEY,
   CategoryName NVARCHAR(100) NOT NULL

   
);

-- Item-Category Table
CREATE TABLE ItemCategory
(
   ItemId INTEGER,
   CategoryId INTEGER,
   FOREIGN KEY (ItemId) REFERENCES Item (ItemId) 
        ON DELETE CASCADE ON UPDATE NO ACTION
   FOREIGN KEY (CategoryId) REFERENCES ProductCategory (CategoryId) 
        ON DELETE CASCADE ON UPDATE NO ACTION   
);

-- Item-Brand Table
CREATE TABLE ItemBrand
(
   BrandId INTEGER PRIMARY KEY,
   BrandName NVARCHAR(100) NOT NULL
);

-- Item-Condition Table
CREATE TABLE ItemCondition
(
   ConditionId INTEGER PRIMARY KEY,
   ConditionName NVARCHAR(100) NOT NULL
);

-- Item-Size Table
CREATE TABLE ItemSize
(
   SizeId INTEGER PRIMARY KEY,
   SizeName NVARCHAR(100) NOT NULL
);

-- Item-Model Table
CREATE TABLE ItemModel
(
   ModelId INTEGER PRIMARY KEY,
   ModelName NVARCHAR(100) NOT NULL
);
CREATE TABLE ItemImage
(
    ImageId INTEGER PRIMARY KEY,
    ItemId INTEGER,
    ImageUrl NVARCHAR(255),
    FOREIGN KEY (ItemId) REFERENCES Item (ItemId) 
        ON DELETE CASCADE ON UPDATE NO ACTION
);


/*
EXTRA FEATURES

-- Review Table
CREATE TABLE Review (
    ReviewId INTEGER PRIMARY KEY,
    UserId INTEGER,
    ItemId INTEGER,
    Rating INTEGER,
    Comment TEXT,
    ReviewDate DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (ItemId) REFERENCES Item (ItemId) ON DELETE NO ACTION ON UPDATE NO ACTION
);

-- PaymentHistory Table
CREATE TABLE PaymentHistory (
    PaymentId INTEGER PRIMARY KEY,
    BuyerId INTEGER,
    SellerId INTEGER,
    ItemId INTEGER,
    PaymentDate DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (BuyerId) REFERENCES User (UserId) ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (SellerId) REFERENCES User (UserId) ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (ItemId) REFERENCES Item (ItemId) ON DELETE NO ACTION ON UPDATE NO ACTION
);

-- Notification Table
CREATE TABLE Notification (
    NotificationId INTEGER PRIMARY KEY,
    UserId INTEGER,
    NotificationText TEXT,
    NotificationDate DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE NO ACTION ON UPDATE NO ACTION
);
*/

/*******************************************************************************
   Create Foreign Keys
********************************************************************************/

/*******************************************************************************
   Populate Tables
********************************************************************************/
-- Inserir dados de exemplo na tabela User
INSERT INTO User (UserId, FirstName, LastName, Username, Email, Password, Address, City, District, Country, PostalCode, Phone, ImageUrl, Admin)
VALUES
(1, 'John', 'Doe', 'johndoe', 'johndoe@example.com', 'password123', '123 Main St', 'Anytown', 'Anydistrict', 'AnyCountry', '12345', '123-456-7890', 'https://example.com/avatar1.jpg', 0),
(2, 'Jane', 'Smith', 'janesmith', 'janesmith@example.com', 'password456', '456 Oak St', 'Othertown', 'Otherdistrict', 'OtherCountry', '54321', '987-654-3210', 'https://example.com/avatar2.jpg', 0),
(3, 'Admin', 'Admin', 'admin', 'admin@example.com', 'admin123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

-- Inserir dados de exemplo na tabela ProductCategory
INSERT INTO ProductCategory (CategoryId, CategoryName)
VALUES
(1, 'Electronics'),
(2, 'Clothing'),
(3, 'Books'),
(4, 'Furniture');

-- Inserir dados de exemplo na tabela ItemBrand
INSERT INTO ItemBrand (BrandId, BrandName)
VALUES
(1, 'Samsung'),
(2, 'Apple'),
(3, 'Nike'),
(4, 'Adidas');

-- Inserir dados de exemplo na tabela ItemCondition
INSERT INTO ItemCondition (ConditionId, ConditionName)
VALUES
(1, 'New'),
(2, 'Used - Like New'),
(3, 'Used - Good'),
(4, 'Used - Fair');

-- Inserir dados de exemplo na tabela ItemSize
INSERT INTO ItemSize (SizeId, SizeName)
VALUES
(1, 'Small'),
(2, 'Medium'),
(3, 'Large');
