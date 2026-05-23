-- Script para actualizar la tabla profesores existente
-- Ejecutar en phpMyAdmin o línea de comandos MySQL
-- Este script agrega los campos: CUIL, Dirección, Teléfono, Mail, Título, Legajo

USE `control_asistencias`;

ALTER TABLE `profesores`
  ADD COLUMN `cuil` VARCHAR(15) NULL AFTER `dni`,
  ADD COLUMN `direccion` VARCHAR(255) NULL AFTER `cuil`,
  ADD COLUMN `telefono` VARCHAR(30) NULL AFTER `direccion`,
  ADD COLUMN `mail` VARCHAR(150) NULL AFTER `telefono`,
  ADD COLUMN `titulo` VARCHAR(150) NULL AFTER `mail`,
  ADD COLUMN `legajo` VARCHAR(30) NULL AFTER `titulo`,
  ADD UNIQUE KEY `idx_cuil` (`cuil`),
  ADD UNIQUE KEY `idx_legajo` (`legajo`);
