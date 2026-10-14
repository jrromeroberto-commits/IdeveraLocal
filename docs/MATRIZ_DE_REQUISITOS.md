# Matriz de requisitos de IDEVERA STUDIO

Fuente: [documento del cliente v1.0](../IDEVERA_REQUERIMIENTOS_CLIENTE_MVP_V1.docx). Se incluyen sus 96 identificadores: 46 funcionales, 12 de experiencia, 28 no funcionales y 10 reglas de negocio. Los perfiles y estados de la sección 5, la auditoría de la sección 10 y el recorrido de la sección 11 están desarrollados en el [mapa de permisos](MAPA_DE_PANTALLAS_Y_PERMISOS.md), la [arquitectura](ARQUITECTURA_Y_DOMINIO.md) y el [plan](PLAN_DE_IMPLEMENTACION.md).

Las fases indican cuándo se construye y se verifica cada punto. Ninguna fila se considera cumplida solo por estar documentada. F1 incorpora roles, estados de cuenta, alta local de OWNER, administración de cuentas y desactivación con retención; su alcance y pruebas están en [F1: roles y acceso](F1_ROLES_Y_ACCESO.md). RF-CLI-001/RF-CLI-002 cuentan con pruebas de backend; siguen pendientes el diseño F2 y los canales F8. RF-CLI-004, RF-STA-001 y RF-PRO-003 requieren además recursos y asignaciones de F4–F7; no se declaran completos por disponer de áreas separadas.

La aclaración del usuario del 11 de octubre de 2026 establece un proyecto de pruebas local hasta terminarlo. La evidencia de esta entrega se obtiene con cuentas/datos de prueba, correo local y PayPal Sandbox; no requiere dominio comprado, cuenta oficial Culqi o cobros Live. Se distinguirá integración verificada de simulación de estados. Una demo gratuita externa es opcional después del cierre local.

Al cerrar un requisito añadiremos referencia al cambio/commit, prueba o revisión visual que demuestre su cumplimiento. Los criterios de esta tabla son evidencia mínima; las condiciones adicionales de permisos, estados e historial de los documentos asociados también se aplican.

F2 entrega la home y el prototipo del detalle CLIENT, con componentes, estados y SSR. Su evidencia visual y técnica está en [F2: diseño y componentes](F2_DISENO_Y_COMPONENTES.md). F3 conecta la home al contenido publicado e implementa servicios, portfolio, páginas, contacto local y CMS OWNER; evidencia en [F3: web pública y contenido](F3_WEB_PUBLICA_Y_CONTENIDO.md). RF-PUB-001/002/003/005 y RF-OWN-003 cuentan con implementación y pruebas locales. RF-PUB-004 dispone de pantallas, pero sus textos legales siguen provisionales y pendientes de revisión en F9. Las pantallas de proyecto de ejemplo no cierran RF-PRO, RF-DEL, RF-CHA ni RF-PAY.

## Requisitos funcionales

