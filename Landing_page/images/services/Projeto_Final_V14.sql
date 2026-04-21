-- --------------------------------------------------------
-- Anfitrião:                    127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- SO do servidor:               Win64
-- HeidiSQL Versão:              12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- A despejar estrutura da base de dados para database_aio
DROP DATABASE IF EXISTS `database_aio`;
CREATE DATABASE IF NOT EXISTS `database_aio` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `database_aio`;

-- A despejar estrutura para tabela database_aio.area_corpo
DROP TABLE IF EXISTS `area_corpo`;
CREATE TABLE IF NOT EXISTS `area_corpo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.area_corpo: ~3 rows (aproximadamente)
INSERT INTO `area_corpo` (`id`, `nome`) VALUES
	(1, 'Abdómen'),
	(2, 'Braços e peito'),
	(3, 'Pernas');

-- A despejar estrutura para tabela database_aio.atividades
DROP TABLE IF EXISTS `atividades`;
CREATE TABLE IF NOT EXISTS `atividades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.atividades: ~9 rows (aproximadamente)
INSERT INTO `atividades` (`id`, `nome`, `descricao`) VALUES
	(1, 'Fitness em casa', 'Com utilização mínima de equipamento'),
	(2, 'Calistenia', 'Treino muscular com o peso corporal'),
	(3, 'Caminhar', 'Caminhada intervalada guiada'),
	(4, 'Correr', 'Corrida intervalada guiada'),
	(5, 'HIIT', 'Treinos rápidos e intensivos'),
	(6, 'Ioga', 'Movimentos e respiração conscientes'),
	(7, 'Dança', 'Cárdio com músicas energizantes'),
	(8, 'Ginásio', 'Inclua pesos e máquinas'),
	(9, 'Luta', 'Treinos para desenvolver os músculos');

-- A despejar estrutura para tabela database_aio.ativo
DROP TABLE IF EXISTS `ativo`;
CREATE TABLE IF NOT EXISTS `ativo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `valor_inicial` decimal(10,2) DEFAULT NULL,
  `data_aquisicao` date DEFAULT NULL,
  `vida_util_meses` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `ativo_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `tipo_ativo` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.ativo: ~3 rows (aproximadamente)
INSERT INTO `ativo` (`id`, `descricao`, `id_categoria`, `valor_inicial`, `data_aquisicao`, `vida_util_meses`) VALUES
	(1, 'APP', 1, 80000.00, '2025-01-01', 36),
	(2, 'Software de Faturação', 1, 420.00, '2025-01-01', 36),
	(3, 'Computador', 1, 600.00, '2025-01-01', 36);

