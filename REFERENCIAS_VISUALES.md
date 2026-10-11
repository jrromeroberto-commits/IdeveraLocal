# IDEVERA STUDIO — investigación y propuesta visual

Fecha de revisión: 10 de octubre de 2026.

## Alcance

Revisión de las cinco fuentes indicadas por el cliente mediante el navegador conectado. En Awwwards se exploró el catálogo de agencias y se seleccionaron seis sitios; se abrieron sus páginas originales y se observaron composiciones, desplazamiento y algunas interacciones. Es una selección editorial, no una revisión exhaustiva del catálogo ni una auditoría de rendimiento o accesibilidad.

La propuesta toma como restricciones el documento de requisitos: web convencional, identidad premium, fondos oscuros, índigo/violeta, alto contraste, espacios amplios, movimiento fluido y coherencia entre el sitio público y las áreas CLIENT, STAFF y OWNER. Las recomendaciones siguientes son propuestas para el diseño, no decisiones ya aprobadas ni cambios funcionales.

## Awwwards: referencias seleccionadas

Catálogo consultado: [Design Agencies](https://www.awwwards.com/websites/design-agencies/).

| Sitio original | Ficha en Awwwards | Observación en navegador | Aplicación propuesta a Idevera |
| --- | --- | --- | --- |
| [Metic](https://metic.studio/en) | [Ficha](https://www.awwwards.com/sites/metic-take-a-closer-look) | Hero asimétrico, gran titular, dos CTA claros y un motivo visual recurrente de lupa. La vista inicial es clara; el menú abierto es oscuro y usa enlaces de gran tamaño. | Jerarquía del hero, claridad de «Iniciar proyecto» y composición precisa. Crear un motivo propio para Idevera. |
| [TWKS](https://twks.ch/en) | [Ficha](https://www.awwwards.com/sites/twks-1) | Apertura dominada por trabajo visual y bloques editoriales con tipografía enorme, márgenes amplios y divisores finos. | Ritmo entre contenido y proyectos, escala tipográfica y estructura de presentación del estudio. Adaptar a la paleta oscura requerida. |
| [Preymaker](https://preymaker.com/) | [Ficha](https://www.awwwards.com/sites/preymaker) | Portada atmosférica con textura monocroma a pantalla completa y navegación mínima. El mensaje aparece sobre la imagen al avanzar. | Dirección cinematográfica del portfolio: imágenes de calidad que muestren el trabajo y texto legible por encima. |
| [Studio Mirage](https://studiomirage.io/) | [Ficha](https://www.awwwards.com/sites/studio-mirage) | Portal escultórico oscuro en el hero y recorrido espacial con imágenes de proyectos. | Una pieza abstracta distintiva en la primera pantalla, con luz y movimiento suaves. Mantener el resto del recorrido comprensible. |
| [Wonder Vision](https://www.wonder-vision.com/) | [Ficha](https://www.awwwards.com/sites/wonder-vision-3) | Gran titular, mucho espacio y bloque visual panorámico con acceso al showreel. | Dar escala a los proyectos y separar bien explicación, imagen y llamada a la acción. |
| [Onda Studio](https://www.ondastudio.co/) | [Ficha](https://www.awwwards.com/sites/onda-studio) | Trabajo visual a gran escala, navegación flotante compacta, secciones negras con amplio espacio y contraste de títulos. | Navegación pública contenida, ritmo de secciones y servicios con jerarquía clara. Usar la identidad índigo/violeta de Idevera. |

**Selección principal:** Metic para composición y conversión; Preymaker para presentación del trabajo; Mirage para una pieza visual de identidad. TWKS, Wonder Vision y Onda complementan tipografía, espacio y navegación.

Estas referencias sirven para estudiar relaciones entre elementos y movimientos. Logotipos, textos, proyectos, imágenes y recursos de marca deben ser propios de Idevera. Sus servicios, páginas y funciones no se incorporan al alcance por aparecer en las referencias.

## Bibliotecas de movimiento

### Vengeance UI

Se revisaron el catálogo y tres demos:

- [Stagger Text](https://www.vengenceui.com/components/stagger-text): se accionó la repetición de la animación y se observó la entrada progresiva del texto. Es la opción más útil para revelar el slogan del hero.
- [Light Lines](https://www.vengenceui.com/components/light-lines): líneas luminosas sobre un fondo azul intenso. Si se adapta, conviene reducir brillo, cantidad y protagonismo; su apariencia original domina demasiado la composición prevista.
- [Aurora Hero](https://www.vengenceui.com/components/aurora-hero): textura de luz y vidrio acanalado con varios colores. Puede orientar un material del hero, limitado a la paleta de Idevera. Se revisó su apariencia; no se verificaron todos sus estados interactivos.

### Skiper UI

Se revisaron la portada, el catálogo y la demo [Image Reveal](https://skiper-ui.com/v1/skiper71). Al desplazarse, la imagen pasa de una aparición parcial a una pieza visible; la página describe un revelado mediante recorte y cambios de luminosidad.

Aplicación propuesta: entrada breve de imágenes del portfolio, preservando nombre, categoría y acceso al proyecto. La demo marca el acceso al código como Pro; ver una demostración no equivale a disponer de su código. No se compró ni instaló ningún componente.

### Animmaster

Se inspeccionaron la [portada y el catálogo](https://animmasterlib.dev/), incluidos sus grupos de texto, hero, scroll, menús y transiciones. Varios reproductores indicaron que el contenido multimedia no se podía reproducir. El enlace a Navigation Menus tampoco cargó correctamente.

Es una fuente secundaria pendiente de validación visual de sus demos. No se atribuye a sus animaciones una calidad o comportamiento que no se pudo observar.

## getdesign.md: consistencia entre pantallas

Se revisaron el [catálogo](https://getdesign.md/), la [guía visible de Linear](https://getdesign.md/linear.app/design-md) y la [vista de Clay](https://getdesign.md/clay/design-md).

Linear aporta un ejemplo útil de organización: niveles de superficies oscuras, bordes finos, acento cromático contenido y reglas compartidas para texto, botones y campos. Es una ficha de análisis independiente; no se inspeccionó una sesión privada del producto Linear. Esta referencia orienta la estructura visual de los paneles, adaptada a las necesidades de Idevera.

La ficha de Clay presenta una discrepancia: su resumen habla de agencia creativa, mientras que la vista de ejemplo contiene un producto de datos/CRM. No se utiliza como referencia principal.

El resultado útil de esta fuente es el formato de una guía propia: documentar colores, tipografía, espacios, radios, estados, componentes, responsive y movimiento de Idevera en un futuro DESIGN.md. No se ejecutaron los comandos que muestra el sitio.

## Dirección propuesta

**Estudio tecnológico y creativo, oscuro, editorial y cinematográfico.**

1. **Hero:** slogan «From vision to reality.» con gran presencia, una breve explicación, CTA principal «Iniciar proyecto» y acceso secundario al portfolio. Una sola pieza abstracta con luz índigo/violeta concentra la identidad visual.
2. **Portfolio:** imágenes amplias, composición con variaciones controladas y metadatos claros. El movimiento acompaña la entrada de las imágenes y la interacción con cada proyecto.
3. **Servicios:** titulares con buena escala, descripciones legibles y separadores sutiles. La organización responde a los servicios definidos en los requisitos.
4. **Navegación pública:** pocos elementos visibles, CTA reconocible y menú con transición breve. El acceso a las áreas privadas debe ser fácil de encontrar.
5. **Áreas privadas:** conservar paleta, familias tipográficas, botones y lenguaje de estados. Priorizar lectura y trabajo con proyectos, cotizaciones, entregables, chat y pagos. El detalle visual debe mejorar la comprensión de esos flujos.

## Movimiento propuesto para prototipar

Los siguientes valores son un punto de partida propio, no tiempos medidos en los sitios de referencia.

| Elemento | Comportamiento | Duración inicial |
| --- | --- | --- |
| Slogan del hero | Revelado por líneas o grupos de palabras, una vez al entrar | 600–900 ms en total |
| Secciones | Opacidad y desplazamiento corto al entrar en pantalla | 400–600 ms |
| Imágenes del portfolio | Recorte breve con imagen ya disponible | 500–700 ms |
| Botones y enlaces | Cambio de color, borde o posición pequeña del icono | 150–220 ms |
| Menú, modales y drawers | Transición de apertura/cierre clara | 180–300 ms |
| Fondo del hero | Movimiento lento de luz o forma, de bajo contraste | Ajustar en el prototipo |

La versión móvil debe mantener el mensaje y el CTA antes que el recurso decorativo, ofrecer interacciones táctiles y limitar el coste visual. Con prefers-reduced-motion, el contenido debe quedar disponible con transiciones mínimas y sin movimiento continuo. Las acciones importantes de los paneles necesitan respuesta inmediata y estados comprensibles.

## Límites y comprobaciones pendientes

- Revisión de escritorio a 1280 × 720. Se solicitó un tamaño de móvil de 390 × 844, pero el navegador siguió informando 1280 × 720; no se da por validada la adaptación móvil. Se restableció la configuración temporal.
- Se observaron estados y desplazamientos, pero no se midieron FPS, rendimiento de carga, contraste ni navegación completa por teclado.
- Algunas demos multimedia de Animmaster y otros sitios no pudieron reproducirse. Las observaciones se limitan a lo visible.
- No se compraron recursos, descargaron plantillas ni seleccionaron dependencias técnicas a partir de este estudio.
- Próximo entregable de diseño: propuesta visual de la home y una pantalla representativa del área de cliente, con la misma identidad, seguida de validación responsive y de movimiento.
