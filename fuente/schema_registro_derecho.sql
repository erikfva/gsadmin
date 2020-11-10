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

ALTER TABLE ONLY registro_derecho.usuario DROP CONSTRAINT usuario_fk_perfil;
ALTER TABLE ONLY registro_derecho.geoprocesamiento DROP CONSTRAINT geoprocesamiento_fk_usuario;
DROP TRIGGER geoprocesamiento_tr_notify_end ON registro_derecho.geoprocesamiento;
DROP TRIGGER geoprocesamiento_tr_au ON registro_derecho.geoprocesamiento;
DROP INDEX registro_derecho.usuario_fk1;
DROP INDEX registro_derecho.userlevels_fk;
DROP INDEX registro_derecho."user";
ALTER TABLE ONLY registro_derecho.usuario DROP CONSTRAINT usuario_primary;
ALTER TABLE ONLY registro_derecho.shapefiles DROP CONSTRAINT shapefiles_pkey;
ALTER TABLE ONLY registro_derecho.pruebas DROP CONSTRAINT pruebas_pkey;
ALTER TABLE ONLY registro_derecho.perfil DROP CONSTRAINT perfil_pkey;
ALTER TABLE ONLY registro_derecho.appacciones DROP CONSTRAINT opciones_pkey;
ALTER TABLE ONLY registro_derecho.geoprocesamiento DROP CONSTRAINT geoprocesamiento_pkey;
ALTER TABLE ONLY registro_derecho.comportamiento DROP CONSTRAINT comportamiento_primary;
ALTER TABLE registro_derecho.usuario ALTER COLUMN idusuario DROP DEFAULT;
ALTER TABLE registro_derecho.shapefiles ALTER COLUMN idshapefile DROP DEFAULT;
ALTER TABLE registro_derecho.pruebas ALTER COLUMN idprueba DROP DEFAULT;
ALTER TABLE registro_derecho.perfil ALTER COLUMN idperfil DROP DEFAULT;
ALTER TABLE registro_derecho.geoprocesamiento ALTER COLUMN idgeoproceso DROP DEFAULT;
DROP SEQUENCE registro_derecho.usuario_idusuario_seq;
DROP TABLE registro_derecho.usuario;
DROP TABLE registro_derecho.userlevels;
DROP TABLE registro_derecho.userlevelpermissions;
DROP SEQUENCE registro_derecho.shapefiles_idshapefile_seq;
DROP TABLE registro_derecho.shapefiles;
DROP SEQUENCE registro_derecho.pruebas_idprueba_seq;
DROP TABLE registro_derecho.pruebas;
DROP SEQUENCE registro_derecho.perfil_idperfil_seq;
DROP TABLE registro_derecho.perfil;
DROP SEQUENCE registro_derecho.opciones_idopcion_seq;
DROP SEQUENCE registro_derecho.geoprocesamiento_idgeoproceso_seq;
DROP TABLE registro_derecho.geoprocesamiento;
DROP TABLE registro_derecho.comportamiento;
DROP TABLE registro_derecho.appacciones;
DROP SCHEMA registro_derecho;
--
-- Name: registro_derecho; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA registro_derecho;


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: appacciones; Type: TABLE; Schema: registro_derecho; Owner: -
--

CREATE TABLE registro_derecho.appacciones (
    idaccion character varying(150) NOT NULL,
    accion character varying(200),
    contexto character varying(200)
);


--
-- Name: comportamiento; Type: TABLE; Schema: registro_derecho; Owner: -
--

CREATE TABLE registro_derecho.comportamiento (
    idcomportamiento integer NOT NULL,
    descripcion text NOT NULL,
    categoria character varying(150)
);


--
-- Name: geoprocesamiento; Type: TABLE; Schema: registro_derecho; Owner: -
--

