CREATE DATABASE IF NOT EXISTS conectanet;

USE conectanet;

CREATE TABLE cliente (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    email VARCHAR(100),
    telefone VARCHAR(20),
    endereco VARCHAR(200),
    senha VARCHAR(100) NOT NULL
);

CREATE TABLE funcionario (
    id_funcionario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cargo VARCHAR(50),
    email VARCHAR(100),
    senha VARCHAR(100) NOT NULL
);

CREATE TABLE chamado (
    id_chamado INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(255) NOT NULL,
    data_abertura DATETIME NOT NULL,
    status ENUM('aberto','em_andamento','finalizado') NOT NULL
);

CREATE TABLE agendamento (
    id_agendamento INT AUTO_INCREMENT PRIMARY KEY,
    data DATE NOT NULL,
    horario TIME NOT NULL,
    tecnico VARCHAR(100),
    realizada TINYINT(1) NOT NULL DEFAULT 0
);

CREATE TABLE fatura (
    id_fatura INT AUTO_INCREMENT PRIMARY KEY,
    valor FLOAT NOT NULL,
    vencimento DATE NOT NULL,
    status ENUM('pendente','paga','cancelada') NOT NULL
);

CREATE TABLE abre (
    id_cliente INT,
    id_chamado INT,
    PRIMARY KEY (id_cliente, id_chamado),
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
    FOREIGN KEY (id_chamado) REFERENCES chamado(id_chamado)
);

CREATE TABLE atende (
    id_chamado INT,
    id_funcionario INT,
    PRIMARY KEY (id_chamado, id_funcionario),
    FOREIGN KEY (id_chamado) REFERENCES chamado(id_chamado),
    FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario)
);

CREATE TABLE gera (
    id_chamado INT,
    id_agendamento INT,
    PRIMARY KEY (id_chamado, id_agendamento),
    FOREIGN KEY (id_chamado) REFERENCES chamado(id_chamado),
    FOREIGN KEY (id_agendamento) REFERENCES agendamento(id_agendamento)
);

CREATE TABLE possui (
    id_cliente INT,
    id_fatura INT,
    PRIMARY KEY (id_cliente, id_fatura),
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
    FOREIGN KEY (id_fatura) REFERENCES fatura(id_fatura)
);

-- Caso o banco já exista sem a coluna abaixo, rode esta linha manualmente:
-- ALTER TABLE agendamento ADD COLUMN realizada TINYINT(1) NOT NULL DEFAULT 0;

DESC cliente;
DESC funcionario;
DESC chamado;
DESC agendamento;
DESC fatura;
DESC abre;
DESC atende;
DESC gera;
DESC possui;