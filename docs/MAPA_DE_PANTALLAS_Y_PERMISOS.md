# Pantallas y permisos de IDEVERA STUDIO

Este mapa traduce las secciones 5 y 6 de los [requisitos](../IDEVERA_REQUERIMIENTOS_CLIENTE_MVP_V1.docx) en navegación y controles de acceso. Las rutas y la distribución de pantallas son propuestas. Cada capacidad debe autorizarse en Laravel; ocultar un botón o cambiar un identificador no protege el recurso.

## Perfiles y alcance

| Perfil | Alcance | Restricción principal |
| --- | --- | --- |
| Visitante | Contenido publicado, contacto, registro y entrada a la solicitud | No accede a datos de cuentas o proyectos |
| CLIENT | Su perfil y recursos propios o compartidos expresamente | No accede a otro cliente; no ve notas internas ni borradores de trabajo internos |
| STAFF | Proyectos y trabajo asignados, según responsabilidades | No recibe administración global ni autoridad financiera por pertenecer al equipo |
| OWNER | Gestión integral y operaciones administrativas autorizadas | Respeta validación, historial, estados, idempotencia y protección reforzada |

El registro público crea CLIENT. STAFF se incorpora mediante OWNER. El primer OWNER se provisiona mediante un procedimiento controlado. El rol global, la especialidad del staff y la responsabilidad en un proyecto se almacenan y autorizan por separado.

F1 adopta un rol global por cuenta. Una especialidad como DESIGNER no concede automáticamente la responsabilidad PROJECT_LEAD. Están implementadas las entradas `/client`, `/staff`, `/owner` y la gestión unificada `/owner/accounts`; seguridad y dos factores se configuran en `/settings/security`. Los listados y detalles de negocio de este mapa siguen previstos para sus fases. Consultar [F1: roles y acceso](F1_ROLES_Y_ACCESO.md) para estados, rutas actuales y evidencia.

## Cuenta y estados

| Tipo | Estado del Word | Comportamiento propuesto |
| --- | --- | --- |
| CLIENT | PENDING_VERIFICATION | Puede completar verificación y recuperar acceso; no opera recursos privados de negocio |
| CLIENT | ACTIVE | Puede actuar sobre recursos autorizados |
| CLIENT | SUSPENDED | Acciones de negocio bloqueadas; alcance de soporte y lectura histórica por acordar en D03 |
| CLIENT | DEACTIVATED | Acceso de negocio bloqueado; retención y recuperación por acordar |
| STAFF | ACTIVE | Puede actuar en asignaciones vigentes |
| STAFF | INACTIVE | Acceso de trabajo bloqueado; histórico conservado conforme a política |
| STAFF | SUSPENDED | Acceso de trabajo bloqueado; reactivación administrativa auditada |

La verificación del correo no debe reactivar una cuenta suspendida. Al quitar una asignación o restringir una cuenta, las siguientes peticiones HTTP, descargas y suscripciones deben reevaluar permisos. El Word no define estados de OWNER; no se reutiliza silenciosamente el catálogo de cliente para ese rol.

## Web pública

| Pantalla | Ruta propuesta | Contenido y acciones | Fase |
| --- | --- | --- | --- |
| Home | `/` | Slogan, propuesta, servicios, proyectos destacados, proceso y CTA | F2/F3 |
| Servicios | `/servicios` | Listado de servicios publicados | F3 |
| Servicio | `/servicios/{slug}` | Detalle, contexto, ejemplos y CTA con servicio preseleccionado | F3 |
| Portfolio | `/portfolio` | Casos públicos, categorías y filtros pertinentes | F3 |
| Caso | `/portfolio/{slug}` | Problema, solución, resultados y galería; tecnologías solo si se decide publicarlas | F3 |
| Estudio | `/about` | Presentación y contenido institucional | F3 |
| Proceso | `/proceso` | Cómo se pasa del brief a la entrega | F3 |
| Contacto | `/contacto` | Datos y formulario; bandeja privada local OWNER, D13 resuelto para pruebas | F3 |
| Inicio de solicitud | `/iniciar-proyecto` | Explicación y acceso al brief; autenticar antes de guardar una solicitud privada | F3/F4 |
| Legal | `/legal/{slug}` | Términos, privacidad, cancelaciones/reembolsos y cookies cuando corresponda | F3/F9 |
| No encontrado | Cualquier ruta inexistente | 404 con orientación y acceso de regreso | F2/F3 |

