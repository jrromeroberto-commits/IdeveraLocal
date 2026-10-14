---
name: IDEVERA STUDIO
description: "From vision to reality. — Índice editorial"
colors:
  primary: "#6d4aff"
  primary-hover: "#7a5aff"
  primary-text: "#ffffff"
  lilac-link: "#b4a7ff"
  lilac-emphasis: "#a48fff"
  focus-ring: "#8b7afb"
  ground: "#09090f"
  surface: "#12121c"
  surface-raised: "#1a1a28"
  surface-active: "#242035"
  text: "#f5f5f8"
  text-secondary: "#b5b5c6"
  text-metadata: "#c8c8d5"
  border: "#303044"
  border-quiet: "#252530"
  border-control: "#3f3f50"
  border-select: "#49495c"
  border-field: "#68687f"
  success: "#74d6a5"
  warning: "#f4c76b"
  error: "#ff8e9c"
typography:
  display:
    fontFamily: "Pontano Sans, sans-serif"
    fontSize: "8.91vw"
    fontWeight: 700
    lineHeight: 1
    letterSpacing: "-0.035em"
  headline:
    fontFamily: "Pontano Sans, sans-serif"
    fontSize: "clamp(38px, 5.5vw, 80px)"
    fontWeight: 600
    lineHeight: 1.12
    letterSpacing: "-0.035em"
  section:
    fontFamily: "Pontano Sans, sans-serif"
    fontSize: "clamp(36px, 4.3vw, 72px)"
    fontWeight: 600
    lineHeight: 1.08
    letterSpacing: "-0.03em"
  task-title:
    fontFamily: "Instrument Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "clamp(28px, 3vw, 42px)"
    fontWeight: 500
    lineHeight: 1.12
    letterSpacing: "-0.035em"
  body:
    fontFamily: "Instrument Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "16px"
    fontWeight: 400
    lineHeight: 1.7
  label:
    fontFamily: "Instrument Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "14px"
    fontWeight: 500
rounded:
  flat: "0px"
  status: "5px"
  editor-field: "6px"
  public-field: "7px"
  control: "8px"
  base: "10px"
  panel: "12px"
spacing:
  xs: "8px"
  sm: "12px"
  md: "16px"
  row: "20px"
  lg: "24px"
  xl: "32px"
  section: "48px"
  wide: "64px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.primary-text}"
    typography: "{typography.label}"
    rounded: "{rounded.control}"
    padding: "13px 21px"
  button-primary-hover:
    backgroundColor: "{colors.primary-hover}"
  button-secondary:
    backgroundColor: "transparent"
    textColor: "{colors.text}"
    typography: "{typography.label}"
    rounded: "{rounded.control}"
    padding: "13px 21px"
  button-secondary-hover:
    backgroundColor: "{colors.surface-raised}"
  button-light:
    backgroundColor: "{colors.text}"
    textColor: "#151024"
    typography: "{typography.label}"
    rounded: "{rounded.control}"
    padding: "13px 21px"
  public-field:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.text}"
    typography: "{typography.body}"
    rounded: "{rounded.public-field}"
    padding: "12px"
  publication-status:
    backgroundColor: "#74d6a530"
    textColor: "{colors.success}"
    rounded: "{rounded.status}"
    padding: "5px 9px"
  cms-panel:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.text}"
    rounded: "{rounded.panel}"
    padding: "24px"
  service-row:
    backgroundColor: "transparent"
    textColor: "{colors.text}"
    rounded: "{rounded.flat}"
    padding: "24px 0"
---

# Design System: IDEVERA STUDIO

## Overview

**Creative North Star: "Índice editorial"**

IDEVERA presenta desarrollo, diseño y arte digital sobre un campo casi negro teñido de violeta. La voz es directa, amplia y sobria: titulares blancos, énfasis lila sólido, imágenes materiales y reglas finas organizan una lectura editorial. IDEVERA STUDIO y «From vision to reality.» siguen siendo la identidad; el texto de interfaz está en español y se dirige con igual importancia a negocios y personas con proyectos propios.

El trabajo visual conserva profundidad dentro de sus imágenes; la interfaz usa planos y tipografía para ordenar el contenido. Los títulos, categorías y controles se construyen como texto real. ORBIT y FORMA son exploraciones conceptuales identificadas. El CMS conserva los nueve servicios existentes y la distinción entre borrador y publicación; el prototipo CLIENT conserva su identificación de ejemplo y sus estados, sin presentar F4 como operación disponible.

