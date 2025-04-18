CREATE DATABASE SALMONERA;
USE SALMONERA;
CREATE TABLE CENTRO(
CENTROID NUMERIC NOT NULL,
CENTRONUM NUMERIC NOT NULL,
UBICACION VARCHAR(30),
NUMJAULAS NUMERIC NOT NULL,
PRIMARY KEY(CENTROID));
CREATE TABLE JAULA(
JAULAID NUMERIC NOT NULL,
PRIMARY KEY(JAULAID),
JAULANUM NUMERIC NOT NULL,
BIOMASA NUMERIC NOT NULL,
OXIGENO NUMERIC NOT NULL,
TEMPERATURA NUMERIC NOT NULL,
MUERTEESP NUMERIC NOT NULL,
MUERTEACT NUMERIC NOT NULL,
NATALIDAD NUMERIC NOT NULL,
CENTROID NUMERIC NOT NULL,
CONSTRAINT FK_CENTROJAULA FOREIGN KEY(CENTROID) REFERENCES CENTRO(CENTROID));
