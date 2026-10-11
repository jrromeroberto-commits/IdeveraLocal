# PostgreSQL local

Aplicación: `G:\PW\PaginaIde\idevera-base`. Servicio comprobado: `postgresql-x64-18`, activo en `127.0.0.1:5432`. PHP dispone de `pdo_pgsql`.

## Crear la base de desarrollo

Abrir una conexión de administración desde PowerShell:

```powershell
& "C:\Program Files\PostgreSQL\18\bin\psql.exe" -X -h 127.0.0.1 -p 5432 -U postgres -d postgres -W
```

Introducir la contraseña del administrador en el aviso de la terminal. Cuando aparezca `postgres=#`, ejecutar:

```sql
CREATE ROLE idevera_app LOGIN NOSUPERUSER NOCREATEDB NOCREATEROLE;
```

Asignar una contraseña al usuario de la aplicación con el comando de psql:

```text
\password idevera_app
```

Introducir una contraseña nueva y confirmarla. Después crear la base:

```sql
CREATE DATABASE idevera_dev OWNER idevera_app ENCODING 'UTF8' TEMPLATE template0;
```

Salir de psql con `\q`. Si alguno de los objetos ya existe o aparece otro error, detenerse y revisar su propietario y configuración antes de continuar.

## Conectar Laravel

En `idevera-base/.env`, sustituir el bloque de base de datos por:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=idevera_dev
DB_USERNAME=idevera_app
DB_PASSWORD="CONTRASEÑA_DEL_USUARIO_IDEVERA_APP"
```

El marcador debe sustituirse por la contraseña asignada a `idevera_app`. Si contiene comillas, barras invertidas u otros caracteres especiales, debe escaparse según la sintaxis de dotenv. `.env` está excluido de Git; `.env.example` documenta la conexión sin contraseña.

Desde la carpeta de la aplicación:

```powershell
php artisan config:clear
php artisan migrate
php artisan migrate:status
php artisan db:show --database=pgsql
```

Las migraciones crean las tablas iniciales en la base exclusiva del proyecto. No se necesita `migrate:fresh`, ni borrar la base SQLite anterior.

## Comprobación y pruebas

Conexión verificada el 10 de octubre de 2026: Laravel consulta `idevera_dev` con `idevera_app` en PostgreSQL 18.1. El usuario no tiene permisos de superusuario, creación de bases ni creación de roles. Las cinco migraciones iniciales están ejecutadas y existen diez tablas, incluidas las de usuarios, sesiones, caché, colas y passkeys.

Las credenciales se conservan únicamente en `.env`, excluido de Git. La base SQLite anterior se conserva y no tenía usuarios al realizar el cambio.

Las pruebas automatizadas de la plantilla mantienen SQLite en memoria, según `phpunit.xml`. El workflow de GitHub prepara también una base SQLite independiente para ejecutar las comprobaciones; no necesita las credenciales locales de PostgreSQL.

Después de cambiar `.env` a PostgreSQL se ejecutó `php artisan test`: las 40 pruebas pasaron, con 138 aserciones.

Referencias oficiales: [roles](https://www.postgresql.org/docs/18/sql-createrole.html), [creación de bases](https://www.postgresql.org/docs/18/sql-createdatabase.html), [psql](https://www.postgresql.org/docs/18/app-psql.html) y [configuración de Laravel](https://laravel.com/framework/docs/13.x/database).
