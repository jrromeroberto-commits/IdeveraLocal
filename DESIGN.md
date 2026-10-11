# Diseño de IDEVERA STUDIO

Propuesta inicial de diseño para la primera versión. Fuente funcional: [requisitos](IDEVERA_REQUERIMIENTOS_CLIENTE_MVP_V1.docx), secciones 7 y 8. Fuente de exploración visual: [referencias revisadas](REFERENCIAS_VISUALES.md). Los tokens y composiciones siguientes deben comprobarse en el prototipo; todavía no son una implementación ni una validación visual en dispositivos.

La identidad será oscura, editorial y cinematográfica, con índigo y violeta contenidos. La home dará protagonismo al trabajo creativo y al mensaje del estudio. Los paneles compartirán tipografía, superficies, botones y lenguaje de estados, con una densidad adecuada para trabajar con proyectos y decisiones.

## Referencias y aplicación concreta

| Referencia | Aplicación propuesta |
| --- | --- |
| [Metic](https://metic.studio/en) | Jerarquía clara del hero, composición asimétrica y CTA reconocible |
| [TWKS](https://twks.ch/en) y [Onda](https://www.ondastudio.co/) | Escala editorial, márgenes y ritmo entre bloques de proyectos |
| [Preymaker](https://preymaker.com/) y [Wonder Vision](https://www.wonder-vision.com/) | Imágenes amplias, presencia del portfolio y secuencia cinematográfica |
| [Studio Mirage](https://studiomirage.io/) | Inspiración para un recurso abstracto de identidad con profundidad y luz |
| [Vengeance UI](https://www.vengenceui.com/components/stagger-text) | Entrada escalonada del slogan; brillo y fondos limitados a la identidad de Idevera |
| [Skiper Image Reveal](https://skiper-ui.com/v1/skiper71) | Revelado breve de imágenes con texto y acceso al caso disponibles |
| [Animmaster](https://animmasterlib.dev/) | Fuente secundaria; demos no reproducidas deben validarse antes de elegir movimientos |
| [getdesign.md](https://getdesign.md/) | Estructura de reglas compartidas para colores, espacios, componentes y estados |

La investigación anterior documenta lo observado y sus límites. No se copian contenidos o recursos de marca, ni se asume acceso al código de demos de pago. Cada movimiento se adapta al contenido y a la accesibilidad de IDEVERA.

## Identidad y contenido

- Marca visible: IDEVERA STUDIO; wordmark tipográfico provisional hasta contar con logo propio.
- Slogan exacto: “From vision to reality.”
- Interfaz propuesta en español, pendiente de D04; el slogan conserva su idioma.
- CTA público principal: “Iniciar proyecto”. Secundario: “Ver proyectos”.
- Texto concreto sobre servicios y trabajo; evitar promesas, resultados o testimonios inventados.
- Portfolio con material propio autorizado. Los ejemplos de diseño se identifican como provisionales.
- Un motivo geométrico propio en el hero, con planos, luz y profundidad en índigo/violeta; la implementación se decide al prototipar.

## Tokens iniciales

| Token | Valor propuesto | Uso |
| --- | --- | --- |
| Fondo base | `#09090F` | Página y fondo de aplicación |
| Superficie | `#12121C` | Paneles y bloques de contenido |
| Superficie elevada | `#1A1A28` | Menú, modal, drawer y elementos superpuestos |
| Borde | `#303044` | Separación de superficies; controles deben tener identificación suficiente |
| Texto principal | `#F5F5F8` | Títulos y cuerpo |
| Texto secundario | `#B5B5C6` | Descripciones y metadatos legibles |
| Acento | `#8B7AFB` | Enlaces, foco y detalles |
| Acción principal | `#6D4AFF` | Botón primario con texto claro |
| Éxito | `#74D6A5` | Confirmación y estados favorables |
| Advertencia | `#F4C76B` | Pendiente o atención |
| Error | `#FF8E9C` | Error y fallo |
| Información | `#A3B4FF` | Información neutral |

El contraste se comprueba en combinaciones concretas, incluyendo hover, foco, disabled, campos y superficies con imagen. Texto pequeño y estados no dependen de un color tenue. Cada estado usa etiqueta e icono cuando aporten claridad; color por sí solo no comunica la condición.

### Tipografía

Punto de partida: Instrument Sans, instalada en la base, con pesos 400, 500 y 600. La elección final se valida con los textos reales del hero, briefs y cotizaciones antes de añadir fuentes. Se usa una sola familia inicialmente, con escala, espacio y composición para diferenciar jerarquías.

| Nivel | Tamaño inicial | Regla |
| --- | --- | --- |
| Hero | 44–120 px según viewport | Titular fluido, saltos controlados y altura de línea cercana a 1 |
| Título de sección pública | 32–64 px | Espacio suficiente y longitud legible |
| Título de pantalla privada | 24–32 px | Identifica la tarea y permite acciones cercanas |
| Subtítulo | 18–24 px | Jerarquía clara de bloques |
| Cuerpo | 16 px | Altura de línea 1.5–1.7 |
| Metadatos | 14 px | Contraste suficiente y lectura cómoda |

Importes, fechas y contadores se alinean de forma estable. No se usa una fuente decorativa para briefs, mensajes, condiciones o números financieros.

### Espaciado y forma

- Escala de espacio: 4, 8, 12, 16, 24, 32, 48, 64, 96 y 128 px.
- Contenido público con ancho máximo aproximado de 1320 px; márgenes de 20–24 px en móvil y crecimiento en escritorio.
- Radios iniciales: 10–12 px en controles, 16–20 px en superficies; no redondear cada elemento con una forma distinta.
- Bordes finos y sombras contenidas; degradados y glow reservados al hero y acentos puntuales.
- Botones con área táctil mínima propuesta de 44 × 44 px; estados de foco visibles.

## Home propuesta

| Orden | Sección | Composición y propósito |
| --- | --- | --- |
| 1 | Navegación | Wordmark, servicios, portfolio, estudio y acceso; CTA claro; menú compacto en móvil |
| 2 | Hero | Slogan protagonista, explicación breve, dos CTA y motivo abstracto propio; contenido útil antes que la decoración |
| 3 | Trabajo destacado | Dos o tres casos propios con imágenes grandes, título y categoría; jerarquía editorial |
| 4 | Servicios | Oferta del Word organizada en bloques legibles, con detalle de cada servicio |
| 5 | Proceso | Brief → cotización → colaboración → entrega, explicado en lenguaje del cliente |
| 6 | Estudio | Presentación corta, enfoque y evidencia real del trabajo |
| 7 | Contacto | Invitación concreta a iniciar proyecto y alternativa de contacto |
| 8 | Footer | Navegación secundaria, contacto, redes reales y enlaces legales |

El hero debe comunicar qué hace el estudio y ofrecer una acción antes de exigir scroll. El portfolio tiene variaciones controladas de proporción y tamaño. El orden final se comprueba en el prototipo con los recursos disponibles; no se incluyen métricas o marcas de clientes ficticias para llenar espacio.

## Paneles con identidad compartida

En escritorio: navegación lateral de unos 240 px, cabecera de contexto y contenido principal. En móvil: cabecera compacta y drawer de navegación; las acciones frecuentes permanecen cerca del contenido. Cada rol tiene su navegación y dashboard, aunque reutilice componentes.

| Área | Prioridad del dashboard |
| --- | --- |
| CLIENT | Qué necesita responder o aprobar, proyectos activos, cotizaciones, entregables y pagos propios |
| STAFF | Trabajo asignado, bloqueos, revisiones y contexto de proyectos |
| OWNER | Solicitudes y decisiones pendientes, operación de proyectos, pagos, incidencias y actividad |

Se priorizan acciones requeridas sobre estadísticas decorativas. Los gráficos solo aparecen si ayudan a una decisión y tienen datos reales. Se diferencian contadores de notificaciones, conversaciones y acciones de negocio.

### Pantalla representativa de CLIENT

El detalle de proyecto será el segundo prototipo:

1. Cabecera con nombre, referencia y dos estados separados: operacional y financiero.
2. Próxima acción del cliente, si existe, con contexto y CTA.
3. Resumen de alcance y siguiente entrega/hito.
4. Pestañas de trabajo compartido, entregables, archivos, conversación, pagos y actividad.
5. Detalle o drawer para entregable y revisión, conservando el contexto del proyecto.

En móvil se mantiene ese orden de lectura; pestañas y acciones no se pierden fuera del viewport. No se muestran notas internas, revisiones sin publicar o controles sin permiso.

## Componentes que construiremos

| Grupo | Componentes | Variantes necesarias |
| --- | --- | --- |
| Marca y navegación | Wordmark, header público, menú, footer, shell por rol, breadcrumbs | Desktop, móvil, foco, apertura y ruta activa |
| Acciones | Botón, enlace de acción, menú contextual, confirmación | Primario, secundario, peligro, pending, disabled y éxito |
| Formularios | Campo, textarea, selector, fechas, importe/moneda, upload, stepper | Etiqueta, ayuda, obligatorio, error, guardando, guardado y conflicto |
| Contenido público | Hero, tarjeta de servicio, caso, galería, proceso | Imagen disponible/ausente, vídeo con controles y acceso al detalle |
| Datos | Tabla/lista, filtros, paginación, tablero, card de resumen | Carga, vacío, búsqueda sin resultados, error y permiso restringido |
| Dominio | Badge de estado, historial, quote version, payment milestone, deliverable version | Estados exactos del Word y visualización histórica |
| Colaboración | Conversación, mensaje, adjunto, lectura, notificación, acción requerida | Envío, fallo recuperable, no leído, resolución y acceso revocado |
| Superposición | Modal, drawer, tooltip, toast | Foco, cierre por teclado, scroll y confirmación accesible |

Se reutiliza la base Radix/shadcn y Lucide ya instalada, adaptando el lenguaje visual. Los componentes compartidos no determinan permisos de backend. Formularios de dinero y estados reciben opciones autorizadas y muestran valores calculados por Laravel.

## Movimiento inicial

Los tiempos son propuestas propias para prototipar, no medidas de las referencias. Easing inicial: `cubic-bezier(0.22, 1, 0.36, 1)` para entradas y `ease-out` para respuesta breve.

| Elemento | Movimiento | Duración inicial | Reducción de movimiento |
| --- | --- | --- | --- |
| Hero | Slogan por líneas o grupos; entrada de explicación y CTA | 600–900 ms en total | Contenido visible inmediatamente |
| Fondo del hero | Luz o forma a velocidad baja | Continua, coste a medir | Fondo estático |
| Sección | Opacidad y desplazamiento de 12–24 px, una vez | 400–600 ms | Aparición inmediata o fade breve |
| Imagen de portfolio | Recorte breve después de cargar | 500–700 ms | Imagen completa |
| Hover | Color, borde e icono con desplazamiento pequeño | 150–220 ms | Cambio de color sin desplazamiento |
| Menú/modal/drawer | Apertura y cierre conservando foco | 180–300 ms | Cambio directo o fade breve |
| Navegación | Indicador de progreso y transición discreta | 150–250 ms | Feedback inmediato |
| Formulario | Respuesta de guardado, error y éxito | 150–220 ms | Mismo contenido y feedback |
| Skeleton/loader | Reserva del espacio y progreso comprensible | Según espera real | Sin pulso continuo |
| Drag de trabajo | Feedback de posición con alternativa por menú/teclado | Inmediato | Cambio de estado sin exigir drag |

CSS/SVG y capacidades existentes son el primer recurso. Una biblioteca adicional se decide si el hero o una transición la necesitan, revisando licencia, peso y funcionamiento con React. No se instala el catálogo completo de ninguna referencia.

Las acciones importantes deben responder enseguida: la animación nunca retrasa aceptación, envío, aprobación o pago. No se secuestra el scroll ni se exige un cursor personalizado. El contenido permanece accesible si falla el movimiento o JavaScript de una decoración.

## Responsive y accesibilidad

- Diseñar desde móvil y validar a 360/390, 768, 1024 y 1440 px, además de alturas pequeñas y zoom.
- Orden del DOM acorde con lectura y teclado; ninguna función depende exclusivamente de hover o drag.
- Labels, instrucciones y errores asociados a campos; foco al error/resumen cuando corresponda.
- Menús y diálogos con foco controlado, cierre accesible y regreso al activador.
- Tabs, tablas y filtros con semántica; tablas anchas dentro de un contenedor identificado y usable.
- Botones de carga con texto claro; avisos importantes no dependen de un toast efímero.
- Imágenes con dimensiones reservadas y texto alternativo; medios decorativos excluidos de lectura.
- Video con controles; no autoplay con audio. El contenido esencial no depende de reproducción automática.
- El diseño oscuro será la referencia inicial; cualquier variante de apariencia se valida con los mismos criterios.

## Errores, vacíos y conectividad

Cada pantalla con datos contempla carga, vacío inicial, vacío tras filtros y error recuperable. El vacío ofrece una acción pertinente: iniciar solicitud, recuperar borrador o volver a un contexto autorizado. Un fallo de red conserva el formulario cuando sea razonable y permite reintentar sin duplicar la operación.

Conflictos de versión, sesión vencida y permisos revocados requieren mensajes específicos. Los datos autoritativos del backend no se sustituyen por un éxito optimista permanente cuando el servidor rechaza la acción.

## Validación del prototipo

- [ ] Home y detalle CLIENT comparten identidad sin perder su función.
- [ ] Slogan, explicación y CTA son legibles en móvil y escritorio.
- [ ] Material de portfolio y textos son propios o provisionales identificados.
- [ ] Teclado, foco, zoom y contraste revisados en las pantallas y componentes elegidos.
- [ ] Movimiento completo y reducido funcionan; la versión reducida conserva todas las acciones.
- [ ] Imágenes y fuentes no producen saltos visibles y la carga se mide con recursos reales.
- [ ] Interacciones táctiles y los estados de error/validación han sido probados.
- [ ] Los resultados y decisiones se registran aquí antes de extender el diseño a todas las pantallas.

Las pruebas de móvil, contraste y rendimiento siguen pendientes; la revisión de referencias anterior no equivale a haberlas realizado en IDEVERA.
