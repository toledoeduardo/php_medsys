CREATE DATABASE MedSys;

USE MedSys;

-- Tabela de usuários
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255),
    tipo ENUM('administrador', 'medico', 'recepcionista') NOT NULL
);

-- Tabela de pacientes
CREATE TABLE pacientes (
    id_paciente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    data_nascimento DATE,
    cpf VARCHAR(14) UNIQUE,
    telefone VARCHAR(15),
    endereco TEXT
);

-- Tabela de funcionários
CREATE TABLE funcionarios (
    id_funcionario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    cargo VARCHAR(50),
    telefone VARCHAR(15),
    email VARCHAR(100) UNIQUE
);

-- Tabela de consultas
CREATE TABLE consultas (
    id_consulta INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT,
    id_funcionario INT,
    data_hora DATETIME,
    status ENUM('agendada', 'realizada', 'cancelada') NOT NULL,
    evolucao TEXT,
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente),
    FOREIGN KEY (id_funcionario) REFERENCES funcionarios(id_funcionario)
);

-- Tabela de prontuários
CREATE TABLE prontuarios (
    id_prontuario INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT,
    data_hora DATETIME,
    anotacoes TEXT,
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente)
);

-- Tabela de relatórios
CREATE TABLE relatorios (
    id_relatorio INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    descricao TEXT,
    data_geracao DATETIME,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

