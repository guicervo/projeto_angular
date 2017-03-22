-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 21-Mar-2017 às 13:26
-- Versão do servidor: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `controle_equipamentos`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `endereco` varchar(200) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `data_cadastro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`id`, `nome`, `endereco`, `cidade`, `estado`, `telefone`, `email`, `data_cadastro`) VALUES
(10, 'Marcos', 'Av. Independência, 1800', 'Santa Cruz do Sul', 'RS', '(51) 99999-9999', 'teste@gmail.com', '2017-03-18'),
(11, 'Helena', 'Av. Imigrante', 'Santa Maria', 'RS', '(51) 9856-9878', 'teste@gmail.com', '2017-03-18'),
(12, 'Guilherme', 'Rua Alberto Pasqualine', 'Venâncio Aires', 'RS', '(51) 99888-4567', 'teste@gmail.com', '2017-03-18'),
(13, 'Ana', 'Rua 28 de Setembro', 'Porto Alegre', 'RS', '(51) 99999-0999', 'teste@gmail.com', '2017-03-18');

-- --------------------------------------------------------

--
-- Estrutura da tabela `equipamento`
--

CREATE TABLE `equipamento` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_func_responsavel` int(11) DEFAULT NULL COMMENT 'não precisa sair adicionando func no cadastro, pois todos podem estar ocupados',
  `marca` varchar(100) NOT NULL COMMENT 'colocar por texto, correto seria criar outra tabela',
  `tipo` varchar(100) DEFAULT NULL,
  `problema` text,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `equipamento`
--

INSERT INTO `equipamento` (`id`, `id_cliente`, `id_func_responsavel`, `marca`, `tipo`, `problema`, `data`) VALUES
(4, 10, 2, 'Asus', 'Notebookk', 'HD queimado', '2017-03-18'),
(5, 10, 3, 'PCYES', 'Fone de ouvido', 'Fio estraviado', '2017-03-18'),
(6, 13, 1, 'Samsung', 'Celular', 'Visor trincado', '2017-03-20');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fotos`
--

CREATE TABLE `fotos` (
  `id` int(11) NOT NULL,
  `id_equipamento` int(11) NOT NULL,
  `nome_foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcionario`
--

CREATE TABLE `funcionario` (
  `id` int(11) NOT NULL,
  `nome` varchar(75) NOT NULL,
  `setor` varchar(50) NOT NULL,
  `idade` int(2) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `funcionario`
--

INSERT INTO `funcionario` (`id`, `nome`, `setor`, `idade`, `telefone`) VALUES
(1, 'Fernando', 'Mecânica', 29, '(51) 99999-8888'),
(2, 'Felipe', 'Mecânica', 34, '(55) 98888-7777'),
(3, 'Monica', 'Mecânica', 30, '(54) 96654-7415'),
(4, 'Marco', 'Solda', 28, '(51) 93214-8522');

-- --------------------------------------------------------

--
-- Estrutura da tabela `relatorio_equipamento`
--

CREATE TABLE `relatorio_equipamento` (
  `id` int(11) NOT NULL,
  `id_equipamento` int(11) NOT NULL,
  `descricao` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `relatorio_equipamento`
--

INSERT INTO `relatorio_equipamento` (`id`, `id_equipamento`, `descricao`) VALUES
(4, 4, 'Fio desconectado'),
(5, 5, NULL),
(6, 6, '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `retorno_cliente`
--

CREATE TABLE `retorno_cliente` (
  `id` int(11) NOT NULL,
  `id_relatorio_equipamento` int(11) NOT NULL,
  `status` enum('Aceito','Recusado') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `retorno_cliente`
--

INSERT INTO `retorno_cliente` (`id`, `id_relatorio_equipamento`, `status`) VALUES
(2, 4, NULL),
(3, 5, NULL),
(4, 6, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `status_equipamento`
--

CREATE TABLE `status_equipamento` (
  `id` int(11) NOT NULL,
  `id_equipamento` int(11) NOT NULL,
  `status` enum('Aguardando Conserto','Em conserto','Consertado','Análise pelo Cliente','Sem conserto') NOT NULL DEFAULT 'Aguardando Conserto'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `status_equipamento`
--

INSERT INTO `status_equipamento` (`id`, `id_equipamento`, `status`) VALUES
(3, 4, 'Em conserto'),
(4, 5, 'Aguardando Conserto'),
(5, 6, 'Aguardando Conserto');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipamento`
--
ALTER TABLE `equipamento`
  ADD PRIMARY KEY (`id`,`id_cliente`),
  ADD KEY `id_cliente_fk` (`id_cliente`);

--
-- Indexes for table `fotos`
--
ALTER TABLE `fotos`
  ADD PRIMARY KEY (`id`,`id_equipamento`) USING BTREE,
  ADD KEY `id_equipamento_fk_f` (`id_equipamento`);

--
-- Indexes for table `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `relatorio_equipamento`
--
ALTER TABLE `relatorio_equipamento`
  ADD PRIMARY KEY (`id`,`id_equipamento`) USING BTREE,
  ADD KEY `id_equipamento_fk_re` (`id_equipamento`);

--
-- Indexes for table `retorno_cliente`
--
ALTER TABLE `retorno_cliente`
  ADD PRIMARY KEY (`id`,`id_relatorio_equipamento`),
  ADD KEY `id_relatorio_equipamento_fk` (`id_relatorio_equipamento`);

--
-- Indexes for table `status_equipamento`
--
ALTER TABLE `status_equipamento`
  ADD PRIMARY KEY (`id`,`id_equipamento`),
  ADD KEY `id_equipamento_fk` (`id_equipamento`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `equipamento`
--
ALTER TABLE `equipamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `fotos`
--
ALTER TABLE `fotos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `relatorio_equipamento`
--
ALTER TABLE `relatorio_equipamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `retorno_cliente`
--
ALTER TABLE `retorno_cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `status_equipamento`
--
ALTER TABLE `status_equipamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `equipamento`
--
ALTER TABLE `equipamento`
  ADD CONSTRAINT `id_cliente_fk` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `fotos`
--
ALTER TABLE `fotos`
  ADD CONSTRAINT `id_equipamento_fk_f` FOREIGN KEY (`id_equipamento`) REFERENCES `equipamento` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `relatorio_equipamento`
--
ALTER TABLE `relatorio_equipamento`
  ADD CONSTRAINT `id_equipamento_fk_re` FOREIGN KEY (`id_equipamento`) REFERENCES `equipamento` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `retorno_cliente`
--
ALTER TABLE `retorno_cliente`
  ADD CONSTRAINT `id_relatorio_equipamento_fk` FOREIGN KEY (`id_relatorio_equipamento`) REFERENCES `relatorio_equipamento` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `status_equipamento`
--
ALTER TABLE `status_equipamento`
  ADD CONSTRAINT `id_equipamento_fk` FOREIGN KEY (`id_equipamento`) REFERENCES `equipamento` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
