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
-- Data for Name: usuario; Type: TABLE DATA; Schema: registro_derecho; Owner: -
--

COPY registro_derecho.usuario (idusuario, userlevelid, "user", password, nombre, email, idperfil, activo, autologinip) FROM stdin;
1	-1	admin	21232f297a57a5a743894a0e4a801fc3	Administrador	@	1	1	\N
8	-1	aalavarez2	123456	alexander alvarez (Servidor prueba)	a@a.com	1	1	192.168.50.1
6	-1	aalvarezv	56789	Alexander Alvarez Vaca (localhost)	@	1	1	127.0.0.1
\.


--
-- Name: usuario_idusuario_seq; Type: SEQUENCE SET; Schema: registro_derecho; Owner: -
--

SELECT pg_catalog.setval('registro_derecho.usuario_idusuario_seq', 8, true);


--
-- PostgreSQL database dump complete
--

