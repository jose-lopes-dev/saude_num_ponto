-- --------------------------------------------------------
-- Anfitrião:                    127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- SO do servidor:               Win64
-- HeidiSQL Versão:              12.12.0.7122
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
CREATE DATABASE IF NOT EXISTS `database_aio` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `database_aio`;

-- A despejar estrutura para tabela database_aio.area_corpo
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
CREATE TABLE IF NOT EXISTS `ativo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `valor_inicial` double NOT NULL,
  `data_aquisicao` date DEFAULT NULL,
  `vida_util_meses` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `ativo_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `tipo_ativo` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.ativo: ~3 rows (aproximadamente)
INSERT INTO `ativo` (`id`, `descricao`, `id_categoria`, `valor_inicial`, `data_aquisicao`, `vida_util_meses`) VALUES
	(1, 'APP', 1, 80000, '2025-01-01', 36),
	(2, 'Software de Faturação', 1, 420, '2025-01-01', 36),
	(3, 'Computador', 1, 600, '2025-01-01', 36);

-- A despejar estrutura para tabela database_aio.aulas_grupo
CREATE TABLE IF NOT EXISTS `aulas_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo` int(11) NOT NULL,
  `id_servico` int(11) NOT NULL,
  `codigo_rh` int(11) NOT NULL,
  `data` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `link_aula` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `criado_em` datetime DEFAULT current_timestamp(),
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_servico` (`id_servico`),
  KEY `codigo_rh` (`codigo_rh`),
  CONSTRAINT `aulas_grupo_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_aula_grupo` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aulas_grupo_ibfk_2` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aulas_grupo_ibfk_3` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.aulas_grupo: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.aula_participantes
CREATE TABLE IF NOT EXISTS `aula_participantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_aula` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `data_inscricao` datetime DEFAULT current_timestamp(),
  `id_estado` int(11) NOT NULL,
  `avaliacao` tinyint(1) DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_aula` (`id_aula`,`id_cliente`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `aula_participantes_ibfk_1` FOREIGN KEY (`id_aula`) REFERENCES `aulas_grupo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `aula_participantes_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `aula_participantes_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.aula_participantes: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.chat
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
CREATE TABLE IF NOT EXISTS `cliente` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilizador` int(11) NOT NULL,
  `nome_completo` varchar(150) NOT NULL,
  `contacto` varchar(20) DEFAULT NULL,
  `nif` varchar(20) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
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
  `data_inicio` date DEFAULT curdate(),
  `id_estado` int(11) DEFAULT 1,
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
  CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id`),
  CONSTRAINT `cliente_ibfk_2` FOREIGN KEY (`id_objetivo`) REFERENCES `objetivo` (`id`),
  CONSTRAINT `cliente_ibfk_3` FOREIGN KEY (`id_nivel`) REFERENCES `nivel_atividade` (`id`),
  CONSTRAINT `cliente_ibfk_4` FOREIGN KEY (`id_atividades`) REFERENCES `atividades` (`id`),
  CONSTRAINT `cliente_ibfk_5` FOREIGN KEY (`id_tipo_corpo`) REFERENCES `tipo_corpo` (`id`),
  CONSTRAINT `cliente_ibfk_6` FOREIGN KEY (`id_habito_diario`) REFERENCES `habito_diario` (`id`),
  CONSTRAINT `cliente_ibfk_7` FOREIGN KEY (`id_area_corpo`) REFERENCES `area_corpo` (`id`),
  CONSTRAINT `cliente_ibfk_8` FOREIGN KEY (`id_tipo_dieta`) REFERENCES `tipo_dieta` (`id`),
  CONSTRAINT `cliente_ibfk_9` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.cliente: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.cliente_condicao
CREATE TABLE IF NOT EXISTS `cliente_condicao` (
  `codigo_cliente` int(11) NOT NULL,
  `id_condicao` int(11) NOT NULL,
  `outra_condicao` text DEFAULT NULL,
  PRIMARY KEY (`codigo_cliente`,`id_condicao`),
  KEY `id_condicao` (`id_condicao`),
  CONSTRAINT `cliente_condicao_ibfk_1` FOREIGN KEY (`codigo_cliente`) REFERENCES `cliente` (`codigo`),
  CONSTRAINT `cliente_condicao_ibfk_2` FOREIGN KEY (`id_condicao`) REFERENCES `condicao_saude` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.cliente_condicao: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.comissao
CREATE TABLE IF NOT EXISTS `comissao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_rh` int(11) DEFAULT NULL,
  `id_funcao` int(11) DEFAULT NULL,
  `numero_consultas` int(11) DEFAULT NULL,
  `total_pagar` double DEFAULT NULL,
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

-- A despejar estrutura para tabela database_aio.condicao_saude
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
CREATE TABLE IF NOT EXISTS `consulta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_cliente` int(11) DEFAULT NULL,
  `id_prestador` int(11) DEFAULT NULL,
  `id_servico` int(11) DEFAULT NULL,
  `data_consulta` date DEFAULT NULL,
  `hora_consulta` time DEFAULT NULL,
  `preco` double DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `codigo_cliente` (`codigo_cliente`),
  KEY `id_prestador` (`id_prestador`),
  KEY `id_servico` (`id_servico`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `consulta_ibfk_1` FOREIGN KEY (`codigo_cliente`) REFERENCES `cliente` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `consulta_ibfk_2` FOREIGN KEY (`id_prestador`) REFERENCES `rh` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `consulta_ibfk_3` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `consulta_ibfk_4` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.consulta: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.conta_bancaria
CREATE TABLE IF NOT EXISTS `conta_bancaria` (
  `id_movimento` int(11) NOT NULL AUTO_INCREMENT,
  `data_movimento` date DEFAULT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `tipo` enum('Entrada','Saída') DEFAULT NULL,
  `valor` double DEFAULT NULL,
  PRIMARY KEY (`id_movimento`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.conta_bancaria: ~2 rows (aproximadamente)
INSERT INTO `conta_bancaria` (`id_movimento`, `data_movimento`, `descricao`, `tipo`, `valor`) VALUES
	(1, '2025-01-30', 'emprestimo', 'Entrada', 173961.92),
	(2, '2025-03-30', 'pagamento', 'Saída', 136510.99);

-- A despejar estrutura para tabela database_aio.custo
CREATE TABLE IF NOT EXISTS `custo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) DEFAULT NULL,
  `valor` double DEFAULT NULL,
  `mes_referencia` date DEFAULT NULL,
  `id_tipo_custo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tipo_custo` (`id_tipo_custo`),
  CONSTRAINT `custo_ibfk_1` FOREIGN KEY (`id_tipo_custo`) REFERENCES `tipo_custo` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.custo: ~33 rows (aproximadamente)
