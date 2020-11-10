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
-- Data for Name: userlevels; Type: TABLE DATA; Schema: registro_derecho; Owner: -
--

COPY registro_derecho.userlevels (userlevelid, userlevelname, idperfil) FROM stdin;
-2	Anónimo	\N
0	Default	1
-1	Administrator	1
-2	Anónimo	0
\.


--
-- PostgreSQL database dump complete
--