-- A despejar estrutura para tabela database_aio.aula
DROP TABLE IF EXISTS `aula`;
CREATE TABLE IF NOT EXISTS `aula` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_inicio` datetime NOT NULL,
  `duracao_min` int(11) NOT NULL DEFAULT 60,
  `limite_participantes` int(11) NOT NULL DEFAULT 10,
  `nivel` enum('Iniciante','Intermédio','Avançado') DEFAULT 'Iniciante',
  `preco` decimal(10,2) DEFAULT 0.00,
  `id_pt` int(11) DEFAULT NULL,
  `sala_virtual_url` varchar(255) DEFAULT NULL,
  `id_estado` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_pt` (`id_pt`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `aula_ibfk_1` FOREIGN KEY (`id_pt`) REFERENCES `utilizador` (`id`),
  CONSTRAINT `aula_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.aula: ~2 rows (aproximadamente)
INSERT INTO `aula` (`id`, `titulo`, `descricao`, `data_inicio`, `duracao_min`, `limite_participantes`, `nivel`, `preco`, `id_pt`, `sala_virtual_url`, `id_estado`, `created_at`, `updated_at`) VALUES
	(8, 'Calestenia', ' Treino de musculação usando o próprio corpo.', '2025-12-16 14:00:00', 50, 10, 'Iniciante', 12.00, 20, 'https://meet.jit.si/fitness-nova-aula-1765892041553', 2, '2025-12-16 13:35:39', NULL),
	(9, 'Cardio', 'Treino de resistência e força', '2025-12-17 09:00:00', 45, 10, 'Intermédio', 12.00, 20, 'https://meet.jit.si/fitness-nova-aula-1765892201282', 2, '2025-12-16 13:37:36', NULL);

-- A despejar estrutura para tabela database_aio.calendario_cliente
DROP TABLE IF EXISTS `calendario_cliente`;
CREATE TABLE IF NOT EXISTS `calendario_cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilizador` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `localizacao` varchar(255) DEFAULT NULL,
  `concluido` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user` (`id_utilizador`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.calendario_cliente: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.calendario_nutricionista
DROP TABLE IF EXISTS `calendario_nutricionista`;
CREATE TABLE IF NOT EXISTS `calendario_nutricionista` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_rh` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `localizacao` varchar(255) DEFAULT NULL,
  `concluido` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_rh` (`codigo_rh`),
  CONSTRAINT `fk_cal_nutricionista` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.calendario_nutricionista: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.carrinho
DROP TABLE IF EXISTS `carrinho`;
CREATE TABLE IF NOT EXISTS `carrinho` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT 1,
  `data_adicao` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_produto` (`id_produto`),
  CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`codigo`),
  CONSTRAINT `carrinho_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produto_marketplace` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.carrinho: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.chat
DROP TABLE IF EXISTS `chat`;
CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_remetente` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` datetime DEFAULT current_timestamp(),
  `lida` tinyint(1) DEFAULT 0,
  `apagada_remetente` tinyint(1) DEFAULT 0,
  `apagada_destinatario` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `id_remetente` (`id_remetente`),
  KEY `id_destinatario` (`id_destinatario`),
  CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`id_remetente`) REFERENCES `utilizador` (`id`),
  CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`id_destinatario`) REFERENCES `utilizador` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.chat: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.cliente
DROP TABLE IF EXISTS `cliente`;
CREATE TABLE IF NOT EXISTS `cliente` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilizador` int(11) NOT NULL,
  `nome_completo` varchar(150) NOT NULL,
  `contacto` varchar(20) DEFAULT NULL,
  `nif` varchar(20) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `perfil_completo` tinyint(1) NOT NULL DEFAULT 0,
  `genero` enum('Masculino','Feminino','Outro') NOT NULL,
  `altura` double DEFAULT NULL,
  `peso` double DEFAULT NULL,
  `peso_pretendido` double DEFAULT NULL,
  `id_objetivo` int(11) DEFAULT NULL,
  `id_nivel` int(11) DEFAULT NULL,
  `id_atividades` int(11) DEFAULT NULL,
  `id_tipo_corpo` int(11) DEFAULT NULL,
  `id_habito_diario` int(11) DEFAULT NULL,
  `id_area_corpo` int(11) DEFAULT NULL,
  `id_tipo_dieta` int(11) DEFAULT NULL,
  `data_inicio` datetime DEFAULT current_timestamp(),
  `id_estado` int(11) DEFAULT 1,
  `id_condicao_saude` int(11) DEFAULT NULL,
  `id_frequencia` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_utilizador` (`id_utilizador`),
  KEY `id_objetivo` (`id_objetivo`),
  KEY `id_nivel` (`id_nivel`),
  KEY `id_atividades` (`id_atividades`),
  KEY `id_tipo_corpo` (`id_tipo_corpo`),
  KEY `id_habito_diario` (`id_habito_diario`),
  KEY `id_area_corpo` (`id_area_corpo`),
  KEY `id_tipo_dieta` (`id_tipo_dieta`),
  KEY `id_estado` (`id_estado`),
  KEY `fk_cliente_condicao_saude` (`id_condicao_saude`),
  KEY `fk_frequencia` (`id_frequencia`),
  CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id`),
  CONSTRAINT `cliente_ibfk_2` FOREIGN KEY (`id_objetivo`) REFERENCES `objetivo` (`id`),
  CONSTRAINT `cliente_ibfk_3` FOREIGN KEY (`id_nivel`) REFERENCES `nivel_atividade` (`id`),
  CONSTRAINT `cliente_ibfk_4` FOREIGN KEY (`id_atividades`) REFERENCES `atividades` (`id`),
  CONSTRAINT `cliente_ibfk_5` FOREIGN KEY (`id_tipo_corpo`) REFERENCES `tipo_corpo` (`id`),
  CONSTRAINT `cliente_ibfk_6` FOREIGN KEY (`id_habito_diario`) REFERENCES `habito_diario` (`id`),
  CONSTRAINT `cliente_ibfk_7` FOREIGN KEY (`id_area_corpo`) REFERENCES `area_corpo` (`id`),
  CONSTRAINT `cliente_ibfk_8` FOREIGN KEY (`id_tipo_dieta`) REFERENCES `tipo_dieta` (`id`),
  CONSTRAINT `cliente_ibfk_9` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`),
  CONSTRAINT `fk_cliente_condicao_saude` FOREIGN KEY (`id_condicao_saude`) REFERENCES `condicao_saude` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_frequencia` FOREIGN KEY (`id_frequencia`) REFERENCES `frequencia_semanal` (`id_frequencia`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.cliente: ~18 rows (aproximadamente)
INSERT INTO `cliente` (`codigo`, `id_utilizador`, `nome_completo`, `contacto`, `nif`, `data_nascimento`, `perfil_completo`, `genero`, `altura`, `peso`, `peso_pretendido`, `id_objetivo`, `id_nivel`, `id_atividades`, `id_tipo_corpo`, `id_habito_diario`, `id_area_corpo`, `id_tipo_dieta`, `data_inicio`, `id_estado`, `id_condicao_saude`, `id_frequencia`) VALUES
	(1, 4, 'Álvaro Almeida', '910123456', '210892153', '1990-05-12', 0, 'Masculino', 1.8, 80, 75, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 2, NULL, NULL),
	(2, 5, 'Vera Gonçalves', '910987654', '238967093', '1992-03-25', 0, 'Feminino', 1.68, 62, 58, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 1, NULL, NULL),
	(3, 6, 'César Sá', '911234567', '236322915', '1988-11-08', 0, 'Masculino', 1.75, 78, 72, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 2, NULL, NULL),
	(4, 7, 'Vicente Amorim-Leal', '924567890', '215540352', '1991-07-19', 0, 'Masculino', 1.82, 85, 80, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 1, NULL, NULL),
	(5, 8, 'Iara Barbosa', '911876543', '205490000', '1993-09-14', 0, 'Feminino', 1.65, 60, 55, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 1, NULL, NULL),
	(6, 9, 'Flor Fonseca-Soares', '924321098', '269544577', '1995-12-02', 0, 'Feminino', 1.7, 68, 60, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 1, NULL, NULL),
	(7, 10, 'Naiara Magalhães', '912345678', '269911871', '1996-01-22', 0, 'Feminino', 1.64, 59, 54, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 1, NULL, NULL),
	(8, 11, 'Frederico Lopes Miranda', '912345679', '269911872', '1989-04-18', 0, 'Masculino', 1.83, 88, 80, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 1, NULL, NULL),
	(9, 12, 'Soraia Maia', '912789012', '245558705', '1994-10-30', 0, 'Feminino', 1.67, 65, 58, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 1, NULL, NULL),
	(10, 13, 'Isabel Neto', '925678901', '274412551', '1990-08-07', 0, 'Feminino', 1.66, 63, 58, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 1, NULL, NULL),
	(21, 35, 'abc', '949949864', '324782974', '2025-11-10', 1, 'Feminino', 1.8, 90, 70, 1, 1, 8, 2, 3, 2, 9, '2025-11-28 12:50:20', 1, 15, NULL),
	(22, 36, 'abc', '826426847', '483748374', '2025-11-27', 1, 'Feminino', 1.8, 100, 80, 1, 3, 1, 2, 3, 2, 9, '2025-11-28 13:56:23', 1, 15, NULL),
	(28, 42, 'Edu', '858585858', '546456646', '2025-12-22', 0, 'Masculino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 21:00:01', 2, NULL, NULL),
	(29, 44, 'teste', '958930950', '464646464', '2025-12-17', 1, 'Masculino', 1.8, 100, 80, 2, 1, 7, 1, 2, 1, 8, '2025-12-12 19:57:57', 2, 14, NULL),
	(30, 45, 'Edu', '675757575', '676575757', '2025-12-16', 0, 'Masculino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-14 19:15:38', 1, NULL, NULL),
	(31, 46, 'Jose Lopes', '967777777', '222222222', '2005-06-06', 1, 'Masculino', 1.6, 80, 90, 2, 2, 1, 2, 4, 2, 1, '2025-12-17 10:25:19', 1, 15, NULL),
	(32, 49, 'jose lopes', '954694569', '258345345', '2000-11-11', 0, 'Masculino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-17 11:17:43', 1, NULL, NULL),
	(33, 50, 'Romao', '532453463', '436534654', '2000-12-31', 1, 'Masculino', 1.8, 80, 90, 2, 2, 1, 2, 2, 3, 1, '2025-12-17 11:19:07', 1, 15, NULL);

-- A despejar estrutura para tabela database_aio.cliente_condicao
DROP TABLE IF EXISTS `cliente_condicao`;
CREATE TABLE IF NOT EXISTS `cliente_condicao` (
  `codigo_cliente` int(11) NOT NULL,
  `id_condicao` int(11) NOT NULL,
  `outra_condicao` text DEFAULT NULL,
  PRIMARY KEY (`codigo_cliente`,`id_condicao`),
  KEY `id_condicao` (`id_condicao`),
  CONSTRAINT `cliente_condicao_ibfk_1` FOREIGN KEY (`codigo_cliente`) REFERENCES `cliente` (`codigo`),
  CONSTRAINT `cliente_condicao_ibfk_2` FOREIGN KEY (`id_condicao`) REFERENCES `condicao_saude` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.cliente_condicao: ~10 rows (aproximadamente)
INSERT INTO `cliente_condicao` (`codigo_cliente`, `id_condicao`, `outra_condicao`) VALUES
	(1, 1, NULL),
	(1, 4, NULL),
	(2, 2, NULL),
	(3, 3, NULL),
	(4, 15, ''),
	(5, 2, NULL),
	(5, 15, NULL),
	(6, 15, ''),
	(7, 15, NULL),
	(8, 16, 'Recuperação pós-operatória no joelho');

-- A despejar estrutura para tabela database_aio.cliente_plano
DROP TABLE IF EXISTS `cliente_plano`;
CREATE TABLE IF NOT EXISTS `cliente_plano` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_cliente` int(11) NOT NULL,
  `id_servico` int(11) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `renovacao_automatica` tinyint(1) DEFAULT 0,
  `criado_em` datetime DEFAULT current_timestamp(),
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `codigo_cliente` (`codigo_cliente`),
  KEY `id_servico` (`id_servico`),
  CONSTRAINT `cliente_plano_ibfk_1` FOREIGN KEY (`codigo_cliente`) REFERENCES `cliente` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cliente_plano_ibfk_2` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.cliente_plano: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.comissao
DROP TABLE IF EXISTS `comissao`;
CREATE TABLE IF NOT EXISTS `comissao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_rh` int(11) DEFAULT NULL,
  `id_funcao` int(11) DEFAULT NULL,
  `numero_consultas` int(11) DEFAULT NULL,
  `total_pagar` decimal(10,2) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL,
  `data_prevista` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_funcao` (`id_funcao`),
  KEY `codigo_rh` (`codigo_rh`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `comissao_ibfk_1` FOREIGN KEY (`id_funcao`) REFERENCES `funcao` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `comissao_ibfk_2` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `comissao_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.comissao: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.comissao_consulta
DROP TABLE IF EXISTS `comissao_consulta`;
CREATE TABLE IF NOT EXISTS `comissao_consulta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_consulta` int(11) NOT NULL,
  `codigo_rh` int(11) NOT NULL,
  `percentagem` int(11) NOT NULL DEFAULT 70,
  `valor_pago` decimal(10,2) NOT NULL DEFAULT 0.00,
  `valor_comissao` decimal(10,2) NOT NULL DEFAULT 0.00,
  `id_estado` int(11) NOT NULL DEFAULT 13,
  `data_pagamento` datetime DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_consulta` (`id_consulta`),
  KEY `codigo_rh` (`codigo_rh`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `cc_ibfk_1` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cc_ibfk_2` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `cc_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.comissao_consulta: ~1 rows (aproximadamente)
INSERT INTO `comissao_consulta` (`id`, `id_consulta`, `codigo_rh`, `percentagem`, `valor_pago`, `valor_comissao`, `id_estado`, `data_pagamento`, `criado_em`) VALUES
	(1, 33, 21, 70, 50.00, 35.00, 12, '2025-12-17 00:21:20', '2025-12-17 00:19:52');

-- A despejar estrutura para tabela database_aio.condicao_saude
DROP TABLE IF EXISTS `condicao_saude`;
CREATE TABLE IF NOT EXISTS `condicao_saude` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.condicao_saude: ~16 rows (aproximadamente)
INSERT INTO `condicao_saude` (`id`, `nome`) VALUES
	(1, 'Diabetes'),
	(2, 'Hipertensão'),
	(3, 'Obesidade'),
	(4, 'Colesterol Elevado'),
	(5, 'Problemas Cardíacos'),
	(6, 'Asma'),
	(7, 'Lesões Musculares'),
	(8, 'Dores Lombares'),
	(9, 'Artrite'),
	(10, 'Ansiedade'),
	(11, 'Depressão'),
	(12, 'Stress'),
	(13, 'Insónia'),
	(14, 'Burnout'),
	(15, 'Nenhuma condição'),
	(16, 'Outro');

-- A despejar estrutura para tabela database_aio.consulta
DROP TABLE IF EXISTS `consulta`;
CREATE TABLE IF NOT EXISTS `consulta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `id_prestador` int(11) NOT NULL,
  `id_servico` int(11) NOT NULL,
  `data_hora` datetime NOT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1,
  `preco` decimal(10,2) NOT NULL DEFAULT 0.00,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_prestador` (`id_prestador`),
  KEY `id_servico` (`id_servico`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `consulta_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `consulta_ibfk_2` FOREIGN KEY (`id_prestador`) REFERENCES `rh` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `consulta_ibfk_3` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `consulta_ibfk_4` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.consulta: ~14 rows (aproximadamente)
INSERT INTO `consulta` (`id`, `id_cliente`, `id_prestador`, `id_servico`, `data_hora`, `id_estado`, `preco`, `criado_em`, `atualizado_em`) VALUES
	(21, 29, 23, 2, '2025-12-24 12:00:00', 2, 0.00, '2025-12-14 17:59:21', '2025-12-14 18:08:56'),
	(22, 29, 23, 2, '0000-00-00 00:00:00', 3, 0.00, '2025-12-14 18:09:18', '2025-12-14 18:09:24'),
	(23, 29, 23, 2, '2025-12-30 12:00:00', 2, 0.00, '2025-12-14 18:09:33', '2025-12-14 18:09:37'),
	(24, 29, 21, 1, '0000-00-00 00:00:00', 1, 0.00, '2025-12-14 19:02:45', '2025-12-14 19:02:45'),
	(25, 29, 21, 1, '2025-12-30 12:00:00', 1, 0.00, '2025-12-14 19:03:17', '2025-12-14 19:03:17'),
	(26, 29, 21, 1, '2025-12-24 12:00:00', 1, 0.00, '2025-12-14 19:06:24', '2025-12-14 19:06:24'),
	(33, 1, 21, 8, '2025-12-16 20:00:00', 16, 50.00, '2025-12-16 18:34:17', '2025-12-17 00:50:35'),
	(35, 3, 21, 8, '2025-12-17 22:22:00', 15, 48.00, '2025-12-16 18:43:29', '2025-12-16 18:44:57'),
	(36, 28, 21, 8, '2025-12-17 22:45:00', 15, 40.00, '2025-12-16 18:44:32', '2025-12-16 18:44:32'),
	(37, 7, 21, 8, '2025-12-17 20:00:00', 15, 40.00, '2025-12-17 00:51:38', '2025-12-17 00:51:38'),
	(38, 31, 23, 2, '2025-12-18 12:00:00', 2, 0.00, '2025-12-17 10:36:25', '2025-12-17 10:54:23'),
	(39, 1, 21, 8, '2025-12-18 20:00:00', 15, 53.00, '2025-12-17 11:01:12', '2025-12-17 11:01:12'),
	(40, 33, 23, 2, '2025-12-18 12:00:00', 2, 0.00, '2025-12-17 11:22:17', '2025-12-17 11:29:09'),
	(41, 3, 21, 8, '2025-12-18 20:00:00', 15, 53.00, '2025-12-17 11:32:56', '2025-12-17 11:32:56');

-- A despejar estrutura para tabela database_aio.consulta_servico_extra
DROP TABLE IF EXISTS `consulta_servico_extra`;
CREATE TABLE IF NOT EXISTS `consulta_servico_extra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_consulta` int(11) NOT NULL,
  `id_servico_extra` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cse_consulta` (`id_consulta`),
  KEY `fk_cse_servico` (`id_servico_extra`),
  CONSTRAINT `fk_cse_consulta` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cse_servico` FOREIGN KEY (`id_servico_extra`) REFERENCES `servico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.consulta_servico_extra: ~8 rows (aproximadamente)
INSERT INTO `consulta_servico_extra` (`id`, `id_consulta`, `id_servico_extra`) VALUES
	(1, 25, 13),
	(2, 25, 14),
	(5, 35, 13),
	(7, 33, 11),
	(8, 39, 13),
	(9, 39, 14),
	(10, 41, 13),
	(11, 41, 14);

-- A despejar estrutura para tabela database_aio.conta_bancaria
DROP TABLE IF EXISTS `conta_bancaria`;
CREATE TABLE IF NOT EXISTS `conta_bancaria` (
  `id_movimento` int(11) NOT NULL AUTO_INCREMENT,
  `data_movimento` date DEFAULT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `tipo` enum('Entrada','Saída') DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_movimento`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.conta_bancaria: ~2 rows (aproximadamente)
INSERT INTO `conta_bancaria` (`id_movimento`, `data_movimento`, `descricao`, `tipo`, `valor`) VALUES
	(1, '2025-01-30', 'emprestimo', 'Entrada', 173961.92),
	(2, '2025-03-30', 'pagamento', 'Saída', 136510.99);

-- A despejar estrutura para tabela database_aio.custo
DROP TABLE IF EXISTS `custo`;
CREATE TABLE IF NOT EXISTS `custo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `mes_referencia` date DEFAULT NULL,
  `id_tipo_custo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tipo_custo` (`id_tipo_custo`),
  CONSTRAINT `custo_ibfk_1` FOREIGN KEY (`id_tipo_custo`) REFERENCES `tipo_custo` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.custo: ~33 rows (aproximadamente)
INSERT INTO `custo` (`id`, `descricao`, `valor`, `mes_referencia`, `id_tipo_custo`) VALUES
	(1, 'Fornecimentos e Serviços Externos', 3150.00, '2025-01-01', 1),
	(2, 'Programa de Computador', 49200.00, '2025-01-01', 1),
	(3, 'Equipamento Administrativo', 600.00, '2025-01-01', 1),
	(4, 'Depreciação', 15.00, '2025-01-01', 1),
	(5, 'Depreciação', 17.00, '2025-02-01', 2),
	(6, 'Juros', 500.00, '2025-02-01', 2),
	(7, 'Depreciação', 17.00, '2025-03-01', 2),
	(8, 'Juros', 500.00, '2025-03-01', 2),
	(9, 'Depreciação', 17.00, '2025-04-01', 2),
	(10, 'Juros', 490.00, '2025-04-01', 2),
	(11, 'Depreciação', 17.00, '2025-05-01', 2),
	(12, 'Juros', 485.00, '2025-05-01', 2),
	(13, 'Fornecimentos e Serviços Externos', 4165.00, '2025-06-01', 2),
	(14, 'Programa de Computador', 49200.00, '2025-06-01', 2),
	(15, 'Depreciação', 17.00, '2025-06-01', 2),
	(16, 'Juros', 480.00, '2025-06-01', 2),
	(17, 'Gastos a Reconhecer', 64.00, '2025-06-01', 2),
	(18, 'Pessoal', 2558.00, '2025-07-01', 2),
	(19, 'Ao Estado', 733.00, '2025-07-01', 2),
	(20, 'Ativo Intangivel', 420.00, '2025-07-01', 2),
	(21, 'Depreciação', 2460.00, '2025-07-01', 2),
	(22, 'Fornecimentos e Serviços Externos', 5537.00, '2025-07-01', 2),
	(23, 'Juros', 475.00, '2025-07-01', 2),
	(24, 'Pessoal', 1605.00, '2025-08-01', 2),
	(25, 'Ao Estado', 417.00, '2025-08-01', 2),
	(26, 'Depreciação', 2251.00, '2025-08-01', 2),
	(27, 'Fornecimentos e Serviços Externos', 4133.00, '2025-08-01', 2),
	(28, 'Juros', 470.00, '2025-08-01', 2),
	(29, 'Pessoal', 1617.00, '2025-09-01', 2),
	(30, 'Ao Estado', 417.00, '2025-09-01', 2),
	(31, 'Depreciação', 2251.00, '2025-09-01', 2),
	(32, 'Fornecimentos e Serviços Externos', 6039.00, '2025-09-01', 2),
	(33, 'Juros', 464.00, '2025-09-01', 2);

-- A despejar estrutura para tabela database_aio.depreciacao
DROP TABLE IF EXISTS `depreciacao`;
CREATE TABLE IF NOT EXISTS `depreciacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ativo` int(11) DEFAULT NULL,
  `mes_referencia` date DEFAULT NULL,
  `valor_depreciacao` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_ativo` (`id_ativo`),
  CONSTRAINT `depreciacao_ibfk_1` FOREIGN KEY (`id_ativo`) REFERENCES `ativo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.depreciacao: ~15 rows (aproximadamente)
INSERT INTO `depreciacao` (`id`, `id_ativo`, `mes_referencia`, `valor_depreciacao`) VALUES
	(1, 1, '2025-07-01', 2430.90),
	(2, 1, '2025-08-01', 2222.22),
	(3, 1, '2025-09-01', 2222.22),
	(4, 2, '2025-07-01', 11.67),
	(5, 2, '2025-08-01', 11.67),
	(6, 2, '2025-09-01', 11.67),
	(7, 3, '2025-01-01', 15.05),
	(8, 3, '2025-02-01', 16.71),
	(9, 3, '2025-03-01', 16.71),
	(10, 3, '2025-04-01', 16.71),
	(11, 3, '2025-05-01', 16.72),
	(12, 3, '2025-06-01', 16.71),
	(13, 3, '2025-07-01', 16.71),
	(14, 3, '2025-08-01', 16.72),
	(15, 3, '2025-09-01', 16.71);

-- A despejar estrutura para tabela database_aio.disponibilidade_prestador
DROP TABLE IF EXISTS `disponibilidade_prestador`;
CREATE TABLE IF NOT EXISTS `disponibilidade_prestador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_rh` int(11) NOT NULL,
  `dia_semana` enum('Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo') DEFAULT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `codigo_rh` (`codigo_rh`),
  CONSTRAINT `disponibilidade_prestador_ibfk_1` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.disponibilidade_prestador: ~18 rows (aproximadamente)
INSERT INTO `disponibilidade_prestador` (`id`, `codigo_rh`, `dia_semana`, `hora_inicio`, `hora_fim`, `ativo`) VALUES
	(1, 18, 'Segunda', '09:00:00', '13:00:00', 1),
	(2, 18, 'Quarta', '14:00:00', '18:00:00', 1),
	(3, 18, 'Sexta', '09:00:00', '12:00:00', 1),
	(4, 19, 'Terça', '09:00:00', '13:00:00', 1),
	(5, 19, 'Quinta', '14:00:00', '18:00:00', 1),
	(6, 19, 'Sábado', '09:00:00', '12:00:00', 1),
	(7, 16, 'Segunda', '07:00:00', '11:00:00', 1),
	(8, 16, 'Quarta', '14:00:00', '18:00:00', 1),
	(9, 16, 'Sexta', '08:00:00', '12:00:00', 1),
	(10, 20, 'Terça', '08:00:00', '12:00:00', 1),
	(11, 20, 'Quinta', '09:00:00', '13:00:00', 1),
	(12, 20, 'Sábado', '10:00:00', '14:00:00', 1),
	(13, 21, 'Segunda', '08:00:00', '12:00:00', 1),
	(14, 21, 'Quarta', '09:00:00', '13:00:00', 1),
	(15, 21, 'Sexta', '14:00:00', '18:00:00', 1),
	(16, 17, 'Terça', '10:00:00', '14:00:00', 1),
	(17, 17, 'Quinta', '13:00:00', '17:00:00', 1),
	(18, 17, 'Sábado', '09:00:00', '12:00:00', 1);

-- A despejar estrutura para tabela database_aio.emprestimo
DROP TABLE IF EXISTS `emprestimo`;
CREATE TABLE IF NOT EXISTS `emprestimo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mes` int(11) NOT NULL,
  `valor_prestacao` decimal(10,2) DEFAULT NULL,
  `juros` decimal(10,2) DEFAULT NULL,
  `amortizacao` decimal(10,2) DEFAULT NULL,
  `saldo_devedor` decimal(10,2) DEFAULT NULL,
  `data_prevista` date NOT NULL,
  `pago` tinyint(1) DEFAULT 0,
  `data_pagamento` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.emprestimo: ~82 rows (aproximadamente)
INSERT INTO `emprestimo` (`id`, `mes`, `valor_prestacao`, `juros`, `amortizacao`, `saldo_devedor`, `data_prevista`, `pago`, `data_pagamento`) VALUES
	(1, 1, 1500.00, 500.00, 1000.00, 99000.00, '2025-01-31', 1, '2025-10-20'),
	(2, 2, 1500.00, 495.00, 1005.00, 97995.00, '2025-02-28', 1, '2025-10-20'),
	(3, 3, 1500.00, 489.98, 1010.02, 96984.98, '2025-03-31', 1, '2025-10-20'),
	(4, 4, 1500.00, 484.92, 1015.08, 95969.90, '2025-04-30', 1, '2025-10-20'),
	(5, 5, 1500.00, 479.85, 1020.15, 94949.75, '2025-05-31', 1, '2025-10-20'),
	(6, 6, 1500.00, 474.75, 1025.25, 93924.50, '2025-06-30', 1, '2025-10-20'),
	(7, 7, 1500.00, 469.62, 1030.38, 92894.12, '2025-07-31', 1, '2025-10-20'),
	(8, 8, 1500.00, 464.47, 1035.53, 91858.59, '2025-08-31', 1, '2025-10-20'),
	(9, 9, 1500.00, 459.29, 1040.71, 90817.88, '2025-09-30', 1, '2025-10-20'),
	(10, 10, 1500.00, 454.09, 1045.91, 89771.97, '2025-10-31', 1, '2025-10-21'),
	(11, 11, 1500.00, 448.86, 1051.14, 88720.83, '2025-11-30', 1, '2025-10-21'),
	(12, 12, 1500.00, 443.60, 1056.40, 87664.44, '2025-12-31', 1, '2025-12-17'),
	(13, 13, 1500.00, 438.32, 1061.68, 86602.76, '2026-01-31', 1, '2025-12-17'),
	(14, 14, 1500.00, 433.01, 1066.99, 85535.77, '2026-02-28', 1, '2025-12-17'),
	(15, 15, 1500.00, 427.68, 1072.32, 84463.45, '2026-03-31', 0, NULL),
	(16, 16, 1500.00, 422.32, 1077.68, 83385.77, '2026-04-30', 0, NULL),
	(17, 17, 1500.00, 416.93, 1083.07, 82302.70, '2026-05-31', 0, NULL),
	(18, 18, 1500.00, 411.51, 1088.49, 81214.21, '2026-06-30', 0, NULL),
	(19, 19, 1500.00, 406.07, 1093.93, 80120.28, '2026-07-31', 0, NULL),
	(20, 20, 1500.00, 400.60, 1099.40, 79020.88, '2026-08-31', 0, NULL),
	(21, 21, 1500.00, 395.10, 1104.90, 77915.99, '2026-09-30', 0, NULL),
	(22, 22, 1500.00, 389.58, 1110.42, 76805.57, '2026-10-31', 0, NULL),
	(23, 23, 1500.00, 384.03, 1115.97, 75689.60, '2026-11-30', 0, NULL),
	(24, 24, 1500.00, 378.45, 1121.55, 74568.04, '2026-12-31', 0, NULL),
	(25, 25, 1500.00, 372.84, 1127.16, 73440.88, '2027-01-31', 0, NULL),
	(26, 26, 1500.00, 367.20, 1132.80, 72308.09, '2027-02-28', 0, NULL),
	(27, 27, 1500.00, 361.54, 1138.46, 71169.63, '2027-03-31', 0, NULL),
	(28, 28, 1500.00, 355.85, 1144.15, 70025.48, '2027-04-30', 0, NULL),
	(29, 29, 1500.00, 350.13, 1149.87, 68875.61, '2027-05-31', 0, NULL),
	(30, 30, 1500.00, 344.38, 1155.62, 67719.98, '2027-06-30', 0, NULL),
	(31, 31, 1500.00, 338.60, 1161.40, 66558.58, '2027-07-31', 0, NULL),
	(32, 32, 1500.00, 332.79, 1167.21, 65391.38, '2027-08-31', 0, NULL),
	(33, 33, 1500.00, 326.96, 1173.04, 64218.33, '2027-09-30', 0, NULL),
	(34, 34, 1500.00, 321.09, 1178.91, 63039.42, '2027-10-31', 0, NULL),
	(35, 35, 1500.00, 315.20, 1184.80, 61854.62, '2027-11-30', 0, NULL),
	(36, 36, 1500.00, 309.27, 1190.73, 60663.90, '2027-12-31', 0, NULL),
	(37, 37, 1500.00, 303.32, 1196.68, 59467.21, '2028-01-31', 0, NULL),
	(38, 38, 1500.00, 297.34, 1202.66, 58264.55, '2028-02-29', 0, NULL),
	(39, 39, 1500.00, 291.32, 1208.68, 57055.87, '2028-03-31', 0, NULL),
	(40, 40, 1500.00, 285.28, 1214.72, 55841.15, '2028-04-30', 0, NULL),
	(41, 41, 1500.00, 279.21, 1220.79, 54620.36, '2028-05-31', 0, NULL),
	(42, 42, 1500.00, 273.10, 1226.90, 53393.46, '2028-06-30', 0, NULL),
	(43, 43, 1500.00, 266.97, 1233.03, 52160.43, '2028-07-31', 0, NULL),
	(44, 44, 1500.00, 260.80, 1239.20, 50921.23, '2028-08-31', 0, NULL),
	(45, 45, 1500.00, 254.61, 1245.39, 49675.84, '2028-09-30', 0, NULL),
	(46, 46, 1500.00, 248.38, 1251.62, 48424.22, '2028-10-31', 0, NULL),
	(47, 47, 1500.00, 242.12, 1257.88, 47166.34, '2028-11-30', 0, NULL),
	(48, 48, 1500.00, 235.83, 1264.17, 45902.17, '2028-12-31', 0, NULL),
	(49, 49, 1500.00, 229.51, 1270.49, 44631.68, '2029-01-31', 0, NULL),
	(50, 50, 1500.00, 223.16, 1276.84, 43354.84, '2029-02-28', 0, NULL),
	(51, 51, 1500.00, 216.77, 1283.23, 42071.61, '2029-03-31', 0, NULL),
	(52, 52, 1500.00, 210.36, 1289.64, 40781.97, '2029-04-30', 0, NULL),
	(53, 53, 1500.00, 203.91, 1296.09, 39485.88, '2029-05-31', 0, NULL),
	(54, 54, 1500.00, 197.43, 1302.57, 38183.31, '2029-06-30', 0, NULL),
	(55, 55, 1500.00, 190.92, 1309.08, 36874.23, '2029-07-31', 0, NULL),
	(56, 56, 1500.00, 184.37, 1315.63, 35558.60, '2029-08-31', 0, NULL),
	(57, 57, 1500.00, 177.79, 1322.21, 34236.39, '2029-09-30', 0, NULL),
	(58, 58, 1500.00, 171.18, 1328.82, 32907.57, '2029-10-31', 0, NULL),
	(59, 59, 1500.00, 164.54, 1335.46, 31572.11, '2029-11-30', 0, NULL),
	(60, 60, 1500.00, 157.86, 1342.14, 30229.97, '2029-12-31', 0, NULL),
	(61, 61, 1500.00, 151.15, 1348.85, 28881.12, '2030-01-31', 0, NULL),
	(62, 62, 1500.00, 144.41, 1355.59, 27525.52, '2030-02-28', 0, NULL),
	(63, 63, 1500.00, 137.63, 1362.37, 26163.15, '2030-03-31', 0, NULL),
	(64, 64, 1500.00, 130.82, 1369.18, 24793.97, '2030-04-30', 0, NULL),
	(65, 65, 1500.00, 123.97, 1376.03, 23417.94, '2030-05-31', 0, NULL),
	(66, 66, 1500.00, 117.09, 1382.91, 22035.03, '2030-06-30', 0, NULL),
	(67, 67, 1500.00, 110.18, 1389.82, 20645.20, '2030-07-31', 0, NULL),
	(68, 68, 1500.00, 103.23, 1396.77, 19248.43, '2030-08-31', 0, NULL),
	(69, 69, 1500.00, 96.24, 1403.76, 17844.67, '2030-09-30', 0, NULL),
	(70, 70, 1500.00, 89.22, 1410.78, 16433.89, '2030-10-31', 0, NULL),
	(71, 71, 1500.00, 82.17, 1417.83, 15016.06, '2030-11-30', 0, NULL),
	(72, 72, 1500.00, 75.08, 1424.92, 13591.14, '2030-12-31', 0, NULL),
	(73, 73, 1500.00, 67.96, 1432.04, 12159.10, '2031-01-31', 0, NULL),
	(74, 74, 1500.00, 60.80, 1439.20, 10719.90, '2031-02-28', 0, NULL),
	(75, 75, 1500.00, 53.60, 1446.40, 9273.50, '2031-03-31', 0, NULL),
	(76, 76, 1500.00, 46.37, 1453.63, 7819.86, '2031-04-30', 0, NULL),
	(77, 77, 1500.00, 39.10, 1460.90, 6358.96, '2031-05-31', 0, NULL),
	(78, 78, 1500.00, 31.79, 1468.21, 4890.76, '2031-06-30', 0, NULL),
	(79, 79, 1500.00, 24.45, 1475.55, 3415.21, '2031-07-31', 0, NULL),
	(80, 80, 1500.00, 17.08, 1482.92, 1932.29, '2031-08-31', 0, NULL),
	(81, 81, 1500.00, 9.66, 1490.34, 441.95, '2031-09-30', 0, NULL),
	(82, 82, 444.16, 2.21, 441.95, 0.00, '2031-10-31', 0, NULL);

-- A despejar estrutura para tabela database_aio.estado
DROP TABLE IF EXISTS `estado`;
CREATE TABLE IF NOT EXISTS `estado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.estado: ~16 rows (aproximadamente)
INSERT INTO `estado` (`id`, `descricao`) VALUES
	(1, 'Agendada'),
	(2, 'Ativo'),
	(3, 'Bloqueado'),
	(4, 'Cancelado'),
	(5, 'Disponível'),
	(6, 'Em atraso'),
	(7, 'Inscrito'),
	(8, 'Entregue'),
	(9, 'Enviado'),
	(10, 'Esgotado'),
	(11, 'Inativo'),
	(12, 'Pago'),
	(13, 'Pendente'),
	(14, 'Suspenso'),
	(15, 'Confirmado'),
	(16, 'Concluido');

-- A despejar estrutura para tabela database_aio.evento
DROP TABLE IF EXISTS `evento`;
CREATE TABLE IF NOT EXISTS `evento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `data_inicio` datetime DEFAULT NULL,
  `data_fim` datetime DEFAULT NULL,
  `concluido` tinyint(1) NOT NULL DEFAULT 0,
  `categoria` varchar(50) NOT NULL DEFAULT 'bg-info-subtle',
  `localizacao` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.evento: ~4 rows (aproximadamente)
INSERT INTO `evento` (`id`, `titulo`, `descricao`, `data_inicio`, `data_fim`, `concluido`, `categoria`, `localizacao`) VALUES
	(1, 'Entrega dos safts', 'Autoridade Tributária', '2025-09-01 00:00:00', '2025-09-05 00:00:00', 0, 'Obrigações Declarativas', NULL),
	(2, 'Entrega dos ivas', 'Autoridade Tributária', '2025-09-09 00:00:00', '2025-09-10 00:00:00', 1, 'Obrigações Declarativas', ''),
	(3, 'Entrega DMR', 'Autoridade Tributária', '2025-09-09 00:00:00', '2025-09-10 00:00:00', 1, 'Obrigações Declarativas', NULL),
	(4, 'Entrega DRI', 'Segurança Social', '2025-09-09 00:00:00', '2025-09-10 00:00:00', 0, 'Obrigações Declarativas', NULL);

-- A despejar estrutura para tabela database_aio.fornecedor
DROP TABLE IF EXISTS `fornecedor`;
CREATE TABLE IF NOT EXISTS `fornecedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fornecedor` varchar(100) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `total_debito` decimal(10,2) DEFAULT NULL,
  `total_credito` decimal(10,2) DEFAULT NULL,
  `saldo` decimal(10,2) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `estado` enum('pendente','concluido') DEFAULT 'pendente',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.fornecedor: ~18 rows (aproximadamente)
INSERT INTO `fornecedor` (`id`, `fornecedor`, `descricao`, `total_debito`, `total_credito`, `saldo`, `data`, `estado`) VALUES
	(1, 'SP_Conecta Finanças-Contabilidade e Finanças', 'Contabilidade', 2214.00, 2214.00, 0.00, '2025-01-01', 'concluido'),
	(2, 'INSTITUTO DOS REGISTOS E DO NOTARIADO, I.P.', 'Cartório', 1350.00, 1350.00, 0.00, '2025-01-01', 'concluido'),
	(3, 'Digital Aurora', 'Marketing', 4920.00, 4920.00, 0.00, '2025-06-01', 'concluido'),
	(4, 'GGLE PORTUGAL, LDA', 'servico', 30.75, 30.75, 0.00, '2025-06-01', 'concluido'),
	(5, 'APPLE PORTUGAL, UNIPESSOAL, LDA', 'servico', 121.77, 121.77, 0.00, '2025-06-01', 'concluido'),
	(6, 'DMNS - DOMÍNIOS, S.A.', 'tecnologia', 36.90, 36.90, 0.00, '2025-06-01', 'concluido'),
	(7, 'FOUND UNITED MINDS, LDA', 'servico', 92.25, 92.25, 0.00, '2025-06-01', 'concluido'),
	(8, 'SP_Code Crafters Évora', 'APP', 49200.00, 0.00, 49200.00, '2025-06-01', 'concluido'),
	(9, 'SP_Tranquilidade', 'Seguros', 335.00, 335.00, 0.00, '2025-07-01', 'concluido'),
	(10, 'SP_MEO', 'Internet', 30.75, 30.75, 0.00, '2025-07-01', 'concluido'),
	(11, 'Digital Aurora', 'Marketing', 615.00, 615.00, 0.00, '2025-07-01', 'concluido'),
	(12, 'DMNS - DOMÍNIOS, S.A.', 'tecnologia', 64.21, 64.21, 0.00, '2025-07-01', 'concluido'),
	(13, 'SP_Worten', 'Computador', 738.00, 0.00, 738.00, '2025-07-01', 'concluido'),
	(14, 'SP_Invoice Xpress', 'software de faturação', 516.60, 516.60, 0.00, '2025-07-01', 'concluido'),
	(15, 'SP_MEO', 'Internet', 30.75, 30.75, 0.00, '2025-08-01', 'concluido'),
	(16, 'Digital Aurora', 'Marketing', 615.00, 615.00, 0.00, '2025-08-01', 'concluido'),
	(17, 'SP_MEO', 'Internet', 0.00, 30.75, -30.75, '2025-09-01', 'concluido'),
	(18, 'Digital Aurora', 'Marketing', 615.00, 615.00, 0.00, '2025-09-01', 'concluido');

-- A despejar estrutura para tabela database_aio.frequencia_semanal
DROP TABLE IF EXISTS `frequencia_semanal`;
CREATE TABLE IF NOT EXISTS `frequencia_semanal` (
  `id_frequencia` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  PRIMARY KEY (`id_frequencia`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.frequencia_semanal: ~4 rows (aproximadamente)
INSERT INTO `frequencia_semanal` (`id_frequencia`, `descricao`) VALUES
	(1, '2x/semana'),
	(2, '3x/semana'),
	(3, '4x/semana'),
	(4, '5x/semana');

-- A despejar estrutura para tabela database_aio.funcao
DROP TABLE IF EXISTS `funcao`;
CREATE TABLE IF NOT EXISTS `funcao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.funcao: ~4 rows (aproximadamente)
INSERT INTO `funcao` (`id`, `descricao`) VALUES
	(1, 'Administração'),
	(2, 'Nutrição'),
	(3, 'Personal Trainer'),
	(4, 'Psicologia');

-- A despejar estrutura para tabela database_aio.genero
DROP TABLE IF EXISTS `genero`;
CREATE TABLE IF NOT EXISTS `genero` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.genero: ~2 rows (aproximadamente)
INSERT INTO `genero` (`id`, `nome`) VALUES
	(1, 'Masculino'),
	(2, 'Feminino');

-- A despejar estrutura para tabela database_aio.habito_diario
DROP TABLE IF EXISTS `habito_diario`;
CREATE TABLE IF NOT EXISTS `habito_diario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.habito_diario: ~4 rows (aproximadamente)
INSERT INTO `habito_diario` (`id`, `descricao`) VALUES
	(1, 'No escritório'),
	(2, 'Caminhadas diárias'),
	(3, 'Trabalho físico'),
	(4, 'Maioritariamente em casa');

-- A despejar estrutura para tabela database_aio.historico_treino_cliente
DROP TABLE IF EXISTS `historico_treino_cliente`;
CREATE TABLE IF NOT EXISTS `historico_treino_cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `id_treino` int(11) NOT NULL,
  `data_realizacao` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_treino` (`id_treino`),
  CONSTRAINT `historico_treino_cliente_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`codigo`),
  CONSTRAINT `historico_treino_cliente_ibfk_2` FOREIGN KEY (`id_treino`) REFERENCES `treinos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.historico_treino_cliente: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.imposto
DROP TABLE IF EXISTS `imposto`;
CREATE TABLE IF NOT EXISTS `imposto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mes` varchar(20) DEFAULT NULL,
  `dmr` varchar(255) DEFAULT NULL,
  `dri` varchar(255) DEFAULT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.imposto: ~12 rows (aproximadamente)
INSERT INTO `imposto` (`id`, `mes`, `dmr`, `dri`, `data_criacao`) VALUES
	(1, 'Janeiro', '', '', NULL),
	(2, 'Fevereiro', '', '', NULL),
	(3, 'Março', '', '', NULL),
	(4, 'Abril', '', '', NULL),
	(5, 'Maio', '', '', NULL),
	(6, 'Junho', '', '', NULL),
	(7, 'Julho', 'uploads/impostos/1760715527_DMR.pdf', 'uploads/impostos/1760715537_DRI.pdf', '2025-10-17 16:39:15'),
	(8, 'Agosto', '', '', NULL),
	(9, 'Setembro', '', '', NULL),
	(10, 'Outubro', '', '', NULL),
	(11, 'Novembro', '', '', NULL),
	(12, 'Dezembro', '', '', NULL);

-- A despejar estrutura para tabela database_aio.inscricao_aula
DROP TABLE IF EXISTS `inscricao_aula`;
CREATE TABLE IF NOT EXISTS `inscricao_aula` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_aula` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_aula_cliente` (`id_aula`,`id_cliente`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `inscricao_aula_ibfk_1` FOREIGN KEY (`id_aula`) REFERENCES `aula` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inscricao_aula_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`codigo`) ON DELETE CASCADE,
  CONSTRAINT `inscricao_aula_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.inscricao_aula: ~2 rows (aproximadamente)
INSERT INTO `inscricao_aula` (`id`, `id_aula`, `id_cliente`, `id_estado`, `created_at`) VALUES
	(8, 8, 1, 15, '2025-12-16 13:36:02'),
	(9, 9, 1, 15, '2025-12-16 13:38:03');

-- A despejar estrutura para tabela database_aio.nivel_atividade
DROP TABLE IF EXISTS `nivel_atividade`;
CREATE TABLE IF NOT EXISTS `nivel_atividade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.nivel_atividade: ~3 rows (aproximadamente)
INSERT INTO `nivel_atividade` (`id`, `nome`, `descricao`) VALUES
	(1, 'Baixo', 'Sedentário ou pouca prática de exercício físico semanal (0–1 vezes por semana).'),
	(2, 'Moderado', 'Pratica atividade física com alguma regularidade (2–3 vezes por semana).'),
	(3, 'Alto', 'Pessoa muito ativa, pratica exercício intenso regularmente (4 ou mais vezes por semana).');

-- A despejar estrutura para tabela database_aio.notificacao
DROP TABLE IF EXISTS `notificacao`;
CREATE TABLE IF NOT EXISTS `notificacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilizador` int(11) NOT NULL,
  `tipo` enum('consulta','evento','mensagem','alerta') NOT NULL,
  `referencia_id` int(11) DEFAULT NULL,
  `titulo` varchar(255) NOT NULL,
  `texto` text DEFAULT NULL,
  `lida` tinyint(1) DEFAULT 0,
  `criada_em` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela database_aio.notificacao: ~7 rows (aproximadamente)
INSERT INTO `notificacao` (`id`, `id_utilizador`, `tipo`, `referencia_id`, `titulo`, `texto`, `lida`, `criada_em`) VALUES
	(7, 20, 'consulta', 24, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2025-12-14 19:02:45'),
	(8, 20, 'consulta', 25, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2025-12-14 19:03:17'),
	(9, 20, 'consulta', 26, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2025-12-14 19:06:24'),
	(10, 45, 'consulta', 27, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2025-12-14 19:18:05'),
	(11, 45, 'consulta', 28, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2025-12-14 19:38:02'),
	(12, 45, 'consulta', 29, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2025-12-15 14:57:50'),
	(13, 45, 'consulta', 30, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2025-12-15 15:05:59');

-- A despejar estrutura para tabela database_aio.objetivo
DROP TABLE IF EXISTS `objetivo`;
CREATE TABLE IF NOT EXISTS `objetivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.objetivo: ~3 rows (aproximadamente)
INSERT INTO `objetivo` (`id`, `nome`, `descricao`) VALUES
	(1, 'Perder Peso', 'Reduzir gordura corporal de forma saudável através de treino físico e alimentação equilibrada.'),
	(2, 'Desenvolver Músculo', 'Aumentar a massa muscular e a força com planos de treino focados em hipertrofia e nutrição rica em proteínas.'),
	(3, 'Manter a forma', 'Manter o peso e o condicionamento físico, combinando treinos leves com alimentação equilibrada.');

-- A despejar estrutura para tabela database_aio.obrigacao
DROP TABLE IF EXISTS `obrigacao`;
CREATE TABLE IF NOT EXISTS `obrigacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_obrigacao` int(11) DEFAULT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `data_vencimento` date DEFAULT NULL,
  `data_pagamento` date DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tipo_obrigacao` (`id_tipo_obrigacao`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `obrigacao_ibfk_1` FOREIGN KEY (`id_tipo_obrigacao`) REFERENCES `tipo_obrigacao` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `obrigacao_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.obrigacao: ~11 rows (aproximadamente)
INSERT INTO `obrigacao` (`id`, `id_tipo_obrigacao`, `descricao`, `valor`, `data_vencimento`, `data_pagamento`, `id_estado`) VALUES
	(1, 2, 'Pagamento-SP_MEO', 30.75, '2025-09-30', '2025-09-30', 3),
	(2, 2, 'Pagamento-SP_Digital Aurora', 500.00, '2025-09-30', '2025-09-30', 3),
	(3, 3, 'Pagamento-SP_Joana Freitas', 1699.55, '2025-09-30', '2025-09-30', 13),
	(4, 3, 'Pagamento-SP_Maria Beatriz Martins', 1012.50, '2025-09-30', '2025-09-30', 13),
	(5, 3, 'Pagamento-SP_Joao Ferreira', 982.50, '2025-09-30', '2025-09-30', 13),
	(6, 3, 'Pagamento-SP_Guilherme Sousa', 1602.00, '2025-09-30', '2025-09-30', 13),
	(7, 3, 'Pagamento-SP_Ana Sofia Marques', 1602.00, '2025-09-30', '2025-09-30', 13),
	(8, 3, 'Pagamento-SP_Lucia Mendes', 315.00, '2025-09-30', '2025-09-30', 13),
	(9, 4, 'Pagamento-IVA', 2000.00, '2025-09-20', '2025-09-20', 13),
	(10, 4, 'Pagamento-DMR', 100.00, '2025-09-20', '2025-09-20', 13),
	(11, 4, 'Pagamento-DRI', 89.00, '2025-09-20', '2025-09-20', 13);

-- A despejar estrutura para tabela database_aio.parceiro_marketplace
DROP TABLE IF EXISTS `parceiro_marketplace`;
CREATE TABLE IF NOT EXISTS `parceiro_marketplace` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `contato` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `percentual_comissao` decimal(5,2) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.parceiro_marketplace: ~5 rows (aproximadamente)
INSERT INTO `parceiro_marketplace` (`id`, `nome`, `contato`, `email`, `percentual_comissao`, `ativo`) VALUES
	(1, 'Zumub', 'Paulo Santos', 'contato@zumub.com', 10.00, 1),
	(2, 'FitStore', 'Alice Vieira', 'suporte@fitstore.com', 12.00, 1),
	(3, 'MuscleShop', 'João Correia', 'contact@muscleshop.com', 8.50, 1),
	(4, 'GymPro', 'Ana Silva', 'support@gympro.com', 15.00, 1),
	(5, 'SportFit', 'Hernandez Chavez', 'info@sportfit.com', 10.00, 1);

-- A despejar estrutura para tabela database_aio.planos_treino
DROP TABLE IF EXISTS `planos_treino`;
CREATE TABLE IF NOT EXISTS `planos_treino` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `total_tempo` int(11) NOT NULL DEFAULT 0,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `planos_treino_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilizador` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.planos_treino: ~3 rows (aproximadamente)
INSERT INTO `planos_treino` (`id`, `user_id`, `titulo`, `total_tempo`, `criado_em`) VALUES
	(1, 1, 'teste', 9, '2025-12-09 20:44:45'),
	(2, 1, 'sddsad', 12, '2025-12-09 20:46:55'),
	(8, 50, 'costas', 5, '2025-12-17 11:21:02');

-- A despejar estrutura para tabela database_aio.plano_alimentar
DROP TABLE IF EXISTS `plano_alimentar`;
CREATE TABLE IF NOT EXISTS `plano_alimentar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_calorias` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `plano_alimentar_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilizador` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.plano_alimentar: ~1 rows (aproximadamente)
INSERT INTO `plano_alimentar` (`id`, `user_id`, `titulo`, `criado_em`, `total_calorias`) VALUES
	(8, 50, 'almoco', '2025-12-17 11:21:35', 300);

-- A despejar estrutura para tabela database_aio.plano_ficheiros
DROP TABLE IF EXISTS `plano_ficheiros`;
CREATE TABLE IF NOT EXISTS `plano_ficheiros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `nutricionista_id` int(11) NOT NULL,
  `nome_original` varchar(255) DEFAULT NULL,
  `nome_ficheiro` varchar(255) DEFAULT NULL,
  `caminho` varchar(255) DEFAULT NULL,
  `data_envio` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.plano_ficheiros: ~2 rows (aproximadamente)
INSERT INTO `plano_ficheiros` (`id`, `cliente_id`, `nutricionista_id`, `nome_original`, `nome_ficheiro`, `caminho`, `data_envio`) VALUES
	(4, 30, 0, 'Contrato Lucia.pdf', NULL, 'uploads/planos/1765968840_ContratoLucia.pdf', '2025-12-17 10:54:00'),
	(5, 1, 0, 'Contrato Lucia.pdf', NULL, 'uploads/planos/1765970940_ContratoLucia.pdf', '2025-12-17 11:29:00');

-- A despejar estrutura para tabela database_aio.plano_ingredientes
DROP TABLE IF EXISTS `plano_ingredientes`;
CREATE TABLE IF NOT EXISTS `plano_ingredientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plano_id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `calorias` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `plano_id` (`plano_id`),
  CONSTRAINT `plano_ingredientes_ibfk_1` FOREIGN KEY (`plano_id`) REFERENCES `plano_alimentar` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.plano_ingredientes: ~1 rows (aproximadamente)
INSERT INTO `plano_ingredientes` (`id`, `plano_id`, `nome`, `calorias`) VALUES
	(9, 8, 'salada', 300);

-- A despejar estrutura para tabela database_aio.plano_nutricionista
DROP TABLE IF EXISTS `plano_nutricionista`;
CREATE TABLE IF NOT EXISTS `plano_nutricionista` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_rh` int(11) NOT NULL,
  `codigo_cliente` int(11) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `ficheiro` varchar(255) DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `codigo_rh` (`codigo_rh`),
  KEY `codigo_cliente` (`codigo_cliente`),
  CONSTRAINT `plano_nutricionista_ibfk_1` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`),
  CONSTRAINT `plano_nutricionista_ibfk_2` FOREIGN KEY (`codigo_cliente`) REFERENCES `cliente` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.plano_nutricionista: ~2 rows (aproximadamente)
INSERT INTO `plano_nutricionista` (`id`, `codigo_rh`, `codigo_cliente`, `titulo`, `ficheiro`, `criado_em`) VALUES
	(8, 23, 30, NULL, '1765968840_ContratoLucia.pdf', '2025-12-17 10:54:00'),
	(9, 23, 1, NULL, '1765970940_ContratoLucia.pdf', '2025-12-17 11:29:00');

-- A despejar estrutura para tabela database_aio.plano_pt
DROP TABLE IF EXISTS `plano_pt`;
CREATE TABLE IF NOT EXISTS `plano_pt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_rh` int(11) NOT NULL,
  `codigo_cliente` int(11) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `codigo_rh` (`codigo_rh`),
  KEY `codigo_cliente` (`codigo_cliente`),
  CONSTRAINT `plano_PT_ibfk_1` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`),
  CONSTRAINT `plano_PT_ibfk_2` FOREIGN KEY (`codigo_cliente`) REFERENCES `cliente` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela database_aio.plano_pt: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.plano_pt_ficheiros
DROP TABLE IF EXISTS `plano_pt_ficheiros`;
CREATE TABLE IF NOT EXISTS `plano_pt_ficheiros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `pt_id` int(11) NOT NULL,
  `nome_original` varchar(255) DEFAULT NULL,
  `nome_ficheiro` varchar(255) DEFAULT NULL,
  `caminho` varchar(255) DEFAULT NULL,
  `data_envio` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `pt_id` (`pt_id`),
  CONSTRAINT `fk_plano_pt_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_plano_pt_rh` FOREIGN KEY (`pt_id`) REFERENCES `rh` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela database_aio.plano_pt_ficheiros: ~3 rows (aproximadamente)
INSERT INTO `plano_pt_ficheiros` (`id`, `cliente_id`, `pt_id`, `nome_original`, `nome_ficheiro`, `caminho`, `data_envio`) VALUES
	(5, 29, 29, 'Currículo Eduardo.pdf', '6940683d24051_Currículo Eduardo.pdf', 'uploads/planos_pt', '2025-12-15 19:57:49'),
	(6, 31, 23, 'Contrato Lucia.pdf', '6942896045c64_Contrato Lucia.pdf', 'uploads/planos_pt', '2025-12-17 10:43:44'),
	(7, 10, 23, 'Contrato Lucia.pdf', '694292d82b786_Contrato Lucia.pdf', 'uploads/planos_pt', '2025-12-17 11:24:08');

-- A despejar estrutura para tabela database_aio.produto_marketplace
DROP TABLE IF EXISTS `produto_marketplace`;
CREATE TABLE IF NOT EXISTS `produto_marketplace` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `imagem` varchar(255) DEFAULT NULL,
  `id_parceiro` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_parceiro` (`id_parceiro`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `produto_marketplace_ibfk_1` FOREIGN KEY (`id_parceiro`) REFERENCES `parceiro_marketplace` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `produto_marketplace_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.produto_marketplace: ~8 rows (aproximadamente)
INSERT INTO `produto_marketplace` (`id`, `nome`, `descricao`, `preco`, `stock`, `imagem`, `id_parceiro`, `id_estado`) VALUES
	(5, 'Proteína Whey 1kg', 'Proteína ideal para ganho de massa muscular', 29.90, 50, '.backoffice/assets/images/products/1765324538_concentrate_mockups_1kg_256_NEW_NORM.jpg', 1, 2),
	(6, 'Tapete  IOGA', 'Tapetes de yoga extra grossos de 10mm para iniciantes, tapetes de exercício antiderrapantes para academia, fitness, pilates, meiogaditação, casa, academia, treino', 12.99, 100, '.backoffice/assets/images/products/1765332514_ab-mat-yoga-esterilla-azul-fitnesstech_0007_7V7A0942.webp', 2, 2),
	(7, 'Halteres', 'Conjunto de 2 halteres para treino de resistência; cada uma pesa 2 kg.\\r\\nA textura de neoprene proporciona uma aderência fácil e segura\\r\\nCom número de peso impresso em cada extremidade e código de cores para uma identificação rápida.\\r\\nForma hexagonal para evitar o rolamento do haltere.\\r\\nIdeal para aulas de fitness ou rotinas de treino em casa.', 25.99, 5, '.backoffice/assets/images/products/1765332715_halteres-redondos-de-musculacao-20kg-par-boomfit.jpg', 4, 2),
	(8, 'Tênis Fitness', 'Design de cinco dedos: Os sapatos de ponta larga oferecem mais espaço para cada dedo, enquanto o design de separação dos dedos ajuda o pé a ter um melhor contacto com o chão, imitando a caminhada descalça. Isso não só melhora o equilíbrio e a flexibilidade, mas também reduz efetivamente a pressão e a deformação dos dedos.', 55.00, 50, '.backoffice/assets/images/products/1765332893_tenis-para-treino-de-cross-white-orange-50113-3.jpg', 3, 2),
	(9, 'Bola de Fitness', 'EQUIPAMENTO PARA USO PROFISSIONAL\\r\\nA bola de fitness dá a possibilidade de realizar múltiplos exercícios, melhora o equilíbrio e a coordenação, o fortalecimento da zona abdominal e também permite a correção da postura do atleta. É ideal para quem tem problemas lombares.\\r\\nFabricada em espuma de PVC, é um acessório indispensável no seu ginásio.', 12.00, 100, '.backoffice/assets/images/products/1765333148_transferir.jpg', 5, 2),
	(10, 'kettlebells', 'Os kettlebells revestidos em borracha são perfeitos para quem procura melhorar a força, resistência, potência e desempenho cardiovascular, oferecendo um design ergonómico e revestimento de borracha de alta qualidade, que assegura durabilidade e conforto excecionais durante os treinos. Disponíveis em pesos de 4 kg a 24 kg.', 16.99, 55, '.backoffice/assets/images/products/1765333330_Kettlebell.webp', 4, 2),
	(11, 'Creatina', 'Sobre este produto\\r\\n100% puro\\r\\nUma escolha simples, fiável e eficaz\\r\\nMelhora o desempenho e a potência muscular', 22.99, 100, '.backoffice/assets/images/products/1765333477_566452-suplemento-alimentar-de-creatina-em-po-sem-sabor-317-gramas-kg-on-optimum-nutrition20221221100403.webp', 1, 2),
	(12, 'T-shirt desportiva', 'Material do tecido exterior: 100% poliéster\\r\\nTecido: Jersey\\r\\nTecnologia: Dri-Fit (Nike)\\r\\nInstruções de cuidados: Lavar à máquina a 30 °C', 15.99, 2, '.backoffice/assets/images/products/1765333675_7864907-frente.jpg', 4, 2);

-- A despejar estrutura para tabela database_aio.profissional_servico
DROP TABLE IF EXISTS `profissional_servico`;
CREATE TABLE IF NOT EXISTS `profissional_servico` (
  `codigo_rh` int(11) NOT NULL,
  `id_servico` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`codigo_rh`,`id_servico`),
  KEY `fk_ps_servico` (`id_servico`),
  CONSTRAINT `fk_ps_rh` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ps_servico` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.profissional_servico: ~1 rows (aproximadamente)
INSERT INTO `profissional_servico` (`codigo_rh`, `id_servico`, `ativo`) VALUES
	(21, 8, 1);

-- A despejar estrutura para tabela database_aio.profissional_tipo_aula_grupo
DROP TABLE IF EXISTS `profissional_tipo_aula_grupo`;
CREATE TABLE IF NOT EXISTS `profissional_tipo_aula_grupo` (
  `codigo_rh` int(11) NOT NULL,
  `id_tipo_aula_grupo` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`codigo_rh`,`id_tipo_aula_grupo`),
  KEY `fk_ptag_tipo` (`id_tipo_aula_grupo`),
  CONSTRAINT `fk_ptag_rh` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ptag_tipo` FOREIGN KEY (`id_tipo_aula_grupo`) REFERENCES `tipo_aula_grupo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.profissional_tipo_aula_grupo: ~1 rows (aproximadamente)
INSERT INTO `profissional_tipo_aula_grupo` (`codigo_rh`, `id_tipo_aula_grupo`, `ativo`) VALUES
	(21, 2, 1);

-- A despejar estrutura para tabela database_aio.progresso_cliente
DROP TABLE IF EXISTS `progresso_cliente`;
CREATE TABLE IF NOT EXISTS `progresso_cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilizador` int(11) NOT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `calorias` int(11) DEFAULT NULL,
  `tempo_treino` int(11) DEFAULT NULL,
  `data_registo` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.progresso_cliente: ~13 rows (aproximadamente)
INSERT INTO `progresso_cliente` (`id`, `id_utilizador`, `peso`, `calorias`, `tempo_treino`, `data_registo`) VALUES
	(1, 36, 110.00, 8000, 46, '2025-12-09 17:11:50'),
	(2, 36, 100.00, 8000, 46, '2025-12-09 17:12:03'),
	(3, 39, 110.00, 2000, 47, '2025-12-09 17:39:30'),
	(4, 39, 90.00, 2000, 47, '2025-12-09 17:39:36'),
	(5, 39, 140.00, 2000, 47, '2025-12-09 17:39:49'),
	(6, 41, 100.00, 800, 50, '2025-12-11 18:19:01'),
	(7, 41, 90.00, 800, 50, '2025-12-11 18:19:09'),
	(8, 41, 70.00, 800, 50, '2025-12-11 18:19:14'),
	(9, 41, 120.00, 800, 50, '2025-12-11 18:19:19'),
	(10, 46, 73.00, 1200, 45, '2025-12-17 10:27:02'),
	(11, 46, 80.00, 1200, 45, '2025-12-17 10:27:08'),
	(12, 50, 75.00, 1800, 45, '2025-12-17 11:20:21'),
	(13, 50, 80.00, 2000, 45, '2025-12-17 11:20:28');

-- A despejar estrutura para tabela database_aio.rh
DROP TABLE IF EXISTS `rh`;
CREATE TABLE IF NOT EXISTS `rh` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilizador` int(11) NOT NULL,
  `nome_completo` varchar(150) NOT NULL,
  `nif` varchar(20) DEFAULT NULL,
  `contacto` varchar(20) DEFAULT NULL,
  `id_funcao` int(11) DEFAULT NULL,
  `qualificacao` varchar(255) DEFAULT NULL,
  `experiencia_anos` int(11) DEFAULT NULL,
  `id_tipo_contrato` int(11) DEFAULT NULL,
  `id_estado` int(11) DEFAULT 1,
  `contrato` varchar(255) DEFAULT NULL,
  `recibo` varchar(255) DEFAULT NULL,
  `data_contratacao` date DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_utilizador` (`id_utilizador`),
  KEY `id_funcao` (`id_funcao`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `rh_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id`),
  CONSTRAINT `rh_ibfk_2` FOREIGN KEY (`id_funcao`) REFERENCES `funcao` (`id`),
  CONSTRAINT `rh_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.rh: ~11 rows (aproximadamente)
INSERT INTO `rh` (`codigo`, `id_utilizador`, `nome_completo`, `nif`, `contacto`, `id_funcao`, `qualificacao`, `experiencia_anos`, `id_tipo_contrato`, `id_estado`, `contrato`, `recibo`, `data_contratacao`) VALUES
	(1, 2, 'José Lopes', '297678788', '964274942', 1, 'Licenciatura em Gestão', 10, 6, 2, 'src/uploads/1762769776_Contrato_José_Lopes.pdf', '', '2025-11-10'),
	(16, 17, 'Ana Sofia Marques', '282643648', '912345004', 3, 'Personal Trainer Certificada', 3, 2, 2, NULL, NULL, '2024-01-05'),
	(17, 18, 'Lúcia Mendes', '273082868', '912345005', 4, 'Psicóloga Clínica', 7, 2, 2, NULL, NULL, '2021-09-20'),
	(18, 14, 'Maria Beatriz Martins', '215904141', '912345001', 2, 'Licenciada em Nutrição', 4, 2, 2, NULL, NULL, '2023-06-01'),
	(19, 15, 'João Ferreira', '285177893', '912345002', 2, 'Mestre em Ciências da Nutrição', 6, 2, 2, NULL, NULL, '2022-11-15'),
	(20, 16, 'Guilherme Sousa', '237919389', '912345003', 3, 'Licenciado em Educação Física', 5, 2, 2, NULL, NULL, '2023-03-10'),
	(21, 20, 'Filipe Pimentel', '258564321', '956780943', 3, 'Licenciatura em Educação Fisica', 2, 2, 2, 'src/uploads/1763036438_Contrato_Filipe_Pimentel.pdf', '', '2025-11-13'),
	(23, 43, 'Eduardo Frechaut', '253456552', '965625623', 2, 'Licenciatura em Nutrição', 5, 2, 2, NULL, '', '2025-12-11'),
	(29, 45, 'Eduardo', '444444444', '444444444', 3, 'Licenciatura em Educação Fisica', 2, 2, 2, NULL, NULL, '2025-12-14'),
	(30, 47, 'Jose Silva', '2132423432', '964343535', 4, 'nivel 5', 4, 1, 2, 'src/uploads/1765969500_Contrato Joana.pdf', '', '2025-12-17'),
	(31, 48, 'wefwf', '4353453', '234223', 3, 'dasdew', 432, 5, 2, 'src/uploads/1765969539_Contrato Joana.pdf', '', '2025-12-17');

-- A despejar estrutura para tabela database_aio.salario
DROP TABLE IF EXISTS `salario`;
CREATE TABLE IF NOT EXISTS `salario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_rh` int(11) DEFAULT NULL,
  `id_funcao` int(11) DEFAULT NULL,
  `salario_bruto` decimal(10,2) DEFAULT NULL,
  `irs` decimal(10,2) DEFAULT NULL,
  `ss` decimal(10,2) DEFAULT NULL,
  `salario_liquido` decimal(10,2) DEFAULT NULL,
  `subsidio_alimentacao` decimal(10,2) DEFAULT NULL,
  `subsidio_ferias` decimal(10,2) DEFAULT NULL,
  `subsidio_natal` decimal(10,2) DEFAULT NULL,
  `salario_total` decimal(10,2) DEFAULT NULL,
  `data_prevista` date DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_funcao` (`id_funcao`),
  KEY `codigo_rh` (`codigo_rh`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `salario_ibfk_1` FOREIGN KEY (`id_funcao`) REFERENCES `funcao` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `salario_ibfk_2` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `salario_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.salario: ~3 rows (aproximadamente)
INSERT INTO `salario` (`id`, `codigo_rh`, `id_funcao`, `salario_bruto`, `irs`, `ss`, `salario_liquido`, `subsidio_alimentacao`, `subsidio_ferias`, `subsidio_natal`, `salario_total`, `data_prevista`, `id_estado`) VALUES
	(1, 16, 1, 1992.55, 158.40, 132.00, 1699.55, 132.00, 0.00, 0.00, 1185.37, '2025-07-31', 12),
	(2, 1, 1, 1320.00, 158.40, 132.00, 1188.00, 132.00, 0.00, 0.00, 1185.37, '2025-08-31', 12),
	(3, 1, 1, 1332.00, 158.40, 132.00, 1200.00, 132.00, 0.00, 0.00, 1185.37, '2025-09-30', 12);

-- A despejar estrutura para tabela database_aio.servico
DROP TABLE IF EXISTS `servico`;
CREATE TABLE IF NOT EXISTS `servico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.servico: ~14 rows (aproximadamente)
INSERT INTO `servico` (`id`, `descricao`, `preco`) VALUES
	(1, 'PLANO BASICO', 0.00),
	(2, 'PLANO MÉDIO', 70.00),
	(3, 'PLANO PRO', 160.00),
	(4, 'PLANO DUO', 110.00),
	(5, 'PACK LAR ', 500.00),
	(6, 'CONSULTA PSICOLOGIA', 60.00),
	(7, 'CONSULTA NUTRIÇÃO', 50.00),
	(8, 'CONSULTA PT', 40.00),
	(9, 'AULA DE GRUPO', 12.00),
	(10, 'MARKETPLACE', 0.00),
	(11, 'EXAME BIOIMPEDÂNCIA', 10.00),
	(12, 'AVALIAÇÃO FÍSICA', 15.00),
	(13, 'RELATÓRIO DETALHADO', 8.00),
	(14, 'AVALIAÇÃO INICIAL', 5.00);

-- A despejar estrutura para tabela database_aio.suporte_assuntos
DROP TABLE IF EXISTS `suporte_assuntos`;
CREATE TABLE IF NOT EXISTS `suporte_assuntos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela database_aio.suporte_assuntos: ~6 rows (aproximadamente)
INSERT INTO `suporte_assuntos` (`id`, `titulo`) VALUES
	(1, 'Problemas na App'),
	(2, 'Planos de Treino'),
	(3, 'Planos Alimentares'),
	(4, 'Pagamentos'),
	(5, 'Configurações da Conta'),
	(6, 'Outros');

-- A despejar estrutura para tabela database_aio.suporte_pedidos
DROP TABLE IF EXISTS `suporte_pedidos`;
CREATE TABLE IF NOT EXISTS `suporte_pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `assunto_id` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `estado` enum('aberto','em_progresso','resolvido') DEFAULT 'aberto',
  `imagem` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_sp_user` (`user_id`),
  KEY `fk_sp_assunto` (`assunto_id`),
  CONSTRAINT `fk_sp_assunto` FOREIGN KEY (`assunto_id`) REFERENCES `suporte_assuntos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_sp_user` FOREIGN KEY (`user_id`) REFERENCES `utilizador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela database_aio.suporte_pedidos: ~2 rows (aproximadamente)
INSERT INTO `suporte_pedidos` (`id`, `user_id`, `assunto_id`, `mensagem`, `estado`, `imagem`, `criado_em`) VALUES
	(4, 36, 3, 'wsadadawd', 'aberto', NULL, '2025-12-09 19:35:00'),
	(5, 1, 4, 'sdadad', 'aberto', NULL, '2025-12-09 19:47:15');

-- A despejar estrutura para tabela database_aio.tipo_ativo
DROP TABLE IF EXISTS `tipo_ativo`;
CREATE TABLE IF NOT EXISTS `tipo_ativo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.tipo_ativo: ~5 rows (aproximadamente)
INSERT INTO `tipo_ativo` (`id`, `categoria`) VALUES
	(1, 'Informática'),
	(2, 'Mobiliário'),
	(3, 'Viaturas'),
	(4, 'Instalações'),
	(5, 'Outros');

-- A despejar estrutura para tabela database_aio.tipo_aula_grupo
DROP TABLE IF EXISTS `tipo_aula_grupo`;
CREATE TABLE IF NOT EXISTS `tipo_aula_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `nivel_dificuldade` enum('Iniciante','Intermédio','Avançado') DEFAULT 'Iniciante',
  `duracao_minutos` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.tipo_aula_grupo: ~5 rows (aproximadamente)
INSERT INTO `tipo_aula_grupo` (`id`, `nome`, `descricao`, `nivel_dificuldade`, `duracao_minutos`) VALUES
	(1, 'Yoga', 'Aulas de Yoga para relaxamento e flexibilidade', 'Iniciante', 60),
	(2, 'CrossFit', 'Treino funcional intenso para força e resistência', 'Avançado', 45),
	(3, 'Pilates', 'Exercícios de alongamento e fortalecimento do core', 'Intermédio', 50),
	(4, 'Zumba', 'Aula de dança aeróbica com foco em cardio', 'Iniciante', 55),
	(5, 'Spinning', 'Treino de bicicleta indoor para resistência e cardio', 'Intermédio', 45);

-- A despejar estrutura para tabela database_aio.tipo_contrato
DROP TABLE IF EXISTS `tipo_contrato`;
CREATE TABLE IF NOT EXISTS `tipo_contrato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.tipo_contrato: ~6 rows (aproximadamente)
INSERT INTO `tipo_contrato` (`id`, `descricao`) VALUES
	(1, 'Parcial'),
	(2, 'Prestação de Serviços'),
	(3, 'Sem Termo'),
	(4, 'Tempo Incerto'),
	(5, 'Temporário'),
	(6, 'Termo Certo');

-- A despejar estrutura para tabela database_aio.tipo_corpo
DROP TABLE IF EXISTS `tipo_corpo`;
CREATE TABLE IF NOT EXISTS `tipo_corpo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.tipo_corpo: ~3 rows (aproximadamente)
INSERT INTO `tipo_corpo` (`id`, `nome`, `descricao`) VALUES
	(1, 'Ectomorfo', 'Corpo naturalmente magro, com metabolismo acelerado, dificuldade em ganhar massa muscular e gordura. Geralmente apresenta membros longos e ombros estreitos.'),
	(2, 'Mesomorfo', 'Corpo naturalmente atlético, com facilidade para ganhar músculo e perder gordura. Estrutura corporal equilibrada e boa resposta a treinos físicos.'),
	(3, 'Endomorfo', 'Corpo com tendência a acumular gordura, metabolismo mais lento e maior facilidade em ganhar peso. Requer maior controlo alimentar e treinos focados em resistência e perda de gordura.');

-- A despejar estrutura para tabela database_aio.tipo_custo
DROP TABLE IF EXISTS `tipo_custo`;
CREATE TABLE IF NOT EXISTS `tipo_custo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.tipo_custo: ~3 rows (aproximadamente)
INSERT INTO `tipo_custo` (`id`, `descricao`) VALUES
	(1, 'INICIAL'),
	(2, 'MENSAL'),
	(3, 'EXTRAORDINARIO');

-- A despejar estrutura para tabela database_aio.tipo_dieta
DROP TABLE IF EXISTS `tipo_dieta`;
CREATE TABLE IF NOT EXISTS `tipo_dieta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.tipo_dieta: ~10 rows (aproximadamente)
INSERT INTO `tipo_dieta` (`id`, `nome`) VALUES
	(1, 'Tradicional'),
	(2, 'Vegetariano'),
	(3, 'Keto'),
	(4, 'Pescatoriano'),
	(5, 'Vegan (dieta à base de plantas)'),
	(6, 'Paleo'),
	(7, 'Mediterrâneo'),
	(8, 'Diabetes tipo 1'),
	(9, 'Diabetes tipo 2'),
	(10, 'Alto teor de proteína');

-- A despejar estrutura para tabela database_aio.tipo_obrigacao
DROP TABLE IF EXISTS `tipo_obrigacao`;
CREATE TABLE IF NOT EXISTS `tipo_obrigacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.tipo_obrigacao: ~4 rows (aproximadamente)
INSERT INTO `tipo_obrigacao` (`id`, `descricao`) VALUES
	(1, 'Funcionário'),
	(2, 'Fornecedor'),
	(3, 'Prestador de Serviço'),
	(4, 'Estado');

-- A despejar estrutura para tabela database_aio.tipo_user
DROP TABLE IF EXISTS `tipo_user`;
CREATE TABLE IF NOT EXISTS `tipo_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.tipo_user: ~5 rows (aproximadamente)
INSERT INTO `tipo_user` (`id`, `nome`) VALUES
	(1, 'Admin'),
	(2, 'PT'),
	(3, 'Cliente'),
	(4, 'Nutricionista'),
	(5, 'Psicólogo');

-- A despejar estrutura para tabela database_aio.treinos
DROP TABLE IF EXISTS `treinos`;
CREATE TABLE IF NOT EXISTS `treinos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `duracao_min` int(11) DEFAULT NULL,
  `nivel` enum('Iniciante','Intermédio','Avançado') DEFAULT 'Iniciante',
  `grupo_muscular` varchar(100) DEFAULT NULL,
  `video_url` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.treinos: ~9 rows (aproximadamente)
INSERT INTO `treinos` (`id`, `titulo`, `descricao`, `duracao_min`, `nivel`, `grupo_muscular`, `video_url`, `thumbnail`, `ativo`) VALUES
	(1, '20 MIN Alongamento Corpo Inteiro – Sem Fala', 'Sessão completa de alongamento de 20 minutos focada em mobilidade, flexibilidade e alívio de stress. Treino guiado visualmente, sem fala.', 20, 'Iniciante', 'Corpo Inteiro', 'https://www.youtube.com/watch?v=99ZBq-dZ3nc', 'https://img.youtube.com/vi/99ZBq-dZ3nc/maxresdefault.jpg', 1),
	(2, 'Treino Completo para Iniciantes de 20 Minutos com Halteres – Em Casa', 'Treino rápido de 20 minutos que trabalha todo o corpo utilizando apenas um par de halteres. Ideal para iniciantes e pessoas acima dos 50 anos que procuram ganhar força, energia e mobilidade em casa. Movimentos simples, ritmo acessível e resultados reais. Se sentires tonturas, dor ou falta de ar, pára o treino e procura aconselhamento profissional.', 20, 'Iniciante', 'Corpo Inteiro', 'https://www.youtube.com/watch?v=QzRBnkCugow', 'https://img.youtube.com/vi/QzRBnkCugow/maxresdefault.jpg', 1),
	(3, '15 MIN CARDIO HIIT para perder barriga rápido, sem equipamentos e em casa', 'Neste treino, você irá realizar uma série de exercícios de alta intensidade por intervalos curtos, seguidos de períodos de descanso. Esse tipo de treino é uma ótima maneira de queimar calorias e melhorar sua condição física.\r\nEste treino é composto por exercícios diferentes, que trabalham todos os principais grupos musculares. Você não precisará de nenhum equipamento, apenas um pouco de espaço no chão.\r\nPara obter melhores resultados, é importante seguir as instruções do vídeo e realizar cada exercício com a máxima intensidade possível.', 15, 'Iniciante', 'Corpo Inteiro', 'https://www.youtube.com/watch?v=ImvFAvSqaLc&t=29s', 'https://img.youtube.com/vi/ImvFAvSqaLc/maxresdefault.jpg', 1),
	(4, 'Treino Queima de Gordura em 10 Minutos – Sem Equipamentos', 'Transforme o seu corpo em apenas 10 minutos com este treino rápido, intenso e totalmente sem equipamentos.\r\nÉ perfeito para queimar gordura, acelerar o metabolismo e aumentar a resistência física — tudo no conforto da sua casa.\r\nEste treino de corpo inteiro (full body) combina movimentos dinâmicos e acessíveis para todos os níveis, ajudando a queimar calorias de forma eficaz mesmo sem halteres ou material de ginásio. Basta dar o play e acompanhar!', 10, 'Intermédio', 'Corpo Inteiro', 'https://www.youtube.com/watch?v=NMSOpenaNRM', 'https://img.youtube.com/vi/NMSOpenaNRM/maxresdefault.jpg', 1),
	(5, 'Pilates Energizante de 20 Minutos – Treino Diário Rápido e Moderado (Express)', 'Este treino express de Pilates de 20 minutos foi criado para aumentar a energia, melhorar a postura e fortalecer o corpo de forma equilibrada. Ideal para praticar todos os dias, este fluxo moderado combina exercícios suaves e eficazes que ativam o core, alongam o corpo e promovem bem-estar físico e mental. Perfeito para quem deseja movimentar-se de forma rápida, consciente e sem impacto — em casa, no escritório ou onde estiver.', 20, 'Iniciante', 'Corpo Inteiro', 'https://www.youtube.com/watch?v=ezke0GlKeM4', 'https://img.youtube.com/vi/ezke0GlKeM4/maxresdefault.jpg', 1),
	(6, 'Treino Intenso de Corpo Inteiro – 20 Minutos Sem Equipamentos', 'Prepare-se para um treino intenso de 20 minutos, totalmente sem equipamentos, que trabalha o corpo inteiro e acelera o metabolismo. Este full body é perfeito para treinar onde quiser, enquanto desafia força, resistência e foco. Siga os movimentos por 30 segundos cada e permita-se evoluir no seu ritmo — faça pausas sempre que precisar!\r\nEste treino aumenta a frequência cardíaca, fortalece todos os grupos musculares e entrega aquela sensação incrível de missão cumprida. Pegue a sua toalha ou tapete e venha treinar comigo. Sem desculpas!', 20, 'Iniciante', 'Corpo Inteiro', 'https://www.youtube.com/watch?v=Y2eOW7XYWxc', 'https://img.youtube.com/vi/Y2eOW7XYWxc/maxresdefault.jpg', 1),
	(7, 'Treino Intenso de Abdômen em 15 Minutos – Sixpack Rápido Sem Equipamentos', 'Prepare-se para um treino intenso de abdominais de 15 minutos, pensado para trabalhar TODAS as regiões do core: inferiores, superiores, oblíquos e estabilidade profunda. Começamos com movimentos controlados para ativar a musculatura, seguimos para pranchas desafiadoras e terminamos com exercícios de alta intensidade para elevar a frequência cardíaca e fortalecer ainda mais o core.\r\nNão precisa de equipamentos, não precisa de muito espaço — apenas vontade de treinar! Siga cada exercício durante 30 segundos e faça pausas sempre que precisar. O importante é continuar até o fim!', 15, 'Iniciante', 'Abdominais', 'https://www.youtube.com/watch?v=EfJ4aB_enVE', 'https://img.youtube.com/vi/EfJ4aB_enVE/maxresdefault.jpg', 1),
	(8, 'Queime 500 Calorias em 20 Min – Cardio HIIT Intenso Sem Equipamentos (No Repeat)', 'Prepare-se para um Cardio HIIT explosivo de 20 minutos projetado para queimar até 500 calorias — tudo isso sem equipamentos e com exercícios sem repetições. Perfeito para treinar em casa e acelerar a perda de peso enquanto melhora o condicionamento físico.\r\nEste treino de corpo inteiro combina movimentos rápidos e dinâmicos que elevam a frequência cardíaca e desafiam todo o corpo. Basta seguir o vídeo: 30 segundos de trabalho e 15 segundos de descanso entre cada exercício.\r\nPegue a sua toalha, respire fundo e vamos queimar essas calorias juntos!', 20, 'Intermédio', 'Corpo Inteiro', 'https://www.youtube.com/watch?v=RcLz_atcq8w', 'https://img.youtube.com/vi/RcLz_atcq8w/maxresdefault.jpg', 1),
	(9, 'Treino de 20 Minutos: Pernas e Glúteos Definidos em Casa, Sem Equipamentos', 'Neste treino, você irá realizar uma série de exercícios de alta intensidade em intervalos curtos, seguidos por pequenos períodos de descanso. Esse tipo de treino é excelente para queimar calorias, tonificar os músculos e melhorar a sua condição física geral.\r\nA sessão é composta por diferentes movimentos que trabalham todos os principais grupos musculares das pernas e glúteos. Você não precisará de nenhum equipamento — apenas um pouco de espaço no chão e disposição para dar o seu melhor.\r\nPara obter resultados mais rápidos e eficientes, siga as instruções do vídeo e execute cada exercício com a maior intensidade que conseguir, sempre mantendo a boa postura.\r\n⚠ Lembre-se: cada corpo é único, e você deve adaptar este treino ao seu ritmo.\r\nFaça pausas mais longas sempre que precisar, reduza a intensidade caso sinta desconforto e priorize a segurança. O importante é não desistir e evoluir no seu próprio tempo!', 20, 'Iniciante', 'Pernas e Glúteos ', 'https://www.youtube.com/watch?v=sjYpqD2et0I', 'https://img.youtube.com/vi/sjYpqD2et0I/maxresdefault.jpg', 1);

-- A despejar estrutura para tabela database_aio.treino_exercicios
DROP TABLE IF EXISTS `treino_exercicios`;
CREATE TABLE IF NOT EXISTS `treino_exercicios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plano_id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `repeticoes` int(11) NOT NULL,
  `tempo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `plano_id` (`plano_id`),
  CONSTRAINT `treino_exercicios_ibfk_1` FOREIGN KEY (`plano_id`) REFERENCES `planos_treino` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.treino_exercicios: ~4 rows (aproximadamente)
INSERT INTO `treino_exercicios` (`id`, `plano_id`, `nome`, `repeticoes`, `tempo`) VALUES
	(1, 1, 'teste', 2, 4),
	(2, 1, 'teste2', 3, 5),
	(3, 2, 'awdad', 12, 12),
	(9, 8, 'remada baixa', 12, 5);

-- A despejar estrutura para tabela database_aio.utilizador
DROP TABLE IF EXISTS `utilizador`;
CREATE TABLE IF NOT EXISTS `utilizador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_tipo_user` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `data_registo` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_tipo_user` (`id_tipo_user`),
  CONSTRAINT `utilizador_ibfk_1` FOREIGN KEY (`id_tipo_user`) REFERENCES `tipo_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.utilizador: ~30 rows (aproximadamente)
INSERT INTO `utilizador` (`id`, `username`, `email`, `password`, `id_tipo_user`, `foto`, `data_registo`) VALUES
	(1, 'Admin', 'admin@gmail.com', '$2y$10$vWiS7Q8ZMpez9OkrKrihoOfJX3fb1byovlLfL9/x7Y5KZ0W2jf3oq', 1, NULL, '2025-11-10 10:02:45'),
	(2, 'JLopes', 'jsilvalopes84@gmail.com', '$2y$10$TFGqTTpWvSfp3sQMuALda.0N4ydNPhafczZlXJbtR/6YV46/hTDKG', 1, NULL, '2025-11-10 10:16:16'),
	(4, 'alvaroalmeida', 'alvaroalmeida@gmail.pt', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 3, NULL, '2025-11-10 18:06:33'),
	(5, 'veragoncalves', 'veragoncalves@hotmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 3, NULL, '2025-11-10 18:06:33'),
	(6, 'cesarsa', 'cesarsa@outlook.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 3, NULL, '2025-11-10 18:06:33'),
	(7, 'vicenteamorimleal', 'vicenteamorimleal@hotmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 3, NULL, '2025-11-10 18:06:33'),
	(8, 'iarabarbosa', 'iarabarbosa@yahoo.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 3, NULL, '2025-11-10 18:06:33'),
	(9, 'florfonsecasoares', 'florfonsecasoares@gmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 3, NULL, '2025-11-10 18:06:33'),
	(10, 'naiaramagalhaes', 'naiaramagalhaes@gmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 3, NULL, '2025-11-10 18:06:33'),
	(11, 'fredericomiranda', 'frederico.miranda@gmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 3, NULL, '2025-11-10 18:06:33'),
	(12, 'soraiamaia', 'soraiamaia@outlook.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 3, NULL, '2025-11-10 18:06:33'),
	(13, 'isabelneto', 'isabelneto@gmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 3, NULL, '2025-11-10 18:06:33'),
	(14, 'maria.martins', 'maria.martins@gmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 2, NULL, '2025-11-10 18:30:40'),
	(15, 'joao.ferreira', 'joao.ferreira@hotmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 2, NULL, '2025-11-10 18:30:40'),
	(16, 'guilherme.sousa', 'guilherme.sousa@hotmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 2, NULL, '2025-11-10 18:30:40'),
	(17, 'ana.marques', 'ana.marques@gmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 2, NULL, '2025-11-10 18:30:40'),
	(18, 'lucia.mendes', 'lucia.mendes@gmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 2, NULL, '2025-11-10 18:30:40'),
	(20, 'FPimentel', 'filipemtp2005@gmail.com', '$2y$10$B9ChN86xFpBxYEVDr0BKA.QJ5xE7WRNo4n7XdGFA84RouM3avkM1i', 2, NULL, '2025-11-13 12:20:38'),
	(35, 'abc@gmail.com', 'abc@gmail.com', '$2y$10$MNXCsFGhhH7iPOHhVCTcW.j.8t5tR/E/CES0rgmtZXPwBeXpjoBVa', 3, NULL, '2025-11-28 12:50:20'),
	(36, 'abc', 'abcd@gmail.com', '$2y$10$4yzDsCXbXHMtwy3.smSVSuRstB3efgKQyFQQyS8f.HAVMQGnA8.ve', 3, NULL, '2025-11-28 13:56:23'),
	(41, 'ffsdfsf', 'sd@gmail.com', '$2y$10$8fUcL.UFyFj5E4fwO7fP0OR4ZF7F8kqs.Za.cYiT4iRWvUWudYOcG', 3, NULL, '2025-12-11 17:40:39'),
	(42, 'edu', 'edu@gmail.com', '$2y$10$UBr7M1ImQFXkOXxJq0WJa.vyezBWZC046Ju//TmOFy801kpUxRPFu', 4, NULL, '2025-12-11 21:00:01'),
	(43, 'EFrechaut', 'eduardofrechaut07@gmail.com', '$2y$10$mF7mSRRfWpItPYrTcHyMH.jIe4t8CwqPP05VluldHGHUXKBZkgWLu', 4, NULL, '2025-12-11 23:39:56'),
	(44, 'teste', 'teste@gmail.com', '$2y$10$7Oq/stOwJhFQRaaqGbroMu4IX0BIigbmGpMW1HcfOUuzNvcthEs6i', 3, NULL, '2025-12-12 19:57:57'),
	(45, 'edufre', 'eduardofrechaut02@gmail.com', '$2y$10$DNNRSdp/yn9533x0vmVHiuFsHKkltWXxyVDoKT2IZ6z2bPhxnlagW', 2, NULL, '2025-12-14 19:15:38'),
	(46, 'jose', 'jose@gmail.com', '$2y$10$7tsXJ4MsOT61/zo2lMmPTOx/Bf5xnrYTIs5cgl63qnkEjefauMq.S', 3, NULL, '2025-12-17 10:25:19'),
	(47, 'Js', 'jmanuelsilva84@gmail.com', '$2y$10$iLmaMFgByk4arT6I9QurGejrA6C9dFHD8HizdgEL.DSxt2Zamv5cS', 5, NULL, '2025-12-17 11:05:00'),
	(48, '3254324', 'filipemtp2005@gmail.com', '$2y$10$GI/9rwwbBTlNiDsqqvpUD.MEWjq9Q8xMoXDGs37ZEuz5jDI1xZMzK', 1, NULL, '2025-12-17 11:05:39'),
	(49, 'joselopes', 'jose@gmai.com', '$2y$10$3G/mWUPEb.KH3nGC8gPKKOiDS4oKOM.xhQHclXTQwvuqImI333XzS', 3, NULL, '2025-12-17 11:17:43'),
	(50, 'romao', 'romao@gmail.com', '$2y$10$4JB4pZBuGj/u8avp7rp/k.AUbP3EvGpQi.1oTimsoGvyCivHiOwNa', 3, NULL, '2025-12-17 11:19:07');

-- A despejar estrutura para tabela database_aio.venda
DROP TABLE IF EXISTS `venda`;
CREATE TABLE IF NOT EXISTS `venda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `id_servico` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_venda` date NOT NULL,
  `metodo_pagamento` enum('cartao','transferencia','paypal','gratis') DEFAULT 'cartao',
  `id_estado` int(11) NOT NULL,
  `fatura` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_cliente` (`id_cliente`),
  KEY `idx_servico` (`id_servico`),
  KEY `idx_estado` (`id_estado`),
  CONSTRAINT `venda_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `venda_ibfk_2` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `venda_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.venda: ~6 rows (aproximadamente)
INSERT INTO `venda` (`id`, `id_cliente`, `id_servico`, `valor`, `data_venda`, `metodo_pagamento`, `id_estado`, `fatura`) VALUES
	(1, 1, 2, 70.00, '2025-11-01', 'cartao', 12, ''),
	(2, 2, 3, 160.00, '2025-11-02', 'transferencia', 13, ''),
	(3, 3, 6, 60.00, '2025-11-03', 'paypal', 12, ''),
	(4, 1, 10, 120.00, '2025-11-04', 'cartao', 13, ''),
	(5, 2, 10, 250.00, '2025-11-05', 'paypal', 12, ''),
	(6, 4, 2, 70.00, '2025-11-06', 'cartao', 12, '');

-- A despejar estrutura para tabela database_aio.venda_marketplace
DROP TABLE IF EXISTS `venda_marketplace`;
CREATE TABLE IF NOT EXISTS `venda_marketplace` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_venda` int(11) NOT NULL,
  `id_parceiro` int(11) NOT NULL,
  `produto_nome` varchar(255) NOT NULL,
  `preco_produto` decimal(10,2) NOT NULL,
  `percentual_comissao` decimal(5,2) NOT NULL,
  `valor_comissao` decimal(10,2) GENERATED ALWAYS AS (`preco_produto` * (`percentual_comissao` / 100)) STORED,
  PRIMARY KEY (`id`),
  KEY `idx_venda` (`id_venda`),
  KEY `idx_parceiro` (`id_parceiro`),
  CONSTRAINT `venda_marketplace_ibfk_1` FOREIGN KEY (`id_venda`) REFERENCES `venda` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venda_marketplace_ibfk_2` FOREIGN KEY (`id_parceiro`) REFERENCES `parceiro_marketplace` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.venda_marketplace: ~6 rows (aproximadamente)
INSERT INTO `venda_marketplace` (`id`, `id_venda`, `id_parceiro`, `produto_nome`, `preco_produto`, `percentual_comissao`) VALUES
	(13, 4, 1, 'Halteres 10kg', 50.00, 10.00),
	(14, 4, 1, 'Tapete de Yoga', 70.00, 10.00),
	(15, 5, 2, 'Tênis de Corrida', 150.00, 12.00),
	(16, 5, 3, 'Suplemento Whey Protein', 100.00, 8.50),
	(17, 5, 4, 'Kettlebell 12kg', 80.00, 15.00),
	(18, 5, 5, 'Bola de Pilates', 60.00, 10.00);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
