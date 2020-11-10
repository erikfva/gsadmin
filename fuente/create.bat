@echo off
call config.bat
psql -p %pgport% -f "schema_registro_derecho.sql" postgresql://%pguser%:%pgpsw%@%pghost%/%pgdb%
psql -p %pgport% -f "data_userlevels.sql" postgresql://%pguser%:%pgpsw%@%pghost%/%pgdb%
psql -p %pgport% -f "data_perfil.sql" postgresql://%pguser%:%pgpsw%@%pghost%/%pgdb%
psql -p %pgport% -f "data_usuario.sql" postgresql://%pguser%:%pgpsw%@%pghost%/%pgdb%
psql -p %pgport% -f "data_appacciones.sql" postgresql://%pguser%:%pgpsw%@%pghost%/%pgdb%