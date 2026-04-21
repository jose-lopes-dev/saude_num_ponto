-- --------------------------------------------------------
-- Anfitrião:                    127.0.0.1
-- Versão do servidor:           8.4.3 - MySQL Community Server - GPL
-- SO do servidor:               Win64
-- HeidiSQL Versão:              12.13.0.7147
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
CREATE DATABASE IF NOT EXISTS `database_aio` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `database_aio`;

-- A despejar estrutura para tabela database_aio.admin
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_utilizador` int NOT NULL,
  `nome_completo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nif` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contacto` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_registo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_admin_utilizador` (`id_utilizador`),
  CONSTRAINT `fk_admin_utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.admin: ~1 rows (aproximadamente)
INSERT INTO `admin` (`id`, `id_utilizador`, `nome_completo`, `nif`, `contacto`, `data_registo`) VALUES
	(1, 1, 'Administrador do Sistema', '999999999', '910000000', '2026-01-04 20:54:46');

-- A despejar estrutura para tabela database_aio.area_corpo
CREATE TABLE IF NOT EXISTS `area_corpo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.area_corpo: ~3 rows (aproximadamente)
INSERT INTO `area_corpo` (`id`, `nome`) VALUES
	(1, 'Abdómen'),
	(2, 'Braços e peito'),
	(3, 'Pernas');

-- A despejar estrutura para tabela database_aio.atividades
CREATE TABLE IF NOT EXISTS `atividades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.atividades: ~7 rows (aproximadamente)
INSERT INTO `atividades` (`id`, `nome`, `descricao`) VALUES
	(1, 'Fitness em casa', 'Com utilização mínima de equipamento'),
	(2, 'Calistenia', 'Treino muscular com o peso corporal'),
	(3, 'Caminhar', 'Caminhada intervalada guiada'),
	(4, 'Correr', 'Corrida intervalada guiada'),
	(5, 'HIIT', 'Treinos rápidos e intensivos'),
	(6, 'Ioga', 'Movimentos e respiração conscientes'),
	(8, 'Ginásio', 'Inclua pesos e máquinas');