INSERT INTO `custo` (`id`, `descricao`, `valor`, `mes_referencia`, `id_tipo_custo`) VALUES
	(1, 'Fornecimentos e Serviços Externos', 3150, '2025-01-01', 1),
	(2, 'Programa de Computador', 49200, '2025-01-01', 1),
	(3, 'Equipamento Administrativo', 600, '2025-01-01', 1),
	(4, 'Depreciação', 15, '2025-01-01', 1),
	(5, 'Depreciação', 17, '2025-02-01', 2),
	(6, 'Juros', 500, '2025-02-01', 2),
	(7, 'Depreciação', 17, '2025-03-01', 2),
	(8, 'Juros', 500, '2025-03-01', 2),
	(9, 'Depreciação', 17, '2025-04-01', 2),
	(10, 'Juros', 490, '2025-04-01', 2),
	(11, 'Depreciação', 17, '2025-05-01', 2),
	(12, 'Juros', 485, '2025-05-01', 2),
	(13, 'Fornecimentos e Serviços Externos', 4165, '2025-06-01', 2),
	(14, 'Programa de Computador', 49200, '2025-06-01', 2),
	(15, 'Depreciação', 17, '2025-06-01', 2),
	(16, 'Juros', 480, '2025-06-01', 2),
	(17, 'Gastos a Reconhecer', 64, '2025-06-01', 2),
	(18, 'Pessoal', 2558, '2025-07-01', 2),
	(19, 'Ao Estado', 733, '2025-07-01', 2),
	(20, 'Ativo Intangivel', 420, '2025-07-01', 2),
	(21, 'Depreciação', 2460, '2025-07-01', 2),
	(22, 'Fornecimentos e Serviços Externos', 5537, '2025-07-01', 2),
	(23, 'Juros', 475, '2025-07-01', 2),
	(24, 'Pessoal', 1605, '2025-08-01', 2),
	(25, 'Ao Estado', 417, '2025-08-01', 2),
	(26, 'Depreciação', 2251, '2025-08-01', 2),
	(27, 'Fornecimentos e Serviços Externos', 4133, '2025-08-01', 2),
	(28, 'Juros', 470, '2025-08-01', 2),
	(29, 'Pessoal', 1617, '2025-09-01', 2),
	(30, 'Ao Estado', 417, '2025-09-01', 2),
	(31, 'Depreciação', 2251, '2025-09-01', 2),
	(32, 'Fornecimentos e Serviços Externos', 6039, '2025-09-01', 2),
	(33, 'Juros', 464, '2025-09-01', 2);