CREATE TABLE registro_derecho.geoprocesamiento (
    idgeoproceso integer NOT NULL,
    idusuario integer NOT NULL,
    proceso character varying(255) NOT NULL,
    entrada jsonb,
    inicio timestamp(0) without time zone,
    fin timestamp(0) without time zone,
    salida jsonb,
    opciones jsonb,
    geojson text
);


--
-- Name: geoprocesamiento_idgeoproceso_seq; Type: SEQUENCE; Schema: registro_derecho; Owner: -
--

CREATE SEQUENCE registro_derecho.geoprocesamiento_idgeoproceso_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: geoprocesamiento_idgeoproceso_seq; Type: SEQUENCE OWNED BY; Schema: registro_derecho; Owner: -
--

ALTER SEQUENCE registro_derecho.geoprocesamiento_idgeoproceso_seq OWNED BY registro_derecho.geoprocesamiento.idgeoproceso;


--
-- Name: opciones_idopcion_seq; Type: SEQUENCE; Schema: registro_derecho; Owner: -
--

CREATE SEQUENCE registro_derecho.opciones_idopcion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 2147483647
    CACHE 1;


--
-- Name: perfil; Type: TABLE; Schema: registro_derecho; Owner: -
--

CREATE TABLE registro_derecho.perfil (
    perfil character varying(150) NOT NULL,
    interfaz text,
    comportamiento character varying(255),
    opciones text,
    idperfil integer NOT NULL
);


--
-- Name: perfil_idperfil_seq; Type: SEQUENCE; Schema: registro_derecho; Owner: -
--

CREATE SEQUENCE registro_derecho.perfil_idperfil_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: perfil_idperfil_seq; Type: SEQUENCE OWNED BY; Schema: registro_derecho; Owner: -
--

ALTER SEQUENCE registro_derecho.perfil_idperfil_seq OWNED BY registro_derecho.perfil.idperfil;


--
-- Name: pruebas; Type: TABLE; Schema: registro_derecho; Owner: -
--

CREATE TABLE registro_derecho.pruebas (
    idprueba integer NOT NULL,
    entrada character varying,
    salida character varying,
    tipo character varying(30),
    descripcion character varying(1000),
    test character varying(200)
);


--
-- Name: pruebas_idprueba_seq; Type: SEQUENCE; Schema: registro_derecho; Owner: -
--

CREATE SEQUENCE registro_derecho.pruebas_idprueba_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: pruebas_idprueba_seq; Type: SEQUENCE OWNED BY; Schema: registro_derecho; Owner: -
--

ALTER SEQUENCE registro_derecho.pruebas_idprueba_seq OWNED BY registro_derecho.pruebas.idprueba;


--
-- Name: shapefiles; Type: TABLE; Schema: registro_derecho; Owner: -
--

CREATE TABLE registro_derecho.shapefiles (
    idshapefile integer NOT NULL,
    idaplicacion character varying(255),
    token character varying(50),
    idusuario integer,
    tipo character varying(255),
    folder character varying(255),
    narchivo character varying(255),
    narchivoorigen character varying(255),
    fechacreacion timestamp without time zone,
    tamano bigint,
    srid integer,
    tipogeom integer
);


--
-- Name: TABLE shapefiles; Type: COMMENT; Schema: registro_derecho; Owner: -
--

COMMENT ON TABLE registro_derecho.shapefiles IS 'contiene el registro de los archivos shape comprimidos cargados al sistema';


--
-- Name: COLUMN shapefiles.tipogeom; Type: COMMENT; Schema: registro_derecho; Owner: -
--

COMMENT ON COLUMN registro_derecho.shapefiles.tipogeom IS 'Código del tipo de geometrías para archio shapefile
0 Null Shape
1 Point
3 PolyLine
5 Polygon
8 MultiPoint
11 PointZ
13 PolyLineZ
15 PolygonZ
18 MultiPointZ
21 PointM
23 PolyLineM
25 PolygonM
28 MultiPointM
31 MultiPatch';