Servicios del Word: Desarrollo Web; Software & Aplicaciones; PWA & Web Apps; Datos & BI; Automatización & Integraciones; Branding & Diseño; Digital Art & Animation; Soporte / Consultoría; Custom / Other cuando corresponda. Ofrecer el servicio PWA & Web Apps no convierte esta plataforma en una PWA.

Un visitante puede iniciar el recorrido y elegir servicio. El guardado autoritativo del brief requiere una cuenta; al pasar por login/registro se conserva el destino y la selección. No se almacenan adjuntos privados o datos sensibles en una caché pública del navegador para lograr esa continuidad.

## Autenticación y pantallas comunes

| Pantalla | Ruta existente o propuesta | Observación |
| --- | --- | --- |
| Registro / acceso | `/register`, `/login` | Reutilizar Fortify y adaptar el diseño |
| Recuperación | `/forgot-password`, `/reset-password/{token}` | Probar envío y enlace mediante log/visor local; correo externo si se necesita en una demo posterior |
| Verificación | `/email/verify` | Aviso, reenvío y confirmación de correo |
| Segundo factor | `/two-factor-challenge` | Integrado con protección reforzada de OWNER |
| Perfil | `/settings/profile` | Datos permitidos; no permite editar rol ni estado administrativo |
| Seguridad | `/settings/security` | Contraseña, sesiones y capacidades existentes de 2FA/passkeys que se decida exponer |
| Apariencia | `/settings/appearance` | Ruta de la plantilla; alcance del selector pendiente del diseño, cuyo punto de partida es oscuro |
| Entrada al área | `/dashboard` | Resolver destino según rol y estado; evitar un dashboard genérico para todos |
| Notificaciones | `/notifications` | Solo notificaciones del usuario actual; filtros y lectura |
| Archivo privado | `/files/{file}/download` | Descargar mediante autorización contextual en cada petición |
| Acceso restringido / conflicto / error | Según contexto | Mensaje comprensible, siguiente paso y datos recuperables |

Las URLs de recuperación y verificación existentes se mantienen conforme a Fortify; no se reemplazan por un sistema de autenticación paralelo.

## Área CLIENT

| Pantalla | Ruta propuesta | Acciones principales | Fase |
| --- | --- | --- | --- |
| Dashboard | `/client` | Acciones requeridas, cotizaciones, proyectos, entregables, mensajes, pagos y solicitudes propias | F4; completar F7/F8 |
| Solicitudes | `/client/requests` | Listar propias y recuperar borradores | F4 |
| Brief nuevo | `/client/requests/create` | Formulario por pasos, adjuntos y guardado | F4 |
| Solicitud | `/client/requests/{request}` | Resumen, estado, aclaraciones, archivos e historial visible | F4 |
| Cotizaciones | `/client/quotes` | Familias propias y pendientes de respuesta | F5 |
| Familia de cotización | `/client/quotes/{quoteFamily}` | Versiones publicadas, negociación, aceptación/rechazo y vigencia | F5 |
| Versión | `/client/quotes/{quoteFamily}/versions/{version}` | Alcance, precio y condiciones históricas sin edición | F5 |
| Proyectos | `/client/projects` | Activos e historial; filtros por estado | F6 |
| Proyecto | `/client/projects/{project}` | Resumen, alcance, equipo autorizado, trabajo compartido, actividad, entregables, archivos, chat y pagos | F6/F7/F8 |
| Entregable | `/client/projects/{project}/deliverables/{deliverable}` | Versiones enviadas, comentario, solicitud de cambios y aprobación | F7 |
| Mensajes | `/client/messages` | Bandeja de conversaciones propias con enlace al contexto | F4/F7 |
| Pagos | `/client/payments` | Hitos, pendientes y transacciones propias; pago antes de proyecto cuando corresponda | F8 |
| Pago | `/client/payments/{payment}` | Detalle y resultado confirmado por backend | F8 |

Las pestañas de proyecto forman una pantalla coherente, con URL que conserva la pestaña y filtros. CLIENT solo ve trabajo marcado para compartir y entregables enviados; nunca notas internas, revisiones internas o detalles financieros de otros clientes.

## Área STAFF

