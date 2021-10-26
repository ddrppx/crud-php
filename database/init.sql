-- A ordem deve ser respeitada por causa das chaves estrangeiras

CREATE TABLE IF NOT EXISTS cores(
    idcor INTEGER(4) PRIMARY KEY AUTO_INCREMENT,
    cor VARCHAR(20) NOT NULL
);

INSERT INTO cores(cor) VALUES('Amarelo');
INSERT INTO cores(cor) VALUES('Azul');
INSERT INTO cores(cor) VALUES('Vermelho');

CREATE TABLE IF NOT EXISTS produtos(
    idprod INTEGER(8) PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(40) NOT NULL,
    cor INT(2) NOT NULL
);

CREATE TABLE IF NOT EXISTS precos(
    idpreco INTEGER(8) PRIMARY KEY AUTO_INCREMENT,
    idprod INTEGER(8) NOT NULL,
    preco DECIMAL(8,2) NOT NULL,
    FOREIGN KEY(idprod) REFERENCES produtos (idprod)
);