-- A despejar estrutura para tabela database_aio.depreciacao
CREATE TABLE IF NOT EXISTS `depreciacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ativo` int(11) DEFAULT NULL,
  `mes_referencia` date DEFAULT NULL,
  `valor_depreciacao` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_ativo` (`id_ativo`),
  CONSTRAINT `depreciacao_ibfk_1` FOREIGN KEY (`id_ativo`) REFERENCES `ativo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.depreciacao: ~15 rows (aproximadamente)
INSERT INTO `depreciacao` (`id`, `id_ativo`, `mes_referencia`, `valor_depreciacao`) VALUES
	(1, 1, '2025-07-01', 2430.9),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.disponibilidade_prestador: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.emprestimo
CREATE TABLE IF NOT EXISTS `emprestimo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mes` int(11) NOT NULL,
  `valor_prestacao` double NOT NULL,
  `juros` double NOT NULL,
  `amortizacao` double NOT NULL,
  `saldo_devedor` double NOT NULL,
  `data_prevista` date NOT NULL,
  `pago` tinyint(1) DEFAULT 0,
  `data_pagamento` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.emprestimo: ~82 rows (aproximadamente)
INSERT INTO `emprestimo` (`id`, `mes`, `valor_prestacao`, `juros`, `amortizacao`, `saldo_devedor`, `data_prevista`, `pago`, `data_pagamento`) VALUES
	(1, 1, 1500, 500, 1000, 99000, '2025-01-31', 1, '2025-10-20'),
	(2, 2, 1500, 495, 1005, 97995, '2025-02-28', 1, '2025-10-20'),
	(3, 3, 1500, 489.98, 1010.02, 96984.98, '2025-03-31', 1, '2025-10-20'),
	(4, 4, 1500, 484.92, 1015.08, 95969.9, '2025-04-30', 1, '2025-10-20'),
	(5, 5, 1500, 479.85, 1020.15, 94949.75, '2025-05-31', 1, '2025-10-20'),
	(6, 6, 1500, 474.75, 1025.25, 93924.5, '2025-06-30', 1, '2025-10-20'),
	(7, 7, 1500, 469.62, 1030.38, 92894.12, '2025-07-31', 1, '2025-10-20'),
	(8, 8, 1500, 464.47, 1035.53, 91858.59, '2025-08-31', 1, '2025-10-20'),
	(9, 9, 1500, 459.29, 1040.71, 90817.88, '2025-09-30', 1, '2025-10-20'),
	(10, 10, 1500, 454.09, 1045.91, 89771.97, '2025-10-31', 1, '2025-10-21'),
	(11, 11, 1500, 448.86, 1051.14, 88720.83, '2025-11-30', 1, '2025-10-21'),
	(12, 12, 1500, 443.6, 1056.4, 87664.44, '2025-12-31', 0, NULL),
	(13, 13, 1500, 438.32, 1061.68, 86602.76, '2026-01-31', 0, NULL),
	(14, 14, 1500, 433.01, 1066.99, 85535.77, '2026-02-28', 0, NULL),
	(15, 15, 1500, 427.68, 1072.32, 84463.45, '2026-03-31', 0, NULL),
	(16, 16, 1500, 422.32, 1077.68, 83385.77, '2026-04-30', 0, NULL),
	(17, 17, 1500, 416.93, 1083.07, 82302.7, '2026-05-31', 0, NULL),
	(18, 18, 1500, 411.51, 1088.49, 81214.21, '2026-06-30', 0, NULL),
	(19, 19, 1500, 406.07, 1093.93, 80120.28, '2026-07-31', 0, NULL),
	(20, 20, 1500, 400.6, 1099.4, 79020.88, '2026-08-31', 0, NULL),
	(21, 21, 1500, 395.1, 1104.9, 77915.99, '2026-09-30', 0, NULL),
	(22, 22, 1500, 389.58, 1110.42, 76805.57, '2026-10-31', 0, NULL),
	(23, 23, 1500, 384.03, 1115.97, 75689.6, '2026-11-30', 0, NULL),
	(24, 24, 1500, 378.45, 1121.55, 74568.04, '2026-12-31', 0, NULL),
	(25, 25, 1500, 372.84, 1127.16, 73440.88, '2027-01-31', 0, NULL),
	(26, 26, 1500, 367.2, 1132.8, 72308.09, '2027-02-28', 0, NULL),
	(27, 27, 1500, 361.54, 1138.46, 71169.63, '2027-03-31', 0, NULL),
	(28, 28, 1500, 355.85, 1144.15, 70025.48, '2027-04-30', 0, NULL),
	(29, 29, 1500, 350.13, 1149.87, 68875.61, '2027-05-31', 0, NULL),
	(30, 30, 1500, 344.38, 1155.62, 67719.98, '2027-06-30', 0, NULL),
	(31, 31, 1500, 338.6, 1161.4, 66558.58, '2027-07-31', 0, NULL),
	(32, 32, 1500, 332.79, 1167.21, 65391.38, '2027-08-31', 0, NULL),
	(33, 33, 1500, 326.96, 1173.04, 64218.33, '2027-09-30', 0, NULL),
	(34, 34, 1500, 321.09, 1178.91, 63039.42, '2027-10-31', 0, NULL),
	(35, 35, 1500, 315.2, 1184.8, 61854.62, '2027-11-30', 0, NULL),
	(36, 36, 1500, 309.27, 1190.73, 60663.9, '2027-12-31', 0, NULL),
	(37, 37, 1500, 303.32, 1196.68, 59467.21, '2028-01-31', 0, NULL),
	(38, 38, 1500, 297.34, 1202.66, 58264.55, '2028-02-29', 0, NULL),
	(39, 39, 1500, 291.32, 1208.68, 57055.87, '2028-03-31', 0, NULL),
	(40, 40, 1500, 285.28, 1214.72, 55841.15, '2028-04-30', 0, NULL),
	(41, 41, 1500, 279.21, 1220.79, 54620.36, '2028-05-31', 0, NULL),
	(42, 42, 1500, 273.1, 1226.9, 53393.46, '2028-06-30', 0, NULL),
	(43, 43, 1500, 266.97, 1233.03, 52160.43, '2028-07-31', 0, NULL),
	(44, 44, 1500, 260.8, 1239.2, 50921.23, '2028-08-31', 0, NULL),
	(45, 45, 1500, 254.61, 1245.39, 49675.84, '2028-09-30', 0, NULL),
	(46, 46, 1500, 248.38, 1251.62, 48424.22, '2028-10-31', 0, NULL),
	(47, 47, 1500, 242.12, 1257.88, 47166.34, '2028-11-30', 0, NULL),
	(48, 48, 1500, 235.83, 1264.17, 45902.17, '2028-12-31', 0, NULL),
	(49, 49, 1500, 229.51, 1270.49, 44631.68, '2029-01-31', 0, NULL),
	(50, 50, 1500, 223.16, 1276.84, 43354.84, '2029-02-28', 0, NULL),
	(51, 51, 1500, 216.77, 1283.23, 42071.61, '2029-03-31', 0, NULL),
	(52, 52, 1500, 210.36, 1289.64, 40781.97, '2029-04-30', 0, NULL),
	(53, 53, 1500, 203.91, 1296.09, 39485.88, '2029-05-31', 0, NULL),
	(54, 54, 1500, 197.43, 1302.57, 38183.31, '2029-06-30', 0, NULL),
	(55, 55, 1500, 190.92, 1309.08, 36874.23, '2029-07-31', 0, NULL),
	(56, 56, 1500, 184.37, 1315.63, 35558.6, '2029-08-31', 0, NULL),
	(57, 57, 1500, 177.79, 1322.21, 34236.39, '2029-09-30', 0, NULL),
	(58, 58, 1500, 171.18, 1328.82, 32907.57, '2029-10-31', 0, NULL),
	(59, 59, 1500, 164.54, 1335.46, 31572.11, '2029-11-30', 0, NULL),
	(60, 60, 1500, 157.86, 1342.14, 30229.97, '2029-12-31', 0, NULL),
	(61, 61, 1500, 151.15, 1348.85, 28881.12, '2030-01-31', 0, NULL),
	(62, 62, 1500, 144.41, 1355.59, 27525.52, '2030-02-28', 0, NULL),
	(63, 63, 1500, 137.63, 1362.37, 26163.15, '2030-03-31', 0, NULL),
	(64, 64, 1500, 130.82, 1369.18, 24793.97, '2030-04-30', 0, NULL),
	(65, 65, 1500, 123.97, 1376.03, 23417.94, '2030-05-31', 0, NULL),
	(66, 66, 1500, 117.09, 1382.91, 22035.03, '2030-06-30', 0, NULL),
	(67, 67, 1500, 110.18, 1389.82, 20645.2, '2030-07-31', 0, NULL),
	(68, 68, 1500, 103.23, 1396.77, 19248.43, '2030-08-31', 0, NULL),
	(69, 69, 1500, 96.24, 1403.76, 17844.67, '2030-09-30', 0, NULL),
	(70, 70, 1500, 89.22, 1410.78, 16433.89, '2030-10-31', 0, NULL),
	(71, 71, 1500, 82.17, 1417.83, 15016.06, '2030-11-30', 0, NULL),
	(72, 72, 1500, 75.08, 1424.92, 13591.14, '2030-12-31', 0, NULL),
	(73, 73, 1500, 67.96, 1432.04, 12159.1, '2031-01-31', 0, NULL),
	(74, 74, 1500, 60.8, 1439.2, 10719.9, '2031-02-28', 0, NULL),
	(75, 75, 1500, 53.6, 1446.4, 9273.5, '2031-03-31', 0, NULL),
	(76, 76, 1500, 46.37, 1453.63, 7819.86, '2031-04-30', 0, NULL),
	(77, 77, 1500, 39.1, 1460.9, 6358.96, '2031-05-31', 0, NULL),
	(78, 78, 1500, 31.79, 1468.21, 4890.76, '2031-06-30', 0, NULL),
	(79, 79, 1500, 24.45, 1475.55, 3415.21, '2031-07-31', 0, NULL),
	(80, 80, 1500, 17.08, 1482.92, 1932.29, '2031-08-31', 0, NULL),
	(81, 81, 1500, 9.66, 1490.34, 441.95, '2031-09-30', 0, NULL),
	(82, 82, 444.16, 2.21, 441.95, 0, '2031-10-31', 0, NULL);