Esta es la actualización autorizada del mundo inicial al índice editorial B. B es una guía visual editable, según la instrucción del usuario; la fuente implementada determina los valores. La composición concreta de portada permanece en [su contrato](idevera-base/.impeccable/surfaces/resources-js-pages-welcome-tsx.md). La aplicación sigue siendo una web local, con las restricciones de [PRODUCT.md](idevera-base/PRODUCT.md).

**Key Characteristics:**

- Campo oscuro violeta y acento lila sólido.
- Pontano Sans para titulares públicos; Instrument Sans para lectura y tareas.
- Retículas asimétricas, listas abiertas y separación por reglas finas.
- Profundidad de imágenes aprobadas y superficies planas de interfaz.
- Movimiento breve que conserva contenido y acciones disponibles.

## Colors

La paleta combina violeta de acción, lila de lectura y neutros oscuros con un matiz violeta; el frontmatter fija los valores extraídos.

### Primary

- **Violeta de acción** (`primary`, `primary-hover`): fondos de acciones principales y bloque de contacto; texto blanco `primary-text`.
- **Lila legible** (`lilac-link`): enlaces de regreso, publicación y paginación, flechas de servicios, selección y foco público.
- **Lila de énfasis** (`lilac-emphasis`): énfasis tipográfico sólido en el contenido público.
- **Anillo violeta** (`focus-ring`): foco de los componentes base y borde activo de tabs/controles secundarios.

### Secondary

- **Verde de confirmación** (`success`), **ámbar de atención** (`warning`) y **rosa de error** (`error`): estados acompañados por texto; no sustituyen etiquetas ni explicación.

### Neutral

- **Campo violeta negro** (`ground`): fondo público y modo oscuro privado.
- **Plano de trabajo** (`surface`): campos, sidebar y paneles privados.
- **Plano elevado** (`surface-raised`): diálogos y hover secundario.
- **Plano activo** (`surface-active`): selección, hover de filas y próxima acción CLIENT.
- **Texto claro**, **lectura secundaria** y **metadatos claros** (`text`, `text-secondary`, `text-metadata`): títulos, descripciones y categorías.
- **Reglas y controles** (`border`, `border-quiet`, `border-control`, `border-select`, `border-field`): divisores y trazos de controles identificables.

**The Action and Reading Rule.** El violeta saturado se usa como fondo de acción; los enlaces pequeños sobre fondo oscuro usan lila legible.

OWNER mantiene la preferencia clara/oscura de la cuenta mediante las variables de `app.css`. La paleta anterior describe el mundo oscuro de marca. Las variantes claras privadas de éxito y error se conservan en la fuente; cualquier cambio de tema requiere comprobar su combinación concreta.

## Typography

**Display Font:** Pontano Sans, con fallback sans-serif. Variable autoalojada (pesos 400–700, `font-display: swap`), acompañada por su licencia OFL.

**Body Font:** Instrument Sans, con fallbacks ui-sans-serif, system-ui y sans-serif.

**Character:** Pontano aporta una voz pública amplia, con tracking negativo contenido. Instrument sostiene formularios, descripción, navegación, datos y tareas privadas.

### Hierarchy

- **Display:** rol `display`, slogan público; en móvil pasa a `clamp(48px, 15.5vw, 84px)`, altura de línea 1.06 y dos grupos en bloque.
- **Headline:** rol `headline`, encabezados públicos; el encabezado de página móvil usa 38px.
- **Section:** rol `section`, títulos de proceso y estudio. Cabecera de servicios: 4.036vw, peso 700 y altura 1; en móvil 36px/1.1.
- **Task title:** rol `task-title`, títulos OWNER; en móvil 30px. CLIENT usa `clamp(28px, 2.6vw, 40px)` y títulos internos de 24–26px.
- **Body:** rol `body`; lectura larga de hasta 65ch. Servicios usan 16px/1.7 y estudio/proceso 16px/1.8. Detalle conserva sus reglas específicas de párrafo.
- **Label:** rol `label`, acciones y tabs. Estados/metadatos privados de 12–14px; campos editables de 16px. Importes, contadores y paginación conservan números tabulares donde la fuente los establece.

**The Public and Task Scale Rule.** Los titulares públicos llevan Pontano y escala editorial; las tareas privadas usan Instrument y una escala orientada al trabajo.

## Layout

El contenedor público mide `min(1320px, calc(100% - 112px))`. A un máximo de 1100px deja 32px por lado; a un máximo de 767px deja 20px. La portada tiene medidas propias: cabecera/introducción de 91.6%, trabajo/servicios de 94.8%, pareja de trabajo 1.68fr/1fr y gap 1.237vw. Estas medidas pertenecen a esa superficie.