| Pantalla | Ruta propuesta | Acciones principales | Fase |
| --- | --- | --- | --- |
| Dashboard | `/staff` | Trabajo asignado, bloqueos, entregables, actividad y acciones requeridas | F6/F7 |
| Proyectos asignados | `/staff/projects` | Listar proyectos con membresía vigente | F6 |
| Proyecto | `/staff/projects/{project}` | Alcance de trabajo autorizado, equipo, tablero, archivos y conversación | F6/F7 |
| Mi trabajo | `/staff/work` | Lista/tablero, filtros y tareas asignadas | F6 |
| Elemento de trabajo | `/staff/work/{workItem}` | Detalle, comentarios, archivos e historial; edición según permiso | F6 |
| Entregables | `/staff/deliverables` | Borradores y revisiones de proyectos asignados | F7 |
| Mensajes | `/staff/messages` | Conversaciones donde tiene participación autorizada | F7 |

La pertenencia al proyecto permite colaboración, pero no concede por sí sola revisión comercial de solicitudes, acceso a cotizaciones, cambios de equipo, publicación al cliente ni gestión de pagos. D02 definirá las facultades exactas de responsables. No habrá una clasificación del equipo por Story Points, velocidad o productividad.

## Área OWNER

| Pantalla | Ruta propuesta | Acciones principales | Fase |
| --- | --- | --- | --- |
| Dashboard | `/owner` | Solicitudes, clientes, trabajo, entregas, pagos, alertas e incidencias | Completar F4–F8 |
| Cuentas | `/owner/accounts` | CLIENT/STAFF: incorporación, estados, especialidades y actividad reciente; implementado | F1 |
| Clientes | `/owner/clients` y `/{client}` | Detalle, recursos y actividad comercial; estados ya gestionados en Cuentas | F4 |
| Staff | `/owner/staff` y `/{staff}` | Detalle y asignaciones; incorporación y especialidades ya gestionadas en Cuentas | F6 |
| Servicios | `/owner/content?kind=SERVICE`, `/owner/content/{entry}/edit` | Crear, ordenar, editar borrador y publicar | F3 |
| Portfolio | `/owner/content?kind=PORTFOLIO`, `/owner/content/{entry}/edit` | Contenido del caso y medios públicos elegidos | F3 |
| Contenido | `/owner/content?kind=PAGE`, `/owner/content/{entry}/edit` | Secciones institucionales y páginas; publicación independiente del borrador | F3 |
| Datos públicos | `/owner/content/settings` | Responsable, correo, dirección y aviso legal provisional | F3 |
| Contacto recibido | `/owner/contact` | Leer consultas privadas y marcarlas como revisadas; sin envío externo | F3 |
| Solicitudes | `/owner/requests` y `/{request}` | Bandeja, revisión, aclaraciones, notas privadas, viabilidad y cotización | F4 |
| Cotizaciones | `/owner/quotes` y `/{quoteFamily}` | Versiones, publicación, negociación y aceptación histórica | F5 |
| Proyectos | `/owner/projects` y `/{project}` | Estado, equipo, alcance aceptado, trabajo y actividad | F6 |
| Trabajo | `/owner/work` y `/{workItem}` | Organización y responsabilidades por proyecto | F6 |
| Entregables | `/owner/deliverables` y `/{deliverable}` | Revisión interna, envío, versiones y resolución de cambios | F7 |
| Archivos | `/owner/files` | Gestión contextual y acceso autorizado, sin volver públicos los adjuntos | F4/F7 |
| Mensajes | `/owner/messages` | Conversaciones autorizadas de solicitudes y proyectos | F4/F7 |
| Pagos | `/owner/payments` y `/{payment}` | Hitos, conciliación, transacciones y operaciones financieras | F8 |
| Auditoría | `/owner/audit` | Actor, operación, contexto, fecha y resultado sin secretos | Base F1/F4; completar F8 |
| Configuración | `/owner/settings` | Preferencias de producto y canales; secretos en configuración del entorno | F3/F8 |
| Seguridad | `/owner/security` | Eventos y operaciones administrativas protegidas | F1/F9 |

## Matriz de capacidades propuesta

`Propio` implica relación autorizada con el cliente. `Asignado` exige membresía vigente y la capacidad concreta; no es permiso global. `Gestiona` exige sesión válida, permiso administrativo, estado compatible y protección adicional cuando corresponda. Todas las filas están sujetas al estado de la cuenta.

