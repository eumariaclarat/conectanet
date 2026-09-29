create database conectanet;

use conectanet;

-- cliente
create table cliente (
    id_cliente int auto_increment primary key,
    nome varchar(100) not null,
    cpf varchar(14) unique not null,
    email varchar(100) not null,
    telefone varchar(20) not null,
    endereco varchar(200) not null,
    senha varchar(100) not null
);
desc cliente;


-- funcionario
create table funcionario (
    id_funcionario int auto_increment primary key,
    nome varchar(100) not null,
    cargo varchar(50) not null,
    email varchar(100) not null,
    senha varchar(100) not null
);
desc funcionario;


-- chamado
create table chamado (
    id_chamado int auto_increment primary key,
    descricao varchar(255) not null,
    data_abertura datetime not null,
    status enum('aberto', 'em_andamento', 'finalizado') not null
);
desc chamado;


-- agendamento
create table agendamento (
    id_agendamento int auto_increment primary key,
    data date not null,
    horario time not null,
    tecnico varchar(100) not null
);
desc agendamento;


-- fatura
create table fatura (
    id_fatura int auto_increment primary key,
    valor float not null,
    vencimento date not null,
    status enum('pendente', 'paga', 'cancelada') not null
);
desc fatura;


-- abre
create table abre (
    id_cliente int,
    id_chamado int,
    primary key (id_cliente, id_chamado),
    foreign key (id_cliente) references cliente(id_cliente),
    foreign key (id_chamado) references chamado(id_chamado)
);
desc abre;


-- atende
create table atende (
    id_chamado int,
    id_funcionario int,
    primary key (id_chamado, id_funcionario),
    foreign key (id_chamado) references chamado(id_chamado),
    foreign key (id_funcionario) references funcionario(id_funcionario)
);
desc atende;


-- gera
create table gera (
    id_chamado int,
    id_agendamento int,
    primary key (id_chamado, id_agendamento),
    foreign key (id_chamado) references chamado(id_chamado),
    foreign key (id_agendamento) references agendamento(id_agendamento)
);
desc gera;


-- possui
create table possui (
    id_cliente int,
    id_fatura int,
    primary key (id_cliente, id_fatura),
    foreign key (id_cliente) references cliente(id_cliente),
    foreign key (id_fatura) references fatura(id_fatura)
);
desc possui;