| ID | Función | Fase | Evidencia de aceptación |
| --- | --- | --- | --- |
| RF-PUB-001 | Home | F2/F3 | Explica el estudio, muestra slogan, servicios, casos, proceso y CTA usable |
| RF-PUB-002 | Servicios | F3 | Los nueve grupos del documento se contemplan y los publicados tienen detalle |
| RF-PUB-003 | Portfolio | F3 | OWNER publica un caso con contenido y medios; solo casos elegidos son públicos |
| RF-PUB-004 | Institucional y legal | F3/F9 | About, proceso, contacto y contenido legal pertinente disponibles |
| RF-PUB-005 | Páginas de sistema | F2/F3 | 404 y errores relevantes orientan al usuario sin exponer detalles sensibles |
| RF-CLI-001 | Registro y acceso | F1/F8 | Registro, login, logout, verificación y recuperación mediante correo log/visor local comprobados |
| RF-CLI-002 | Perfil | F1/F2 | Edita campos permitidos propios y no puede modificar rol o estado administrativo |
| RF-CLI-003 | Dashboard CLIENT | F4–F8 | Muestra sus solicitudes, cotizaciones, proyectos, acciones, entregables, mensajes, pagos y notificaciones |
| RF-CLI-004 | Acceso del cliente | F1–F9 | Peticiones de lectura, búsqueda, descarga y modificación de otro cliente se deniegan |
| RF-REQ-001 | Crear solicitud y borrador | F4 | Borrador persistente recuperable; envío válido sin duplicación |
| RF-REQ-002 | Contenido del brief | F4 | Servicio, título, descripción, objetivos, requisitos, referencias, presupuesto, fecha, adjuntos y datos adicionales según caso |
| RF-REQ-003 | Estados de solicitud | F4 | Los diez estados exactos se validan; transiciones no permitidas rechazadas |
| RF-REQ-004 | Revisión OWNER | F4 | Revisa, pide aclaración, toma notas, comunica, evalúa y prepara cotización/rechazo/cierre |
| RF-REQ-005 | Notas internas | F4 | No aparecen en respuestas, búsquedas o props de CLIENT |
| RF-QUO-001 | Familia de cotizaciones | F5 | Solicitud elegible genera familia con los ocho estados exactos |
| RF-QUO-002 | Versionado de cotización | F5 | Publicar otra versión conserva contenido y acceso histórico permitido a la anterior |
| RF-QUO-003 | Estados de versión | F5 | Los ocho estados exactos y sus condiciones están cubiertos |
| RF-QUO-004 | Contenido de versión | F5 | Alcance, entregables, precio/moneda, condiciones, cronograma, hitos, exclusiones y vigencia persisten |
| RF-QUO-005 | Negociación | F5 | CLIENT y OWNER intercambian cambios con contexto y trazabilidad |
| RF-QUO-006 | Aceptación | F5/F6/F8 | Versión congelada; aceptación y creación de proyecto únicas ante reintentos/concurrencia |
| RF-PRO-001 | Estado operacional | F6 | Los diez estados exactos se gestionan sin confundirlos con finanzas |
| RF-PRO-002 | Información del proyecto | F6–F8 | Cliente, equipo, alcance aceptado, fechas, entregas, archivos, actividad, comunicación, finanzas e historial relacionados |
| RF-PRO-003 | Responsabilidades | F1/F6 | TEAM_MEMBER, PROJECT_LEAD y SCRUM_MASTER independientes de rol y especialidad |
| RF-PRO-004 | Tipos de trabajo | F6 | EPIC, STORY y TASK persistidos con relaciones válidas |
| RF-PRO-005 | Estados de trabajo | F6 | BACKLOG, TO_DO, IN_PROGRESS, REVIEW, DONE y BLOCKED cubiertos |
| RF-PRO-006 | Gestión del trabajo | F6 | Crear, asignar, editar, priorizar, comentar, adjuntar, cambiar estado y consultar historial según permiso |
| RF-PRO-007 | Sin ranking de staff | F6/F9 | No hay clasificación automática por productividad o Story Points |
| RF-DEL-001 | Estado de entregable | F7 | Los cinco estados exactos y sus actores están cubiertos |
| RF-DEL-002 | Versiones de entregable | F7 | Nueva versión conserva archivos, descripción, comentarios y revisiones anteriores |
| RF-DEL-003 | Revisión | F7 | Revisión interna → envío → cambios o aprobación de versión exacta |
| RF-FIL-001 | Archivos por contexto | F4–F7 | Adjuntos funcionan en solicitudes, proyectos, chat, entregables y documentación |
| RF-FIL-002 | Privacidad de archivo | F4–F9 | URL conocida o permiso revocado no permiten descargar/previsualizar un archivo privado |
| RF-CHA-001 | Chat contextual | F4/F7 | Texto, adjuntos, fechas, lectura y participantes autorizados en solicitud/proyecto |
| RF-CHA-002 | Historial persistente | F7 | Cerrar sesión y volver conserva el historial autorizado |
| RF-NOT-001 | Eventos relevantes | F4–F8 | Se notifican solicitudes, quotes, mensajes, asignación, entregas, aprobaciones, pagos y acciones según canal acordado |
| RF-NOT-002 | Tipos de pendiente | F4/F7 | Leer notificación no marca chat como leído ni resuelve una acción requerida |
| RF-PAY-001 | Hitos de pago | F8 | Los seis estados exactos reflejan saldo, vencimiento y cancelación autorizada |
| RF-PAY-002 | Transacciones | F8 | Los doce estados exactos tienen tratamiento y mapeo de evento/procedimiento pertinente |
| RF-PAY-003 | Autoridad financiera | F5/F8 | Manipular precio, moneda o éxito desde el navegador no altera el importe ni confirma pago |
| RF-PAY-004 | Historial financiero | F8 | Pagos, devoluciones, disputas y reversos conservan actor/evento, importe y referencia |
| RF-PAY-005 | Datos sensibles | F8/F9 | No se guardan PAN/CVV ni se exponen credenciales del proveedor en datos o logs |
| RF-PAY-006 | Estado financiero | F6/F8 | Los ocho estados exactos se calculan y presentan separados de operación |
| RF-STA-001 | Dashboard STAFF | F6/F7 | Solo trabajo/proyectos asignados, entregas, actividad y acciones autorizadas |
| RF-OWN-001 | Dashboard OWNER | F4–F8 | Visión de solicitudes, quotes, clientes, staff, proyectos, trabajo, entregas, pagos, alertas e incidencias |
| RF-OWN-002 | Administración integral | F1–F8 | Todos los módulos administrativos del mapa tienen flujos autorizados y auditados |
| RF-OWN-003 | Contenido administrable | F3 | OWNER actualiza servicios, portfolio y contenido seleccionado sin editar código |

