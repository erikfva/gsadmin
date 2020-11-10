set sicobfn_dir=../spatialSQL
set PATH_OGR2OGR=C:\Program Files\QGIS 3.2\bin\ogr2ogr.exe
set PATH_GDB=F:\ASIG\coberturas\Geodatabase.gdb
set pghost=localhost
set pgport=5432
REM set pgdb=geo
set pgdb=geosicob
REM set pguser=postgres
set pguser=admderechos
REM set pgpsw=arma
set pgpsw=Geo2020*
REM ******************
REM * El password para el usuario "pguser" debe configurarse en el archivo .pgpass del sistema.
REM * On Microsoft Windows the file is named %APPDATA%\postgresql\pgpass.conf (where %APPDATA% refers to the Application Data subdirectory in the user's profile).
REM * El formato es -> hostname:port:database:username:password
REM ******************
set pgschema=coberturas
set pgsrid=3003
set pggeom=the_geom
REM set pgencoding="UTF-8"
SETLOCAL ENABLEEXTENSIONS EnableDelayedExpansion