-- A despejar estrutura para tabela database_aio.estado
CREATE TABLE IF NOT EXISTS `estado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.estado: ~16 rows (aproximadamente)
INSERT INTO `estado` (`id`, `descricao`) VALUES
	(1, 'Aguardando confirmação'),
	(2, 'Ativo'),
	(3, 'Bloqueado'),
	(4, 'Cancelado'),
	(5, 'Disponível'),
	(6, 'Em atraso'),
	(7, 'Em processamento'),
	(8, 'Entregue'),
	(9, 'Enviado'),
	(10, 'Esgotado'),
	(11, 'Inativo'),
	(12, 'Pago'),
	(13, 'Pedente'),
	(14, 'Suspenso'),
	(15, 'Confirmado'),
	(16, 'Concluido');

-- A despejar estrutura para tabela database_aio.evento
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
	(2, 'Entrega dos ivas', 'Autoridade Tributária', '2025-09-09 00:00:00', '2025-09-10 00:00:00', 0, 'Obrigações Declarativas', ''),
	(3, 'Entrega DMR', 'Autoridade Tributária', '2025-09-09 00:00:00', '2025-09-10 00:00:00', 0, 'Obrigações Declarativas', NULL),
	(4, 'Entrega DRI', 'Segurança Social', '2025-09-09 00:00:00', '2025-09-10 00:00:00', 0, 'Obrigações Declarativas', NULL);

-- A despejar estrutura para tabela database_aio.fornecedor
CREATE TABLE IF NOT EXISTS `fornecedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fornecedor` varchar(100) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `total_debito` double DEFAULT NULL,
  `total_credito` double DEFAULT NULL,
  `saldo` double DEFAULT NULL,
  `data` date DEFAULT NULL,
  `estado` enum('pendente','concluido') DEFAULT 'pendente',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.fornecedor: ~18 rows (aproximadamente)
INSERT INTO `fornecedor` (`id`, `fornecedor`, `descricao`, `total_debito`, `total_credito`, `saldo`, `data`, `estado`) VALUES
	(1, 'SP_Conecta Finanças-Contabilidade e Finanças', 'Contabilidade', 2214, 2214, 0, '2025-01-01', 'concluido'),
	(2, 'INSTITUTO DOS REGISTOS E DO NOTARIADO, I.P.', 'Cartório', 1350, 1350, 0, '2025-01-01', 'concluido'),
	(3, 'Digital Aurora', 'Marketing', 4920, 4920, 0, '2025-06-01', 'concluido'),
	(4, 'GGLE PORTUGAL, LDA', 'servico', 30.75, 30.75, 0, '2025-06-01', 'concluido'),
	(5, 'APPLE PORTUGAL, UNIPESSOAL, LDA', 'servico', 121.77, 121.77, 0, '2025-06-01', 'concluido'),
	(6, 'DMNS - DOMÍNIOS, S.A.', 'tecnologia', 36.9, 36.9, 0, '2025-06-01', 'concluido'),
	(7, 'FOUND UNITED MINDS, LDA', 'servico', 92.25, 92.25, 0, '2025-06-01', 'concluido'),
	(8, 'SP_Code Crafters Évora', 'APP', 49200, 0, 49200, '2025-06-01', 'concluido'),
	(9, 'SP_Tranquilidade', 'Seguros', 335, 335, 0, '2025-07-01', 'concluido'),
	(10, 'SP_MEO', 'Internet', 30.75, 30.75, 0, '2025-07-01', 'concluido'),
	(11, 'Digital Aurora', 'Marketing', 615, 615, 0, '2025-07-01', 'concluido'),
	(12, 'DMNS - DOMÍNIOS, S.A.', 'tecnologia', 64.21, 64.21, 0, '2025-07-01', 'concluido'),
	(13, 'SP_Worten', 'Computador', 738, 0, 738, '2025-07-01', 'pendente'),
	(14, 'SP_Invoice Xpress', 'software de faturação', 516.6, 516.6, 0, '2025-07-01', 'concluido'),
	(15, 'SP_MEO', 'Internet', 30.75, 30.75, 0, '2025-08-01', 'concluido'),
	(16, 'Digital Aurora', 'Marketing', 615, 615, 0, '2025-08-01', 'concluido'),
	(17, 'SP_MEO', 'Internet', 0, 30.75, -30.75, '2025-09-01', 'pendente'),
	(18, 'Digital Aurora', 'Marketing', 615, 615, 0, '2025-09-01', 'concluido');

-- A despejar estrutura para tabela database_aio.funcao
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

-- A despejar estrutura para tabela database_aio.habito_diario
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

-- A despejar estrutura para tabela database_aio.imposto
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

-- A despejar estrutura para tabela database_aio.nivel_atividade
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

-- A despejar estrutura para tabela database_aio.objetivo
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
CREATE TABLE IF NOT EXISTS `obrigacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_obrigacao` int(11) DEFAULT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `valor` double DEFAULT NULL,
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
	(1, 2, 'Pagamento-SP_MEO', 30.75, '2025-09-30', '2025-09-30', 13),
	(2, 2, 'Pagamento-SP_Digital Aurora', 500, '2025-09-30', '2025-09-30', 13),
	(3, 3, 'Pagamento-SP_Joana Freitas', 1699.55, '2025-09-30', '2025-09-30', 13),
	(4, 3, 'Pagamento-SP_Maria Beatriz Martins', 1012.5, '2025-09-30', '2025-09-30', 13),
	(5, 3, 'Pagamento-SP_Joao Ferreira', 982.5, '2025-09-30', '2025-09-30', 13),
	(6, 3, 'Pagamento-SP_Guilherme Sousa', 1602, '2025-09-30', '2025-09-30', 13),
	(7, 3, 'Pagamento-SP_Ana Sofia Marques', 1602, '2025-09-30', '2025-09-30', 13),
	(8, 3, 'Pagamento-SP_Lucia Mendes', 315, '2025-09-30', '2025-09-30', 13),
	(9, 4, 'Pagamento-IVA', 2000, '2025-09-20', '2025-09-20', 13),
	(10, 4, 'Pagamento-DMR', 100, '2025-09-20', '2025-09-20', 13),
	(11, 4, 'Pagamento-DRI', 89, '2025-09-20', '2025-09-20', 13);

-- A despejar estrutura para tabela database_aio.rh
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
  CONSTRAINT `rh_ibfk_2` FOREIGN KEY (`id_funcao`) REFERENCES `tipo_user` (`id`),
  CONSTRAINT `rh_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.rh: ~2 rows (aproximadamente)
