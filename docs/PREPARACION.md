# Preparación del repositorio

Comprobación técnica del entorno: 10 de octubre de 2026. Actualización de preparación y mapa: 11 de octubre de 2026. Directorio: `G:\PW\PaginaIde`.

## Base preparada

- Documento original de requisitos y revisión visual conservados en sus rutas existentes.
- README con enlaces a la documentación.
- `.gitignore` para dependencias, configuración local y archivos generados.
- `.editorconfig` para UTF-8, finales de línea LF y formato de edición consistente.
- `.gitattributes` para normalizar archivos de texto y tratar el documento Word como binario.
- Repositorio Git con rama `main`, primer commit y remoto `origin`; el usuario confirmó el push.

Git se verificó desde la cuenta de Windows del usuario. Como el repositorio fue creado por el entorno aislado, se registró exclusivamente `G:/PW/PaginaIde` en `safe.directory` de esa cuenta. Se conservaron los propietarios y permisos existentes. La comprobación confirmó que `.env` y dependencias se excluyen, mientras que los ejemplos de entorno, lockfiles y documentos pueden versionarse.

## Herramientas comprobadas

| Herramienta | Resultado |
| --- | --- |
| Git | 2.53.0.windows.2 |
| Node.js | 24.15.0 |
| npm | 11.12.1 |
| pnpm | 12.9.1 |
| PHP CLI | 8.4.23 |
| Composer | 2.9.8 |
| Cliente PostgreSQL | 18.1 |
| PostgreSQL local | 18.1; Laravel conecta a `idevera_dev` con `idevera_app` |
| Docker | Cliente instalado; no se pudo verificar conexión con el daemon |

PHP dispone de las extensiones básicas requeridas por Laravel y de `pdo_pgsql`. Se verificaron la versión del servidor, las credenciales de la aplicación y su conexión a la base exclusiva de desarrollo. El usuario `idevera_app` no dispone de permisos de superusuario, creación de bases ni creación de roles.

La consulta de Docker encontró una restricción de acceso a su configuración y no encontró el endpoint del daemon. La propuesta de desarrollo local utiliza las herramientas nativas instaladas; Docker puede decidirse posteriormente.

## Base instalada y gestor de paquetes

La base oficial de Laravel con React se instaló en `idevera-base/`. Laravel Framework 13.35.0 fue confirmado por el usuario. El frontend utiliza React, TypeScript, Inertia, Tailwind y Vite Plus; su apariencia se adaptará a la identidad de Idevera.

La aplicación está actualmente en la subcarpeta `idevera-base/`; conserva la estructura estándar del framework. El workflow se sitúa en la raíz de Git y configura esa subcarpeta como directorio de trabajo. Cualquier traslado posterior de la aplicación deberá actualizar también esas rutas.

Se utiliza pnpm 12.9.1 como único gestor del frontend, declarado en `package.json`, con `pnpm-lock.yaml` y sin `package-lock.json`. Composer utiliza pnpm tanto en `setup` como en `ci:check`. La opción de la plantilla `ignore-scripts=true` se trasladó de `.npmrc` a `ignoreScripts: true` en `pnpm-workspace.yaml`, donde pnpm 12 lee esta configuración. El archivo `.npmrc`, que solo contenía esa opción, se retiró.

Vite Plus se actualizó de 0.3.0 a 0.3.3 para corregir la cadena vulnerable `vite-plus → oxfmt → tinypool`. La auditoría realizada por el usuario tras la actualización no encontró vulnerabilidades conocidas.

Vite se resuelve mediante el alias oficial `@voidzero-dev/vite-plus-core@0.3.3`, compartido por los plugins mediante un override. Vitest queda fijado en la versión 4.1.11 incluida en esa herramienta. Se admite únicamente la versión 0.3.3 del alias como peer de Vite, porque su numeración identifica Vite Plus y no la versión interna de Vite.

La redirección de `/settings` se definió para GET/HEAD. La redirección genérica de la plantilla incluía el método QUERY de Laravel 13, que Wayfinder 0.1.21 no admite en sus tipos generados. El cambio conserva el destino `/settings/profile` y permite regenerar los tipos sin modificar los archivos generados.

PHPStan dispone de un límite de memoria de 512 MB en el script de Composer, porque el límite predeterminado local de 128 MB no bastaba para completar el análisis.

## Validación de la base

Comprobaciones completadas el 10 de octubre de 2026:

- Instalación con `pnpm install --frozen-lockfile` correcta.
- Auditoría de pnpm sin vulnerabilidades conocidas y comprobación de peers sin conflictos.
- Compilación de producción completada.
- `composer validate --strict` correcto; las 138 dependencias de Composer conservan sus versiones.
- `composer ci:check` completo: formato, lint, TypeScript, Pint, PHPStan y PHPUnit.
- Las 40 pruebas de Laravel pasan, con 138 aserciones.

La conexión local a PostgreSQL 18.1 está comprobada. Las cinco migraciones están ejecutadas y Laravel reconoce diez tablas. Los pasos de instalación están en [POSTGRESQL.md](POSTGRESQL.md).

El workflow de GitHub está preparado. El repositorio ya tiene su primer commit y el usuario confirmó el push; el resultado remoto del workflow no se ha comprobado. Las pruebas automatizadas mantienen SQLite en memoria, independiente de PostgreSQL local.

El 11 de octubre el usuario confirmó el registro, la verificación mediante el enlace del log y el inicio de sesión con acceso al dashboard. El correo sigue en modo `log` para pruebas locales; el envío externo se evalúa si se necesita para una demo posterior.

Referencias técnicas oficiales consultadas:

- [Instalación de Laravel](https://laravel.com/framework/docs/13.x/installation).
- [Starter kits de Laravel](https://laravel.com/framework/docs/13.x/starter-kits).
- [Requisitos de Laravel](https://laravel.com/framework/docs/13.x/deployment#server-requirements).

## Orden recomendado para empezar

1. Revisar [el plan y las decisiones de la primera tanda](PLAN_DE_IMPLEMENTACION.md).
2. Implementar roles, estados de cuenta y permisos de la fase F1.
3. Prototipar la home y el detalle de proyecto CLIENT según [DESIGN.md](../DESIGN.md), en paralelo con la base de permisos.
4. Construir los flujos por fases y registrar evidencia en [la matriz de requisitos](MATRIZ_DE_REQUISITOS.md).

## Información que puede prepararse en paralelo

- Logo o preferencia por un logotipo tipográfico inicial.
- Textos de presentación, servicios y proyectos del portfolio.
- Imágenes, vídeos o piezas propias que se puedan mostrar públicamente.
- Destino del repositorio remoto, si se quiere compartir o respaldar el código.

El usuario definió el proyecto como plataforma de pruebas, desarrollada en local hasta terminarla. No se comprará un dominio ni se necesita cuenta oficial de Culqi; PayPal está previsto inicialmente en Sandbox. El correo y los archivos pueden mantenerse locales. Hosting y base de datos gratuitos se evalúan después de terminar, si se decide publicar una demo. Los recursos visuales definitivos pueden incorporarse después de un primer prototipo con contenido provisional identificado.

## Criterio de preparación completa

La aplicación y las dependencias están instaladas, el frontend compila y las comprobaciones básicas pasan. Laravel conecta a una base PostgreSQL exclusiva con las migraciones iniciales ejecutadas. El usuario comprobó el arranque, registro y acceso en el navegador, y confirmó el primer commit y push. La preparación local está completa; el alcance de IDEVERA se desarrollará siguiendo el mapa documentado.
