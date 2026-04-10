CREATE DATABASE IF NOT EXISTS `webanime_ci4_replica` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `webanime_ci4_replica`;

CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO roles (id, nombre) VALUES
  (1, 'Admin'),
  (2, 'Registrado');

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo_publico VARCHAR(20) NOT NULL UNIQUE,
  correo VARCHAR(150) NOT NULL UNIQUE,
  hash_password VARCHAR(255) NOT NULL,
  nombre_mostrar VARCHAR(100) NOT NULL UNIQUE,
  rol_id INT NULL,
  es_premium TINYINT(1) NOT NULL DEFAULT 0,
  premium_vence_en DATETIME NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  bloqueado TINYINT(1) NOT NULL DEFAULT 0,
  bloqueado_en DATETIME NULL,
  bloqueado_por INT NULL,
  desbloqueado_en DATETIME NULL,
  desbloqueado_por INT NULL,
  motivo_bloqueo VARCHAR(255) NULL,
  penalizacion_hasta DATETIME NULL,
  password_actualizado_en DATETIME NULL,
  password_actualizado_por INT NULL,
  rol_actualizado_en DATETIME NULL,
  rol_actualizado_por INT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_usuarios_rol_id (rol_id),
  INDEX idx_usuarios_bloqueado_por (bloqueado_por),
  INDEX idx_usuarios_desbloqueado_por (desbloqueado_por),
  INDEX idx_usuarios_password_actualizado_por (password_actualizado_por),
  INDEX idx_usuarios_rol_actualizado_por (rol_actualizado_por),
  CONSTRAINT fk_usuarios_roles FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE SET NULL,
  CONSTRAINT fk_usuarios_bloqueado_por FOREIGN KEY (bloqueado_por) REFERENCES usuarios(id) ON DELETE SET NULL,
  CONSTRAINT fk_usuarios_desbloqueado_por FOREIGN KEY (desbloqueado_por) REFERENCES usuarios(id) ON DELETE SET NULL,
  CONSTRAINT fk_usuarios_password_actualizado_por FOREIGN KEY (password_actualizado_por) REFERENCES usuarios(id) ON DELETE SET NULL,
  CONSTRAINT fk_usuarios_rol_actualizado_por FOREIGN KEY (rol_actualizado_por) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS usuarios_sesiones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  inicio DATETIME NOT NULL,
  fin DATETIME NULL,
  duracion INT NULL,
  INDEX idx_usuarios_sesiones_usuario (usuario_id),
  CONSTRAINT fk_usuarios_sesiones_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS usuarios_meta (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  meta_key VARCHAR(100) NOT NULL,
  meta_value LONGTEXT NULL,
  UNIQUE KEY uniq_usuario_meta (usuario_id, meta_key),
  CONSTRAINT fk_usuarios_meta_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS usuarios_actividad (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  accion VARCHAR(100) NOT NULL,
  detalles TEXT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_usuarios_actividad_usuario (usuario_id),
  CONSTRAINT fk_usuarios_actividad_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS usuarios_reportes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  anime_id INT NOT NULL,
  mensaje TEXT NOT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_usuarios_reportes_usuario (usuario_id),
  CONSTRAINT fk_usuarios_reportes_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS jikan_cache (
  cache_key CHAR(32) PRIMARY KEY,
  endpoint VARCHAR(255) NOT NULL,
  response LONGTEXT NOT NULL,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_jikan_cache_updated_at (updated_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS anime (
  id INT AUTO_INCREMENT PRIMARY KEY,
  mal_id INT NULL,
  titulo VARCHAR(255) NOT NULL,
  titulo_ingles VARCHAR(255) NULL,
  tipo VARCHAR(50) NOT NULL DEFAULT 'TV',
  estudio VARCHAR(150) NULL,
  estado VARCHAR(50) NULL,
  episodios INT NULL,
  temporada VARCHAR(50) NULL,
  anio INT NULL,
  clasificacion VARCHAR(50) NULL,
  sinopsis TEXT NULL,
  imagen_url VARCHAR(500) NULL,
  trailer_url VARCHAR(500) NULL,
  puntuacion DECIMAL(4,2) NULL DEFAULT 0.00,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  creado_por INT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_anime_mal_id (mal_id),
  INDEX idx_anime_titulo (titulo),
  INDEX idx_anime_tipo (tipo),
  INDEX idx_anime_estado (estado),
  INDEX idx_anime_anio (anio),
  CONSTRAINT fk_anime_creado_por FOREIGN KEY (creado_por) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS generos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS anime_generos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  anime_id INT NOT NULL,
  genero_id INT NOT NULL,
  UNIQUE KEY uniq_anime_genero (anime_id, genero_id),
  INDEX idx_anime_generos_genero (genero_id),
  CONSTRAINT fk_anime_generos_anime FOREIGN KEY (anime_id) REFERENCES anime(id) ON DELETE CASCADE,
  CONSTRAINT fk_anime_generos_genero FOREIGN KEY (genero_id) REFERENCES generos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS usuarios_vistas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  anime_id INT NOT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_usuario_anime_vista (usuario_id, anime_id),
  INDEX idx_usuarios_vistas_anime (anime_id),
  CONSTRAINT fk_usuarios_vistas_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  CONSTRAINT fk_usuarios_vistas_anime FOREIGN KEY (anime_id) REFERENCES anime(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS comentarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  anime_id INT NOT NULL,
  cuerpo TEXT NOT NULL,
  puntuacion TINYINT NULL,
  fuente VARCHAR(30) NOT NULL DEFAULT 'usuario',
  autor_externo VARCHAR(120) NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_comentarios_usuario (usuario_id),
  INDEX idx_comentarios_anime (anime_id),
  INDEX idx_comentarios_creado_en (creado_en),
  CONSTRAINT fk_comentarios_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  CONSTRAINT fk_comentarios_anime FOREIGN KEY (anime_id) REFERENCES anime(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS reportes_comentarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  comentario_id INT NOT NULL,
  reportado_por INT NOT NULL,
  razon VARCHAR(255) NOT NULL,
  estado ENUM('pendiente','en_revision','Revisado','Eliminado') NOT NULL DEFAULT 'pendiente',
  revisado_por INT NULL,
  revisado_en DATETIME NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_reportes_comentarios_comentario (comentario_id),
  INDEX idx_reportes_comentarios_reportado_por (reportado_por),
  INDEX idx_reportes_comentarios_estado (estado),
  INDEX idx_reportes_comentarios_revisado_por (revisado_por),
  CONSTRAINT fk_reportes_comentarios_comentario FOREIGN KEY (comentario_id) REFERENCES comentarios(id) ON DELETE CASCADE,
  CONSTRAINT fk_reportes_comentarios_reportado_por FOREIGN KEY (reportado_por) REFERENCES usuarios(id) ON DELETE CASCADE,
  CONSTRAINT fk_reportes_comentarios_revisado_por FOREIGN KEY (revisado_por) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS anime_episodes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  anime_id INT NOT NULL,
  episode_number INT NOT NULL,
  title VARCHAR(255) DEFAULT NULL,
  synopsis TEXT DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_anime_episode (anime_id, episode_number),
  CONSTRAINT fk_anime_episodes_anime FOREIGN KEY (anime_id) REFERENCES anime(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS solicitudes_anime (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  user_name VARCHAR(120) NULL,
  titulo VARCHAR(255) NOT NULL,
  tipo VARCHAR(50) NOT NULL DEFAULT 'Anime',
  fuente VARCHAR(255) NULL,
  estado ENUM('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  resuelto_en DATETIME NULL,
  resuelto_por INT NULL,
  INDEX idx_solicitudes_estado (estado),
  INDEX idx_solicitudes_creado (creado_en),
  INDEX idx_solicitudes_user (user_id),
  INDEX idx_solicitudes_resuelto_por (resuelto_por),
  CONSTRAINT fk_solicitudes_user FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE SET NULL,
  CONSTRAINT fk_solicitudes_resuelto_por FOREIGN KEY (resuelto_por) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