INSERT INTO `rh` (`codigo`, `id_utilizador`, `nome_completo`, `nif`, `contacto`, `id_funcao`, `qualificacao`, `experiencia_anos`, `id_tipo_contrato`, `id_estado`, `contrato`, `recibo`, `data_contratacao`) VALUES
	(1, 2, 'José Lopes', '297678788', '964274942', 1, 'Licenciatura em Gestão', 10, 6, 2, 'src/uploads/1762769776_Contrato_José_Lopes.pdf', '', '2025-11-10'),
	(2, 3, 'Filipe Pimentel', '273082868', '962325678', 3, 'Licenciatura em Educação Fisica', 2, 2, 2, 'src/uploads/1762770075_Contrato_Filipe_Pimentel.pdf', '', '2025-11-10');

-- A despejar estrutura para tabela database_aio.salario
CREATE TABLE IF NOT EXISTS `salario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_rh` int(11) DEFAULT NULL,
  `id_funcao` int(11) DEFAULT NULL,
  `salario_bruto` double DEFAULT NULL,
  `irs` double DEFAULT NULL,
  `ss` double DEFAULT NULL,
  `salario_liquido` double DEFAULT NULL,
  `subsidio_alimentacao` double DEFAULT NULL,
  `subsidio_ferias` double DEFAULT NULL,
  `subsidio_natal` double DEFAULT NULL,
  `salario_total` double DEFAULT NULL,
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
	(1, 16, 1, 1992.55, 158.4, 132, 1699.55, 132, 0, 0, 1185.37, '2025-07-31', 12),
	(2, 1, 1, 1320, 158.4, 132, 1188, 132, 0, 0, 1185.37, '2025-08-31', 12),
	(3, 1, 1, 1332, 158.4, 132, 1200, 132, 0, 0, 1185.37, '2025-09-30', 13);

