CREATE TABLE users (
    id_user INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    password VARCHAR(255),
    email VARCHAR(255),
    role VARCHAR(50) DEFAULT 'user'
);

CREATE TABLE songs (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    artista VARCHAR(255) NOT NULL,
    duracion VARCHAR(10) NOT NULL,
    archivo VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);