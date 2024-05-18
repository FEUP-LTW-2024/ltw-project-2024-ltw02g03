

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
    DROP TABLE IF EXISTS ItemImage;
    DROP TABLE IF EXISTS ProductImage;
    DROP TABLE IF EXISTS Review;



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
    Active BOOLEAN DEFAULT 1,
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
        Address NVARCHAR(70),
        City NVARCHAR(40),
        District NVARCHAR(40),
        Country NVARCHAR(40),
        PostalCode NVARCHAR(10),
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
        CartId INTEGER PRIMARY KEY AUTOINCREMENT,
        UserId INTEGER,
        ItemId INTEGER,
        Quantity INTEGER,
        FOREIGN KEY (UserId) REFERENCES User (UserId) 
            ON DELETE CASCADE ON UPDATE NO ACTION,
        FOREIGN KEY (ItemId) REFERENCES Item (ItemId) 
            ON DELETE CASCADE ON UPDATE NO ACTION
    );

    -- Communication Table
    CREATE TABLE Communication
    (
        CommunicationId INTEGER PRIMARY KEY AUTOINCREMENT,
        SenderId INTEGER,
        ReceiverId INTEGER,
        ItemId INTEGER,
        CommunicationText Text,
        SendDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (SenderId) REFERENCES User (UserId) 
            ON DELETE NO ACTION ON UPDATE NO ACTION,
        FOREIGN KEY (ReceiverId) REFERENCES User (UserId) 
            ON DELETE NO ACTION ON UPDATE NO ACTION,
        FOREIGN KEY (ItemId) REFERENCES Item (ItemId)
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
            ON DELETE CASCADE ON UPDATE NO ACTION,

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

    CREATE TABLE ProductImage
    (
        ImageId INTEGER PRIMARY KEY,
        ImageUrl NVARCHAR(255)
    );
    CREATE TABLE ItemImage
    (
        ItemId INTEGER,
        ImageId INTEGER,
        FOREIGN KEY (ItemId) REFERENCES Item (ItemId) 
            ON DELETE CASCADE ON UPDATE NO ACTION
        FOREIGN KEY (ImageId) REFERENCES ProductImage (ImageId)
            ON DELETE CASCADE ON UPDATE NO ACTION

        
    );

     -- Review Table
    CREATE TABLE Review (
        ReviewId INTEGER PRIMARY KEY,
        UserId INTEGER,
        ItemId INTEGER,
        Rating FLOAT,
        Comment TEXT,
        ReviewDate DATE DEFAULT CURRENT_DATE,
        FOREIGN KEY (UserId) REFERENCES User (UserId) ON DELETE NO ACTION ON UPDATE NO ACTION,
        FOREIGN KEY (ItemId) REFERENCES Item (ItemId) ON DELETE NO ACTION ON UPDATE NO ACTION
    );
    /*
    EXTRA FEATURES



    
    */


    /*******************************************************************************
    Populate Tables
    ********************************************************************************/
    -- Inserir dados de exemplo na tabela User
    INSERT INTO User (UserId, FirstName, LastName, Username, Email, Password, Address, City, District, Country, PostalCode, Phone, ImageUrl, Admin)
    VALUES
    (1, 'John', 'Doe', 'johndoe', 'johndoe@example.com', 'password123', '123 Main St', 'Anytown', 'Anydistrict', 'AnyCountry', '12345', '123-456-7890', 'https://example.com/avatar3.jpg', 0),
    (2, 'Jane', 'Smith', 'janesmith', 'janesmith@example.com', 'password456', '456 Oak St', 'Othertown', 'Otherdistrict', 'OtherCountry', '54321', '987-654-3210', 'https://example.com/avatar2.jpg', 0),
    (3, 'Admin', 'Admin', 'admin', 'admin@example.com', '$2y$10$E/kh5qRzGdBofI4D3O1.L.Yc2vISpqjDKbZX2CSETRo57I8SVEgeG', '789 Elm St', 'Somewhere', 'Somedistrict', 'SomeCountry', '67890', '555-123-4567', '../database/uploads/user_3/Mortimer_Freeze (1).png', 1),
    (4, 'Maria', 'Silva', 'mariasilva', 'mariasilva@example.com', 'password789', '789 Elm St', 'Somewhere', 'Somedistrict', 'SomeCountry', '67890', '555-123-4567', 'https://example.com/avatar3.jpg', 0),
    (5, 'Carlos', 'Santos', 'carlossantos', 'carlossantos@example.com', 'passwordabc', '101 Pine St', 'Anywhere', 'Anydistrict', 'AnyCountry', '54321', '123-555-7890', 'https://example.com/avatar4.jpg', 0);

    -- Inserir dados de exemplo na tabela Item
    INSERT INTO Item (ItemId, SellerId, Title, Description, Price, ListingDate, ImageId, BrandId, ConditionId, SizeId, ModelId)
    VALUES
    (1, 1, 'Samsung Galaxy S20', 'Brand new Samsung Galaxy S20 smartphone', 799.99, CURRENT_DATE, 1, 1, 1, 2, 1),
    (2, 1, 'Nike Air Max 270', 'Nike Air Max 270 shoes', 129.99, CURRENT_DATE, 2, 3, 1, 3, 2),
    (3, 2, 'Apple MacBook Pro', 'Used MacBook Pro in good condition', 1499.99, CURRENT_DATE, 3, 2, 3, 1, 3),
    (4, 2, 'Nike Air Force 1', 'Classic Nike Air Force 1 shoes', 99.99, CURRENT_DATE, 4, 3, 1, 2, 4),
    (5, 3, 'Adidas Superstar', 'Adidas Superstar sneakers', 79.99, CURRENT_DATE, 5, 4, 1, 3, 5);

    -- Inserir dados de exemplo na tabela ProductCategory
    INSERT INTO ProductCategory (CategoryId, CategoryName)
    VALUES
    (1, 'Electronics'),
    (2, 'Clothing'),
    (3, 'Books'),
    (4, 'Furniture'),
    (5, 'Home Appliances'),
    (6, 'Jewelry');

    -- Inserir dados de exemplo na tabela ItemCategory
    INSERT INTO ItemCategory (ItemId, CategoryId)
    VALUES
    (1, 1),
    (2, 2),
    (3, 1),
    (4, 2),
    (5, 2);


    -- Inserir dados de exemplo na tabela ItemBrand
    INSERT INTO ItemBrand (BrandId, BrandName)
    VALUES
    (1, 'Samsung'),
    (2, 'Apple'),
    (3, 'Nike'),
    (4, 'Adidas'),
    (5, 'Sony'),
    (6, 'Rolex');



    -- Inserir dados de exemplo na tabela ItemCondition
    INSERT INTO ItemCondition (ConditionId, ConditionName)
    VALUES
    (1, 'New'),
    (2, 'Used - Like New'),
    (3, 'Used - Good'),
    (4, 'Used - Fair'),
    (5, 'Bad');

    -- Inserir dados de exemplo na tabela ItemSize
    INSERT INTO ItemSize (SizeId, SizeName)
    VALUES
    (1, 'Extra Small'),
    (2, 'Small'),
    (3, 'Medium'),
    (4, 'Large'),
    (5, 'Extra Large');

    -- Inserir dados de exemplo na tabela ItemModel
    INSERT INTO ItemModel (ModelId, ModelName)
    VALUES
    (1, 'Galaxy S20'),
    (2, 'Air Max 270'),
    (3, 'MacBook Pro'),
    (4, 'Air Force 1'),
    (5, 'Superstar');

    -- Inserir dados de exemplo na tabela ItemImage
    INSERT INTO ItemImage (ItemId, ImageId)
    VALUES
    (1, 1),
    (2, 2),
    (3, 3),
    (4, 4),
    (5, 5);


    -- Inserir dados de exemplo na tabela ProductImage
    INSERT INTO ProductImage(ImageId,ImageUrl)
    VALUES
    (1, 'database/uploads/item_1/samsung-galaxy-s20-fe-5g-g781-128gb-dual-sim-lavanda.jpg'),
    (2, 'database/uploads/item_2/1-nike-air-max-270.jpg'),
    (3, 'database/uploads/item_3/Apple_16-inch-MacBook-Pro_111319_big.jpg.large.jpg'),
    (4, 'database/uploads/item_4/sapatilhas-air-force-1-07-1nfJ59.jpg'),
    (5, 'database/uploads/item_5/adidas-superstar-gore-tex-core-black-white-if6162-658e93bd76e79.jpg');

    -- Inserir dados de exemplo na tabela Payment
    INSERT INTO Payment (PaymentId, BuyerId, SellerId, ItemId, Address, City, District, Country, PostalCode, PaymentDate)
    VALUES
    (1, 2, 1, 1, '123 Main St', 'Anytown', 'Anydistrict', 'AnyCountry', '12345', CURRENT_DATE),
    (2, 3, 2, 3, '456 Oak St', 'Othertown', 'Otherdistrict', 'OtherCountry', '54321', CURRENT_DATE),
    (3, 1, 2, 4, '789 Elm St', 'Somewhere', 'Somedistrict', 'SomeCountry', '67890', CURRENT_DATE),
    (4, 2, 3, 5, '101 Pine St', 'Anywhere', 'Anydistrict', 'AnyCountry', '54321', CURRENT_DATE);

    -- Inserir dados de exemplo na tabela Review
    INSERT INTO Review (ReviewId, UserId, ItemId, Rating, Comment, ReviewDate)
    VALUES
    (1, 2, 5, 3.5, 'Great phone, fast shipping', CURRENT_DATE),
    (2, 5, 5, 3.0, 'Good laptop, fast delivery', CURRENT_DATE),
    (3, 1, 5, 4.0, 'Shoes are nice, but took a while to arrive', CURRENT_DATE),
    (4, 4, 5, 5.0, 'Great sneakers, fast shipping', CURRENT_DATE);