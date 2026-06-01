-- ============================================================
-- bd_projdb — Full Test Data Dump
-- Grocery Management System
-- ============================================================
-- HOW TO IMPORT (phpMyAdmin):
--   1. Open http://localhost/phpmyadmin
--   2. Click on "bd_projdb" in the left sidebar
--      (if it doesn't exist: click New → type bd_projdb → Create)
--   3. Click the "Import" tab at the top
--   4. Click "Choose File" → select this .sql file
--   5. Click "Go" at the bottom
-- ============================================================
--
-- TEST CREDENTIALS:
-- ┌───────────────────┬──────────────────────┬──────────┬────────────────────┐
-- │ Role              │ Email                │ Password │ Notes              │
-- ├───────────────────┼──────────────────────┼──────────┼────────────────────┤
-- │ Admin             │ admin@gmail.com      │ admin    │ Active             │
-- │ Admin             │ kamala@gmail.com     │ admin    │ Active             │
-- │ Customer          │ nir@gmail.com        │ 12345    │ Active             │
-- │ Customer          │ sarah@gmail.com      │ 12345    │ Active             │
-- │ Customer          │ kamal@gmail.com      │ 12345    │ Active             │
-- │ Customer          │ priya@gmail.com      │ 12345    │ DEACTIVATED        │
-- │ Employee          │ john.emp@gmail.com   │ 12345    │ Active             │
-- │ Employee          │ amara.emp@gmail.com  │ 12345    │ Active             │
-- │ Employee          │ ruwan.emp@gmail.com  │ 12345    │ DEACTIVATED        │
-- └───────────────────┴──────────────────────┴──────────┴────────────────────┘
--
-- SCENARIOS COVERED:
--   - Admin login & dashboard access
--   - Active customer login & shopping
--   - Deactivated customer (blocked at login)
--   - Soft-deleted customer (hidden from management view)
--   - Active & deactivated employees
--   - Soft-deleted employee
--   - 25 products across all 14 categories (1 deleted product)
--   - All 14 product categories
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `category_tbl`;
DROP TABLE IF EXISTS `customer_tbl`;
DROP TABLE IF EXISTS `employee_tbl`;
DROP TABLE IF EXISTS `product_tbl`;
DROP TABLE IF EXISTS `login_tbl`;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- CATEGORY TABLE
-- ============================================================

CREATE TABLE `category_tbl` (
  `id` varchar(6) NOT NULL,
  `categoryName` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `category_tbl` (`id`, `categoryName`) VALUES
('CAT001', 'Vegetables'),
('CAT002', 'Fruits'),
('CAT003', 'Dairy Products'),
('CAT004', 'Baking & Pantry'),
('CAT005', 'Pasta & Rice'),
('CAT006', 'Spices'),
('CAT007', 'Snacks'),
('CAT008', 'Desserts Mix'),
('CAT009', 'Coffee, Tea & Malts'),
('CAT010', 'Beverages'),
('CAT011', 'Baby Products'),
('CAT012', 'Health & Beauty'),
('CAT013', 'Household'),
('CAT014', 'Stationery');

-- ============================================================
-- CUSTOMER TABLE
-- ============================================================
-- customerAge is the 8th column; inserted by register.php auto-calculation

CREATE TABLE `customer_tbl` (
  `customerID`       varchar(8)   NOT NULL,
  `customerName`     varchar(250) NOT NULL,
  `customerEmail`    varchar(100) NOT NULL,
  `customerNIC`      varchar(15)  NOT NULL,
  `customerPhone`    varchar(12)  NOT NULL,
  `customerGender`   varchar(10)  NOT NULL,
  `customerBirthday` date         NOT NULL,
  `customerAge`      int(3)       NOT NULL,
  `customerPasswd`   varchar(50)  NOT NULL,
  `d_status`         int(1)       NOT NULL DEFAULT 0,
  `c_date`           timestamp    NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`customerID`),
  UNIQUE KEY `uq_customerEmail` (`customerEmail`),
  UNIQUE KEY `uq_customerNIC`   (`customerNIC`),
  UNIQUE KEY `uq_customerPhone` (`customerPhone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customer_tbl`
  (`customerID`, `customerName`, `customerEmail`, `customerNIC`, `customerPhone`, `customerGender`, `customerBirthday`, `customerAge`, `customerPasswd`, `d_status`)
VALUES
-- Active customers
('CUS00001', 'Nirmala Perera',    'nir@gmail.com',     '321563789901', '+94762493463', 'Female', '2002-05-05', 22, '12345', 0),
('CUS00002', 'Sarah Fernando',    'sarah@gmail.com',   '983456712V',   '+94771234567', 'Female', '1995-08-15', 29, '12345', 0),
('CUS00003', 'Kamal Silva',       'kamal@gmail.com',   '456789012345', '+94712345678', 'Male',   '1990-03-22', 34, '12345', 0),
-- Deactivated (loginStatus=0 in login_tbl; the account still exists but user cannot log in)
('CUS00004', 'Priya Jayawardena', 'priya@gmail.com',   '890123456789', '+94723456789', 'Female', '1998-11-10', 26, '12345', 0),
-- Soft-deleted (d_status=1; hidden from all management views)
('CUS00005', 'Deleted User Demo', 'deleted@gmail.com', '111222333444', '+94700000001', 'Male',   '1985-01-15', 39, '12345', 1);

-- ============================================================
-- EMPLOYEE TABLE
-- ============================================================

CREATE TABLE `employee_tbl` (
  `employeeID`       varchar(8)   NOT NULL,
  `employeeName`     varchar(250) NOT NULL,
  `employeeEmail`    varchar(100) NOT NULL,
  `employeeNIC`      varchar(15)  NOT NULL,
  `employeePhone`    varchar(12)  NOT NULL,
  `employeeGender`   varchar(10)  NOT NULL,
  `employeePassword` varchar(50)  NOT NULL,
  `d_status`         int(1)       NOT NULL DEFAULT 0,
  `c_date`           timestamp    NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`employeeID`),
  UNIQUE KEY `uq_employeeEmail` (`employeeEmail`),
  UNIQUE KEY `uq_employeeNIC`   (`employeeNIC`),
  UNIQUE KEY `uq_employeePhone` (`employeePhone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `employee_tbl`
  (`employeeID`, `employeeName`, `employeeEmail`, `employeeNIC`, `employeePhone`, `employeeGender`, `employeePassword`, `d_status`)
VALUES
-- Active employees
('EMP00001', 'John Bandara',    'john.emp@gmail.com',  '567890123456', '+94755566677', 'Male',   '12345', 0),
('EMP00002', 'Amara Wickrama',  'amara.emp@gmail.com', '234567890123', '+94744455566', 'Female', '12345', 0),
-- Deactivated employee (loginStatus=0 in login_tbl)
('EMP00003', 'Ruwan Mendis',    'ruwan.emp@gmail.com', '345678901234', '+94733344455', 'Male',   '12345', 0),
-- Soft-deleted employee
('EMP00004', 'Deleted Emp',     'delemp@gmail.com',    '999888777666', '+94700000002', 'Male',   '12345', 1);

-- ============================================================
-- PRODUCT TABLE
-- ============================================================
-- productImg is empty here (no physical files); products display without images
-- priceType is either "kg" or "item"
-- PROD prefix + 5 digits = PROD00001 (9 chars → varchar(10))

CREATE TABLE `product_tbl` (
  `productId`          varchar(10)    NOT NULL,
  `productName`        varchar(250)   NOT NULL,
  `productCategory`    varchar(6)     NOT NULL,
  `productSupplier`    varchar(250)   NOT NULL,
  `productImg`         varchar(500)   NOT NULL DEFAULT '',
  `productDescription` varchar(500)   NOT NULL,
  `productPrice`       decimal(10,2)  NOT NULL,
  `priceType`          varchar(10)    NOT NULL,
  `d_status`           int(1)         NOT NULL DEFAULT 0,
  PRIMARY KEY (`productId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `product_tbl`
  (`productId`, `productName`, `productCategory`, `productSupplier`, `productImg`, `productDescription`, `productPrice`, `priceType`, `d_status`)
VALUES
-- Vegetables (CAT001) — 3 products
('PROD00001', 'Fresh Carrots',       'CAT001', 'Green Farm Suppliers', '', 'Organically grown fresh carrots, rich in vitamins',       120.00, 'kg',   0),
('PROD00002', 'Ripe Tomatoes',       'CAT001', 'Sunrise Farms',        '', 'Fresh ripe tomatoes, locally sourced',                    85.00,  'kg',   0),
('PROD00003', 'Fresh Spinach',       'CAT001', 'Green Valley Co.',     '', 'Tender leafy spinach packed with iron and vitamins',      60.00,  'item', 0),

-- Fruits (CAT002) — 3 products
('PROD00004', 'Banana Bunch',        'CAT002', 'Tropical Fresh',       '', 'Sweet ripe bananas, imported',                            95.00,  'kg',   0),
('PROD00005', 'Seedless Watermelon', 'CAT002', 'Sunrise Farms',        '', 'Large juicy seedless watermelon',                         200.00, 'item', 0),
('PROD00006', 'Fresh Strawberries',  'CAT002', 'Berry Best Co.',       '', 'Plump fresh seasonal strawberries',                       350.00, 'kg',   0),

-- Dairy Products (CAT003) — 2 products
('PROD00007', 'Full Cream Milk',     'CAT003', 'Anchor Lanka',         '', 'Fresh pasteurised full cream milk 1L',                    185.00, 'item', 0),
('PROD00008', 'Cheddar Cheese',      'CAT003', 'Keells Foods',         '', 'Mild cheddar cheese block 200g',                          420.00, 'item', 0),

-- Baking & Pantry (CAT004) — 2 products
('PROD00009', 'All Purpose Flour',   'CAT004', 'Prima Ceylon',         '', 'Fine wheat flour for baking and cooking, 1kg',            145.00, 'item', 0),
('PROD00010', 'Granulated Sugar',    'CAT004', 'Lanka Sugar',          '', 'Pure white granulated sugar, 1kg',                        120.00, 'kg',   0),

-- Pasta & Rice (CAT005) — 2 products
('PROD00011', 'Basmati Rice',        'CAT005', 'Imperial Harvest',     '', 'Premium long grain aromatic basmati rice',                320.00, 'kg',   0),
('PROD00012', 'Spaghetti',           'CAT005', 'Barilla Lanka',        '', 'Italian style spaghetti pasta 500g',                      220.00, 'item', 0),

-- Spices (CAT006) — 2 products
('PROD00013', 'Cinnamon Powder',     'CAT006', 'Spice Garden',         '', 'Pure Ceylon cinnamon powder, 100g',                       180.00, 'item', 0),
('PROD00014', 'Black Peppercorns',   'CAT006', 'Spice Garden',         '', 'Whole black peppercorns, 100g',                           290.00, 'item', 0),

-- Snacks (CAT007) — 2 products
('PROD00015', 'Salted Potato Chips', 'CAT007', 'Dairymilk Lanka',      '', 'Crispy salted potato chips 100g',                        150.00, 'item', 0),
('PROD00016', 'Roasted Cashews',     'CAT007', 'Ceylon Nuts Co.',      '', 'Premium roasted and salted cashew nuts 200g',             450.00, 'item', 0),

-- Desserts Mix (CAT008) — 1 active, 1 deleted (tests d_status filter)
('PROD00017', 'Vanilla Cake Mix',    'CAT008', 'Brown & Polson',       '', 'Easy mix vanilla sponge cake, 500g',                      295.00, 'item', 0),
('PROD00018', 'Choc Pudding Mix',    'CAT008', 'Brown & Polson',       '', 'Instant chocolate pudding mix 100g — discontinued',       175.00, 'item', 1),

-- Coffee, Tea & Malts (CAT009) — 2 products
('PROD00019', 'Ceylon Black Tea',    'CAT009', 'Dilmah Lanka',         '', 'Premium high-grown Ceylon black tea 200g',                375.00, 'item', 0),
('PROD00020', 'Nescafe Classic',     'CAT009', 'Nestlé Lanka',         '', 'Rich instant coffee granules 100g',                       620.00, 'item', 0),

-- Beverages (CAT010) — 2 products
('PROD00021', 'Fresh Orange Juice',  'CAT010', 'Elephant House',       '', 'Freshly pressed orange juice, no preservatives, 1L',      280.00, 'item', 0),
('PROD00022', 'Sparkling Water',     'CAT010', 'Nestlé Lanka',         '', 'Natural carbonated mineral water 500ml',                   85.00,  'item', 0),

-- Baby Products (CAT011) — 1 product
('PROD00023', 'Baby Wipes',          'CAT011', 'Johnson & Johnson',    '', 'Gentle fragrance-free baby wipes, pack of 80',            360.00, 'item', 0),

-- Health & Beauty (CAT012) — 1 product
('PROD00024', 'Hand Sanitizer',      'CAT012', 'Hemas Holdings',       '', 'Antibacterial hand sanitizer 70% alcohol 250ml',          290.00, 'item', 0),

-- Household (CAT013) — 2 products
('PROD00025', 'Lemon Dish Soap',     'CAT013', 'Unilever Lanka',       '', 'Lemon fresh dishwashing liquid 750ml',                    195.00, 'item', 0),
('PROD00026', 'Laundry Detergent',   'CAT013', 'Hemas Holdings',       '', 'Concentrated powder laundry detergent 1kg',               320.00, 'item', 0),

-- Stationery (CAT014) — 1 product
('PROD00027', 'Ballpoint Pens 10pk', 'CAT014', 'Pilot Lanka',          '', 'Smooth-write blue ballpoint pens, pack of 10',            120.00, 'item', 0);

-- ============================================================
-- LOGIN TABLE
-- ============================================================
-- loginPasswd stores MD5 hash
-- MD5("admin") = 21232f297a57a5a743894a0e4a801fc3
-- MD5("12345") = 827ccb0eea8a706c4c34a16891f84e7b
--
-- loginStatus: 1 = active, 0 = deactivated
-- d_status:    0 = active, 1 = soft-deleted

CREATE TABLE `login_tbl` (
  `loginId`     varchar(8)   NOT NULL,
  `loginEmail`  varchar(200) NOT NULL,
  `loginPasswd` varchar(200) NOT NULL,
  `loginRole`   varchar(30)  NOT NULL,
  `loginStatus` int(1)       NOT NULL DEFAULT 1,
  `d_status`    int(1)       NOT NULL DEFAULT 0,
  `c_date`      timestamp    NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`loginId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `login_tbl`
  (`loginId`, `loginEmail`, `loginPasswd`, `loginRole`, `loginStatus`, `d_status`)
VALUES
-- ── Admins ──────────────────────────────────────────────────
('ADM00001', 'admin@gmail.com',      '21232f297a57a5a743894a0e4a801fc3', 'admin',    1, 0),
('ADM00002', 'kamala@gmail.com',     '21232f297a57a5a743894a0e4a801fc3', 'admin',    1, 0),

-- ── Customers ───────────────────────────────────────────────
-- Active
('CUS00001', 'nir@gmail.com',        '827ccb0eea8a706c4c34a16891f84e7b', 'customer', 1, 0),
('CUS00002', 'sarah@gmail.com',      '827ccb0eea8a706c4c34a16891f84e7b', 'customer', 1, 0),
('CUS00003', 'kamal@gmail.com',      '827ccb0eea8a706c4c34a16891f84e7b', 'customer', 1, 0),
-- Deactivated (loginStatus=0 → "Account is deactivated" message on login)
('CUS00004', 'priya@gmail.com',      '827ccb0eea8a706c4c34a16891f84e7b', 'customer', 0, 0),
-- Soft-deleted (d_status=1 → not found by auth query, "Email invalid" message)
('CUS00005', 'deleted@gmail.com',    '827ccb0eea8a706c4c34a16891f84e7b', 'customer', 1, 1),

-- ── Employees ───────────────────────────────────────────────
-- Active
('EMP00001', 'john.emp@gmail.com',   '827ccb0eea8a706c4c34a16891f84e7b', 'employee', 1, 0),
('EMP00002', 'amara.emp@gmail.com',  '827ccb0eea8a706c4c34a16891f84e7b', 'employee', 1, 0),
-- Deactivated
('EMP00003', 'ruwan.emp@gmail.com',  '827ccb0eea8a706c4c34a16891f84e7b', 'employee', 0, 0),
-- Soft-deleted
('EMP00004', 'delemp@gmail.com',     '827ccb0eea8a706c4c34a16891f84e7b', 'employee', 1, 1);

COMMIT;