-- A despejar estrutura para tabela database_aio.ativo
CREATE TABLE IF NOT EXISTS `ativo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_categoria` int DEFAULT NULL,
  `valor_inicial` decimal(10,2) DEFAULT NULL,
  `data_aquisicao` date DEFAULT NULL,
  `vida_util_meses` int DEFAULT NULL,
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
CREATE TABLE IF NOT EXISTS `aula` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `data_inicio` datetime NOT NULL,
  `duracao_min` int NOT NULL DEFAULT '60',
  `limite_participantes` int NOT NULL DEFAULT '10',
  `nivel` enum('Iniciante','Intermédio','Avançado') COLLATE utf8mb4_unicode_ci DEFAULT 'Iniciante',
  `preco` decimal(10,2) DEFAULT '0.00',
  `id_pt` int DEFAULT NULL,
  `sala_virtual_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_estado` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `sala_nome` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sala_ativa` tinyint(1) DEFAULT '0',
  `sala_inicio` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pt` (`id_pt`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `aula_ibfk_1` FOREIGN KEY (`id_pt`) REFERENCES `utilizador` (`id`),
  CONSTRAINT `aula_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.aula: ~6 rows (aproximadamente)
INSERT INTO `aula` (`id`, `titulo`, `descricao`, `data_inicio`, `duracao_min`, `limite_participantes`, `nivel`, `preco`, `id_pt`, `sala_virtual_url`, `id_estado`, `created_at`, `updated_at`, `sala_nome`, `sala_ativa`, `sala_inicio`) VALUES
	(8, 'Calistenia', 'Treino focado no uso do peso do próprio corpo para desenvolver força, resistência, mobilidade e controlo corporal. Indicado para quem procura ganhar força funcional e melhorar a postura.  Equipamentos necessários:   Tapete de exercício (recomendado) ; Barra fixa ou apoio estável (opcional) ; Não requer pesos.', '2026-02-06 11:30:00', 50, 20, 'Iniciante', 12.00, 20, '', 2, '2025-12-16 13:35:39', '2026-02-03 09:54:32', 'SPN-Aula-8-Calestenia', 0, NULL),
	(9, 'Cardio', 'Aula focada em exercícios contínuos para melhorar a resistência cardiovascular, a respiração e a saúde do coração. Ritmo acessível e adaptável a diferentes níveis de condição física.  Equipamentos necessários : Não é necessário equipamento ;  Ténis confortáveis ;  Garrafa de água', '2026-02-07 09:00:00', 45, 10, 'Avançado', 12.00, 20, '', 2, '2025-12-16 13:37:36', '2026-02-02 22:03:37', 'SPN-Aula-9-Cardio', 0, NULL),
	(10, 'Zumba', 'Aula dinâmica e divertida que combina dança e exercício cardiovascular ao som de ritmos latinos e internacionais. Ideal para queimar calorias, melhorar a coordenação e aumentar a energia de forma leve e motivadora.  Equipamentos necessários:   Não é necessário equipamento ;  Roupa confortável ; Ténis adequados para dança/exercício.', '2026-02-09 10:00:00', 50, 10, 'Intermédio', 12.00, 20, '', 2, '2025-12-22 17:02:52', '2026-02-02 22:05:17', 'SPN-Aula-10-Zumba', 0, NULL),
	(11, 'HIIT', 'Treino de alta intensidade com exercícios curtos e explosivos intercalados com períodos de descanso. Excelente para melhorar o condicionamento físico, acelerar o metabolismo e maximizar resultados em pouco tempo.  Equipamentos necessários:  Tapete de exercício (recomendado);  Halteres leves ou garrafas de água (opcional) ; Pode ser feito apenas com o peso do corpo.', '2026-02-10 18:00:00', 50, 10, 'Intermédio', 12.00, 20, NULL, 2, '2026-01-28 00:31:59', '2026-02-02 22:05:46', 'SPN-Aula-11-HIIT', 0, NULL),
	(12, 'Yoga Funcional', 'Aula focada no equilíbrio entre corpo e mente, combinando posturas de yoga com movimentos funcionais. Ajuda a melhorar a flexibilidade, a força, a postura e a respiração, promovendo bem-estar físico e mental.  Equipamentos necessários: Tapete de yoga ou exercício ; Roupa confortável ; Não é necessário equipamento adicional.', '2026-02-11 08:00:00', 45, 10, 'Iniciante', 12.00, 17, NULL, 2, '2026-01-28 08:10:48', '2026-02-02 22:06:27', 'SNP-Aula-12-Yoga-Funcional', 0, NULL),
	(13, 'Treino Funcional (Body Workout)', 'Aula de grupo com exercícios variados que trabalham todo o corpo, combinando força, resistência e coordenação. Ideal para quem procura um treino completo, dinâmico e adaptável a diferentes níveis.  Equipamentos necessários:  Tapete de exercício; Halteres leves ou bandas elásticas (opcional); Pode ser realizado apenas com o peso do corpo.', '2026-02-12 19:00:00', 50, 10, 'Avançado', 12.00, 20, NULL, 2, '2026-01-28 08:23:31', '2026-02-02 22:06:44', 'SNP-Aula-13-Treino-Funcional', 0, NULL);

-- A despejar estrutura para tabela database_aio.calendario_cliente
CREATE TABLE IF NOT EXISTS `calendario_cliente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int NOT NULL,
  `id_utilizador` int NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime NOT NULL,
  `categoria` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `localizacao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `concluido` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`id_utilizador`),
  KEY `idx_calendario_cliente_id_cliente` (`id_cliente`),
  CONSTRAINT `fk_calendario_cliente_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.calendario_cliente: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.calendario_nutricionista
CREATE TABLE IF NOT EXISTS `calendario_nutricionista` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo_rh` int NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime NOT NULL,
  `categoria` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `localizacao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `concluido` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_rh` (`codigo_rh`),
  CONSTRAINT `fk_cal_nutricionista` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.calendario_nutricionista: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.calendario_psicologo
CREATE TABLE IF NOT EXISTS `calendario_psicologo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo_rh` int NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime NOT NULL,
  `categoria` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `localizacao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `concluido` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_rh` (`codigo_rh`) USING BTREE,
  CONSTRAINT `fk_cal_psicologo` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.calendario_psicologo: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.carrinho
CREATE TABLE IF NOT EXISTS `carrinho` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int NOT NULL,
  `id_produto` int NOT NULL,
  `quantidade` int DEFAULT '1',
  `data_adicao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_produto` (`id_produto`),
  CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`codigo`),
  CONSTRAINT `carrinho_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produto_marketplace` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.carrinho: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.chat
CREATE TABLE IF NOT EXISTS `chat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_remetente` int NOT NULL,
  `id_destinatario` int NOT NULL,
  `mensagem` text COLLATE utf8mb4_general_ci NOT NULL,
  `data_envio` datetime DEFAULT CURRENT_TIMESTAMP,
  `lida` tinyint(1) DEFAULT '0',
  `apagada_remetente` tinyint(1) DEFAULT '0',
  `apagada_destinatario` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_remetente` (`id_remetente`),
  KEY `id_destinatario` (`id_destinatario`),
  CONSTRAINT `fk_chat_destinatario` FOREIGN KEY (`id_destinatario`) REFERENCES `utilizador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_chat_remetente` FOREIGN KEY (`id_remetente`) REFERENCES `utilizador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela database_aio.chat: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.cliente
CREATE TABLE IF NOT EXISTS `cliente` (
  `codigo` int NOT NULL AUTO_INCREMENT,
  `id_utilizador` int NOT NULL,
  `nome_completo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contacto` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nif` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `perfil_completo` tinyint(1) NOT NULL DEFAULT '0',
  `genero` enum('Masculino','Feminino','Outro') COLLATE utf8mb4_unicode_ci NOT NULL,
  `altura` double DEFAULT NULL,
  `peso` double DEFAULT NULL,
  `peso_pretendido` double DEFAULT NULL,
  `id_objetivo` int DEFAULT NULL,
  `id_nivel` int DEFAULT NULL,
  `id_atividades` int DEFAULT NULL,
  `id_tipo_corpo` int DEFAULT NULL,
  `id_habito_diario` int DEFAULT NULL,
  `id_area_corpo` int DEFAULT NULL,
  `id_tipo_dieta` int DEFAULT NULL,
  `data_inicio` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_estado` int DEFAULT '1',
  `id_condicao_saude` int DEFAULT NULL,
  `id_frequencia` int DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.cliente: ~37 rows (aproximadamente)
INSERT INTO `cliente` (`codigo`, `id_utilizador`, `nome_completo`, `contacto`, `nif`, `data_nascimento`, `perfil_completo`, `genero`, `altura`, `peso`, `peso_pretendido`, `id_objetivo`, `id_nivel`, `id_atividades`, `id_tipo_corpo`, `id_habito_diario`, `id_area_corpo`, `id_tipo_dieta`, `data_inicio`, `id_estado`, `id_condicao_saude`, `id_frequencia`) VALUES
	(1, 4, 'Álvaro Almeida', '910123456', '210892153', '1990-05-12', 0, 'Masculino', 1.8, 80, 75, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 2, 15, NULL),
	(2, 5, 'Vera Gonçalves', '910987654', '238967093', '1992-03-25', 0, 'Feminino', 1.68, 62, 58, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 1, 15, NULL),
	(3, 6, 'César Sá', '911234567', '236322915', '1988-11-08', 0, 'Masculino', 1.75, 78, 72, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 2, 15, NULL),
	(4, 7, 'Vicente Amorim-Leal', '924567890', '215540352', '1991-07-19', 0, 'Masculino', 1.82, 85, 80, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 1, 15, NULL),
	(5, 8, 'Iara Barbosa', '911876543', '205490000', '1993-09-14', 0, 'Feminino', 1.65, 60, 55, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 1, 15, NULL),
	(6, 9, 'Flor Fonseca-Soares', '924321098', '269544577', '1995-12-02', 0, 'Feminino', 1.7, 68, 60, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 1, 15, NULL),
	(7, 10, 'Naiara Magalhães', '912345678', '269911871', '1996-01-22', 0, 'Feminino', 1.64, 59, 54, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 2, 15, NULL),
	(8, 11, 'Frederico Lopes Miranda', '912345679', '269911872', '1989-04-18', 0, 'Masculino', 1.83, 88, 80, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 1, 15, NULL),
	(9, 12, 'Soraia Maia', '912789012', '245558705', '1994-10-30', 0, 'Feminino', 1.67, 65, 58, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 1, 15, NULL),
	(10, 13, 'Isabel Neto', '925678901', '274412551', '1990-08-07', 0, 'Feminino', 1.66, 63, 58, 1, 1, 1, 1, 1, 1, 1, '2025-11-10 00:00:00', 1, 15, NULL),
	(34, 54, 'Filipe', '895644564', '534534534', '2005-12-18', 1, 'Masculino', 1.8, 109, 80, 2, 2, 1, 2, 1, 2, 1, '2025-12-17 14:39:46', 2, 15, NULL),
	(36, 69, 'Ana Flor', '964588888', '236555555', '1987-02-14', 1, 'Feminino', 1.56, 98, 70, 1, 1, 6, 3, 2, 1, 7, '2026-02-03 00:25:02', 2, 15, NULL),
	(37, 70, 'Paulo Jorge', '986555222', '236599855', '2000-12-23', 1, 'Outro', 1.78, 60, 70, 2, 1, 4, 1, 4, 2, 2, '2026-02-03 00:42:26', 2, 15, NULL),
	(38, 71, 'Carlos Santos', '965832545', '235699985', '2002-11-15', 1, 'Masculino', 1.7, 105, 85, 1, 1, 1, 3, 1, 1, 10, '2026-02-03 00:46:48', 2, 1, NULL),
	(39, 72, 'Miriam Sofia', '965322253', '256833225', '1983-08-03', 1, 'Feminino', 1.84, 60, 70, 3, 2, 6, 1, 1, 3, 1, '2026-02-03 00:49:36', 2, 8, NULL),
	(40, 73, 'Ilsa Ailine', '965325552', '123665522', '1991-03-27', 1, 'Feminino', 1.76, 65, 70, 3, 1, 3, 1, 1, 3, 1, '2026-02-03 00:52:23', 2, 15, NULL),
	(41, 74, 'Edmar Lopes', '965325222', '233665555', '2007-02-12', 1, 'Masculino', 1.79, 80, 85, 3, 1, 4, 2, 1, 1, 7, '2026-02-03 00:56:19', 2, 15, NULL),
	(42, 75, 'Felix Araujo', '856522222', '365325555', '1982-04-25', 1, 'Masculino', 1.56, 98, 75, 1, 1, 3, 3, 4, 1, 1, '2026-02-03 00:59:45', 2, 15, NULL),
	(43, 76, 'Kevin Almeida', '985652355', '236656632', '2023-06-09', 1, 'Masculino', 1.62, 50, 65, 3, 1, 1, 2, 2, 1, 5, '2026-02-03 01:02:11', 2, 15, NULL),
	(44, 77, 'Pedro Rocha', '854222222', '326555555', '1998-02-20', 1, 'Masculino', 1.92, 75, 95, 2, 2, 3, 2, 2, 1, 4, '2026-02-03 01:04:32', 2, 7, NULL),
	(45, 78, 'Elsa Gomes', '856522222', '265966652', '1993-05-12', 1, 'Feminino', 1.7, 90, 80, 1, 1, 3, 2, 1, 3, 4, '2026-02-03 01:10:18', 2, 5, NULL),
	(46, 79, 'Bruno Costa', '563322222', '236555211', '1995-07-15', 1, 'Masculino', 1.82, 98, 75, 1, 1, 5, 1, 2, 1, 5, '2026-02-03 02:14:33', 2, 7, NULL),
	(47, 80, 'Fabio Pires', '965533222', '236563255', '1996-03-12', 1, 'Masculino', 1.81, 85, 80, 1, 1, 5, 1, 1, 2, 7, '2026-02-03 02:17:32', 2, 15, NULL),
	(48, 81, 'Marcia Fonseca', '965332333', '236523325', '1991-06-11', 1, 'Feminino', 1.75, 80, 70, 1, 1, 4, 1, 3, 2, 7, '2026-02-03 02:20:30', 2, 15, NULL),
	(49, 82, 'Marvin Lima', '965332233', '236569955', '1989-12-12', 1, 'Masculino', 1.9, 90, 95, 2, 2, 4, 1, 3, 2, 4, '2026-02-03 02:23:13', 2, 15, NULL),
	(50, 83, 'Helder Figueredo', '956235222', '236563322', '2006-03-12', 1, 'Masculino', 1.78, 89, 80, 1, 2, 2, 2, 3, 3, 7, '2026-02-03 02:26:24', 2, 15, NULL),
	(51, 84, 'Mariana Silva', '965323322', '632566655', '2009-02-06', 1, 'Feminino', 1.6, 55, 60, 3, 1, 1, 1, 2, 2, 7, '2026-02-03 02:28:47', 2, 15, NULL),
	(52, 85, 'Edla Maria', '956222222', '236595322', '1989-04-25', 1, 'Feminino', 1.78, 90, 80, 1, 1, 5, 1, 2, 1, 5, '2026-02-03 02:30:53', 2, 15, NULL),
	(53, 86, 'Cristiano Soares', '523622322', '236556222', '2000-09-07', 1, 'Outro', 1.89, 98, 90, 1, 1, 3, 1, 4, 2, 5, '2026-02-03 02:33:02', 2, 15, NULL),
	(54, 87, 'Sonia Lima', '965332222', '265523322', '1985-06-15', 1, 'Feminino', 1.69, 98, 90, 1, 2, 8, 2, 3, 2, 2, '2026-02-03 02:35:29', 2, 15, NULL),
	(55, 88, 'David Miranda', '965323232', '236595633', '2003-02-23', 1, 'Masculino', 1.8, 85, 90, 2, 2, 8, 2, 4, 2, 7, '2026-02-03 02:37:33', 2, 8, NULL),
	(56, 89, 'Tiago Silves', '963256323', '362662233', '2005-10-12', 1, 'Masculino', 1.75, 78, 90, 3, 2, 4, 1, 3, 2, 1, '2026-02-03 02:39:41', 2, 15, NULL),
	(57, 90, 'Antony Sudakov', '956323223', '236223322', '2006-03-12', 1, 'Masculino', 1.8, 78, 80, 2, 2, 6, 2, 3, 2, 7, '2026-02-03 02:41:58', 2, 7, NULL),
	(58, 91, 'Gonçalo Fortes', '965856222', '236552222', '2003-08-07', 1, 'Masculino', 1.78, 85, 90, 2, 1, 5, 2, 3, 2, 7, '2026-02-03 02:45:16', 2, 15, NULL),
	(59, 92, 'David Perez', '956666666', '236522332', '2003-12-15', 1, 'Masculino', 1.79, 70, 80, 2, 2, 6, 2, 4, 3, 2, '2026-02-03 02:48:15', 2, 15, NULL),
	(60, 93, 'Romeu Kirdov', '965622222', '223655623', '2005-11-29', 1, 'Masculino', 1.75, 95, 85, 1, 2, 5, 2, 3, 2, 7, '2026-02-03 02:52:44', 2, 15, NULL),
	(61, 94, 'Marcio Pereira', '965623256', '123252266', '2005-03-15', 1, 'Masculino', 1.75, 78, 90, 2, 2, 6, 2, 3, 2, 7, '2026-02-03 02:55:45', 2, 15, NULL);

-- A despejar estrutura para tabela database_aio.cliente_condicao
CREATE TABLE IF NOT EXISTS `cliente_condicao` (
  `codigo_cliente` int NOT NULL,
  `id_condicao` int NOT NULL,
  `outra_condicao` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`codigo_cliente`,`id_condicao`),
  KEY `id_condicao` (`id_condicao`),
  CONSTRAINT `cliente_condicao_ibfk_1` FOREIGN KEY (`codigo_cliente`) REFERENCES `cliente` (`codigo`),
  CONSTRAINT `cliente_condicao_ibfk_2` FOREIGN KEY (`id_condicao`) REFERENCES `condicao_saude` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.cliente_condicao: ~7 rows (aproximadamente)
INSERT INTO `cliente_condicao` (`codigo_cliente`, `id_condicao`, `outra_condicao`) VALUES
	(1, 1, NULL),
	(2, 2, NULL),
	(4, 15, ''),
	(5, 2, NULL),
	(5, 15, NULL),
	(6, 15, ''),
	(7, 15, NULL);

-- A despejar estrutura para tabela database_aio.cliente_plano
CREATE TABLE IF NOT EXISTS `cliente_plano` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo_cliente` int NOT NULL,
  `id_servico` int NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  `renovacao_automatica` tinyint(1) DEFAULT '0',
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `codigo_cliente` (`codigo_cliente`),
  KEY `id_servico` (`id_servico`),
  CONSTRAINT `cliente_plano_ibfk_1` FOREIGN KEY (`codigo_cliente`) REFERENCES `cliente` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cliente_plano_ibfk_2` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.cliente_plano: ~40 rows (aproximadamente)
INSERT INTO `cliente_plano` (`id`, `codigo_cliente`, `id_servico`, `data_inicio`, `data_fim`, `ativo`, `renovacao_automatica`, `criado_em`, `atualizado_em`) VALUES
	(1, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 01:43:57', '2026-01-22 01:47:43'),
	(2, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 01:47:43', '2026-01-22 01:57:34'),
	(3, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 01:57:34', '2026-01-22 02:06:45'),
	(4, 34, 2, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:06:45', '2026-01-22 02:07:08'),
	(5, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:07:08', '2026-01-22 02:07:10'),
	(6, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:07:10', '2026-01-22 02:07:12'),
	(7, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:07:12', '2026-01-22 02:13:55'),
	(8, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:13:55', '2026-01-22 02:17:52'),
	(9, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:17:52', '2026-01-22 02:18:06'),
	(10, 34, 2, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:18:06', '2026-01-22 02:23:04'),
	(11, 34, 2, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:23:04', '2026-01-22 02:30:49'),
	(12, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:30:49', '2026-01-22 02:31:36'),
	(13, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:31:36', '2026-01-22 02:35:52'),
	(14, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:35:52', '2026-01-22 02:40:45'),
	(15, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:40:45', '2026-01-22 02:41:41'),
	(16, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:41:41', '2026-01-22 02:41:51'),
	(17, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:41:51', '2026-01-22 02:43:05'),
	(18, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:43:05', '2026-01-22 02:43:26'),
	(19, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:43:26', '2026-01-22 02:52:59'),
	(20, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:52:59', '2026-01-22 02:54:42'),
	(21, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:54:42', '2026-01-22 02:55:30'),
	(22, 34, 3, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:55:30', '2026-01-22 02:55:46'),
	(23, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:55:46', '2026-01-22 02:59:01'),
	(24, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:59:01', '2026-01-22 02:59:16'),
	(25, 34, 2, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 02:59:16', '2026-01-22 03:00:19'),
	(26, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 03:00:19', '2026-01-22 03:02:04'),
	(27, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 03:02:04', '2026-01-22 03:04:31'),
	(28, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 03:04:31', '2026-01-22 03:08:26'),
	(29, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 03:08:26', '2026-01-22 03:08:27'),
	(30, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 03:08:27', '2026-01-22 03:08:40'),
	(31, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 03:08:40', '2026-01-22 03:09:08'),
	(32, 34, 2, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 03:09:08', '2026-01-22 03:09:53'),
	(33, 34, 2, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 03:09:53', '2026-01-22 03:10:23'),
	(34, 34, 2, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 03:10:23', '2026-01-22 03:13:28'),
	(35, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 03:13:28', '2026-01-22 03:14:50'),
	(36, 34, 2, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 03:14:50', '2026-01-22 03:55:03'),
	(37, 34, 2, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 03:55:03', '2026-01-22 03:55:03'),
	(38, 34, 1, '2026-01-22', '2026-02-22', 0, 0, '2026-01-22 12:43:41', '2026-01-27 20:04:42'),
	(41, 34, 2, '2026-01-27', '2026-02-27', 1, 0, '2026-01-27 20:04:42', '2026-01-27 20:04:42'),
	(42, 55, 2, '2026-02-03', '2026-03-03', 1, 0, '2026-02-03 16:39:16', '2026-02-03 16:39:16');

-- A despejar estrutura para tabela database_aio.comissao
CREATE TABLE IF NOT EXISTS `comissao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo_rh` int DEFAULT NULL,
  `id_funcao` int DEFAULT NULL,
  `numero_consultas` int DEFAULT NULL,
  `total_pagar` decimal(10,2) DEFAULT NULL,
  `id_estado` int DEFAULT NULL,
  `data_prevista` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_funcao` (`id_funcao`),
  KEY `codigo_rh` (`codigo_rh`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `comissao_ibfk_1` FOREIGN KEY (`id_funcao`) REFERENCES `funcao` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `comissao_ibfk_2` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `comissao_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.comissao: ~16 rows (aproximadamente)
INSERT INTO `comissao` (`id`, `codigo_rh`, `id_funcao`, `numero_consultas`, `total_pagar`, `id_estado`, `data_prevista`) VALUES
	(1, 21, 3, 30, 840.00, 13, '2025-11-30'),
	(2, 23, 2, 22, 770.00, 13, '2025-11-30'),
	(3, 21, 3, 36, 1008.00, 13, '2025-12-31'),
	(4, 23, 2, 25, 875.00, 13, '2025-12-31'),
	(5, 21, 3, 40, 1120.00, 13, '2026-01-31'),
	(6, 23, 2, 28, 980.00, 13, '2026-01-31'),
	(7, 16, 3, 12, 336.00, 12, '2025-11-30'),
	(8, 17, 2, 8, 280.00, 13, '2025-11-30'),
	(9, 18, 4, 6, 252.00, 13, '2025-11-30'),
	(12, 16, 3, 15, 420.00, 12, '2025-12-31'),
	(13, 17, 2, 10, 350.00, 13, '2025-12-31'),
	(14, 18, 4, 7, 294.00, 13, '2025-12-31'),
	(17, 16, 3, 18, 504.00, 12, '2026-01-31'),
	(18, 17, 2, 12, 420.00, 13, '2026-01-31'),
	(19, 18, 4, 9, 378.00, 13, '2026-01-31'),
	(20, 21, 3, 15, 728.00, 12, '2026-02-03');

-- A despejar estrutura para tabela database_aio.comissao_consulta
CREATE TABLE IF NOT EXISTS `comissao_consulta` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_consulta` int NOT NULL,
  `codigo_rh` int NOT NULL,
  `percentagem` int NOT NULL DEFAULT '70',
  `valor_pago` decimal(10,2) NOT NULL DEFAULT '0.00',
  `valor_comissao` decimal(10,2) NOT NULL DEFAULT '0.00',
  `id_estado` int NOT NULL DEFAULT '13',
  `data_pagamento` datetime DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_consulta` (`id_consulta`),
  KEY `codigo_rh` (`codigo_rh`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `cc_ibfk_1` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cc_ibfk_2` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `cc_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.comissao_consulta: ~15 rows (aproximadamente)
INSERT INTO `comissao_consulta` (`id`, `id_consulta`, `codigo_rh`, `percentagem`, `valor_pago`, `valor_comissao`, `id_estado`, `data_pagamento`, `criado_em`) VALUES
	(1, 33, 21, 70, 50.00, 35.00, 12, '2025-12-17 00:21:20', '2025-12-17 00:19:52'),
	(2, 64, 21, 70, 40.00, 28.00, 13, NULL, '2026-02-04 16:13:15'),
	(3, 81, 21, 70, 40.00, 28.00, 13, NULL, '2026-02-04 16:13:15'),
	(4, 110, 21, 70, 40.00, 28.00, 13, NULL, '2026-02-04 16:13:15'),
	(5, 112, 21, 70, 40.00, 28.00, 13, NULL, '2026-02-04 16:13:15'),
	(6, 114, 21, 70, 40.00, 28.00, 13, NULL, '2026-02-04 16:13:15'),
	(9, 44, 23, 70, 50.00, 35.00, 12, '2026-02-05 18:08:30', '2026-02-04 16:18:31'),
	(10, 65, 23, 70, 50.00, 35.00, 12, '2026-02-04 18:12:12', '2026-02-04 16:18:31'),
	(11, 66, 23, 70, 50.00, 35.00, 12, '2026-02-04 18:11:47', '2026-02-04 16:18:31'),
	(12, 69, 23, 70, 50.00, 35.00, 12, '2026-02-04 18:11:54', '2026-02-04 16:18:31'),
	(13, 82, 23, 70, 50.00, 35.00, 12, '2026-02-04 18:12:19', '2026-02-04 16:18:31'),
	(14, 83, 23, 70, 50.00, 35.00, 12, '2026-02-04 18:12:06', '2026-02-04 16:18:31'),
	(15, 111, 23, 70, 50.00, 35.00, 12, '2026-02-05 23:35:12', '2026-02-04 16:18:31'),
	(16, 113, 23, 70, 50.00, 35.00, 13, '2026-02-04 18:11:34', '2026-02-04 16:18:31'),
	(17, 115, 23, 70, 50.00, 35.00, 13, '2026-02-04 18:11:27', '2026-02-04 16:18:31');

-- A despejar estrutura para tabela database_aio.condicao_saude
CREATE TABLE IF NOT EXISTS `condicao_saude` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.condicao_saude: ~7 rows (aproximadamente)
INSERT INTO `condicao_saude` (`id`, `nome`) VALUES
	(1, 'Diabetes'),
	(2, 'Hipertensão'),
	(5, 'Problemas Cardíacos'),
	(6, 'Asma'),
	(7, 'Lesões Musculares'),
	(8, 'Dores Lombares'),
	(15, 'Nenhuma condição');

-- A despejar estrutura para tabela database_aio.consulta
CREATE TABLE IF NOT EXISTS `consulta` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int NOT NULL,
  `id_prestador` int NOT NULL,
  `id_servico` int NOT NULL,
  `data_hora` datetime NOT NULL,
  `id_estado` int NOT NULL DEFAULT '1',
  `preco` decimal(10,2) NOT NULL DEFAULT '0.00',
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `gratuita` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_prestador` (`id_prestador`),
  KEY `id_servico` (`id_servico`),
  KEY `id_estado` (`id_estado`),
  KEY `idx_consulta_gratis_mes` (`id_cliente`,`id_servico`,`gratuita`,`data_hora`,`id_estado`),
  CONSTRAINT `consulta_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `consulta_ibfk_2` FOREIGN KEY (`id_prestador`) REFERENCES `rh` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `consulta_ibfk_3` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `consulta_ibfk_4` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.consulta: ~73 rows (aproximadamente)
INSERT INTO `consulta` (`id`, `id_cliente`, `id_prestador`, `id_servico`, `data_hora`, `id_estado`, `preco`, `criado_em`, `atualizado_em`, `gratuita`) VALUES
	(33, 1, 21, 8, '2025-12-16 20:00:00', 16, 50.00, '2025-12-16 18:34:17', '2025-12-17 00:50:35', 0),
	(35, 3, 21, 8, '2025-12-17 22:22:00', 15, 48.00, '2025-12-16 18:43:29', '2025-12-16 18:44:57', 0),
	(37, 7, 21, 8, '2025-12-17 20:00:00', 15, 40.00, '2025-12-17 00:51:38', '2025-12-17 00:51:38', 0),
	(39, 1, 21, 8, '2025-12-18 20:00:00', 15, 53.00, '2025-12-17 11:01:12', '2025-12-17 11:01:12', 0),
	(41, 3, 21, 8, '2025-12-18 20:00:00', 15, 53.00, '2025-12-17 11:32:56', '2025-12-17 11:32:56', 0),
	(44, 34, 23, 2, '2025-12-18 12:00:00', 16, 50.00, '2025-12-17 14:42:38', '2026-02-04 10:58:19', 0),
	(45, 1, 21, 8, '2025-12-18 20:00:00', 15, 53.00, '2025-12-17 14:50:03', '2025-12-17 14:50:03', 0),
	(62, 34, 21, 8, '2026-01-29 12:00:00', 15, 40.00, '2026-01-26 21:39:59', '2026-01-26 21:40:23', 0),
	(63, 34, 21, 8, '2026-01-29 12:00:00', 15, 40.00, '2026-01-26 21:42:32', '2026-01-26 21:42:40', 0),
	(64, 34, 21, 8, '2026-01-30 12:00:00', 16, 40.00, '2026-01-26 21:43:31', '2026-02-04 10:58:28', 0),
	(65, 34, 23, 7, '2026-01-28 12:00:00', 16, 50.00, '2026-01-26 22:08:30', '2026-02-04 10:58:36', 0),
	(66, 34, 23, 7, '2026-01-31 12:00:00', 16, 50.00, '2026-01-26 22:08:36', '2026-02-04 10:58:53', 0),
	(67, 34, 46, 6, '2026-01-28 12:00:00', 16, 0.00, '2026-01-26 22:10:17', '2026-02-04 10:58:48', 0),
	(68, 34, 46, 6, '2026-01-30 12:00:00', 16, 0.00, '2026-01-26 22:10:22', '2026-02-04 10:56:08', 0),
	(69, 34, 23, 7, '2026-01-31 12:00:00', 16, 50.00, '2026-01-26 22:12:12', '2026-02-04 10:56:23', 0),
	(70, 34, 23, 7, '2026-02-05 12:00:00', 15, 50.00, '2026-01-26 22:12:20', '2026-02-04 11:00:35', 0),
	(72, 34, 23, 7, '2026-02-06 15:00:00', 15, 50.00, '2026-01-26 22:21:06', '2026-02-04 11:02:08', 0),
	(74, 34, 23, 7, '2026-02-10 12:00:00', 15, 50.00, '2026-01-26 22:23:53', '2026-02-04 10:57:18', 0),
	(79, 34, 21, 8, '2026-02-12 12:00:00', 15, 40.00, '2026-01-26 22:43:10', '2026-01-26 22:44:38', 0),
	(80, 34, 21, 8, '2026-02-19 12:00:00', 1, 40.00, '2026-01-26 22:43:31', '2026-02-04 10:59:14', 0),
	(81, 34, 21, 8, '2026-01-29 12:00:00', 16, 40.00, '2026-01-26 22:44:07', '2026-02-04 10:59:22', 0),
	(82, 34, 23, 7, '2026-01-28 12:00:00', 16, 50.00, '2026-01-26 23:28:39', '2026-02-04 10:59:37', 0),
	(83, 34, 23, 7, '2026-01-29 12:00:00', 16, 50.00, '2026-01-27 00:19:39', '2026-02-04 10:59:51', 0),
	(84, 34, 46, 6, '2026-01-29 16:00:00', 16, 0.00, '2026-01-28 07:32:40', '2026-02-04 11:00:03', 0),
	(85, 34, 23, 7, '2026-01-30 10:00:00', 15, 0.00, '2026-01-28 07:41:42', '2026-01-28 07:42:11', 1),
	(90, 45, 21, 8, '2026-02-06 15:00:00', 15, 40.00, '2026-02-03 01:13:45', '2026-02-03 01:22:08', 0),
	(91, 40, 21, 8, '2026-02-06 09:00:00', 15, 40.00, '2026-02-03 01:18:48', '2026-02-03 01:22:19', 0),
	(92, 40, 23, 7, '2026-02-06 11:10:00', 15, 50.00, '2026-02-03 01:19:15', '2026-02-03 01:25:50', 0),
	(93, 44, 21, 8, '2026-02-06 11:00:00', 15, 40.00, '2026-02-03 01:19:57', '2026-02-03 01:22:13', 0),
	(94, 44, 23, 7, '2026-02-06 15:00:00', 15, 50.00, '2026-02-03 01:20:23', '2026-02-03 01:25:47', 0),
	(95, 43, 21, 8, '2026-02-04 08:00:00', 15, 40.00, '2026-02-03 01:31:54', '2026-02-03 01:52:23', 0),
	(96, 43, 23, 7, '2026-02-05 08:00:00', 15, 50.00, '2026-02-03 01:32:10', '2026-02-03 01:32:27', 0),
	(97, 36, 21, 8, '2026-02-03 19:00:00', 15, 40.00, '2026-02-03 01:40:40', '2026-02-03 01:52:26', 0),
	(98, 36, 23, 7, '2026-02-03 20:15:00', 15, 50.00, '2026-02-03 01:41:03', '2026-02-03 01:56:59', 0),
	(99, 37, 21, 8, '2026-02-05 14:00:00', 15, 40.00, '2026-02-03 01:42:35', '2026-02-03 01:52:17', 0),
	(100, 37, 23, 7, '2026-02-05 16:10:00', 15, 50.00, '2026-02-03 01:42:55', '2026-02-03 01:56:51', 0),
	(101, 38, 21, 8, '2026-02-06 17:00:00', 15, 40.00, '2026-02-03 01:44:18', '2026-02-03 01:52:11', 0),
	(102, 38, 23, 7, '2026-02-06 20:10:00', 15, 50.00, '2026-02-03 01:44:39', '2026-02-03 01:56:46', 0),
	(103, 39, 21, 8, '2026-02-04 15:00:00', 15, 40.00, '2026-02-03 01:45:59', '2026-02-03 01:52:20', 0),
	(104, 39, 23, 7, '2026-02-04 16:20:00', 15, 50.00, '2026-02-03 01:46:17', '2026-02-03 01:56:54', 0),
	(105, 41, 21, 8, '2026-02-06 06:45:00', 15, 40.00, '2026-02-03 01:47:24', '2026-02-03 01:52:14', 0),
	(106, 41, 23, 7, '2026-02-06 08:45:00', 15, 50.00, '2026-02-03 01:47:39', '2026-02-03 01:56:49', 0),
	(108, 42, 23, 7, '2026-02-07 10:00:00', 15, 50.00, '2026-02-03 01:48:50', '2026-02-03 01:56:43', 0),
	(109, 45, 23, 7, '2026-02-04 08:00:00', 15, 50.00, '2026-02-03 01:50:29', '2026-02-03 01:56:56', 0),
	(110, 46, 21, 8, '2026-02-03 07:00:00', 16, 40.00, '2026-02-03 02:15:42', '2026-02-03 10:36:38', 0),
	(111, 46, 23, 7, '2026-02-03 08:00:00', 16, 50.00, '2026-02-03 02:15:59', '2026-02-03 10:36:45', 0),
	(112, 47, 21, 8, '2026-02-03 08:00:00', 16, 40.00, '2026-02-03 02:19:01', '2026-02-03 10:36:51', 0),
	(113, 47, 23, 7, '2026-02-03 09:00:00', 16, 50.00, '2026-02-03 02:19:26', '2026-02-03 10:36:58', 0),
	(114, 48, 21, 8, '2026-02-03 10:00:00', 16, 40.00, '2026-02-03 02:21:54', '2026-02-03 10:37:06', 0),
	(115, 48, 23, 7, '2026-02-03 11:00:00', 16, 50.00, '2026-02-03 02:22:04', '2026-02-03 10:33:13', 0),
	(116, 49, 21, 8, '2026-02-04 15:00:00', 15, 40.00, '2026-02-03 02:24:51', '2026-02-03 02:57:59', 0),
	(117, 49, 23, 7, '2026-02-04 16:00:00', 15, 50.00, '2026-02-03 02:25:09', '2026-02-03 03:03:42', 0),
	(118, 50, 21, 8, '2026-02-04 16:00:00', 15, 40.00, '2026-02-03 02:27:29', '2026-02-03 02:57:56', 0),
	(119, 50, 23, 7, '2026-02-04 17:00:00', 15, 50.00, '2026-02-03 02:27:47', '2026-02-03 03:03:40', 0),
	(120, 51, 21, 8, '2026-02-05 06:00:00', 15, 40.00, '2026-02-03 02:29:52', '2026-02-03 02:57:53', 0),
	(121, 51, 23, 7, '2026-02-05 07:00:00', 15, 50.00, '2026-02-03 02:30:10', '2026-02-03 03:03:37', 0),
	(122, 52, 21, 8, '2026-02-05 13:00:00', 15, 40.00, '2026-02-03 02:31:54', '2026-02-03 02:57:50', 0),
	(123, 52, 23, 7, '2026-02-05 14:00:00', 15, 50.00, '2026-02-03 02:32:04', '2026-02-03 03:03:34', 0),
	(125, 53, 23, 7, '2026-02-07 11:00:00', 15, 50.00, '2026-02-03 02:34:31', '2026-02-03 03:03:27', 0),
	(127, 54, 23, 7, '2026-02-07 10:00:00', 15, 50.00, '2026-02-03 02:36:41', '2026-02-03 03:03:30', 0),
	(129, 55, 23, 7, '2026-02-07 16:00:00', 15, 50.00, '2026-02-03 02:38:49', '2026-02-03 03:03:25', 0),
	(131, 56, 23, 7, '2026-02-08 10:00:00', 15, 50.00, '2026-02-03 02:40:47', '2026-02-03 03:03:22', 0),
	(133, 57, 23, 7, '2026-02-08 11:00:00', 15, 50.00, '2026-02-03 02:43:15', '2026-02-03 03:03:20', 0),
	(135, 58, 23, 7, '2026-02-08 15:00:00', 15, 50.00, '2026-02-03 02:46:20', '2026-02-03 03:03:17', 0),
	(136, 59, 21, 8, '2026-02-02 08:00:00', 15, 40.00, '2026-02-03 02:49:29', '2026-02-03 02:58:15', 0),
	(137, 59, 23, 7, '2026-02-09 09:00:00', 15, 50.00, '2026-02-03 02:49:41', '2026-02-03 03:03:09', 0),
	(138, 60, 21, 8, '2026-02-02 09:10:00', 15, 40.00, '2026-02-03 02:53:53', '2026-02-03 02:58:13', 0),
	(139, 60, 23, 7, '2026-02-09 10:10:00', 15, 50.00, '2026-02-03 02:54:43', '2026-02-03 03:03:02', 0),
	(140, 61, 21, 8, '2026-02-02 12:00:00', 15, 40.00, '2026-02-03 02:56:33', '2026-02-03 02:58:10', 0),
	(141, 61, 23, 7, '2026-02-09 11:00:00', 15, 50.00, '2026-02-03 02:56:55', '2026-02-03 03:02:59', 0),
	(142, 34, 23, 7, '2026-02-01 12:00:00', 15, 0.00, '2026-02-03 16:16:42', '2026-02-04 13:25:07', 1),
	(143, 34, 23, 7, '2026-02-12 12:00:00', 15, 50.00, '2026-02-05 14:35:00', '2026-02-05 14:43:56', 0),
	(144, 34, 23, 7, '2026-02-06 12:00:00', 15, 50.00, '2026-02-05 16:40:50', '2026-02-05 18:06:05', 0),
	(145, 34, 19, 7, '2026-02-05 12:00:00', 13, 50.00, '2026-02-05 20:51:19', '2026-02-05 20:51:19', 0),
	(146, 34, 17, 6, '2026-02-05 12:00:00', 13, 0.00, '2026-02-05 23:10:49', '2026-02-05 23:10:49', 0);

-- A despejar estrutura para tabela database_aio.consulta_servico_extra
CREATE TABLE IF NOT EXISTS `consulta_servico_extra` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_consulta` int NOT NULL,
  `id_servico_extra` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cse_consulta` (`id_consulta`),
  KEY `fk_cse_servico` (`id_servico_extra`),
  CONSTRAINT `fk_cse_consulta` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cse_servico` FOREIGN KEY (`id_servico_extra`) REFERENCES `servico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.consulta_servico_extra: ~8 rows (aproximadamente)
INSERT INTO `consulta_servico_extra` (`id`, `id_consulta`, `id_servico_extra`) VALUES
	(5, 35, 13),
	(7, 33, 11),
	(8, 39, 13),
	(9, 39, 14),
	(10, 41, 13),
	(11, 41, 14),
	(12, 45, 13),
	(13, 45, 14);

-- A despejar estrutura para tabela database_aio.conta_bancaria
CREATE TABLE IF NOT EXISTS `conta_bancaria` (
  `id_movimento` int NOT NULL AUTO_INCREMENT,
  `data_movimento` date DEFAULT NULL,
  `descricao` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` enum('Entrada','Saída') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_movimento`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.conta_bancaria: ~2 rows (aproximadamente)
INSERT INTO `conta_bancaria` (`id_movimento`, `data_movimento`, `descricao`, `tipo`, `valor`) VALUES
	(1, '2025-01-30', 'emprestimo', 'Entrada', 173961.92),
	(2, '2025-03-30', 'pagamento', 'Saída', 136510.99);

-- A despejar estrutura para tabela database_aio.cupao
CREATE TABLE IF NOT EXISTS `cupao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('percent','fixo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percent',
  `valor` decimal(10,2) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `uso_max` int DEFAULT NULL,
  `usos_atual` int NOT NULL DEFAULT '0',
  `min_subtotal` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cupao_codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.cupao: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.custo
CREATE TABLE IF NOT EXISTS `custo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `mes_referencia` date DEFAULT NULL,
  `id_tipo_custo` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tipo_custo` (`id_tipo_custo`),
  CONSTRAINT `custo_ibfk_1` FOREIGN KEY (`id_tipo_custo`) REFERENCES `tipo_custo` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.custo: ~37 rows (aproximadamente)
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
	(33, 'Juros', 464.00, '2025-09-01', 2),
	(34, 'Juros', 21235.00, '2026-01-27', 2),
	(35, 'Juros', 5000.00, '2026-02-03', 2),
	(40, 'Custo - Mês Anterior (ex.)', 500.00, '2026-01-01', 1),
	(41, 'Custo - Mês Atual (ex.)', 1000.00, '2026-02-01', 1);

-- A despejar estrutura para tabela database_aio.depreciacao
CREATE TABLE IF NOT EXISTS `depreciacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_ativo` int DEFAULT NULL,
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
CREATE TABLE IF NOT EXISTS `disponibilidade_prestador` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo_rh` int NOT NULL,
  `dia_semana` enum('Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `codigo_rh` (`codigo_rh`) USING BTREE,
  CONSTRAINT `disponibilidade_prestador_ibfk_1` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.disponibilidade_prestador: ~10 rows (aproximadamente)
INSERT INTO `disponibilidade_prestador` (`id`, `codigo_rh`, `dia_semana`, `hora_inicio`, `hora_fim`, `ativo`) VALUES
	(32, 21, 'Segunda', '08:00:00', '12:00:00', 1),
	(33, 21, 'Segunda', '14:00:00', '18:00:00', 1),
	(34, 21, 'Terça', '09:00:00', '12:00:00', 1),
	(35, 21, 'Terça', '14:00:00', '18:00:00', 1),
	(36, 21, 'Quarta', '09:00:00', '12:00:00', 1),
	(37, 21, 'Quarta', '14:00:00', '18:00:00', 1),
	(38, 21, 'Quinta', '09:00:00', '12:00:00', 1),
	(39, 21, 'Quinta', '14:00:00', '18:00:00', 1),
	(40, 21, 'Sexta', '09:00:00', '12:00:00', 1),
	(41, 21, 'Sexta', '14:00:00', '18:00:00', 1);

-- A despejar estrutura para tabela database_aio.emprestimo
CREATE TABLE IF NOT EXISTS `emprestimo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mes` int NOT NULL,
  `valor_prestacao` decimal(10,2) DEFAULT NULL,
  `juros` decimal(10,2) DEFAULT NULL,
  `amortizacao` decimal(10,2) DEFAULT NULL,
  `saldo_devedor` decimal(10,2) DEFAULT NULL,
  `data_prevista` date NOT NULL,
  `pago` tinyint(1) DEFAULT '0',
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
	(15, 15, 1500.00, 427.68, 1072.32, 84463.45, '2026-03-31', 1, '2025-12-17'),
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
CREATE TABLE IF NOT EXISTS `estado` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
CREATE TABLE IF NOT EXISTS `evento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `data_inicio` datetime DEFAULT NULL,
  `data_fim` datetime DEFAULT NULL,
  `concluido` tinyint(1) NOT NULL DEFAULT '0',
  `categoria` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bg-info-subtle',
  `localizacao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.evento: ~6 rows (aproximadamente)
INSERT INTO `evento` (`id`, `titulo`, `descricao`, `data_inicio`, `data_fim`, `concluido`, `categoria`, `localizacao`) VALUES
	(1, 'Entrega dos safts', 'Autoridade Tributária', '2026-02-09 00:00:00', '2026-02-09 00:00:00', 0, 'Obrigações Declarativas', NULL),
	(2, 'Entrega dos ivas', 'Autoridade Tributária', '2025-09-09 00:00:00', '2025-09-10 00:00:00', 1, 'Obrigações Declarativas', ''),
	(3, 'Entrega DMR', 'Autoridade Tributária', '2025-09-09 00:00:00', '2025-09-10 00:00:00', 1, 'Obrigações Declarativas', NULL),
	(4, 'Entrega DRI', 'Segurança Social', '2026-02-10 00:00:00', '2026-02-10 00:00:00', 0, 'Obrigações Declarativas', NULL),
	(30, 'Entrega dos Ivas', 'Autoridade Tributária', '2026-03-17 17:00:00', '2026-03-17 17:00:00', 0, 'Obrigações Declarativas', NULL),
	(31, 'Entrega dos Ivas', 'Autoridade Tributária', '2026-01-17 17:00:00', '2026-01-17 17:00:00', 1, 'Obrigações Declarativas', NULL);

-- A despejar estrutura para tabela database_aio.evento_prestador
CREATE TABLE IF NOT EXISTS `evento_prestador` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo_rh` int NOT NULL,
  `titulo` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `inicio` datetime NOT NULL,
  `fim` datetime NOT NULL,
  `descricao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `codigo_rh` (`codigo_rh`),
  CONSTRAINT `evento_prestador_ibfk_1` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.evento_prestador: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.exercicio
CREATE TABLE IF NOT EXISTS `exercicio` (
  `id_exercicio` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grupo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `equipamento` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dificuldade` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `imagem_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_exercicio`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.exercicio: ~33 rows (aproximadamente)
INSERT INTO `exercicio` (`id_exercicio`, `nome`, `grupo`, `equipamento`, `tipo`, `dificuldade`, `descricao`, `video_url`, `ativo`, `imagem_url`) VALUES
	(55, 'Puxada na frente', 'costas', 'maquina', 'composto', 'iniciante', 'Puxar a barra ao peito mantendo costas direitas.', NULL, 1, NULL),
	(56, 'Remada baixa', 'costas', 'cabos', 'composto', 'iniciante', 'Puxar o cabo em direção ao tronco com controlo.', NULL, 1, NULL),
	(57, 'Remada com barra', 'costas', 'barra', 'composto', 'intermédio', 'Inclinar o tronco e puxar a barra ao abdómen.', NULL, 1, NULL),
	(58, 'Remada unilateral com halter', 'costas', 'halteres', 'composto', 'iniciante', 'Remada com um braço apoiado num banco.', NULL, 1, NULL),
	(59, 'Pullover com halter', 'costas', 'halteres', 'isolamento', 'intermédio', 'Extensão dos ombros focando dorsais.', NULL, 1, NULL),
	(60, 'Deadlift', 'costas', 'barra', 'composto', 'avançado', 'Levantamento do chão com cadeia posterior.', NULL, 1, NULL),
	(61, 'Supino plano', 'peito', 'barra', 'composto', 'intermédio', 'Empurrar a barra deitado no banco.', NULL, 1, NULL),
	(62, 'Supino inclinado com halteres', 'peito', 'halteres', 'composto', 'iniciante', 'Supino em banco inclinado com halteres.', NULL, 1, NULL),
	(63, 'Peck deck', 'peito', 'maquina', 'isolamento', 'iniciante', 'Adução dos braços focando peitoral.', NULL, 1, NULL),
	(64, 'Crucifixo com halteres', 'peito', 'halteres', 'isolamento', 'iniciante', 'Abertura e fecho dos braços.', NULL, 1, NULL),
	(65, 'Flexões', 'peito', 'peso_corporal', 'composto', 'iniciante', 'Empurrar o corpo a partir do chão.', NULL, 1, NULL),
	(66, 'Agachamento', 'pernas', 'barra', 'composto', 'intermédio', 'Flexão de joelhos e ancas com barra.', NULL, 1, NULL),
	(67, 'Leg press', 'pernas', 'maquina', 'composto', 'iniciante', 'Extensão das pernas em máquina.', NULL, 1, NULL),
	(68, 'Lunges', 'pernas', 'halteres', 'composto', 'iniciante', 'Passada à frente com carga.', NULL, 1, NULL),
	(69, 'Cadeira extensora', 'pernas', 'maquina', 'isolamento', 'iniciante', 'Extensão dos joelhos.', NULL, 1, NULL),
	(70, 'Mesa flexora', 'pernas', 'maquina', 'isolamento', 'iniciante', 'Flexão dos joelhos.', NULL, 1, NULL),
	(71, 'Elevação de gémeos', 'pernas', 'peso_corporal', 'isolamento', 'iniciante', 'Elevação dos calcanhares.', NULL, 1, NULL),
	(72, 'Desenvolvimento militar', 'ombros', 'barra', 'composto', 'intermédio', 'Empurrar a barra acima da cabeça.', NULL, 1, NULL),
	(73, 'Elevação lateral', 'ombros', 'halteres', 'isolamento', 'iniciante', 'Elevação dos braços lateralmente.', NULL, 1, NULL),
	(74, 'Elevação frontal', 'ombros', 'halteres', 'isolamento', 'iniciante', 'Elevação frontal dos braços.', NULL, 1, NULL),
	(75, 'Arnold press', 'ombros', 'halteres', 'composto', 'intermédio', 'Press com rotação dos halteres.', NULL, 1, NULL),
	(76, 'Curl com barra', 'biceps', 'barra', 'isolamento', 'iniciante', 'Flexão dos cotovelos com barra.', NULL, 1, NULL),
	(77, 'Curl alternado', 'biceps', 'halteres', 'isolamento', 'iniciante', 'Curl alternado com halteres.', NULL, 1, NULL),
	(78, 'Curl martelo', 'biceps', 'halteres', 'isolamento', 'iniciante', 'Curl neutro focando braquial.', NULL, 1, NULL),
	(79, 'Curl no cabo', 'biceps', 'cabos', 'isolamento', 'intermédio', 'Curl contínuo no cabo.', NULL, 1, NULL),
	(80, 'Tríceps testa', 'triceps', 'barra', 'isolamento', 'intermédio', 'Extensão dos cotovelos deitado.', NULL, 1, NULL),
	(81, 'Pushdown', 'triceps', 'cabos', 'isolamento', 'iniciante', 'Extensão dos cotovelos no cabo.', NULL, 1, NULL),
	(82, 'Mergulhos', 'triceps', 'peso_corporal', 'composto', 'intermédio', 'Empurrar o corpo entre barras.', NULL, 1, NULL),
	(83, 'Kickback', 'triceps', 'halteres', 'isolamento', 'iniciante', 'Extensão do braço para trás.', NULL, 1, NULL),
	(84, 'Prancha', 'core', 'peso_corporal', 'isolamento', 'iniciante', 'Manter posição isométrica.', NULL, 1, NULL),
	(85, 'Crunch', 'core', 'peso_corporal', 'isolamento', 'iniciante', 'Flexão do tronco.', NULL, 1, NULL),
	(86, 'Elevação de pernas', 'core', 'peso_corporal', 'isolamento', 'intermédio', 'Elevação das pernas em decúbito.', NULL, 1, NULL),
	(87, 'Russian twist', 'core', 'halteres', 'isolamento', 'intermédio', 'Rotação do tronco sentado.', NULL, 1, NULL);

-- A despejar estrutura para tabela database_aio.fornecedor
CREATE TABLE IF NOT EXISTS `fornecedor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fornecedor` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descricao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_debito` decimal(10,2) DEFAULT NULL,
  `total_credito` decimal(10,2) DEFAULT NULL,
  `saldo` decimal(10,2) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `estado` enum('pendente','concluido') COLLATE utf8mb4_unicode_ci DEFAULT 'pendente',
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
CREATE TABLE IF NOT EXISTS `frequencia_semanal` (
  `id_frequencia` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_frequencia`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.frequencia_semanal: ~4 rows (aproximadamente)
INSERT INTO `frequencia_semanal` (`id_frequencia`, `descricao`) VALUES
	(1, '2x/semana'),
	(2, '3x/semana'),
	(3, '4x/semana'),
	(4, '5x/semana');

-- A despejar estrutura para tabela database_aio.funcao
CREATE TABLE IF NOT EXISTS `funcao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.funcao: ~4 rows (aproximadamente)
INSERT INTO `funcao` (`id`, `descricao`) VALUES
	(1, 'Administração'),
	(2, 'Nutrição'),
	(3, 'Personal Trainer'),
	(4, 'Psicologia');

-- A despejar estrutura para tabela database_aio.genero
CREATE TABLE IF NOT EXISTS `genero` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.genero: ~3 rows (aproximadamente)
INSERT INTO `genero` (`id`, `nome`) VALUES
	(1, 'Masculino'),
	(2, 'Feminino'),
	(3, 'Outro');

-- A despejar estrutura para tabela database_aio.habito_diario
CREATE TABLE IF NOT EXISTS `habito_diario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.habito_diario: ~4 rows (aproximadamente)
INSERT INTO `habito_diario` (`id`, `descricao`) VALUES
	(1, 'No escritório'),
	(2, 'Caminhadas diárias'),
	(3, 'Trabalho físico'),
	(4, 'Maioritariamente em casa');

-- A despejar estrutura para tabela database_aio.historico_treino_cliente
CREATE TABLE IF NOT EXISTS `historico_treino_cliente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int NOT NULL,
  `id_treino` int NOT NULL,
  `data_realizacao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_treino` (`id_treino`),
  CONSTRAINT `historico_treino_cliente_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`codigo`),
  CONSTRAINT `historico_treino_cliente_ibfk_2` FOREIGN KEY (`id_treino`) REFERENCES `treinos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.historico_treino_cliente: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.imposto
CREATE TABLE IF NOT EXISTS `imposto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mes` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dmr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dri` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
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

-- A despejar estrutura para tabela database_aio.indisponibilidade_prestador
CREATE TABLE IF NOT EXISTS `indisponibilidade_prestador` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo_rh` int NOT NULL,
  `inicio` datetime NOT NULL,
  `fim` datetime NOT NULL,
  `motivo` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `codigo_rh` (`codigo_rh`) USING BTREE,
  CONSTRAINT `indisponibilidade_prestador_ibfk_1` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.indisponibilidade_prestador: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.inscricao_aula
CREATE TABLE IF NOT EXISTS `inscricao_aula` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_aula` int NOT NULL,
  `id_cliente` int NOT NULL,
  `id_estado` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_aula_cliente` (`id_aula`,`id_cliente`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `inscricao_aula_ibfk_1` FOREIGN KEY (`id_aula`) REFERENCES `aula` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inscricao_aula_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`codigo`) ON DELETE CASCADE,
  CONSTRAINT `inscricao_aula_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.inscricao_aula: ~48 rows (aproximadamente)
INSERT INTO `inscricao_aula` (`id`, `id_aula`, `id_cliente`, `id_estado`, `created_at`) VALUES
	(27, 11, 34, 16, '2026-01-05 12:58:49'),
	(28, 10, 34, 16, '2026-01-15 15:00:00'),
	(30, 8, 34, 15, '2026-01-05 10:00:00'),
	(32, 8, 36, 15, '2026-02-03 00:28:36'),
	(33, 8, 37, 15, '2026-02-03 00:45:23'),
	(34, 8, 38, 15, '2026-02-03 00:48:14'),
	(35, 8, 39, 15, '2026-02-03 00:51:21'),
	(36, 8, 40, 15, '2026-02-03 00:53:45'),
	(37, 9, 40, 15, '2026-02-03 00:55:09'),
	(38, 10, 40, 15, '2026-02-03 00:55:20'),
	(39, 12, 41, 15, '2026-02-03 00:57:30'),
	(40, 9, 41, 15, '2026-02-03 00:57:38'),
	(41, 13, 42, 15, '2026-02-03 01:00:53'),
	(42, 9, 42, 15, '2026-02-03 01:00:58'),
	(43, 8, 42, 15, '2026-02-03 01:01:01'),
	(44, 8, 43, 15, '2026-02-03 01:03:23'),
	(45, 9, 43, 15, '2026-02-03 01:03:35'),
	(46, 9, 44, 15, '2026-02-03 01:07:03'),
	(47, 10, 44, 15, '2026-02-03 01:07:07'),
	(48, 10, 45, 15, '2026-02-03 01:11:37'),
	(49, 11, 45, 15, '2026-02-03 01:11:49'),
	(50, 12, 45, 15, '2026-02-03 01:11:53'),
	(51, 10, 43, 15, '2026-02-03 01:31:24'),
	(52, 11, 36, 15, '2026-02-03 01:38:55'),
	(53, 10, 37, 15, '2026-02-03 01:43:19'),
	(54, 11, 37, 15, '2026-02-03 01:43:23'),
	(55, 13, 37, 15, '2026-02-03 01:43:27'),
	(56, 10, 38, 15, '2026-02-03 01:45:02'),
	(57, 13, 38, 15, '2026-02-03 01:45:06'),
	(58, 12, 38, 15, '2026-02-03 01:45:10'),
	(59, 12, 39, 15, '2026-02-03 01:46:25'),
	(60, 11, 39, 15, '2026-02-03 01:46:29'),
	(61, 13, 41, 15, '2026-02-03 01:47:51'),
	(62, 11, 41, 15, '2026-02-03 01:47:55'),
	(63, 12, 42, 15, '2026-02-03 01:48:57'),
	(64, 13, 45, 15, '2026-02-03 01:50:50'),
	(65, 12, 46, 15, '2026-02-03 02:16:07'),
	(66, 11, 46, 15, '2026-02-03 02:16:15'),
	(67, 11, 47, 15, '2026-02-03 02:19:38'),
	(68, 12, 48, 15, '2026-02-03 02:21:24'),
	(69, 13, 49, 15, '2026-02-03 02:24:30'),
	(70, 8, 55, 15, '2026-02-03 09:55:35'),
	(71, 8, 56, 15, '2026-02-03 09:57:41'),
	(74, 10, 55, 15, '2026-02-04 10:33:06'),
	(75, 10, 36, 15, '2026-02-04 10:34:12'),
	(81, 8, 41, 15, '2026-02-05 11:36:23'),
	(83, 13, 34, 15, '2026-02-05 11:38:25'),
	(86, 12, 34, 15, '2026-02-05 12:57:32'),
	(96, 9, 34, 15, '2026-02-05 23:12:16');

-- A despejar estrutura para tabela database_aio.nivel_atividade
CREATE TABLE IF NOT EXISTS `nivel_atividade` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.nivel_atividade: ~3 rows (aproximadamente)
INSERT INTO `nivel_atividade` (`id`, `nome`, `descricao`) VALUES
	(1, 'Baixo', 'Sedentário ou pouca prática de exercício físico semanal (0–1 vezes por semana).'),
	(2, 'Moderado', 'Pratica atividade física com alguma regularidade (2–3 vezes por semana).'),
	(3, 'Alto', 'Pessoa muito ativa, pratica exercício intenso regularmente (4 ou mais vezes por semana).');

-- A despejar estrutura para tabela database_aio.notificacao
CREATE TABLE IF NOT EXISTS `notificacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_utilizador` int NOT NULL,
  `tipo` enum('consulta','evento','mensagem','alerta') COLLATE utf8mb4_general_ci NOT NULL,
  `referencia_id` int DEFAULT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `texto` text COLLATE utf8mb4_general_ci,
  `lida` tinyint(1) DEFAULT '0',
  `criada_em` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela database_aio.notificacao: ~165 rows (aproximadamente)
INSERT INTO `notificacao` (`id`, `id_utilizador`, `tipo`, `referencia_id`, `titulo`, `texto`, `lida`, `criada_em`) VALUES
	(10, 45, 'consulta', 27, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2025-12-14 19:18:05'),
	(11, 45, 'consulta', 28, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2025-12-14 19:38:02'),
	(12, 45, 'consulta', 29, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2025-12-15 14:57:50'),
	(13, 45, 'consulta', 30, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2025-12-15 15:05:59'),
	(31, 44, 'consulta', NULL, 'Consulta aceite', 'A sua consulta foi aceite pelo nutricionista.', 1, '2026-01-05 10:41:28'),
	(33, 44, 'consulta', NULL, 'Treino aceite', 'O seu pedido de treino foi aceite pelo personal trainer.', 1, '2026-01-05 10:55:27'),
	(35, 44, 'consulta', NULL, 'Treino aceite', 'O seu pedido de treino foi aceite pelo personal trainer.', 1, '2026-01-05 10:56:30'),
	(37, 43, 'consulta', 60, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-05 11:09:17'),
	(38, 68, 'consulta', 61, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-01-05 11:10:07'),
	(39, 1, 'consulta', NULL, 'Novo pedido de suporte', 'Foi criado um novo pedido de suporte.', 1, '2026-01-05 11:38:39'),
	(40, 2, 'consulta', NULL, 'Novo pedido de suporte', 'Foi criado um novo pedido de suporte.', 0, '2026-01-05 11:38:39'),
	(41, 48, 'consulta', NULL, 'Novo pedido de suporte', 'Foi criado um novo pedido de suporte.', 0, '2026-01-05 11:38:39'),
	(42, 67, 'consulta', NULL, 'Novo pedido de suporte', 'Foi criado um novo pedido de suporte.', 0, '2026-01-05 11:38:39'),
	(48, 20, 'consulta', 64, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 21:43:31'),
	(50, 43, 'consulta', 65, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 22:08:30'),
	(51, 43, 'consulta', 66, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 22:08:36'),
	(52, 68, 'consulta', 67, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-01-26 22:10:17'),
	(53, 68, 'consulta', 68, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-01-26 22:10:22'),
	(55, 43, 'consulta', 69, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 22:12:12'),
	(56, 43, 'consulta', 70, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 22:12:20'),
	(57, 43, 'consulta', 71, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 22:21:01'),
	(58, 43, 'consulta', 72, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 22:21:06'),
	(61, 43, 'consulta', 73, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 22:23:47'),
	(62, 43, 'consulta', 74, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 22:23:53'),
	(65, 43, 'consulta', 75, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 22:27:14'),
	(66, 54, 'consulta', 75, 'Consulta recusada', 'O nutricionista recusou o pedido. Podes marcar outra data.', 1, '2026-01-26 22:27:26'),
	(67, 43, 'consulta', 76, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 22:27:46'),
	(68, 20, 'consulta', 77, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 22:38:05'),
	(69, 54, 'consulta', NULL, 'Consulta recusada', 'O personal trainer recusou o pedido. Podes marcar outra data.', 1, '2026-01-26 22:39:16'),
	(70, 20, 'consulta', 78, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 22:42:59'),
	(71, 20, 'consulta', 79, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 22:43:10'),
	(72, 20, 'consulta', 80, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 22:43:31'),
	(73, 20, 'consulta', 81, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 22:44:07'),
	(74, 54, 'consulta', NULL, 'Consulta recusada', 'O personal trainer recusou o pedido. Podes marcar outra data.', 1, '2026-01-26 22:44:18'),
	(75, 54, 'consulta', NULL, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 1, '2026-01-26 22:44:22'),
	(76, 54, 'consulta', NULL, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 1, '2026-01-26 22:44:38'),
	(77, 54, 'consulta', NULL, 'Consulta recusada', 'O personal trainer recusou o pedido. Podes marcar outra data.', 1, '2026-01-26 22:45:35'),
	(78, 54, 'consulta', 76, 'Consulta recusada', 'O nutricionista recusou o pedido. Podes marcar outra data.', 1, '2026-01-26 23:25:44'),
	(79, 54, 'consulta', 68, 'Consulta recusada', 'O psicólogo recusou o pedido. Podes marcar outra data.', 1, '2026-01-26 23:27:46'),
	(80, 54, 'consulta', 67, 'Consulta confirmada', 'A tua consulta foi confirmada pelo psicólogo.', 1, '2026-01-26 23:27:49'),
	(81, 43, 'consulta', 82, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-26 23:28:39'),
	(82, 54, 'consulta', 82, 'Consulta recusada', 'O nutricionista recusou o pedido. Podes marcar outra data.', 1, '2026-01-26 23:28:49'),
	(83, 43, 'consulta', 83, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-27 00:19:39'),
	(84, 54, 'consulta', NULL, 'Consulta aceite', 'A sua consulta foi aceite pelo nutricionista.', 1, '2026-01-27 00:20:04'),
	(85, 68, 'consulta', 84, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-01-28 07:32:40'),
	(86, 43, 'consulta', 85, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-28 07:41:42'),
	(87, 54, 'consulta', 85, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 1, '2026-01-28 07:42:11'),
	(88, 43, 'consulta', 86, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-01-31 17:00:45'),
	(89, 44, 'consulta', 86, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-01-31 17:01:07'),
	(90, 43, 'consulta', 87, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-01 21:50:36'),
	(91, 44, 'consulta', 87, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-01 21:50:44'),
	(92, 20, 'consulta', 88, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-01 22:00:48'),
	(93, 44, 'consulta', 88, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-01 22:00:54'),
	(94, 43, 'consulta', 89, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-02 00:02:12'),
	(95, 44, 'consulta', 89, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-02 00:02:27'),
	(96, 20, 'consulta', 90, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-03 01:13:45'),
	(97, 20, 'consulta', 91, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-03 01:18:48'),
	(98, 43, 'consulta', 92, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-03 01:19:15'),
	(99, 20, 'consulta', 93, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-03 01:19:57'),
	(100, 43, 'consulta', 94, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-03 01:20:23'),
	(101, 78, 'consulta', 90, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 01:22:08'),
	(102, 77, 'consulta', 93, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 01:22:13'),
	(103, 73, 'consulta', 91, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 01:22:19'),
	(104, 77, 'consulta', 94, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 01:25:47'),
	(105, 73, 'consulta', 92, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 01:25:50'),
	(106, 20, 'consulta', 95, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-03 01:31:54'),
	(107, 43, 'consulta', 96, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-03 01:32:10'),
	(108, 76, 'consulta', 96, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 1, '2026-02-03 01:32:27'),
	(109, 20, 'consulta', 97, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-03 01:40:40'),
	(110, 43, 'consulta', 98, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 01:41:03'),
	(111, 20, 'consulta', 99, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-03 01:42:35'),
	(112, 43, 'consulta', 100, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 01:42:55'),
	(113, 20, 'consulta', 101, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-03 01:44:18'),
	(114, 43, 'consulta', 102, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 01:44:39'),
	(115, 20, 'consulta', 103, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-03 01:45:59'),
	(116, 43, 'consulta', 104, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 01:46:17'),
	(117, 20, 'consulta', 105, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-03 01:47:24'),
	(118, 43, 'consulta', 106, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 01:47:39'),
	(119, 20, 'consulta', 107, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 1, '2026-02-03 01:48:39'),
	(120, 43, 'consulta', 108, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 01:48:50'),
	(121, 43, 'consulta', 109, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 01:50:29'),
	(122, 75, 'consulta', 107, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 01:52:08'),
	(123, 71, 'consulta', 101, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 01:52:11'),
	(124, 74, 'consulta', 105, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 01:52:14'),
	(125, 70, 'consulta', 99, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 01:52:17'),
	(126, 72, 'consulta', 103, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 01:52:20'),
	(127, 76, 'consulta', 95, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 01:52:23'),
	(128, 69, 'consulta', 97, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 01:52:26'),
	(129, 75, 'consulta', 108, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 01:56:43'),
	(130, 71, 'consulta', 102, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 01:56:46'),
	(131, 74, 'consulta', 106, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 01:56:49'),
	(132, 70, 'consulta', 100, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 01:56:51'),
	(133, 72, 'consulta', 104, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 01:56:54'),
	(134, 78, 'consulta', 109, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 01:56:56'),
	(135, 69, 'consulta', 98, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 01:56:59'),
	(136, 20, 'consulta', 110, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:15:42'),
	(137, 43, 'consulta', 111, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:15:59'),
	(138, 20, 'consulta', 112, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:19:01'),
	(139, 43, 'consulta', 113, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:19:26'),
	(140, 20, 'consulta', 114, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:21:54'),
	(141, 43, 'consulta', 115, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:22:04'),
	(142, 20, 'consulta', 116, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:24:51'),
	(143, 43, 'consulta', 117, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:25:09'),
	(144, 20, 'consulta', 118, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:27:29'),
	(145, 43, 'consulta', 119, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:27:47'),
	(146, 20, 'consulta', 120, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:29:52'),
	(147, 43, 'consulta', 121, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:30:10'),
	(148, 20, 'consulta', 122, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:31:54'),
	(149, 43, 'consulta', 123, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:32:04'),
	(150, 20, 'consulta', 124, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:34:21'),
	(151, 43, 'consulta', 125, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:34:31'),
	(152, 20, 'consulta', 126, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:36:27'),
	(153, 43, 'consulta', 127, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:36:41'),
	(154, 20, 'consulta', 128, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:38:39'),
	(155, 43, 'consulta', 129, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:38:49'),
	(156, 20, 'consulta', 130, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:40:37'),
	(157, 43, 'consulta', 131, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:40:47'),
	(158, 20, 'consulta', 132, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:43:08'),
	(159, 43, 'consulta', 133, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:43:15'),
	(160, 20, 'consulta', 134, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:46:10'),
	(161, 43, 'consulta', 135, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:46:20'),
	(162, 20, 'consulta', 136, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:49:29'),
	(163, 43, 'consulta', 137, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:49:41'),
	(164, 20, 'consulta', 138, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:53:54'),
	(165, 43, 'consulta', 139, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:54:43'),
	(166, 20, 'consulta', 140, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:56:33'),
	(167, 43, 'consulta', 141, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 02:56:55'),
	(168, 91, 'consulta', 134, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 02:57:32'),
	(169, 90, 'consulta', 132, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 02:57:35'),
	(170, 89, 'consulta', 130, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 02:57:38'),
	(171, 88, 'consulta', 128, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 02:57:41'),
	(172, 86, 'consulta', 124, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 02:57:45'),
	(173, 87, 'consulta', 126, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 02:57:47'),
	(174, 85, 'consulta', 122, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 02:57:50'),
	(175, 84, 'consulta', 120, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 02:57:53'),
	(176, 83, 'consulta', 118, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 02:57:56'),
	(177, 82, 'consulta', 116, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 02:57:59'),
	(178, 81, 'consulta', 114, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 02:58:02'),
	(179, 80, 'consulta', 112, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 02:58:05'),
	(180, 79, 'consulta', 110, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 02:58:07'),
	(181, 94, 'consulta', 140, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 1, '2026-02-03 02:58:10'),
	(182, 93, 'consulta', 138, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 02:58:13'),
	(183, 92, 'consulta', 136, 'Consulta confirmada', 'A tua consulta foi confirmada pelo personal trainer.', 0, '2026-02-03 02:58:15'),
	(184, 94, 'consulta', 141, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:02:59'),
	(185, 93, 'consulta', 139, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:03:02'),
	(186, 92, 'consulta', 137, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:03:09'),
	(187, 91, 'consulta', 135, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:03:17'),
	(188, 90, 'consulta', 133, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:03:20'),
	(189, 89, 'consulta', 131, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:03:22'),
	(190, 88, 'consulta', 129, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:03:25'),
	(191, 86, 'consulta', 125, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:03:27'),
	(192, 87, 'consulta', 127, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:03:30'),
	(193, 85, 'consulta', 123, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:03:34'),
	(194, 84, 'consulta', 121, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:03:37'),
	(195, 83, 'consulta', 119, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:03:40'),
	(196, 82, 'consulta', 117, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:03:42'),
	(197, 81, 'consulta', 115, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:03:45'),
	(198, 80, 'consulta', 113, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:03:48'),
	(199, 79, 'consulta', 111, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-03 03:03:50'),
	(200, 43, 'consulta', 142, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-03 16:16:42'),
	(201, 54, 'consulta', 142, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 1, '2026-02-03 16:26:30'),
	(202, 43, 'consulta', 143, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-05 14:35:00'),
	(203, 54, 'consulta', 143, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 1, '2026-02-05 14:43:56'),
	(204, 43, 'consulta', 144, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-05 16:40:50'),
	(205, 54, 'consulta', 144, 'Consulta confirmada', 'A tua consulta foi confirmada pelo nutricionista.', 0, '2026-02-05 18:06:05'),
	(206, 15, 'consulta', 145, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-05 20:51:19'),
	(207, 18, 'consulta', 146, 'Nova consulta pendente', 'Um cliente marcou uma nova consulta e aguarda confirmação', 0, '2026-02-05 23:10:49');

-- A despejar estrutura para tabela database_aio.objetivo
CREATE TABLE IF NOT EXISTS `objetivo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.objetivo: ~3 rows (aproximadamente)
INSERT INTO `objetivo` (`id`, `nome`, `descricao`) VALUES
	(1, 'Perder Peso', 'Reduzir gordura corporal de forma saudável através de treino físico e alimentação equilibrada.'),
	(2, 'Desenvolver Músculo', 'Aumentar a massa muscular e a força com planos de treino focados em hipertrofia e nutrição rica em proteínas.'),
	(3, 'Manter a forma', 'Manter o peso e o condicionamento físico, combinando treinos leves com alimentação equilibrada.');

-- A despejar estrutura para tabela database_aio.obrigacao
CREATE TABLE IF NOT EXISTS `obrigacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_tipo_obrigacao` int DEFAULT NULL,
  `descricao` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `data_vencimento` date DEFAULT NULL,
  `data_pagamento` date DEFAULT NULL,
  `id_estado` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tipo_obrigacao` (`id_tipo_obrigacao`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `obrigacao_ibfk_1` FOREIGN KEY (`id_tipo_obrigacao`) REFERENCES `tipo_obrigacao` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `obrigacao_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.obrigacao: ~11 rows (aproximadamente)
INSERT INTO `obrigacao` (`id`, `id_tipo_obrigacao`, `descricao`, `valor`, `data_vencimento`, `data_pagamento`, `id_estado`) VALUES
	(1, 2, 'Pagamento-SP_MEO', 30.75, '2026-01-31', '2026-02-03', 12),
	(2, 2, 'Pagamento-SP_Digital Aurora', 500.00, '2026-01-05', '2026-02-03', 13),
	(3, 3, 'Pagamento-SP_Joana Freitas', 1699.55, '2026-01-31', '2026-02-02', 12),
	(4, 3, 'Pagamento-SP_Maria Beatriz Martins', 1012.50, '2026-01-31', '2026-02-02', 12),
	(5, 3, 'Pagamento-SP_Joao Ferreira', 982.50, '2026-01-31', '2026-02-02', 12),
	(6, 3, 'Pagamento-SP_Guilherme Sousa', 1602.00, '2026-01-31', '2026-02-02', 12),
	(7, 3, 'Pagamento-SP_Ana Sofia Marques', 1602.00, '2026-01-31', '2026-02-02', 12),
	(8, 3, 'Pagamento-SP_Lucia Mendes', 315.00, '2026-01-31', '2026-02-02', 12),
	(9, 4, 'Pagamento-IVA', 2000.00, '2026-01-31', '2026-02-02', 13),
	(10, 4, 'Pagamento-DMR', 100.00, '2026-01-31', '2026-02-02', 13),
	(11, 4, 'Pagamento-DRI', 89.00, '2026-01-31', '2026-02-02', 13);

-- A despejar estrutura para tabela database_aio.pagamento_square
CREATE TABLE IF NOT EXISTS `pagamento_square` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_venda` int NOT NULL,
  `square_nonce` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `square_payment_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `square_status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'EUR',
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_square_payment` (`square_payment_id`),
  KEY `idx_venda` (`id_venda`),
  CONSTRAINT `fk_pag_square_venda` FOREIGN KEY (`id_venda`) REFERENCES `venda` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.pagamento_square: ~1 rows (aproximadamente)
INSERT INTO `pagamento_square` (`id`, `id_venda`, `square_nonce`, `square_payment_id`, `square_status`, `amount`, `currency`, `criado_em`) VALUES
	(13, 104, NULL, 'vba884G1f9W55H86uouZnRJ7BbYZY', 'COMPLETED', 70.00, 'USD', '2026-02-03 16:39:16');

-- A despejar estrutura para tabela database_aio.parceiro_marketplace
CREATE TABLE IF NOT EXISTS `parceiro_marketplace` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contato` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `percentual_comissao` decimal(5,2) NOT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.parceiro_marketplace: ~5 rows (aproximadamente)
INSERT INTO `parceiro_marketplace` (`id`, `nome`, `contato`, `email`, `percentual_comissao`, `ativo`) VALUES
	(1, 'Zumub', 'Paulo Santos', 'contato@zumub.com', 10.00, 1),
	(2, 'FitStore', 'Alice Vieira', 'suporte@fitstore.com', 12.00, 1),
	(3, 'MuscleShop', 'João Correia', 'contact@muscleshop.com', 8.50, 1),
	(4, 'GymPro', 'Ana Silva', 'support@gympro.com', 15.00, 1),
	(5, 'SportFit', 'Hernandez Chavez', 'info@sportfit.com', 10.00, 1);

-- A despejar estrutura para tabela database_aio.plano_alimentar
CREATE TABLE IF NOT EXISTS `plano_alimentar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_calorias` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `plano_alimentar_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilizador` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.plano_alimentar: ~3 rows (aproximadamente)
INSERT INTO `plano_alimentar` (`id`, `user_id`, `titulo`, `criado_em`, `total_calorias`) VALUES
	(12, 54, 'Almoco', '2026-02-03 16:14:42', 200);

-- A despejar estrutura para tabela database_aio.plano_ficheiros
CREATE TABLE IF NOT EXISTS `plano_ficheiros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `nutricionista_id` int NOT NULL,
  `nome_original` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome_ficheiro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caminho` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_envio` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.plano_ficheiros: ~1 rows (aproximadamente)
INSERT INTO `plano_ficheiros` (`id`, `cliente_id`, `nutricionista_id`, `nome_original`, `nome_ficheiro`, `caminho`, `data_envio`) VALUES
	(11, 34, 23, 'Menu_Semanal.pdf', '1770134130_Menu_Semanal.pdf', 'uploads/planos/1770134130_Menu_Semanal.pdf', '2026-02-03 15:55:30');

-- A despejar estrutura para tabela database_aio.plano_ingredientes
CREATE TABLE IF NOT EXISTS `plano_ingredientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `plano_id` int NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `calorias` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `plano_id` (`plano_id`),
  CONSTRAINT `plano_ingredientes_ibfk_1` FOREIGN KEY (`plano_id`) REFERENCES `plano_alimentar` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.plano_ingredientes: ~3 rows (aproximadamente)
INSERT INTO `plano_ingredientes` (`id`, `plano_id`, `nome`, `calorias`) VALUES
	(13, 12, 'Aveia', 200);

-- A despejar estrutura para tabela database_aio.plano_nutricionista
CREATE TABLE IF NOT EXISTS `plano_nutricionista` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo_rh` int NOT NULL,
  `codigo_cliente` int NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ficheiro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `codigo_rh` (`codigo_rh`),
  KEY `codigo_cliente` (`codigo_cliente`),
  CONSTRAINT `plano_nutricionista_ibfk_1` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`),
  CONSTRAINT `plano_nutricionista_ibfk_2` FOREIGN KEY (`codigo_cliente`) REFERENCES `cliente` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.plano_nutricionista: ~3 rows (aproximadamente)
INSERT INTO `plano_nutricionista` (`id`, `codigo_rh`, `codigo_cliente`, `titulo`, `ficheiro`, `criado_em`) VALUES
	(13, 23, 34, NULL, 'Menu_Semanal.pdf', '2026-01-22 16:10:32'),
	(15, 23, 34, NULL, '1770134130_Menu_Semanal.pdf', '2026-02-03 15:55:30'),
	(16, 23, 1, NULL, '1770250652_ContratoJoana.pdf', '2026-02-05 00:17:32');

-- A despejar estrutura para tabela database_aio.plano_pt
CREATE TABLE IF NOT EXISTS `plano_pt` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo_rh` int DEFAULT NULL,
  `codigo_cliente` int NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `publicado` tinyint(1) NOT NULL DEFAULT '0',
  `criado_por` enum('PT','CLIENTE') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'PT',
  PRIMARY KEY (`id`),
  KEY `codigo_rh` (`codigo_rh`),
  KEY `codigo_cliente` (`codigo_cliente`),
  CONSTRAINT `plano_PT_ibfk_1` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`),
  CONSTRAINT `plano_PT_ibfk_2` FOREIGN KEY (`codigo_cliente`) REFERENCES `cliente` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela database_aio.plano_pt: ~6 rows (aproximadamente)
INSERT INTO `plano_pt` (`id`, `codigo_rh`, `codigo_cliente`, `titulo`, `criado_em`, `publicado`, `criado_por`) VALUES
	(8, 21, 34, 'Full Body', '2026-01-01 17:51:57', 1, 'PT'),
	(9, 21, 34, 'Cardio', '2026-01-01 17:52:28', 0, 'PT');

-- A despejar estrutura para tabela database_aio.plano_pt_dia
CREATE TABLE IF NOT EXISTS `plano_pt_dia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `plano_id` int NOT NULL,
  `dia_semana` tinyint NOT NULL,
  `nome` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_plano_dia` (`plano_id`,`dia_semana`),
  CONSTRAINT `fk_plano_dia_plano` FOREIGN KEY (`plano_id`) REFERENCES `plano_pt` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.plano_pt_dia: ~8 rows (aproximadamente)
INSERT INTO `plano_pt_dia` (`id`, `plano_id`, `dia_semana`, `nome`) VALUES
	(1, 8, 1, 'Costas + Biceps'),
	(2, 8, 3, 'Peito'),
	(3, 8, 5, 'Perna');

-- A despejar estrutura para tabela database_aio.plano_pt_dia_exercicio
CREATE TABLE IF NOT EXISTS `plano_pt_dia_exercicio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `plano_dia_id` int NOT NULL,
  `id_exercicio` int NOT NULL,
  `ordem` int NOT NULL DEFAULT '1',
  `series` int DEFAULT NULL,
  `reps` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descanso_seg` int DEFAULT NULL,
  `rpe` decimal(3,1) DEFAULT NULL,
  `tempo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacoes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_plano_dia` (`plano_dia_id`),
  KEY `idx_exercicio` (`id_exercicio`),
  CONSTRAINT `fk_plano_dia_ex_exercicio` FOREIGN KEY (`id_exercicio`) REFERENCES `exercicio` (`id_exercicio`) ON UPDATE CASCADE,
  CONSTRAINT `fk_plano_dia_ex_plano_dia` FOREIGN KEY (`plano_dia_id`) REFERENCES `plano_pt_dia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.plano_pt_dia_exercicio: ~20 rows (aproximadamente)
INSERT INTO `plano_pt_dia_exercicio` (`id`, `plano_dia_id`, `id_exercicio`, `ordem`, `series`, `reps`, `descanso_seg`, `rpe`, `tempo`, `observacoes`) VALUES
	(6, 2, 63, 1, 3, '12', 180, 0.0, '', ''),
	(7, 2, 62, 2, 3, '12', 180, 0.0, '', ''),
	(8, 3, 66, 1, 3, '15', 180, 10.0, '', ''),
	(9, 3, 67, 2, 3, '12', 180, 10.0, '', ''),
	(10, 3, 71, 3, 4, '10', 180, 8.0, '', ''),
	(38, 1, 56, 1, 3, '12-15', 180, 7.0, '3-1-1', 'Última série até falha técnica'),
	(39, 1, 57, 2, 3, '12', 180, 7.0, '3-1-1', 'Última série até falha técnica'),
	(40, 1, 59, 3, 3, '12', 180, 7.0, '3-1-1', 'Aumentar carga se passar das 10 reps'),
	(41, 1, 78, 4, 2, '12', 180, 7.0, '3-1-1', 'Última série até falha técnica'),
	(42, 1, 77, 5, 2, '12', 180, 7.0, '3-1-1', 'Aumentar carga se passar das 10 reps');

-- A despejar estrutura para tabela database_aio.plano_pt_ficheiros
CREATE TABLE IF NOT EXISTS `plano_pt_ficheiros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `pt_id` int NOT NULL,
  `nome_original` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nome_ficheiro` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `caminho` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `data_envio` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `pt_id` (`pt_id`),
  CONSTRAINT `fk_plano_pt_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_plano_pt_rh` FOREIGN KEY (`pt_id`) REFERENCES `rh` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela database_aio.plano_pt_ficheiros: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.plano_vantagem
CREATE TABLE IF NOT EXISTS `plano_vantagem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_servico` int NOT NULL,
  `chave` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_plano_chave` (`id_servico`,`chave`),
  KEY `idx_plano_vantagem_servico` (`id_servico`),
  CONSTRAINT `fk_plano_vantagem_servico` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.plano_vantagem: ~21 rows (aproximadamente)
INSERT INTO `plano_vantagem` (`id`, `id_servico`, `chave`, `valor`) VALUES
	(1, 1, 'acesso_app', 1),
	(2, 1, 'plano_alimentar_personalizado', 0),
	(3, 1, 'sessao_pt_mes', 0),
	(4, 1, 'consulta_nutricionista_mes', 0),
	(5, 1, 'chat', 0),
	(6, 2, 'acesso_app', 1),
	(7, 2, 'plano_alimentar_personalizado', 1),
	(8, 2, 'sessao_pt_mes', 1),
	(9, 2, 'consulta_nutricionista_mes', 1),
	(10, 2, 'chat', 1),
	(11, 3, 'acesso_app', 1),
	(12, 3, 'plano_alimentar_personalizado', 1),
	(13, 3, 'sessao_pt_mes', 4),
	(14, 3, 'consulta_nutricionista_mes', 1),
	(15, 3, 'chat', 1),
	(16, 1, 'nutri_gratis_mes', 0),
	(17, 1, 'pt_gratis_mes', 0),
	(18, 2, 'nutri_gratis_mes', 1),
	(19, 2, 'pt_gratis_mes', 1),
	(20, 3, 'nutri_gratis_mes', 1),
	(21, 3, 'pt_gratis_mes', 4);

-- A despejar estrutura para tabela database_aio.planos_treino
CREATE TABLE IF NOT EXISTS `planos_treino` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_tempo` int NOT NULL DEFAULT '0',
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `planos_treino_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilizador` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.planos_treino: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.produto_marketplace
CREATE TABLE IF NOT EXISTS `produto_marketplace` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `preco` decimal(10,2) NOT NULL,
  `stock` int DEFAULT '0',
  `imagem` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_parceiro` int NOT NULL,
  `id_estado` int NOT NULL,
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
	(7, 'Halteres', 'Conjunto de 2 halteres para treino de resistência; cada uma pesa 2 kg.A textura de neoprene proporciona uma aderência fácil e segura.Com número de peso impresso em cada extremidade e código de cores para uma identificação rápida.Forma hexagonal para evitar o rolamento do haltere.Ideal para aulas de fitness ou rotinas de treino em casa.', 25.99, 5, '.backoffice/assets/images/products/1765332715_halteres-redondos-de-musculacao-20kg-par-boomfit.jpg', 4, 2),
	(8, 'Tênis Fitness', 'A tecnologia Flex-H assegura movimentos naturais e a sola em EVA absorve os impactos. Esta forma é pequena, recomendamos que optes por um número maior do que o teu tamanho habitual.', 55.00, 50, '.backoffice/assets/images/products/1765332893_tenis-para-treino-de-cross-white-orange-50113-3.jpg', 3, 2),
	(9, 'Bola de Fitness', 'Equipamento especialmente usado para Yoga e Pilates, e bastante recomendado por treinadores, terapeutas físicos e quiropráticos.', 12.00, 100, '.backoffice/assets/images/products/1765333148_transferir.jpg', 5, 2),
	(10, 'kettlebells', 'Os kettlebells revestidos em borracha são perfeitos para quem procura melhorar a força, resistência, potência e desempenho cardiovascular, oferecendo um design ergonómico e revestimento de borracha de alta qualidade, que assegura durabilidade e conforto excecionais durante os treinos. Disponíveis em pesos de 4 kg a 24 kg.', 16.99, 55, '.backoffice/assets/images/products/1765333330_Kettlebell.webp', 4, 2),
	(11, 'Creatina', 'Optimum Nutrition é uma marca muito reconhecida devido ao seu prestígio e qualidade. Recomendada por quem a toma, Creatine Powder é a fórmula original com 99,9% de creatina monohidrato que melhora os resultados de ganho de massa muscular.', 22.99, 100, '.backoffice/assets/images/products/1765333477_566452-suplemento-alimentar-de-creatina-em-po-sem-sabor-317-gramas-kg-on-optimum-nutrition20221221100403.webp', 1, 2),
	(12, 'T-shirt desportiva', 'Esta t-shirt de corrida para homem respirável elimina a humidade para te manter seco em tempo quente. Adequada para uma variedade de desportos atividades ou uso diário também seca mais rapidamente que uma t-shirt de algodão.', 15.99, 2, '.backoffice/assets/images/products/1765333675_7864907-frente.jpg', 4, 2);

-- A despejar estrutura para tabela database_aio.profissional_servico
CREATE TABLE IF NOT EXISTS `profissional_servico` (
  `codigo_rh` int NOT NULL,
  `id_servico` int NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`codigo_rh`,`id_servico`),
  KEY `fk_ps_servico` (`id_servico`),
  CONSTRAINT `fk_ps_rh` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ps_servico` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.profissional_servico: ~3 rows (aproximadamente)
INSERT INTO `profissional_servico` (`codigo_rh`, `id_servico`, `ativo`) VALUES
	(21, 8, 1),
	(21, 13, 1),
	(21, 14, 1);

-- A despejar estrutura para tabela database_aio.profissional_tipo_aula_grupo
CREATE TABLE IF NOT EXISTS `profissional_tipo_aula_grupo` (
  `codigo_rh` int NOT NULL,
  `id_tipo_aula_grupo` int NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`codigo_rh`,`id_tipo_aula_grupo`),
  KEY `fk_ptag_tipo` (`id_tipo_aula_grupo`),
  CONSTRAINT `fk_ptag_rh` FOREIGN KEY (`codigo_rh`) REFERENCES `rh` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ptag_tipo` FOREIGN KEY (`id_tipo_aula_grupo`) REFERENCES `tipo_aula_grupo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.profissional_tipo_aula_grupo: ~1 rows (aproximadamente)
INSERT INTO `profissional_tipo_aula_grupo` (`codigo_rh`, `id_tipo_aula_grupo`, `ativo`) VALUES
	(21, 2, 1);

-- A despejar estrutura para tabela database_aio.progresso_cliente
CREATE TABLE IF NOT EXISTS `progresso_cliente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_utilizador` int NOT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `calorias` int DEFAULT NULL,
  `tempo_treino` int DEFAULT NULL,
  `data_registo` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.progresso_cliente: ~22 rows (aproximadamente)
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
	(13, 50, 80.00, 2000, 45, '2025-12-17 11:20:28'),
	(19, 54, 92.00, 0, 0, '2026-01-23 13:34:13'),
	(20, 54, 93.00, 1800, 45, '2026-01-23 13:34:28'),
	(21, 54, 96.00, 1800, 45, '2026-01-23 13:34:34'),
	(22, 54, 93.00, 1800, 45, '2026-01-23 13:34:39'),
	(23, 54, 98.00, 1800, 45, '2026-01-23 13:34:45'),
	(24, 54, 98.00, 1800, 45, '2026-01-23 13:34:50'),
	(25, 54, 102.00, 1800, 45, '2026-02-03 16:12:08'),
	(26, 54, 104.00, 1800, 45, '2026-02-05 14:31:15'),
	(27, 54, 106.00, 1800, 45, '2026-02-05 16:36:14'),
	(28, 54, 108.00, 1800, 45, '2026-02-05 20:48:25'),
	(29, 54, 109.00, 1800, 45, '2026-02-05 23:02:14');

-- A despejar estrutura para tabela database_aio.realtime_events
CREATE TABLE IF NOT EXISTS `realtime_events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `evento` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `realtime_events_chk_1` CHECK (json_valid(`payload`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.realtime_events: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.rh
CREATE TABLE IF NOT EXISTS `rh` (
  `codigo` int NOT NULL AUTO_INCREMENT,
  `id_utilizador` int NOT NULL,
  `nome_completo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nif` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contacto` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_funcao` int DEFAULT NULL,
  `qualificacao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experiencia_anos` int DEFAULT NULL,
  `id_tipo_contrato` int DEFAULT NULL,
  `id_estado` int DEFAULT '1',
  `contrato` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recibo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_contratacao` date DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_utilizador` (`id_utilizador`),
  KEY `id_funcao` (`id_funcao`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `rh_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id`),
  CONSTRAINT `rh_ibfk_2` FOREIGN KEY (`id_funcao`) REFERENCES `funcao` (`id`),
  CONSTRAINT `rh_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.rh: ~11 rows (aproximadamente)
INSERT INTO `rh` (`codigo`, `id_utilizador`, `nome_completo`, `nif`, `contacto`, `id_funcao`, `qualificacao`, `experiencia_anos`, `id_tipo_contrato`, `id_estado`, `contrato`, `recibo`, `data_contratacao`) VALUES
	(1, 2, 'José Lopes', '297678788', '964274942', 1, 'Licenciatura em Gestão', 10, 6, 2, 'src/uploads/1762769776_Contrato_José_Lopes.pdf', '', '2025-11-10'),
	(16, 17, 'Ana Sofia Marques', '282643648', '912345004', 3, 'Personal Trainer Certificada', 3, 2, 2, NULL, 'src/uploads/recibos/1765981087_Contrato_José_Lopes.pdf', '2024-01-05'),
	(17, 18, 'Lúcia Mendes', '273082868', '912345005', 4, 'Psicóloga Clínica', 7, 2, 2, NULL, NULL, '2021-09-20'),
	(18, 14, 'Maria Beatriz Martins', '215904141', '912345001', 2, 'Licenciada em Nutrição', 4, 2, 2, NULL, NULL, '2023-06-01'),
	(19, 15, 'João Ferreira', '285177893', '912345002', 2, 'Mestre em Ciências da Nutrição', 6, 2, 2, NULL, NULL, '2022-11-15'),
	(20, 16, 'Guilherme Sousa', '237919389', '912345003', 3, 'Licenciado em Educação Física', 5, 2, 2, NULL, NULL, '2023-03-10'),
	(21, 20, 'Filipe Pimentel', '258564321', '956780943', 3, 'Licenciatura em Educação Fisica', 2, 2, 2, 'src/uploads/1763036438_Contrato_Filipe_Pimentel.pdf', '', '2025-11-13'),
	(23, 43, 'Eduardo Frechaut', '253456552', '965625623', 2, 'Licenciatura em Nutrição', 5, 2, 2, NULL, '', '2025-12-11'),
	(29, 45, 'Carlos Santos', '444444444', '444444444', 3, 'Licenciatura em Educação Fisica', 2, 2, 2, NULL, 'src/uploads/recibos/1770088122_Contrato_José_Lopes.pdf', '2025-12-14'),
	(45, 67, 'Manuel Silva', '20255225', '2526666', 1, 'mestrado', 10, 3, 2, NULL, '', '2025-12-21'),
	(46, 68, 'Eduardo Silveira', '234567878', '964584746', 4, 'Licenciatura em Psicologia', 10, 2, 2, 'src/uploads/1766853324_Contrato Joana.pdf', '', '2025-12-27');

-- A despejar estrutura para tabela database_aio.salario
CREATE TABLE IF NOT EXISTS `salario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo_rh` int DEFAULT NULL,
  `id_funcao` int DEFAULT NULL,
  `salario_bruto` decimal(10,2) DEFAULT NULL,
  `irs` decimal(10,2) DEFAULT NULL,
  `ss` decimal(10,2) DEFAULT NULL,
  `salario_liquido` decimal(10,2) DEFAULT NULL,
  `subsidio_alimentacao` decimal(10,2) DEFAULT NULL,
  `subsidio_ferias` decimal(10,2) DEFAULT NULL,
  `subsidio_natal` decimal(10,2) DEFAULT NULL,
  `salario_total` decimal(10,2) DEFAULT NULL,
  `data_prevista` date DEFAULT NULL,
  `id_estado` int DEFAULT NULL,
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
	(1, 1, 1, 1992.55, 158.40, 132.00, 1699.55, 132.00, 0.00, 0.00, 1185.37, '2025-11-30', 12),
	(2, 1, 1, 1320.00, 158.40, 132.00, 1188.00, 132.00, 0.00, 0.00, 1185.37, '2025-12-31', 12),
	(3, 1, 1, 1332.00, 158.40, 132.00, 1200.00, 132.00, 0.00, 0.00, 1185.37, '2026-01-31', 13);

-- A despejar estrutura para tabela database_aio.servico
CREATE TABLE IF NOT EXISTS `servico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
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
CREATE TABLE IF NOT EXISTS `suporte_assuntos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
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
CREATE TABLE IF NOT EXISTS `suporte_pedidos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `assunto_id` int NOT NULL,
  `mensagem` text COLLATE utf8mb4_general_ci NOT NULL,
  `estado` enum('aberto','em_progresso','resolvido') COLLATE utf8mb4_general_ci DEFAULT 'aberto',
  `imagem` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_sp_user` (`user_id`),
  KEY `fk_sp_assunto` (`assunto_id`),
  CONSTRAINT `fk_sp_assunto` FOREIGN KEY (`assunto_id`) REFERENCES `suporte_assuntos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_sp_user` FOREIGN KEY (`user_id`) REFERENCES `utilizador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela database_aio.suporte_pedidos: ~1 rows (aproximadamente)
INSERT INTO `suporte_pedidos` (`id`, `user_id`, `assunto_id`, `mensagem`, `estado`, `imagem`, `criado_em`) VALUES
	(5, 1, 4, 'sdadad', 'aberto', NULL, '2025-12-09 19:47:15');

-- A despejar estrutura para tabela database_aio.tipo_ativo
CREATE TABLE IF NOT EXISTS `tipo_ativo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categoria` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `nivel_dificuldade` enum('Iniciante','Intermédio','Avançado') COLLATE utf8mb4_unicode_ci DEFAULT 'Iniciante',
  `duracao_minutos` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.tipo_aula_grupo: ~6 rows (aproximadamente)
INSERT INTO `tipo_aula_grupo` (`id`, `nome`, `descricao`, `nivel_dificuldade`, `duracao_minutos`) VALUES
	(1, 'Yoga Funcional', 'Aulas de Yoga para relaxamento e flexibilidade', 'Iniciante', 60),
	(2, 'HIIT', 'Treino funcional intenso para força e resistência', 'Avançado', 45),
	(3, 'Cardio', 'Exercícios de alongamento e fortalecimento do core', 'Intermédio', 50),
	(4, 'Zumba', 'Aula de dança aeróbica com foco em cardio', 'Iniciante', 55),
	(5, 'Calistenia', 'Treino de bicicleta indoor para resistência e cardio', 'Intermédio', 45),
	(6, 'Treino Funcional (Body WorKout)', 'Aula de grupo com exercícios variados que trabalham todo o corpo, combinando força, resistência e coordenação. Ideal para quem procura um treino completo, dinâmico e adaptável a diferentes níveis.  Equipamentos necessários:  Tapete de exercício; Halteres leves ou bandas elásticas (opcional); Pode ser realizado apenas com o peso do corpo.', 'Iniciante', 60);

-- A despejar estrutura para tabela database_aio.tipo_contrato
CREATE TABLE IF NOT EXISTS `tipo_contrato` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.tipo_corpo: ~3 rows (aproximadamente)
INSERT INTO `tipo_corpo` (`id`, `nome`, `descricao`) VALUES
	(1, 'Ectomorfo', 'Corpo naturalmente magro, com metabolismo acelerado, dificuldade em ganhar massa muscular e gordura. Geralmente apresenta membros longos e ombros estreitos.'),
	(2, 'Mesomorfo', 'Corpo naturalmente atlético, com facilidade para ganhar músculo e perder gordura. Estrutura corporal equilibrada e boa resposta a treinos físicos.'),
	(3, 'Endomorfo', 'Corpo com tendência a acumular gordura, metabolismo mais lento e maior facilidade em ganhar peso. Requer maior controlo alimentar e treinos focados em resistência e perda de gordura.');

-- A despejar estrutura para tabela database_aio.tipo_custo
CREATE TABLE IF NOT EXISTS `tipo_custo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.tipo_custo: ~3 rows (aproximadamente)
INSERT INTO `tipo_custo` (`id`, `descricao`) VALUES
	(1, 'INICIAL'),
	(2, 'MENSAL'),
	(3, 'EXTRAORDINARIO');

-- A despejar estrutura para tabela database_aio.tipo_dieta
CREATE TABLE IF NOT EXISTS `tipo_dieta` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.tipo_dieta: ~6 rows (aproximadamente)
INSERT INTO `tipo_dieta` (`id`, `nome`) VALUES
	(1, 'Tradicional'),
	(2, 'Vegetariano'),
	(4, 'Pescatoriano'),
	(5, 'Vegan (dieta à base de plantas)'),
	(7, 'Mediterrâneo'),
	(10, 'Alto teor de proteína');

-- A despejar estrutura para tabela database_aio.tipo_obrigacao
CREATE TABLE IF NOT EXISTS `tipo_obrigacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.tipo_user: ~5 rows (aproximadamente)
INSERT INTO `tipo_user` (`id`, `nome`) VALUES
	(1, 'Admin'),
	(2, 'PT'),
	(3, 'Cliente'),
	(4, 'Nutricionista'),
	(5, 'Psicólogo');

-- A despejar estrutura para tabela database_aio.treino_exercicios
CREATE TABLE IF NOT EXISTS `treino_exercicios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `plano_id` int NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `repeticoes` int NOT NULL,
  `tempo` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `plano_id` (`plano_id`),
  CONSTRAINT `treino_exercicios_ibfk_1` FOREIGN KEY (`plano_id`) REFERENCES `planos_treino` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.treino_exercicios: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.treinos
CREATE TABLE IF NOT EXISTS `treinos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `duracao_min` int DEFAULT NULL,
  `nivel` enum('Iniciante','Intermédio','Avançado') COLLATE utf8mb4_unicode_ci DEFAULT 'Iniciante',
  `grupo_muscular` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT '1',
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

-- A despejar estrutura para tabela database_aio.utilizador
CREATE TABLE IF NOT EXISTS `utilizador` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_tipo_user` int NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_registo` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_tipo_user` (`id_tipo_user`),
  CONSTRAINT `utilizador_ibfk_1` FOREIGN KEY (`id_tipo_user`) REFERENCES `tipo_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.utilizador: ~49 rows (aproximadamente)
INSERT INTO `utilizador` (`id`, `username`, `email`, `password`, `id_tipo_user`, `foto`, `data_registo`) VALUES
	(1, 'Admin', 'admin@gmail.com', '$2y$10$Mmfu8q3zUprq2qGpyAuPfOdXArL2IYGLLI9BcSzE5BO6TqMVtccOC', 1, '/Projeto_Final_AIO/Landing_page/.backoffice/src/uploads/perfis/perfil_1.jpg', '2025-11-10 10:02:45'),
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
	(18, 'lucia.mendes', 'lucia.mendes@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 2, NULL, '2025-11-10 18:30:40'),
	(20, 'FPimentel', 'filipept@gmail.com', '$2y$10$V7va.u3ACFaPgLe4ude7ZuilwRCVo7So1V9RF2/6BB8V4iJ3sUMzW', 2, '/Projeto_Final_AIO/Landing_page/.backoffice/src/uploads/perfis/perfil_20.jpg', '2025-11-13 12:20:38'),
	(43, 'EFrechaut', 'eduardo@gmail.com', '$2y$10$3ti36AYmbpJ6al29mJnvmuWGzGDFhMcEFjDyXQjoZ8y9wLhqkDgqm', 4, '/Projeto_Final_AIO/Landing_page/.backoffice/src/uploads/perfis/perfil_43.jpg', '2025-12-11 23:39:56'),
	(45, 'edufre', 'eduardofrechaut02@gmail.com', '$2y$10$DNNRSdp/yn9533x0vmVHiuFsHKkltWXxyVDoKT2IZ6z2bPhxnlagW', 2, NULL, '2025-12-14 19:15:38'),
	(54, 'filipep', 'filipe@gmail.com', '$2y$10$O3iU5DU/y6914V01Bw9CZ.iKqgu2e.mLqWirpkCGLCTcIAdDvhH.W', 3, '/Projeto_Final_AIO/Landing_page/.backoffice/src/uploads/perfis/perfil_54.jpg', '2025-12-17 14:39:46'),
	(67, 'Manuel', 'jsilvalopes84@hotmail.com', '$2y$10$kolaja1VRyjoVNuppV5c2eCOPtrHj6r.vdIARP.2J9YzOgBY.h.bu', 1, NULL, '2025-12-21 20:04:25'),
	(68, 'ESilveira', 'eduardopsci@gmail.com', '$2y$10$3I6DyW983zPgik9Ex3rtLeCLRh0f0PTMXgyhOSx5S5r4ldJhmbx3u', 5, NULL, '2025-12-27 16:35:24'),
	(69, 'flor', 'ana@gmail.com', '$2y$10$UFQKFpGorfnyBcpx/5BE/eBkvFXR.s.ztdQkpZeBQw8GFsib1f8BO', 3, NULL, '2026-02-03 00:25:02'),
	(70, 'paulo', 'paulo@gmail.com', '$2y$10$VYnwnmCJd/frisUFFC3phuc0CECBhHc51shoSYVMSZDSWAMqAB3GK', 3, NULL, '2026-02-03 00:42:26'),
	(71, 'carlos', 'carlos@gmail.com', '$2y$10$zwl/VRqzHdf.oAHbYf9lpumwR47grUlou3YVybb4Lb2B/KfX/hKeW', 3, NULL, '2026-02-03 00:46:48'),
	(72, 'sofia', 'miriam@gmail.com', '$2y$10$Shmnsif17JjeypaBaODfAOv6sMdNPsQKK3M/Ou6qVVau7Ff2nH9Ru', 3, NULL, '2026-02-03 00:49:36'),
	(73, 'ilsa', 'ilsa@gmail.com', '$2y$10$2ErFuCKJXgEabL1zwoHzKum7k5aXdyOiRHiWyMz4HI/WXUT8aTL1S', 3, NULL, '2026-02-03 00:52:23'),
	(74, 'edmar', 'edmar@gmail.com', '$2y$10$jOZhRyb3acaP4pSK8PPJ9.qwg4EA9pE95OmLcMkYr412xvMRQ5C8a', 3, NULL, '2026-02-03 00:56:19'),
	(75, 'felix', 'felix@gmail.com', '$2y$10$V36zr304hrcxd/ttcXbzR.LLKM5Pt5I5AZ4nD.dQ7Ae1ZjvLj34VW', 3, NULL, '2026-02-03 00:59:45'),
	(76, 'kevin', 'kevin@gmail.com', '$2y$10$I7cvKNTFNylQlO7d7L6j6OEIUU6pMOqLTz7/j43KYpa92rNgxvtL6', 3, NULL, '2026-02-03 01:02:11'),
	(77, 'pedro', 'pedro@gmail.com', '$2y$10$yGe29GXEARsTr1Tep.cVtuwjlcirdQcb6YjxROIE.DlJfxwcA47xK', 3, NULL, '2026-02-03 01:04:32'),
	(78, 'elsa', 'elsa@gmail.com', '$2y$10$k3Of/usY17Fr1dZb0wf8we0O9xel/YPKB9VWobhAx9XuF7OSO7uom', 3, NULL, '2026-02-03 01:10:18'),
	(79, 'bruno', 'bruno@gmail.com', '$2y$10$utq/kk/3WZTZZyoj7LpvuO3Q74fBpzKLQt2SFlKb/9p8xED8gzKk2', 3, NULL, '2026-02-03 02:14:33'),
	(80, 'fabio', 'fabio@gmail.com', '$2y$10$nAT277n1lpgg2QfkTpJMVexbb4YdinDKC3us5G6E2.DV6ZeshV3q.', 3, NULL, '2026-02-03 02:17:32'),
	(81, 'marcia', 'marcia@gmail.com', '$2y$10$InDmzYwHjqn0rDLqPHmSDugGwKJSTxnXFcxlPpQi9g3Aj9CbhYDm2', 3, NULL, '2026-02-03 02:20:30'),
	(82, 'marvin', 'marvin@gmail.com', '$2y$10$JVOiOMaU.VFrZP9IZdPkzOJEN2ZSNrgvmpOlHJT6fe4TNhixODyw6', 3, NULL, '2026-02-03 02:23:13'),
	(83, 'helder', 'helder@gmail.com', '$2y$10$NiL98tbVJga/CvnVtmVEpOoZPQLXKJphYnCFiekWFEZEDqGXGGivC', 3, NULL, '2026-02-03 02:26:24'),
	(84, 'mariana', 'mariana@gmail.com', '$2y$10$dRT7H0m8UNi9HnDDLbYlRebo9jRe4.22NRzVN5Op14HNvl3ftH6gW', 3, NULL, '2026-02-03 02:28:47'),
	(85, 'edla', 'edla@gmail.com', '$2y$10$7KrQQjfhnEcfj9Xj43kOhu8ZNKEEpUAWDmUJBbKUmQIYE3/1yNiqG', 3, NULL, '2026-02-03 02:30:53'),
	(86, 'cristiano', 'cristiano@gmail.com', '$2y$10$zYFNGEqOCaKw0IBngeuYceAowcPigPCSegVkLqqZeAAlwI9E2twIa', 3, NULL, '2026-02-03 02:33:02'),
	(87, 'sonia', 'sonia@gmail.com', '$2y$10$M6VymxmsMubkYMTBUgSkUes0AsZV950jMVosCjNeYQ8otiAyWtORG', 3, NULL, '2026-02-03 02:35:29'),
	(88, 'david', 'david@gmail.com', '$2y$10$xesmyuz2QGvZfYgIDj2MneeC4JpC89IHXOLVFH4.3KX3URfuQWzK6', 3, NULL, '2026-02-03 02:37:33'),
	(89, 'tiago', 'tiago@gmail.com', '$2y$10$R0F1u4URWzoCsSdmfIHHuOZmQW0vauPGqw.ZYiiPLxOBGE242838i', 3, NULL, '2026-02-03 02:39:41'),
	(90, 'antony', 'antony@gmail.com', '$2y$10$GdTbSoxi7Tt/PHTQXPDVwOuecU1K8tK2eHJ4ad3cs2uh8xFRrRW6i', 3, NULL, '2026-02-03 02:41:58'),
	(91, 'goncalo', 'goncalo@gmail.com', '$2y$10$7RJMdvV5j9gJeNWbawA0/ezYpo3X1QvJ.T/cQCGRim6jZ0wdgJDAK', 3, NULL, '2026-02-03 02:45:16'),
	(92, 'hibrido', 'hibrido@gmail.com', '$2y$10$gBA5R4mRY74y0MbvRt8eeOSVu.o9uRHRYw/4GMUUHqz2EDt8Q8AQ.', 3, NULL, '2026-02-03 02:48:15'),
	(93, 'romeu', 'romeu@gmail.com', '$2y$10$VD5Vrd0wJvoRE4Q7X9D1c.rfiXrrwmRPuXP7vubOVk3Nc6zNsspAK', 3, NULL, '2026-02-03 02:52:44'),
	(94, 'marcio', 'marcio@gmail.com', '$2y$10$508Cc3llgCoyoCFRbw2UEOogc60nHZFShz9aJ2jmKCRIUVhz55Pq.', 3, NULL, '2026-02-03 02:55:45');

-- A despejar estrutura para tabela database_aio.venda
CREATE TABLE IF NOT EXISTS `venda` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int NOT NULL,
  `id_servico` int NOT NULL,
  `id_prestador` int DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `desconto` decimal(10,2) DEFAULT '0.00',
  `iva` decimal(10,2) DEFAULT '0.00',
  `total_final` decimal(10,2) DEFAULT NULL,
  `moeda` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'EUR',
  `data_venda` date NOT NULL,
  `metodo_pagamento` enum('cartao','transferencia','paypal','gratis') COLLATE utf8mb4_unicode_ci DEFAULT 'cartao',
  `id_estado` int NOT NULL,
  `fatura` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_cliente` (`id_cliente`),
  KEY `idx_servico` (`id_servico`),
  KEY `idx_estado` (`id_estado`),
  KEY `fk_venda_prestador` (`id_prestador`),
  CONSTRAINT `fk_venda_prestador` FOREIGN KEY (`id_prestador`) REFERENCES `rh` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `venda_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`codigo`) ON UPDATE CASCADE,
  CONSTRAINT `venda_ibfk_2` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `venda_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.venda: ~18 rows (aproximadamente)
INSERT INTO `venda` (`id`, `id_cliente`, `id_servico`, `id_prestador`, `valor`, `subtotal`, `desconto`, `iva`, `total_final`, `moeda`, `data_venda`, `metodo_pagamento`, `id_estado`, `fatura`) VALUES
	(3, 3, 6, 46, 60.00, NULL, 0.00, 0.00, NULL, 'EUR', '2025-11-03', 'paypal', 12, ''),
	(4, 1, 7, 23, 50.00, NULL, 0.00, 0.00, NULL, 'EUR', '2026-01-20', 'cartao', 12, ''),
	(5, 2, 7, 23, 50.00, NULL, 0.00, 0.00, NULL, 'EUR', '2026-01-15', 'paypal', 12, ''),
	(6, 36, 6, 46, 51236.58, NULL, 0.00, 0.00, NULL, 'EUR', '2026-01-03', 'cartao', 12, NULL),
	(7, 3, 10, 20, 20000.00, NULL, 0.00, 0.00, NULL, 'EUR', '2026-02-03', 'cartao', 12, NULL),
	(8, 36, 7, 23, 50.00, NULL, 0.00, 0.00, NULL, 'EUR', '2026-02-03', 'cartao', 13, NULL),
	(9, 57, 7, 23, 50.00, NULL, 0.00, 0.00, NULL, 'EUR', '2026-02-03', 'cartao', 13, NULL),
	(10, 46, 7, 23, 50.00, NULL, 0.00, 0.00, NULL, 'EUR', '2026-02-02', 'cartao', 12, NULL),
	(87, 34, 12, 21, 15.00, NULL, 0.00, 0.00, NULL, 'EUR', '2026-02-01', 'cartao', 12, NULL),
	(88, 34, 12, 21, 15.00, NULL, 0.00, 0.00, NULL, 'EUR', '2026-02-01', 'cartao', 13, NULL),
	(89, 34, 12, 21, 15.00, NULL, 0.00, 0.00, NULL, 'EUR', '2026-02-01', 'cartao', 13, NULL),
	(102, 34, 10, NULL, 15.99, NULL, 0.00, 0.00, NULL, 'EUR', '2026-02-03', 'cartao', 12, NULL),
	(103, 34, 10, NULL, 12.00, NULL, 0.00, 0.00, NULL, 'EUR', '2026-02-03', 'cartao', 12, NULL),
	(104, 55, 2, NULL, 70.00, NULL, 0.00, 0.00, NULL, 'EUR', '2026-02-03', 'cartao', 12, ''),
	(105, 1, 2, NULL, 500.00, NULL, 0.00, 0.00, NULL, 'EUR', '2026-01-15', 'cartao', 12, ''),
	(106, 1, 2, NULL, 1500.00, NULL, 0.00, 0.00, NULL, 'EUR', '2026-02-04', 'cartao', 12, ''),
	(120, 34, 10, NULL, 12.00, NULL, 0.00, 0.00, NULL, 'EUR', '2026-02-05', 'cartao', 12, NULL),
	(121, 34, 10, NULL, 34.99, NULL, 0.00, 0.00, NULL, 'EUR', '2026-02-05', 'cartao', 12, NULL),
	(122, 34, 10, NULL, 67.99, NULL, 0.00, 0.00, NULL, 'EUR', '2026-02-05', 'cartao', 12, NULL),
	(123, 34, 10, NULL, 32.98, NULL, 0.00, 0.00, NULL, 'EUR', '2026-02-05', 'cartao', 12, NULL);

-- A despejar estrutura para tabela database_aio.venda_buyer_snapshot
CREATE TABLE IF NOT EXISTS `venda_buyer_snapshot` (
  `id_venda` int NOT NULL,
  `nome` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nif` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_dados` tinyint(1) NOT NULL DEFAULT '0',
  `termos_aceites` tinyint(1) NOT NULL DEFAULT '1',
  `criado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_venda`),
  CONSTRAINT `fk_venda_buyer_snapshot` FOREIGN KEY (`id_venda`) REFERENCES `venda` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.venda_buyer_snapshot: ~4 rows (aproximadamente)
INSERT INTO `venda_buyer_snapshot` (`id_venda`, `nome`, `email`, `nif`, `telefone`, `alt_dados`, `termos_aceites`, `criado_em`) VALUES
	(102, 'Filipe', 'filipe@gmail.com', '534534534', '895644564', 0, 1, '2026-02-03 15:42:10'),
	(103, 'Filipe', 'filipe@gmail.com', '534534534', '895644564', 0, 1, '2026-02-03 16:19:08'),
	(120, 'Filipe', 'filipe@gmail.com', '534534534', '895644564', 0, 1, '2026-02-05 13:39:55'),
	(121, 'Filipe', 'filipe@gmail.com', '534534534', '895644564', 0, 1, '2026-02-05 14:37:47'),
	(122, 'Filipe', 'filipe@gmail.com', '534534534', '895644564', 0, 1, '2026-02-05 20:52:41'),
	(123, 'Filipe', 'filipe@gmail.com', '534534534', '895644564', 0, 1, '2026-02-05 23:15:34');

-- A despejar estrutura para tabela database_aio.venda_cupao
CREATE TABLE IF NOT EXISTS `venda_cupao` (
  `id_venda` int NOT NULL,
  `id_cupao` int NOT NULL,
  `codigo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desconto_aplicado` decimal(10,2) NOT NULL,
  `criado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_venda`),
  KEY `idx_cupao` (`id_cupao`),
  CONSTRAINT `fk_venda_cupao_cupao` FOREIGN KEY (`id_cupao`) REFERENCES `cupao` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_venda_cupao_venda` FOREIGN KEY (`id_venda`) REFERENCES `venda` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.venda_cupao: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela database_aio.venda_marketplace
CREATE TABLE IF NOT EXISTS `venda_marketplace` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_venda` int NOT NULL,
  `id_parceiro` int NOT NULL,
  `produto_nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `preco_produto` decimal(10,2) NOT NULL,
  `percentual_comissao` decimal(5,2) NOT NULL,
  `valor_comissao` decimal(10,2) GENERATED ALWAYS AS ((`preco_produto` * (`percentual_comissao` / 100))) STORED,
  PRIMARY KEY (`id`),
  KEY `idx_venda` (`id_venda`),
  KEY `idx_parceiro` (`id_parceiro`),
  CONSTRAINT `venda_marketplace_ibfk_1` FOREIGN KEY (`id_venda`) REFERENCES `venda` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venda_marketplace_ibfk_2` FOREIGN KEY (`id_parceiro`) REFERENCES `parceiro_marketplace` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela database_aio.venda_marketplace: ~11 rows (aproximadamente)
INSERT INTO `venda_marketplace` (`id`, `id_venda`, `id_parceiro`, `produto_nome`, `preco_produto`, `percentual_comissao`) VALUES
	(13, 4, 1, 'Halteres 10kg', 50.00, 10.00),
	(14, 4, 1, 'Tapete de Yoga', 70.00, 10.00),
	(15, 5, 2, 'Tênis de Corrida', 150.00, 12.00),
	(16, 5, 3, 'Suplemento Whey Protein', 100.00, 8.50),
	(17, 5, 4, 'Kettlebell 12kg', 80.00, 15.00),
	(18, 5, 5, 'Bola de Pilates', 60.00, 10.00),
	(32, 102, 4, 'T-shirt desportiva', 15.99, 15.00),
	(33, 103, 5, 'Bola de Fitness', 12.00, 10.00),
	(34, 120, 5, 'Bola de Fitness', 12.00, 10.00),
	(35, 121, 5, 'Bola de Fitness', 12.00, 10.00),
	(36, 121, 1, 'Creatina', 22.99, 10.00),
	(37, 122, 2, 'Tapete  IOGA', 12.99, 12.00),
	(38, 122, 3, 'Tênis Fitness', 55.00, 8.50),
	(39, 123, 4, 'T-shirt desportiva', 15.99, 15.00),
	(40, 123, 4, 'kettlebells', 16.99, 15.00);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