-- A despejar estrutura para tabela database_aio.servico
CREATE TABLE IF NOT EXISTS `servico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  `preco` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.servico: ~9 rows (aproximadamente)
INSERT INTO `servico` (`id`, `descricao`, `preco`) VALUES
	(1, 'PLANO BASICO', 0),
	(2, 'PLANO MÉDIO', 70),
	(3, 'PLANO PRO', 160),
	(4, 'PLANO DUO', 110),
	(5, 'PACK LAR ', 500),
	(6, 'PSICOLOGIA', 60),
	(7, 'NUTRIÇÃO', 50),
	(8, 'PT', 40),
	(9, 'AULA DE GRUPO', 12);

-- A despejar estrutura para tabela database_aio.tipo_ativo
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
CREATE TABLE IF NOT EXISTS `tipo_aula_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `nivel_dificuldade` enum('Iniciante','Intermédio','Avançado') DEFAULT 'Iniciante',
  `duracao_minutos` int(3) DEFAULT NULL,
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
CREATE TABLE IF NOT EXISTS `tipo_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.tipo_user: ~3 rows (aproximadamente)
INSERT INTO `tipo_user` (`id`, `nome`) VALUES
	(1, 'Admin'),
	(2, 'Prestador'),
	(3, 'Cliente');

-- A despejar estrutura para tabela database_aio.utilizador
CREATE TABLE IF NOT EXISTS `utilizador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_tipo_user` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `data_registo` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `id_tipo_user` (`id_tipo_user`),
  CONSTRAINT `utilizador_ibfk_1` FOREIGN KEY (`id_tipo_user`) REFERENCES `tipo_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.utilizador: ~3 rows (aproximadamente)