## Experiencia visual y movimiento

| ID | Requisito | Fase | Evidencia de aceptación |
| --- | --- | --- | --- |
| RX-UI-001 | Identidad premium | F2/F9 | Revisión de home y panel con composición y componentes propios de IDEVERA |
| RX-UI-002 | Oscuro, índigo y violeta | F2 | Tokens y superficies coherentes, degradados contenidos y contraste verificado |
| RX-UI-003 | Consistencia | F2–F9 | Tipografía, espacios, componentes y estados compartidos entre público y tres roles |
| RX-UI-004 | Evitar aspecto genérico | F2/F9 | Diseño aplicado con contenido y jerarquía de IDEVERA; no queda el aspecto del starter |
| RX-UI-005 | Mobile-first | F2–F9 | Flujos completos revisados en móvil, tablet y desktop |
| RX-MOT-001 | Movimiento intencional | F2/F9 | Cada animación tiene propósito y no dificulta lectura o interacción |
| RX-MOT-002 | Cobertura de movimiento | F2–F7 | Hero, scroll, textos/cards, hover, navegación, overlays, menús y estados de espera contemplados |
| RX-MOT-003 | Recursos opcionales | F2 | SVG, fondo o parallax solo si aportan valor y superan la revisión; no son obligatorios todos |
| RX-MOT-004 | Interacción y móvil | F2/F9 | No bloquea acciones, scroll o carga; coste revisado con recursos reales |
| RX-MOT-005 | Preferencia de reducción | F2/F9 | `prefers-reduced-motion` cambia los patrones pertinentes |
| RX-MOT-006 | Usable sin animación | F2/F9 | Todo el contenido y acciones funcionan con movimiento reducido/desactivado |
| RX-MOT-007 | Coherencia de motion | F2–F9 | Tiempos, easing y patrones definidos en DESIGN.md y reutilizados |

## Requisitos no funcionales

