
CREATE DATABASE kanban;
USE kanban;

CREATE TABLE usuario (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE tarefa (
  id_tarefa INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  descricao TEXT NOT NULL,
  setor VARCHAR(100) NOT NULL,
  prioridade ENUM('Baixa', 'Média', 'Alta') NOT NULL,
  data_cadastro DATE NOT NULL,
  status ENUM('A Fazer', 'Fazendo', 'Pronto') NOT NULL DEFAULT 'A Fazer',
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);