--
-- Name: shapefiles_idshapefile_seq; Type: SEQUENCE; Schema: registro_derecho; Owner: -
--

CREATE SEQUENCE registro_derecho.shapefiles_idshapefile_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: shapefiles_idshapefile_seq; Type: SEQUENCE OWNED BY; Schema: registro_derecho; Owner: -
--

ALTER SEQUENCE registro_derecho.shapefiles_idshapefile_seq OWNED BY registro_derecho.shapefiles.idshapefile;


--
-- Name: userlevelpermissions; Type: TABLE; Schema: registro_derecho; Owner: -
--

CREATE TABLE registro_derecho.userlevelpermissions (
    userlevelid integer NOT NULL,
    tablename character varying(255) NOT NULL,
    permission integer NOT NULL
);


--
-- Name: userlevels; Type: TABLE; Schema: registro_derecho; Owner: -
--

CREATE TABLE registro_derecho.userlevels (
    userlevelid integer NOT NULL,
    userlevelname character varying(255) NOT NULL,
    idperfil integer
);


--
-- Name: usuario; Type: TABLE; Schema: registro_derecho; Owner: -
--

CREATE TABLE registro_derecho.usuario (
    idusuario integer NOT NULL,
    userlevelid integer NOT NULL,
    "user" character varying(20) NOT NULL,
    password character varying(80),
    nombre character varying(255) NOT NULL,
    email character varying(150),
    idperfil integer NOT NULL,
    activo smallint DEFAULT 1 NOT NULL,
    autologinip character varying(50)
);


--
-- Name: usuario_idusuario_seq; Type: SEQUENCE; Schema: registro_derecho; Owner: -
--

CREATE SEQUENCE registro_derecho.usuario_idusuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: usuario_idusuario_seq; Type: SEQUENCE OWNED BY; Schema: registro_derecho; Owner: -
--

ALTER SEQUENCE registro_derecho.usuario_idusuario_seq OWNED BY registro_derecho.usuario.idusuario;


--
-- Name: geoprocesamiento idgeoproceso; Type: DEFAULT; Schema: registro_derecho; Owner: -
--

ALTER TABLE ONLY registro_derecho.geoprocesamiento ALTER COLUMN idgeoproceso SET DEFAULT nextval('registro_derecho.geoprocesamiento_idgeoproceso_seq'::regclass);


--
-- Name: perfil idperfil; Type: DEFAULT; Schema: registro_derecho; Owner: -
--

ALTER TABLE ONLY registro_derecho.perfil ALTER COLUMN idperfil SET DEFAULT nextval('registro_derecho.perfil_idperfil_seq'::regclass);


--
-- Name: pruebas idprueba; Type: DEFAULT; Schema: registro_derecho; Owner: -
--

ALTER TABLE ONLY registro_derecho.pruebas ALTER COLUMN idprueba SET DEFAULT nextval('registro_derecho.pruebas_idprueba_seq'::regclass);


--
-- Name: shapefiles idshapefile; Type: DEFAULT; Schema: registro_derecho; Owner: -
--

ALTER TABLE ONLY registro_derecho.shapefiles ALTER COLUMN idshapefile SET DEFAULT nextval('registro_derecho.shapefiles_idshapefile_seq'::regclass);


--
-- Name: usuario idusuario; Type: DEFAULT; Schema: registro_derecho; Owner: -
--

ALTER TABLE ONLY registro_derecho.usuario ALTER COLUMN idusuario SET DEFAULT nextval('registro_derecho.usuario_idusuario_seq'::regclass);


--
-- Name: comportamiento comportamiento_primary; Type: CONSTRAINT; Schema: registro_derecho; Owner: -
--

ALTER TABLE ONLY registro_derecho.comportamiento
    ADD CONSTRAINT comportamiento_primary PRIMARY KEY (idcomportamiento);


--
-- Name: geoprocesamiento geoprocesamiento_pkey; Type: CONSTRAINT; Schema: registro_derecho; Owner: -
--

