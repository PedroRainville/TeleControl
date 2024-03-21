CREATE TABLE clientes(
	id int auto_increment primary key,
    nome varchar(100),
    cpf varchar(14),
    endereco varchar(255)
);

CREATE TABLE produtos(
	id int auto_increment primary key,
    codigo int,
    descricao varchar(255),
    status enum('Ativo', 'Inativo'),
    tempo_garantia int
);

CREATE TABLE ordem_servico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_ordem VARCHAR(50) NOT NULL,
    data_abertura DATE NOT NULL,
    cliente_id INT NOT NULL,
    cpf_consumidor VARCHAR(14) NOT NULL,
    produto_id INT NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

ALTER TABLE ordem_servico ADD COLUMN nome_consumidor VARCHAR(100) NOT NULL;
ALTER TABLE ordem_servico ADD CONSTRAINT fk_cliente_ordem_servico FOREIGN KEY (cliente_id) REFERENCES clientes(id);