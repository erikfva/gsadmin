--
-- PostgreSQL database dump
--

-- Dumped from database version 10.14
-- Dumped by pg_dump version 10.14

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: appacciones; Type: TABLE DATA; Schema: registro_derecho; Owner: -
--

COPY registro_derecho.appacciones (idaccion, accion, contexto) FROM stdin;
allow_point	Aceptar sólo geometrías de PUNTOS	subir_shapefile
allow_line	Aceptar sólo geometrías de LINEAS	subir_shapefile
allow_polygon	Aceptar sólo geometrías de POLIGONOS	subir_shapefile
intersect_tit	Realizar la intersección con los predios titulados del INRA	geoprocesamiento
geojson	Retornar resultado en formato geoJSON	subir_shapefile
fix_si	Corregir intersección con sigo mismo	subir_shapefile
sicob_fix_si	Corregir intersección con sigo mismo	geoprocesamiento
sicob_obtener_predio	Encuentra el (los) predio(s) en donde se encuentra la capa de entrada	geoprocesamiento
sicob_build_shapefiles	Crea archivos shapefiles y los empaqueta en un archivo comprimido.	geoprocesamiento
prueba1	Prueba desde webservice	\N
prueba2	Prueba desde webservice	\N
\.


--
-- PostgreSQL database dump complete
--

