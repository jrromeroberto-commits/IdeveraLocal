# F1: roles y acceso

Implementación local del 11 de octubre de 2026. Un rol global por cuenta: CLIENT, STAFF u OWNER. Las especialidades del equipo y las responsabilidades de proyecto son conceptos independientes. Esta fase entrega la base de cuentas; los recursos de solicitudes, archivos y proyectos recibirán sus propias políticas y pruebas en F4–F7.

## Comportamiento implementado

- El registro público crea CLIENT con perfil PENDING_VERIFICATION. Rechaza rol, estado y especialidades enviados por el navegador. Verificar correo activa únicamente perfiles pendientes; nunca levanta una suspensión o desactivación.
- La migración conserva las cuentas existentes como CLIENT, sus contraseñas y su verificación. No eleva automáticamente ninguna a OWNER.
- `/dashboard` dirige a `/client`, `/staff` o `/owner`. Cada área comprueba rol, verificación y estado en el backend. Las pantallas de entrada son funcionales y provisionales; su identidad visual se trabaja en F2.
- STAFF posee perfil ACTIVE, INACTIVE o SUSPENDED, y varias especialidades del catálogo inicial. Solo OWNER crea STAFF. No hay registro público ni cambio de rol desde la interfaz.
- OWNER debe tener correo verificado y dos factores activados y confirmados para abrir su área. Crear STAFF o cambiar acceso exige además confirmar la contraseña en los últimos 15 minutos.
- `/owner/accounts` permite crear STAFF, gestionar estados y especialidades y consultar la actividad reciente. No permite modificar otras cuentas OWNER ni desactivar la propia.
- Las restricciones bloquean todas las rutas privadas, incluidas peticiones de una sesión ya abierta. Cambiar el estado elimina las sesiones almacenadas en base de datos y renueva el token de acceso persistente. El middleware comprueba el estado también con otros controladores de sesión.
- El usuario restringido conserva inicio público, cierre de sesión y verificación de correo; tras cerrar sesión puede recuperar la contraseña. Restablecer contraseña o verificar correo no reactiva el perfil. La lectura histórica privada se definirá junto al módulo de historial.
- La antigua eliminación de la plantilla desactiva CLIENT o deja STAFF inactivo, cierra sesión y conserva la cuenta. OWNER no puede usarla. El proceso de derechos y eliminación de datos se definirá en F8/F9.
- La auditoría registra actor, cuenta y cambios de estado/especialidades para alta OWNER/STAFF, cambios administrativos y desactivación propia. No guarda contraseñas, secretos de dos factores ni tokens. Las verificaciones usan los eventos de autenticación de Laravel; no se registran como eventos administrativos en esta primera tabla.

## Estados y transiciones

| Perfil | Acción | Condición y efecto |
| --- | --- | --- |
| CLIENT | Registro | PENDING_VERIFICATION |
| CLIENT | Verificar correo | PENDING_VERIFICATION → ACTIVE; estados restringidos permanecen iguales |
| CLIENT | Cambiar correo propio | ACTIVE → PENDING_VERIFICATION; verificar el correo nuevo |
| CLIENT | Cambio por OWNER | SUSPENDED o DEACTIVATED desde cualquier estado; ACTIVE exige correo verificado; PENDING_VERIFICATION exige correo sin verificar |
| CLIENT | Desactivación propia | Cuenta verificada y sin restricción → DEACTIVATED |
| STAFF | Alta por OWNER | ACTIVE, correo sin verificar; no entra en negocio hasta verificarlo |
| STAFF | Cambio por OWNER | ACTIVE, INACTIVE o SUSPENDED; ACTIVE sigue exigiendo verificación para entrar |
| STAFF | Desactivación propia | ACTIVE y correo verificado → INACTIVE |
| OWNER | Alta inicial local | Cuenta nueva, correo validado por el operador local; completar dos factores antes de administrar |