Portada: servicios en dos columnas, proceso en cuatro y estudio en dos. A un máximo de 1099px los textos de casos salen de la imagen y el proceso pasa a dos columnas; a un máximo de 767px trabajo, servicios, proceso y estudio pasan a una. Slogan con salto; dos acciones de apertura en dos columnas; imágenes con proporción 1.15. Portfolio usa 1.45fr/1fr, gaps 44px/24px y cubiertas de altura `clamp(260px, 30vw, 440px)`; en móvil una columna y proporción 1.25. Imagen de detalle: proporción 2 y máximo 620px; en móvil proporción 1.3.

OWNER usa hasta 1180px, márgenes de 24px y 72px de salida inferior; en móvil deja 16px por lado. Editor con formulario y columna lateral de 280px, reducida a 230px a un máximo de 1100px y una columna a un máximo de 767px. Título y slug comparten fila en escritorio. La barra de guardado/publicación es sticky arriba (z-index 20) y se envuelve en móvil; tabs OWNER se envuelven.

CLIENT usa sidebar de 240px y área principal flexible, hasta 1450px, con padding horizontal `clamp(24px, 4vw, 64px)`. En móvil el sidebar pasa a cabecera horizontal y el área principal deja 20px por lado. Resumen CLIENT: 1.1fr/1fr y gap 40px; en móvil una columna y gap 16px. Tabs de proyecto conservan desplazamiento horizontal.

La escala del frontmatter recoge pasos repetidos. Proceso/estudio tienen padding `clamp(72px, 8vw, 130px)`. Contacto pasa de formulario/aside 1.15fr/0.85fr a una columna en móvil. Textos/correos largos permiten corte y las grillas usan `minmax(0, 1fr)`.

## Elevation & Depth

La interfaz editorial es plana: reglas finas, planos tonales y espacio distinguen grupos. Los casos públicos y filas OWNER no reciben sombra decorativa; las imágenes aprobadas contienen luz, volumen y textura. El diálogo se distingue por plano elevado, borde y la superposición del componente base. Los controles shadcn privados conservan su sombra mínima; no se convierte en profundidad general de tarjetas.

**The Material in the Image Rule.** La iluminación y el volumen pertenecen al activo visual aprobado; la interfaz los acompaña con planos y reglas discretas.

## Shapes

Imágenes de trabajo y filas de servicio con esquinas rectas; filas OWNER abiertas sin contenedor individual. Controles públicos con radio `control`, campos públicos `public-field`, estados `status` y paneles privados `panel`. Los componentes base privados siguen los radios derivados de `--radius` en `app.css`. Bordes de un píxel delimitan campos, estados y agrupaciones donde existen.

## Components

### Buttons

Acciones con texto real y flecha SVG Lucide cuando corresponde. Primario violeta, secundario transparente con trazo y variante clara en el bloque violeta de contacto. Mínimo público 48px de alto, salvo apertura fluida con mínimo 44px; componentes base privados con mínimo 44px. La apertura móvil conserva mínimo 48px.

Hover primario cambia al violeta de hover; secundario al plano elevado y borde violeta. Foco público: contorno lila de 2px con offset 5px. Color/borde: 160ms `ease`; transform: 140ms `cubic-bezier(0.23, 1, 0.32, 1)`. Pulsación: escala 0.97; flechas: 2px/-2px solamente con hover y puntero fino. Contacto disabled conserva texto de envío, cursor de espera y opacidad 0.65.

### Chips / States

Etiquetas compactas con texto explícito: publicación/borrador OWNER y estados operacional/financiero CLIENT siguen separados. Publicación verde con plano translúcido; borrador neutral. CLIENT conserva etiquetas de atención, éxito y neutral. Las etiquetas conceptuales de casos comunican procedencia del contenido.

### Cards / Containers

Trabajo público con imagen plana y texto HTML; catálogo con metadata debajo. Servicios con regla superior, título/descripción y flecha. OWNER con padding 22px/8px, divisores de un píxel y hover tonal 160ms `ease-out` con puntero fino. Paneles de edición con borde, plano de trabajo, radio y padding de panel. CLIENT distingue la próxima acción con plano activo y padding 28px, 22px en móvil; el resumen queda abierto.

### Inputs / Fields

Campos públicos sobre plano de trabajo, trazo de campo de un píxel, mínimo 48px, padding de campo y texto 16px. Labels, ayudas y errores asociados; foco lila con offset 5px y caret lila. Error: borde rosa y texto asociado. OWNER conserva variables de tema, textarea 16px/1.75, padding 16px y mínimo 260px. Contacto conserva consentimiento, primer error enfocado, envío pendiente y confirmación persistente de la bandeja local.

