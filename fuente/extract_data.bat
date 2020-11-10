@echo off
call config.bat
set pgdb=%pgdb%

REM 1.	Exportar esquema registro_derecho
pg_dump -h %pghost% -U %pguser% -d %pgdb% --no-privileges --no-owner -c -n "registro_derecho" --schema-only -O > schema_registro_derecho.sql

REM 2.	Exportar datos de la tabla "perfil"
pg_dump -h %pghost% -U %pguser% -d %pgdb% --no-privileges --no-owner -a --table "registro_derecho.perfil" -O > data_perfil.sql

REM 3.	Exportar datos de la tabla "userlevels"
pg_dump -h %pghost% -U %pguser% -d %pgdb%  --no-privileges --no-owner -a --table "registro_derecho.userlevels" -O > data_userlevels.sql

REM 4.	Exportar datos de la tabla "usuario"
pg_dump -h %pghost% -U %pguser% -d %pgdb%  --no-privileges --no-owner -a --table "registro_derecho.usuario" -O > data_usuario.sql

REM 5.	Exportar datos de la tabla "appacciones"
pg_dump -h %pghost% -U %pguser% -d %pgdb%  --no-privileges --no-owner -a --table "registro_derecho.appacciones" -O > data_appacciones.sql