INSERT INTO `utilizador` (`id`, `username`, `email`, `password`, `id_tipo_user`, `foto`, `data_registo`) VALUES
	(1, 'Admin', 'admin@gmail.com', '$2y$10$vWiS7Q8ZMpez9OkrKrihoOfJX3fb1byovlLfL9/x7Y5KZ0W2jf3oq', 1, NULL, '2025-11-10 10:02:45'),
	(2, 'JLopes', 'jsilvalopes84@gmail.com', '$2y$10$TFGqTTpWvSfp3sQMuALda.0N4ydNPhafczZlXJbtR/6YV46/hTDKG', 1, NULL, '2025-11-10 10:16:16'),
	(3, 'FPimentel', 'filipemtp2005@gmail.com', '$2y$10$n6ZNwQqV0oVsvdzOyyWx9OhYvsFM5qFhCBg1v3Vs2ZBBFq/4wa2Ce', 2, NULL, '2025-11-10 10:21:15');

-- A despejar estrutura para tabela database_aio.venda
CREATE TABLE IF NOT EXISTS `venda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_cliente` int(11) DEFAULT NULL,
  `id_servico` int(11) DEFAULT NULL,
  `valor` double DEFAULT NULL,
  `data_venda` date DEFAULT NULL,
  `fatura` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `codigo_cliente` (`codigo_cliente`),
  KEY `id_servico` (`id_servico`),
  CONSTRAINT `venda_ibfk_1` FOREIGN KEY (`codigo_cliente`) REFERENCES `cliente` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `venda_ibfk_2` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=519 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.venda: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