Guardar los mismos valores no duplica auditoría ni vuelve a revocar sesiones. Cambiar solo especialidades no concede permisos ni cierra sesiones. Catálogo inicial: DEVELOPER, ARTIST, DESIGNER, ANIMATOR, DATA, AUTOMATION y OTHER; se revisará su ampliación si el producto lo necesita. TEAM_MEMBER, PROJECT_LEAD y SCRUM_MASTER quedan definidos en un enum separado; sus asignaciones y poderes se implementan en F6.

## Primer OWNER: lo ejecuta el usuario

En PowerShell, dentro de `G:\PW\PaginaIde\idevera-base`:

```powershell
php artisan idevera:owner
```

Introduce un correo distinto del CLIENT actual, nombre y una contraseña de al menos 12 caracteres con mayúsculas, minúsculas, números y símbolos. La contraseña se solicita oculta y no se pasa como argumento. El comando se limita a `local`/`testing`, crea solo el primer OWNER y rechaza correos existentes; nunca promueve una cuenta.

1. Inicia sesión con esa cuenta.
2. Abre Ajustes > Seguridad, confirma la contraseña y activa dos factores con una aplicación autenticadora.
3. Introduce el código para confirmar la activación y conserva los códigos de recuperación fuera del repositorio.
4. Abre `/owner/accounts` para crear las cuentas STAFF de prueba.

No se han creado cuentas de prueba con contraseñas conocidas en la base de desarrollo ni se ha modificado el rol del usuario existente. Las factories de CLIENT/STAFF/OWNER sirven a las pruebas; no hay un seeder de credenciales predeterminadas.

## Incorporación local de STAFF

OWNER introduce nombre, correo y especialidades. El backend asigna una contraseña aleatoria que no se muestra ni se comparte. La persona abre Recuperar contraseña, establece una nueva desde el enlace y verifica su correo después de iniciar sesión. Con el mailer `log`, los enlaces se consultan en `idevera-base/storage/logs/laravel.log`. No publicar ese archivo ni sus enlaces. Un correo de invitación específico se puede añadir al conectar el canal externo posteriormente.

## Validación y límites

Comprobación final del 11 de octubre de 2026:

| Comprobación | Resultado |
| --- | --- |
| `composer ci:check` | Aprobado: formato, lint, TypeScript, Pint, PHPStan y 62 pruebas con 308 aserciones |
| `pnpm.cmd run build` | Aprobado con las pantallas y rutas nuevas |
| `git diff --check` | Sin errores |
| `php artisan migrate` en PostgreSQL local | Migración F1 aplicada; seis migraciones y 13 tablas |
| Cuenta existente tras la migración | Un CLIENT, perfil ACTIVE; cero OWNER y cero STAFF. No se muestra información personal ni contraseñas en esta comprobación |

Validación manual confirmada por el usuario el 11 de octubre de 2026: acceso a `/owner/accounts` y funcionamiento de dos factores correctos. F1 queda cerrada para su alcance de cuentas y acceso, con las pruebas automatizadas y esta comprobación manual. El agente no ha realizado una revisión visual mediante navegador en esta fase; el diseño y su revisión corresponden a F2.

Pruebas: `tests/Feature/Accounts/AccountAccessTest.php`, `AccountMigrationTest.php` y pruebas de autenticación/ajustes de la plantilla. Cubren registro sin elevación, áreas por rol, datos de otra cuenta, validación administrativa, dos factores confirmados, contraseña reciente, suspensión de sesión abierta, revocación, auditoría sin duplicados, desactivación con retención, verificación sin reactivación, recuperación de STAFF y provisión local de OWNER.

La suite habitual usa SQLite aislado. La migración de desarrollo se aplica con `php artisan migrate`; nunca se usa `migrate:fresh` sobre PostgreSQL de desarrollo. Concurrencia y recursos por asignación se ampliarán con sus módulos y en PostgreSQL en F9; esta fase no acredita aislamiento de proyectos todavía inexistentes.

```powershell
pnpm.cmd run build
composer ci:check
```

Las pantallas conservan parte del shell y textos de la plantilla hasta F2. Esta fase no incorpora pagos, CMS ni proyectos.