| Acción | Visitante | CLIENT | STAFF | OWNER |
| --- | --- | --- | --- | --- |
| Leer contenido público publicado | Sí | Sí | Sí | Sí |
| Administrar contenido público | No | No | No | Gestiona |
| Editar perfil permitido | No | Propio | Propio | Propio |
| Cambiar roles y estados administrativos | No | No | No | Gestiona |
| Crear/enviar solicitud | Iniciar recorrido | Propio | No | Administración del caso, si se acuerda crear en nombre del cliente |
| Revisar solicitud y notas internas | No | Solo aclaraciones compartidas | No por defecto | Gestiona |
| Editar cotización y publicar versión | No | No | No | Gestiona |
| Negociar cotización | No | Propio | No | Gestiona |
| Aceptar/rechazar versión publicada | No | Propio | No | Registra gestión; aceptación en nombre del cliente requiere una regla explícita |
| Crear proyecto desde aceptación elegible | No | Dispara el recorrido, no fija datos | No | Acción de backend controlada |
| Leer proyecto | No | Propio | Asignado | Gestiona |
| Cambiar estado operacional del proyecto | No | No por defecto | Según D02 | Gestiona |
| Asignar equipo y responsabilidades | No | No | No por defecto; D02 | Gestiona |
| Leer trabajo | No | Compartido | Asignado | Gestiona |
| Crear/asignar/priorizar trabajo | No | No por defecto | Según D02 | Gestiona |
| Actualizar estado y comentar trabajo | No | Comentarios compartidos autorizados | Asignado y autorizado | Gestiona |
| Crear versión de entregable | No | No | Asignado y autorizado | Gestiona |
| Revisar internamente/publicar entregable | No | No | Capacidad explícita según D02 | Gestiona |
| Pedir cambios/aprobar entregable enviado | No | Propio | No como cliente | Seguimiento; sustitución del cliente por acordar |
| Descargar/subir archivos | No, salvo medios públicos | Contexto propio | Contexto asignado | Contexto administrado |
| Leer/enviar mensajes | No | Participante autorizado | Participante autorizado | Contexto administrado autorizado |
| Marcar notificación o conversación como leída | No | Propia | Propia | Propia |
| Leer hitos/transacciones | No | Propio | No por defecto | Gestiona |
| Iniciar pago | No | Propio, importe calculado en backend | No | Gestión permitida |
| Confirmar pago, devolución o reverso | No | No | No | Backend/proveedor; operación administrativa conciliada y auditada |
| Leer auditoría global | No | No | No | Gestiona |
| Alterar/borrar historial de auditoría | No | No | No | No edición ordinaria; retención mediante política controlada |

## Acciones HTTP y exposición de datos

Las rutas anteriores son páginas de lectura. Guardar borrador, enviar, pedir aclaración, publicar versión, aceptar, asignar equipo, aprobar entregable y marcar lectura serán acciones POST/PATCH/DELETE explícitas; no efectos secundarios de una petición GET.

Los endpoints comunes de mensajes, archivos y notificaciones conservan el mismo control contextual que las páginas. Validar también las relaciones anidadas: una versión debe pertenecer a la familia indicada, un entregable al proyecto y un adjunto al contexto autorizado.

Las propiedades enviadas a React se construyen por rol y contexto. No se envían campos internos al cliente para después ocultarlos con CSS. Las listas, contadores, búsquedas y dashboards usan los mismos filtros de autorización que los detalles.

## Escenarios mínimos de acceso

- CLIENT A intenta abrir, buscar, descargar o modificar recursos de CLIENT B: denegado.
- STAFF sin asignación o con asignación revocada intenta entrar a un proyecto: denegado.
- CLIENT intenta forzar `role=OWNER`, un estado o un precio en un formulario: rechazado o ignorado con fuente de verdad del backend.
- CLIENT accede al borrador de una cotización o revisión interna de entregable: denegado.
- Usuario conoce una URL privada o una URL temporal antigua: sigue necesitando autorización vigente.
- Usuario recibe una notificación sobre un recurso cuyo permiso se revocó: el enlace no concede acceso.
- OWNER repite una aceptación o una operación financiera: no elude las restricciones de unicidad ni el historial.
- Usuario restringido mantiene abierta una sesión o conexión realtime: las siguientes operaciones se bloquean conforme a D03.

Los escenarios se convierten en pruebas en la fase donde aparece el recurso, además de su revisión en F9.
