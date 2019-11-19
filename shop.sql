CREATE TABLE `product` (
  `id` int UNIQUE PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(70) NOT NULL,
  `description` varchar(150),
  `image` char(70),
  `bird_max_price` decimal(5,2),
  `bird_minimum_price` decimal(5,2),
  `hot_price` decimal(5,2),
  `created_at` timestamp NOT NULL ,
  `end_at` timestamp NOT NULL
);

CREATE TABLE `product_bird` (
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `created_at` timestamp 
);

CREATE TABLE `product_review` (
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `comment` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL 
);

CREATE TABLE `user` (
  `id` int UNIQUE PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `username` char UNIQUE NOT NULL,
  `password` char NOT NULL,
  `name` varchar(255),
  `created_at` timestamp 
);

CREATE TABLE `category` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL
);

CREATE TABLE `product_category` (
  `product_id` int NOT NULL,
  `category_id` int NOT NULL
);

ALTER TABLE `product_bird` ADD FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

ALTER TABLE `product_bird` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `product_review` ADD FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

ALTER TABLE `product_review` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `product_category` ADD FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

ALTER TABLE `product_category` ADD FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
