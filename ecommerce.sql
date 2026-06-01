create database shoppingstore;
use shoppingstore;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200),
    email VARCHAR(200) UNIQUE,
    password VARCHAR(250),
    address TEXT,
    phone VARCHAR(250),
    user_type ENUM('user', 'admin')
);

CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(255),
    address TEXT,
    total_amount DECIMAL(10, 2) NOT NULL,
    transaction_uuid VARCHAR(255),
    payment_method VARCHAR(255),
    payment_status VARCHAR(255) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    quantity VARCHAR(150) NOT NULL,
    image VARCHAR(255)
);

CREATE TABLE carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(150),
    product_image VARCHAR(255),
    product_price DECIMAL(10,2),

    -- Foreign Key Constraints
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

select * from users;
update  users
set user_type = 'admin'
where id = 3;

insert into users(name, email,password,address, phone, user_type)
value ('Ujjwal Poudel', 'ujjwalpoudel123@gmail.com', 'Ujjwal@1','Gaindakot -04' , '9812345678', 'admin');