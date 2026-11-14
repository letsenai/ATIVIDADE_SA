-- banco/script.sql — criação e carga do banco do sistema Ferrorama / GoMobi.
-- Como executar: phpMyAdmin → aba "SQL" → colar e executar, ou aba "Importar".
-- Pode ser executado mais de uma vez: não apaga nada e não duplica o administrador.

CREATE DATABASE IF NOT EXISTS ferrovias
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE ferrovias;

-- Usuários do sistema.
-- senha:  VARCHAR(255) porque o hash do password_hash() passa de 60 caracteres e pode crescer.
-- papel:  ENUM, então o próprio banco recusa um papel inválido.
--         A lista precisa ser a mesma de PAPEIS em includes/permissao.php.
-- login:  UNIQUE; guarda o e-mail usado para entrar.
CREATE TABLE IF NOT EXISTS usuario (
  id     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome   VARCHAR(100) NOT NULL,
  login  VARCHAR(100) NOT NULL,
  senha  VARCHAR(255) NOT NULL,
  papel  ENUM('administrador', 'operador', 'tecnico') NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_usuario_login (login)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- Administrador inicial (o primeiro usuário não é cadastrado pela tela).
-- A senha NÃO está aqui: só o hash dela.
-- Senha inicial: Admin@2026. Para usar outra senha, gere um novo hash no terminal do XAMPP:
--   C:\xampp\php\php.exe -r "echo password_hash('sua_senha', PASSWORD_DEFAULT);"
-- e troque o texto entre aspas na linha abaixo.
INSERT IGNORE INTO usuario (nome, login, senha, papel) VALUES
  ('Administrador', 'admin@gomobi.com', '$2y$10$2G94syXwCmD2ISTqh7I7SuFizHWkS.hWXlxLViU2K.AqS1zykyTda', 'administrador');

-- Usuários de demonstração (um de cada perfil, para testar o controle de acesso).
--   operador@gomobi.com  /  Operador@2026
--   tecnico@gomobi.com   /  Tecnico@2026
INSERT IGNORE INTO usuario (nome, login, senha, papel) VALUES
  ('Operador Demo', 'operador@gomobi.com', '$2y$10$iWdXA.E0Paz/Siu3Gw2Sm.WbIVuIy/p4RFjYrt4eECJtV1oXr6GX2', 'operador'),
  ('Técnico Demo',  'tecnico@gomobi.com',  '$2y$10$Wy.0Lhe9BmJ4LO/fch8UJusCCo1d/SMgZYhERaoxNdQUTgK18t6ya', 'tecnico');

-- Tokens de recuperação de senha (só o hash do token é guardado).
CREATE TABLE IF NOT EXISTS recuperacao_senha (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id INT UNSIGNED NOT NULL,
  token_hash CHAR(64) NOT NULL,
  expira_em  DATETIME NOT NULL,
  usado      TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_token (token_hash),
  CONSTRAINT fk_recup_usuario FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ===== ENTREGA 6: trens e sensores =====
CREATE TABLE IF NOT EXISTS trem (
  id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  codigo          VARCHAR(20)  NOT NULL,
  linha           VARCHAR(50)  NOT NULL,
  status          ENUM('Online','Warning','Offline') NOT NULL DEFAULT 'Online',
  proxima_estacao VARCHAR(80)  NOT NULL DEFAULT '',
  velocidade      SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY uq_trem_codigo (codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS sensor (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  codigo      VARCHAR(20)  NOT NULL,
  nome        VARCHAR(100) NOT NULL,
  tipo        VARCHAR(30)  NOT NULL,
  localizacao VARCHAR(100) NOT NULL DEFAULT '',
  threshold   DECIMAL(10,2) NOT NULL,
  status      ENUM('Online','Warning','Offline') NOT NULL DEFAULT 'Online',
  trem_id     INT UNSIGNED NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_sensor_codigo (codigo),
  CONSTRAINT fk_sensor_trem FOREIGN KEY (trem_id) REFERENCES trem (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO trem (codigo, linha, status, proxima_estacao, velocidade) VALUES
  ('TR-001','A Line','Warning','125th St',24),
  ('TR-002','C Line','Offline','96th St',0),
  ('TR-003','E Line','Online','72nd St',48),
  ('TR-004','1 Line','Warning','42nd St',18),
  ('TR-005','2 Line','Offline','34th St',0),
  ('TR-006','3 Line','Online','14th St',52);

INSERT IGNORE INTO sensor (codigo, nome, tipo, localizacao, threshold, status, trem_id) VALUES
  ('SENS-001','Temperature Sensor A1','Temperature','Car 3',75.50,'Warning',(SELECT id FROM trem WHERE codigo='TR-001')),
  ('SENS-002','Brake Sensor B2','Brake','Car 1',80.00,'Online',(SELECT id FROM trem WHERE codigo='TR-003')),
  ('SENS-003','Vibration Sensor C1','Vibration','Car 2',60.00,'Online',(SELECT id FROM trem WHERE codigo='TR-003')),
  ('SENS-004','GPS Unit D1','GPS','Cabin',1.00,'Offline',(SELECT id FROM trem WHERE codigo='TR-002')),
  ('SENS-005','Speed Sensor E1','Velocity','Car 1',120.00,'Online',(SELECT id FROM trem WHERE codigo='TR-006'));
