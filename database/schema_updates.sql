-- Schema updates for grocery_mngmnt
-- Run this in phpMyAdmin on database: bd_projdb

-- Add stock column to product_tbl (default 100 for existing products)
ALTER TABLE product_tbl ADD COLUMN IF NOT EXISTS productStock INT DEFAULT 100 AFTER priceType;

-- Orders table
CREATE TABLE IF NOT EXISTS orders_tbl (
    orderId VARCHAR(10) NOT NULL PRIMARY KEY,
    customerId VARCHAR(10) NOT NULL,
    orderDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    orderTotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    orderStatus ENUM('Pending','Processing','Delivered','Cancelled') DEFAULT 'Pending',
    paymentMethod VARCHAR(30) DEFAULT 'cash_on_delivery',
    deliveryAddress TEXT,
    d_status INT DEFAULT 0
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items_tbl (
    itemId INT AUTO_INCREMENT PRIMARY KEY,
    orderId VARCHAR(10) NOT NULL,
    productId VARCHAR(10) NOT NULL,
    productName VARCHAR(255) NOT NULL,
    productPrice DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    itemTotal DECIMAL(10,2) NOT NULL
);

-- Saved payment methods table
CREATE TABLE IF NOT EXISTS payment_methods_tbl (
    methodId INT AUTO_INCREMENT PRIMARY KEY,
    customerId VARCHAR(10) NOT NULL,
    cardHolderName VARCHAR(100) NOT NULL,
    cardLastFour VARCHAR(4) NOT NULL,
    cardExpiry VARCHAR(7) NOT NULL,
    cardType VARCHAR(20) DEFAULT 'Visa',
    isDefault TINYINT DEFAULT 0,
    d_status INT DEFAULT 0
);
