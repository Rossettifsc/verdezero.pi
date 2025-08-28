-- sql/schema.sql
-- Criação de banco e tabelas básicas para o projeto VerdeZero

CREATE DATABASE IF NOT EXISTS verdezero CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE verdezero;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(180) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS ebooks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  author VARCHAR(120) NOT NULL,
  price_cents INT NOT NULL DEFAULT 0,
  category ENUM('receitas','exercicios') NOT NULL,
  cover_path VARCHAR(255) NOT NULL,
  featured TINYINT(1) NOT NULL DEFAULT 0,
  promo TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO ebooks (title, author, price_cents, category, cover_path, featured, promo) VALUES
('Cozinha Verde Essencial','Equipe VerdeZero',4900,'receitas','assets/img/cover_receitas_1.svg',1,0),
('Treinos para Iniciantes','Ana Lima',3900,'exercicios','assets/img/cover_exercicios_1.svg',1,1),
('Sopas Funcionais','Luís Prado',2900,'receitas','assets/img/cover_receitas_2.svg',0,0),
('Mobilidade e Alongamento','Rafael Souza',3500,'exercicios','assets/img/cover_exercicios_2.svg',0,0);
