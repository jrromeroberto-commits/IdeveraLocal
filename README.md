# IDEVERA STUDIO

Plataforma web para presentar el estudio y gestionar el trabajo con clientes mediante las áreas CLIENT, STAFF y OWNER.

## Estado

La base de Laravel con React está instalada en `idevera-base/`. El repositorio conserva los documentos de producto y diseño en la raíz. La aplicación utiliza pnpm para el frontend.

El mapa cubre el alcance completo de la primera versión. F1 está completada: roles CLIENT/STAFF/OWNER, estados, áreas separadas, gestión de cuentas y protección de OWNER. La migración está aplicada en PostgreSQL local; el usuario confirmó el acceso a `/owner/accounts` y el funcionamiento de dos factores. Los siguientes módulos de negocio y el diseño propio se construyen desde F2.

Este proyecto se desarrollará y validará en local hasta terminarlo, como plataforma de pruebas. No requiere dominio comprado ni cuenta oficial de Culqi. PayPal está incluido en el alcance de integración, inicialmente en Sandbox. Al finalizar se podrá evaluar una publicación de demostración con alojamiento y base de datos gratuitos, según su compatibilidad con la aplicación.

## Documentación

- [Requisitos del cliente](IDEVERA_REQUERIMIENTOS_CLIENTE_MVP_V1.docx): fuente del alcance funcional.
- [Referencias visuales](REFERENCIAS_VISUALES.md): investigación y propuesta de dirección visual.
- [Plan de implementación](docs/PLAN_DE_IMPLEMENTACION.md): fases, dependencias, decisiones pendientes y acceso al mapa de pantallas, permisos, dominio y los 96 requisitos.
- [F1: roles y acceso](docs/F1_ROLES_Y_ACCESO.md): comportamiento implementado, pruebas y creación local del primer OWNER.
- [Guía de diseño](DESIGN.md): propuesta de composición, componentes, responsive y animaciones.
- [Preparación del proyecto](docs/PREPARACION.md): entorno comprobado, propuesta técnica y siguientes pasos.
- [PostgreSQL local](docs/POSTGRESQL.md): creación de la base y conexión de Laravel.

## Base técnica

Laravel 13 con React y TypeScript mediante Inertia, Vite Plus y Tailwind. La conexión local utiliza PostgreSQL 18.1, con la base `idevera_dev` y el usuario `idevera_app`. Las pruebas automatizadas utilizan SQLite en memoria.

La aplicación sigue la estructura del framework; el frontend está en `idevera-base/resources/js`. Las dependencias se fijan en `composer.lock` y `pnpm-lock.yaml`, con pnpm 12.9.1 declarado en `package.json`.

## Criterios del proyecto

- Sitio web convencional, sin funciones de PWA.
- Respetar los roles, estados y reglas del documento de requisitos.
- Identidad visual común para sitio público y áreas privadas.
- Diseño responsive, accesibilidad y alternativa con movimiento reducido.
- Versionar los archivos de bloqueo de dependencias cuando existan.
- Mantener las credenciales en configuración local; compartir una plantilla `.env.example` sin secretos al configurar la aplicación.

## Inicio del desarrollo

Desde `idevera-base/`:

```powershell
composer install
pnpm.cmd install --frozen-lockfile
pnpm.cmd run build
```

Para una instalación nueva, configurar `.env` a partir de `.env.example`, generar la clave con `php artisan key:generate` y preparar una base exclusiva de desarrollo antes de ejecutar las migraciones. `composer setup` automatiza esos pasos y ejecuta las migraciones contra la conexión configurada.

Comprobaciones disponibles:

```powershell
composer validate
pnpm.cmd audit
pnpm.cmd run types:check
composer ci:check
```

El workflow de GitHub está en `.github/workflows/tests.yml` y trabaja desde `idevera-base/`. Consultar [la preparación](docs/PREPARACION.md) para el estado del entorno y [el plan](docs/PLAN_DE_IMPLEMENTACION.md) para las siguientes fases.