| ID | Requisito | Fase | Evidencia de aceptación |
| --- | --- | --- | --- |
| RNF-SEG-001 | Denegación por defecto | F1–F9 | Acciones no autorizadas se rechazan; cada capacidad tiene alcance mínimo |
| RNF-SEG-002 | UI/UUID/realtime no autorizan | F1–F9 | Cambiar identificador o recibir evento no concede lectura ni modificación |
| RNF-SEG-003 | Backend autoriza y valida | F1–F9 | Acciones sensibles pasan por validación, política y acción de dominio |
| RNF-SEG-004 | Recursos privados | F4–F9 | URLs directas, previews y relaciones cruzadas siguen protegidas |
| RNF-SEG-005 | OWNER reforzado | F1/F8/F9 | Operaciones sensibles requieren protección acordada, sesión válida y auditoría |
| RNF-PRI-001 | Información legal | F3/F9 | Términos, privacidad, cancelación/reembolso y cookies pertinentes revisados para la operación |
| RNF-PRI-002 | Derechos y retención | F1/F8/F9 | Procedimientos de datos, conservación y eliminación definidos y comprobables |
| RNF-PRI-003 | Logs y auditoría | F1–F9 | Revisión de registros y exports no encuentra secretos ni datos innecesarios |
| RNF-VAL-001 | Entrada no confiable | F1–F9 | Backend no usa campos críticos del formulario sin validarlos/recalcularlos |
| RNF-VAL-002 | Validación completa | F1–F9 | Tipos, formato, límites, relación, estado y permiso probados en cada módulo |
| RNF-VAL-003 | Transición inválida | F4–F8 | Rechaza origen/destino no permitido sin alterar datos ni historial |
| RNF-CON-001 | Reintentos y simultaneidad | F4–F9 | Doble clic, refresh, retry, pestañas y peticiones simultáneas tienen resultados consistentes |
| RNF-CON-002 | No duplicación crítica | F5–F9 | Aceptación, proyecto, pago, entrega y operaciones administrativas cumplen unicidad/idempotencia |
| RNF-ERR-001 | Manejo coherente de errores | F2–F9 | Validación, permisos, inexistencia, conflicto, temporal e interno tienen respuesta adecuada |
| RNF-ERR-002 | Sin detalle sensible | F2–F9 | Usuario no recibe trazas, configuración o credenciales |
| RNF-ERR-003 | Mensaje accionable | F2–F9 | Cada error indica contexto y siguiente paso seguro |
| RNF-STA-001 | Carga, vacío y error | F2–F9 | Cada pantalla con datos tiene estas variantes comprobadas |
| RNF-STA-002 | Feedback de éxito | F2–F9 | Resultado confirmado se comunica sin ocultar un error del servidor |
| RNF-ACC-001 | Accesibilidad | F2–F9 | Teclado, foco, labels, contraste, semántica y errores accesibles revisados |
| RNF-ACC-002 | Animación accesible | F2/F9 | Motion no bloquea foco, interacción ni lectura; alternativa reducida completa |
| RNF-PER-001 | Público rápido y estable | F2/F3/F9 | Carga, navegación y estabilidad medidas con medios y fuentes finales |
| RNF-PER-002 | Recursos contenidos | F2/F9 | Dependencias, imágenes y efectos revisados; no se cargan catálogos innecesarios |
| RNF-PER-003 | Prioridades de rendimiento | F2/F9 | Se revisan carga inicial, interacción, navegación y saltos visuales |
| RNF-SEO-001 | SEO público | F2/F3/F9 | HTML inicial y metadata, URLs, previews, sitemap, robots y canonical comprobados |
| RNF-SEO-002 | Privado sin indexación | F1/F3/F9 | Paneles protegidos y excluidos de indexación; robots no sustituye autenticación |
| RNF-FOR-001 | Formularios confiables | F2/F4–F9 | Errores claros, datos preservados cuando procede y envío repetido seguro |
| RNF-FOR-002 | Formularios largos | F4/F5 | Progreso visible, borrador y recuperación de trabajo sin pérdida silenciosa |
| RNF-FOR-003 | Fallo de conectividad | F4–F9 | Interrupción de red muestra estado recuperable y reintento sin duplicación |

## Reglas de negocio

| ID | Regla | Fase | Evidencia de aceptación |
| --- | --- | --- | --- |
| RN-001 | Estados del documento | F1–F9 | Enums y validadores coinciden con el catálogo; cambios de alcance registrados |
| RN-002 | Operación y finanzas separadas | F6/F8 | Campos, reglas y badges independientes; cambios no se confunden |
| RN-003 | Historial de versiones | F5/F7 | Crear versión conserva snapshots y comentarios/revisiones anteriores |
| RN-004 | Proyecto único | F5/F6/F8 | Restricción de base y prueba concurrente impiden proyectos duplicados por aceptación |
| RN-005 | Notas internas protegidas | F4 | CLIENT no recibe campos o entradas internas en ningún canal |
| RN-006 | URL no concede acceso | F1–F9 | Pruebas directas y después de revocación confirman autorización vigente |
| RN-007 | Fuente de verdad backend | F1–F9 | Manipulación de permisos, estados, relaciones, archivos, quotes, proyectos y pagos no altera reglas |
| RN-008 | Pendientes separados | F4/F7 | Contadores y acciones independientes para notificación, chat y acción requerida |
| RN-009 | Dinero no confiado a UI | F5/F8 | Backend deriva importes y confirma pago con evidencia autoritativa |
| RN-010 | Sin ranking automático | F6/F9 | Dashboards e informes no clasifican staff mediante métricas de productividad |

## Revisión de cierre de la primera versión

La aceptación general requiere un recorrido completo local desde visitante hasta cierre, con pagos aplicables en Sandbox, colaboración e historial; aislamiento entre roles y cuentas; estados y transiciones consistentes; archivos privados protegidos; responsive y accesibilidad; animaciones utilizables; operaciones críticas resistentes a concurrencia; y pantallas con carga, vacío y error.

Durante la implementación esta matriz será el registro de cobertura. Un cambio posterior de funciones, permisos o estados se registra con motivo, impacto y decisión del usuario, conforme a la sección 14 del Word.
