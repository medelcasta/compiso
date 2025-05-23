-- MySQL Script generated by MySQL Workbench
-- Mon Apr  7 12:02:13 2025
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema compisodb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `compisodb` ;

-- -----------------------------------------------------
-- Schema compisodb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `compisodb` DEFAULT CHARACTER SET utf8 ;
USE `compisodb` ;

-- -----------------------------------------------------
-- Table `compisodb`.`Usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `compisodb`.`Usuario` ;

CREATE TABLE IF NOT EXISTS `compisodb`.`Usuario` (
  `id_usuario` VARCHAR(45) NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `apellidos` VARCHAR(45) NULL,
  `email` VARCHAR(45) NOT NULL,
  `telefono` INT NULL,
  `tipo_usuario` VARCHAR(45) NOT NULL,
  `fecha_nacimiento` DATE NOT NULL,
  `sexo` VARCHAR(45) NULL,
  `descripcion` VARCHAR(45) NULL,
  `administrador` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_usuario`))
ENGINE = InnoDB;

CREATE UNIQUE INDEX `email_UNIQUE` ON `compisodb`.`Usuario` (`email` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `compisodb`.`Mensaje`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `compisodb`.`Mensaje` ;

CREATE TABLE IF NOT EXISTS `compisodb`.`Mensaje` (
  `id_mensaje` VARCHAR(45) NOT NULL,
  `id_usuario1` VARCHAR(45) NOT NULL,
  `id_usuario2` VARCHAR(45) NOT NULL,
  `contenido` VARCHAR(45) NULL,
  `fecha` DATE NULL,
  `hora` TIME NULL,
  PRIMARY KEY (`id_mensaje`),
  CONSTRAINT `FK_mensaje_usuario`
    FOREIGN KEY (`id_usuario1` , `id_usuario2`)
    REFERENCES `compisodb`.`Usuario` (`id_usuario` , `id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `FK_mensaje_usuario_idx` ON `compisodb`.`Mensaje` (`id_usuario1` ASC, `id_usuario2` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `compisodb`.`Inquilino`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `compisodb`.`Inquilino` ;

CREATE TABLE IF NOT EXISTS `compisodb`.`Inquilino` (
  `id_inquilino` VARCHAR(45) NOT NULL,
  `preferencias` VARCHAR(45) NULL,
  `datos_bancarios` VARCHAR(45) NULL,
  `id_usuario` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_inquilino`),
  CONSTRAINT `FK_inquilino_usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `compisodb`.`Usuario` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `FK_inquilino_usuario_idx` ON `compisodb`.`Inquilino` (`id_usuario` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `compisodb`.`Propietario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `compisodb`.`Propietario` ;

CREATE TABLE IF NOT EXISTS `compisodb`.`Propietario` (
  `id_propietario` VARCHAR(45) NOT NULL,
  `datos_bancarios` VARCHAR(45) NOT NULL,
  `id_usuario` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_propietario`, `datos_bancarios`),
  CONSTRAINT `FK_propietario_usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `compisodb`.`Usuario` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `id_propietario_UNIQUE` ON `compisodb`.`Propietario` (`id_propietario` ASC) VISIBLE;

CREATE INDEX `FK_propietario_usuario_idx` ON `compisodb`.`Propietario` (`id_usuario` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `compisodb`.`Vivienda`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `compisodb`.`Vivienda` ;

CREATE TABLE IF NOT EXISTS `compisodb`.`Vivienda` (
  `id_vivienda` VARCHAR(45) NOT NULL,
  `direccion` VARCHAR(45) NOT NULL,
  `ciudad` VARCHAR(45) NOT NULL,
  `descripcion` VARCHAR(45) NULL,
  `precio` DECIMAL(2) NOT NULL,
  `habitaciones` INT NULL,
  `baños` INT NULL,
  `metros_cuadrados` INT NULL,
  `disponibilidad` TINYINT NOT NULL,
  `imagenes` VARCHAR(45) NOT NULL,
  `id_propietario` VARCHAR(45) NULL,
  PRIMARY KEY (`id_vivienda`),
  CONSTRAINT `FK_vivienda_propietario`
    FOREIGN KEY (`id_propietario`)
    REFERENCES `compisodb`.`Propietario` (`id_propietario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `FK_vivienda_propietario_idx` ON `compisodb`.`Vivienda` (`id_propietario` ASC) VISIBLE;

CREATE UNIQUE INDEX `direccion_UNIQUE` ON `compisodb`.`Vivienda` (`direccion` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `compisodb`.`Reserva`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `compisodb`.`Reserva` ;

CREATE TABLE IF NOT EXISTS `compisodb`.`Reserva` (
  `id_reserva` VARCHAR(45) NOT NULL,
  `fecha_inicio` DATE NOT NULL,
  `fecha_fin` DATE NULL,
  `estado` VARCHAR(45) NOT NULL,
  `precio` DECIMAL NULL,
  `id_vivienda` VARCHAR(45) NOT NULL,
  `id_inquilino` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_reserva`),
  CONSTRAINT `FK_reserva_inquilino`
    FOREIGN KEY (`id_inquilino`)
    REFERENCES `compisodb`.`Inquilino` (`id_inquilino`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_reserva_vivienda`
    FOREIGN KEY (`id_vivienda`)
    REFERENCES `compisodb`.`Vivienda` (`id_vivienda`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `FK_reseva_inquilino_idx` ON `compisodb`.`Reserva` (`id_inquilino` ASC) VISIBLE;

CREATE INDEX `FK_reserva_vivienda_idx` ON `compisodb`.`Reserva` (`id_vivienda` ASC) VISIBLE;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