ALTER TABLE ONLY registro_derecho.geoprocesamiento
    ADD CONSTRAINT geoprocesamiento_pkey PRIMARY KEY (idgeoproceso);


--
-- Name: appacciones opciones_pkey; Type: CONSTRAINT; Schema: registro_derecho; Owner: -
--

ALTER TABLE ONLY registro_derecho.appacciones
    ADD CONSTRAINT opciones_pkey PRIMARY KEY (idaccion);


--
-- Name: perfil perfil_pkey; Type: CONSTRAINT; Schema: registro_derecho; Owner: -
--

ALTER TABLE ONLY registro_derecho.perfil
    ADD CONSTRAINT perfil_pkey PRIMARY KEY (idperfil);


--
-- Name: pruebas pruebas_pkey; Type: CONSTRAINT; Schema: registro_derecho; Owner: -
--

ALTER TABLE ONLY registro_derecho.pruebas
    ADD CONSTRAINT pruebas_pkey PRIMARY KEY (idprueba);


--
-- Name: shapefiles shapefiles_pkey; Type: CONSTRAINT; Schema: registro_derecho; Owner: -
--

ALTER TABLE ONLY registro_derecho.shapefiles
    ADD CONSTRAINT shapefiles_pkey PRIMARY KEY (idshapefile);


--
-- Name: usuario usuario_primary; Type: CONSTRAINT; Schema: registro_derecho; Owner: -
--

ALTER TABLE ONLY registro_derecho.usuario
    ADD CONSTRAINT usuario_primary PRIMARY KEY (idusuario);


--
-- Name: user; Type: INDEX; Schema: registro_derecho; Owner: -
--

CREATE UNIQUE INDEX "user" ON registro_derecho.usuario USING btree ("user");


--
-- Name: userlevels_fk; Type: INDEX; Schema: registro_derecho; Owner: -
--

CREATE INDEX userlevels_fk ON registro_derecho.userlevels USING btree (idperfil);


--
-- Name: usuario_fk1; Type: INDEX; Schema: registro_derecho; Owner: -
--

CREATE INDEX usuario_fk1 ON registro_derecho.usuario USING btree (idperfil);


--
-- Name: geoprocesamiento geoprocesamiento_tr_au; Type: TRIGGER; Schema: registro_derecho; Owner: -
--

CREATE TRIGGER geoprocesamiento_tr_au AFTER UPDATE ON registro_derecho.geoprocesamiento FOR EACH ROW WHEN ((old.entrada IS DISTINCT FROM new.entrada)) EXECUTE PROCEDURE public.sicob_execute_geoprocess();

ALTER TABLE registro_derecho.geoprocesamiento DISABLE TRIGGER geoprocesamiento_tr_au;


--
-- Name: geoprocesamiento geoprocesamiento_tr_notify_end; Type: TRIGGER; Schema: registro_derecho; Owner: -
--

CREATE TRIGGER geoprocesamiento_tr_notify_end AFTER UPDATE ON registro_derecho.geoprocesamiento FOR EACH ROW WHEN ((old.salida IS DISTINCT FROM new.salida)) EXECUTE PROCEDURE public.sicob_notify_geoprocess_end();


--
-- Name: geoprocesamiento geoprocesamiento_fk_usuario; Type: FK CONSTRAINT; Schema: registro_derecho; Owner: -
--

ALTER TABLE ONLY registro_derecho.geoprocesamiento
    ADD CONSTRAINT geoprocesamiento_fk_usuario FOREIGN KEY (idusuario) REFERENCES registro_derecho.usuario(idusuario);


--
-- Name: usuario usuario_fk_perfil; Type: FK CONSTRAINT; Schema: registro_derecho; Owner: -
--

ALTER TABLE ONLY registro_derecho.usuario
    ADD CONSTRAINT usuario_fk_perfil FOREIGN KEY (idperfil) REFERENCES registro_derecho.perfil(idperfil) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