### Navigation

Marca propia con wordmark; enlaces públicos y ruta actual lila. En móvil (máximo 767px) aparece «Menú» y desaparece la navegación horizontal: diálogo Radix con título, descripción, enlaces y cierre accesible. Acceso a panel/login según sesión. Footer con enlaces legales publicados y datos reales del CMS. Tabs OWNER con trazo activo de 2px; CLIENT mantiene contexto de ejemplo.

### Visible entry, scroll and response

Slogan visible con entrada mediante `@starting-style`: cada grupo parte de `translateY(12px)` y transiciona transform durante 600ms con `cubic-bezier(0.23, 1, 0.32, 1)`; el último grupo añade 70ms. No usa opacidad inicial cero, loop ni observador. Con `prefers-reduced-motion: reduce` queda en transform none, opacidad 1 y sin transición; también se anulan la escala de pulsación y el desplazamiento de flechas. La regla reducida global elimina animaciones/transiciones públicas y de diálogos. Sin soporte de `@starting-style`, el texto sigue visible.

La portada incorpora movimiento de scroll y puntero por petición del usuario del 13 de octubre de 2026, manteniendo B como guía editable. Las imágenes ORBIT/FORMA cambian de `translateY(4%) scale(1.12)` a `translateY(0) scale(1)` durante su recorrido por el viewport: CSS `animation-timeline: view()` con rango `cover 0% cover 100%`. El zoom y la traslación quedan recortados dentro del marco; títulos, enlaces y distribución permanecen estacionarios. Donde no hay soporte de esa API, IntersectionObserver dispara una sola apertura de zoom de 600ms. El estado predeterminado mantiene las imágenes visibles.

El énfasis de servicios entra desde 20px con opacidad mínima 0.7; los cuatro pasos del proceso desde 28px, sin ocultar su texto y con 70ms de separación; las dos líneas del cierre convergen desde ±24px. Son tres momentos específicos del recorrido, no un revelado aplicado a cada sección. Usan la curva `--ease-out` existente y 600ms; cada observador se retira después de su primera entrada, con umbral 0.12.

Con hover y puntero fino, el encuadre responde al mouse con traslación máxima de ±12px/±8px y escala 1.04→1.075. El indicador circular «Ver caso» mide 80px y sigue el puntero mediante un resorte de masa 1, rigidez 100 y amortiguación 10. Se conservan el cursor nativo y el enlace accesible; el indicador decorativo queda fuera del árbol accesible. El foco de teclado muestra el indicador estático, sin transición. Sin hover/puntero fino no se registra seguimiento; movimiento reducido cancela observadores, animaciones y frames activos. Los frames se detienen en reposo, se cancelan al ocultar la pestaña y se limpian al salir de la portada. No se usan listeners de scroll para conducir transformaciones por frame ni actualizaciones de estado React por frame.

## Do's and Don'ts

### Do:

- **Do** mantener la marca, el slogan exacto y texto de interfaz en español.
- **Do** usar Pontano en titulares públicos e Instrument en lectura y tareas privadas.
- **Do** usar violeta como fondo de acción y lila legible para enlaces pequeños sobre oscuro.
- **Do** conservar texto, categorías y controles en HTML, imágenes con dimensiones/alt y labels conceptuales explícitos.
- **Do** preservar foco, labels, errores asociados y feedback de guardado/publicación cerca de las acciones.
- **Do** mantener disponibles contenido y acciones con movimiento reducido o sin la transición de entrada.

### Don't:

- **Don't** convertir filas editoriales abiertas en una grilla uniforme de tarjetas decorativas.
- **Don't** inventar clientes, resultados, testimonios, servicios o capacidades operativas para llenar una superficie.
- **Don't** reemplazar los materiales aprobados por aproximaciones CSS ni añadir glow a cada panel.
- **Don't** trasladar la escala de portada a formularios, pagos, briefs o listas de trabajo.
- **Don't** usar glyphs de texto como iconos, kickers inventados o movimiento continuo como regla de esta identidad.

No canonizado: clases heredadas de escultura flotante, arte procedural y revelados con opacidad cero siguen en CSS anterior sin definir este mundo ni ser usadas por la portada reconstruida. Los rótulos auxiliares heredados y tamaños de 9–11px que aún existan no se convierten en nueva escala de labels. La revisión independiente y su veredicto permanecen en los artefactos de revisión; este documento registra la fuente y no declara validaciones pendientes.
