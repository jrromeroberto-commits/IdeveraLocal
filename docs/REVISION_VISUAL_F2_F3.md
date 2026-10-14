# Revisión visual de F2 y F3

Iniciada el 11 de octubre de 2026 y cerrada el 12 de octubre de 2026 a petición del usuario. Objetivo: mejorar la composición de la web pública, el prototipo CLIENT y el espacio de contenido OWNER con Impeccable, Taste y las guías de Emil, conservando las funciones ya implementadas.

## Decisiones confirmadas

- Audiencia pública: negocios/emprendedores y personas con proyectos propios, con igual importancia.
- Portada y portfolio: presentar propuestas en imágenes y elegir antes de programar.
- Paneles y formularios: trabajar directamente en código y revisar en navegador.
- Paleta, marca, idioma y slogan vienen de los requisitos y de `DESIGN.md`.

## Diagnóstico inicial

| Antes | Cambio propuesto | Por qué |
| --- | --- | --- |
| Portada con texto y escultura divididos en dos columnas | Comparar composiciones que relacionen el slogan con el trabajo concreto del estudio | La apertura puede expresar más del producto que una combinación de texto y decoración |
| Cabeceras públicas con la misma etiqueta pequeña, título grande y espacio vertical | Jerarquías distintas para catálogo, galería, documento y contacto | Cada página tiene una tarea y un contenido diferentes |
| Diez filtros visibles antes del primer caso de portfolio | Reducir la ocupación del filtro y hacer que el trabajo lidere la galería | En móvil, los filtros desplazan la evidencia visual casi fuera de la primera pantalla |
| Numeración ornamental de servicios/secciones y repetición de etiquetas | Conservar números solo en secuencias y quitar decoración que no aporta contexto | Mejora la lectura y reduce ruido |
| Tracking de titulares hasta `-0.075em` | Límites más legibles y medidas equilibradas | La densidad de letras resta claridad a textos largos y pantallas pequeñas |
| Formularios públicos con texto de 15 px | Campos legibles a 16 px y foco/error asociados | Mejora lectura y evita el zoom automático habitual de inputs pequeños en móvil |
| Publicación en un panel situado después del formulario en móvil | Llevar estado y acciones de guardado/publicación a una posición fácil de encontrar | OWNER necesita distinguir contenido guardado y publicado al editar |
| Movimientos de hover sin condición de puntero fino | Limitar desplazamientos a dispositivos que admiten hover y afinar respuesta al pulsar | Evita estados persistentes o engañosos en pantallas táctiles |
| Panel CLIENT con muchas superficies del mismo peso | Priorizar próxima acción, estado y entregable; hacer secundarios los detalles de contexto | Ayuda a reconocer qué necesita atención |

Este diagnóstico combina lectura de código, capturas guardadas y revisión de la portada actual. No es una auditoría completa ni una puntuación de accesibilidad/rendimiento. La nueva implementación y su evidencia se añadirán tras elegir la propuesta.

## Propuestas de composición

Se generaron tres propuestas de portada con ImageGen integrado, usando una captura actual de IDEVERA como referencia de marca. Están en `idevera-base/.impeccable/mocks/decision/`, con el prompt exacto y su estado de aprobación en los archivos `.png.json`.

- [A · Lienzo cinético](../idevera-base/.impeccable/mocks/decision/lienzo.png): pieza central que une las líneas del slogan.
- [B · Índice editorial](../idevera-base/.impeccable/mocks/decision/indice.png): mensaje amplio y trabajo visible inmediatamente, con proyectos de escala desigual.
- [C · Galería inmersiva](../idevera-base/.impeccable/mocks/decision/galeria.png): ORBIT como escenario de apertura y una interfaz discreta.

La mesa de elección muestra las tres imágenes ampliables. Son propuestas de composición, no capturas de una implementación nueva; sus rótulos menores no añaden servicios ni hechos comerciales al contenido del CMS. El usuario eligió **B · Índice editorial** con «vamos por la B». Aprobó las imágenes independientes ORBIT y FORMA y el plan de montaje mediante la revisión nativa de Impeccable; su recibo confirma los tres elementos y el inventario.

Después indicó: **«Usa B como guía visual editable; no exijo una copia exacta del mockup»**. La autoridad actual es la composición implementada en código, con B como referencia visual. Se archivó el estado de comparación exacta; no se declara que esa comparación haya pasado.

## Reconstrucción implementada

- Portada: slogan ancho, descripción concreta del estudio, dos acciones claras y ORBIT/FORMA con escalas diferentes. Los textos provienen del CMS; los casos siguen identificados como exploraciones conceptuales.
- Tipografía: Pontano Sans variable alojada localmente para titulares públicos; Instrument Sans para cuerpo y controles. Fuente incluida correctamente en los recursos compilados y licencia OFL conservada.
- Portfolio: imágenes antes que filtros, selector nativo de servicio y envío GET, títulos fuera de las imágenes y navegación vigente. Los medios propios tienen prioridad; las imágenes conceptuales se usan únicamente en ejemplos.
- Servicios y documentos: jerarquía simplificada, longitud de lectura limitada y numeración solo cuando representa una secuencia.
- Contacto: mismos campos, orden y consentimiento, inputs de 16 px, formulario antes de la información de contacto en móvil. La consulta sigue guardándose en la bandeja privada local de OWNER.
- Movimiento: entrada breve del slogan, respuesta al pulsar y desplazamiento de flechas solo con puntero compatible. Se respeta `prefers-reduced-motion`; el contenido es visible sin esperar una animación.
- Recursos: originales PNG aprobados conservados y derivados WebP sin pérdida, con dimensiones y píxeles RGB verificados idénticos. Reducción de tamaño aproximada de 33–34 %, con prompts exactos y procedencia en `assets/plates`.

- OWNER: encabezados específicos por tarea, lista de contenido con separadores, barra de guardado con estado de borrador y versión pública, acceso a publicación y campos más legibles. Guardar y publicar conservan acciones distintas.
- CLIENT de ejemplo: próxima acción prioritaria, menos superficies anidadas y mejor jerarquía entre contexto, hitos y entregables. Conserva su identificación como prototipo sin operaciones reales.
- Desarrollo: Vite usa `127.0.0.1` y anuncia HMR con `localhost`, tras comprobar que el navegador integrado no activaba las interacciones con los recursos anunciados como `[::1]`.

## Verificación y evidencia

- `composer ci:check`: 83 pruebas y 587 aserciones, PHPStan, Pint y comprobaciones de frontend aprobadas. No hubo cambios de backend posteriores en esta reconstrucción.
- Tras los últimos cambios de frontend: formato, lint, TypeScript y compilaciones CSR/SSR aprobadas. `git diff --check` sin incidencias.
- Detector mecánico de diseño ejecutado una vez: cero hallazgos.
- Portada comprobada a 1440, 870 y 390 px; portfolio, contacto y proyecto de ejemplo en escritorio y móvil. Se corrigió el desbordamiento del pie móvil y se retiraron revelados que dejaban imágenes invisibles en capturas completas.
- OWNER inspeccionado con sesión real: lista de nueve servicios publicados y editor de servicio en escritorio y a 390 px, sin desbordamiento horizontal. Borrador v1 y versión pública v1 aparecen separados. No se guardaron, publicaron ni retiraron entradas reales durante esta inspección.
- Se comprobó el filtro real por Desarrollo Web: envía GET, conserva la selección y muestra ORBIT. En CLIENT, «Revisar entregable» selecciona Entregables; abrir y cerrar la dirección visual funciona. En OWNER, cambiar temporalmente el título sin guardar muestra «Cambios sin guardar» y deshabilita publicación; restaurarlo devuelve «Borrador guardado · v1». Ninguna de estas comprobaciones publica contenido.
- La automatización sufrió interrupciones y el arranque de PHP llegó a agotar 30 segundos al cargar dependencias. Se recuperó el entorno y se completaron las recapturas. Al finalizar se retiraron los servidores propios duplicados, conservando `composer dev` del usuario: Laravel en 8000 y Vite en 5174; la portada carga sus estilos desde esa instancia y no presenta desbordamiento en el ancho actual de 1280 px. Esto no acredita una comprobación manual completa de todos los flujos ni la auditoría F9.

Capturas actuales en [evidencias/redisenio](evidencias/redisenio): [portada](evidencias/redisenio/desktop-opening.jpg), [portada móvil](evidencias/redisenio/mobile.jpg), [portfolio](evidencias/redisenio/portfolio-desktop.jpg), [contacto](evidencias/redisenio/contacto-desktop.jpg), [proyecto de ejemplo](evidencias/redisenio/project-desktop.jpg), [lista OWNER](evidencias/redisenio/owner-list-desktop.jpg), [editor OWNER](evidencias/redisenio/owner-editor-desktop.jpg) y [editor móvil](evidencias/redisenio/owner-editor-mobile.jpg). Las capturas anteriores se conservan como comparación, no como prueba del resultado actual.

## Cierre de revisión y documentación

La revisión independiente inicial consideró conforme la composición al contrato B editable y pidió tres ajustes: contraste de enlaces OWNER, eliminar un eyebrow en Pagos y sustituir flechas Unicode por Lucide. Se aplicaron juntos; formato, lint, TypeScript y compilaciones CSR/SSR volvieron a pasar. Las dieciséis capturas requeridas se recapturaron y validaron.

El pase de veredicto independiente puntuó los tres ajustes como **resolved**, con **disposition: ship** para ese lote. El contraste lila/fondo es 9,37:1. La paginación condicional se verificó en fuente; no se presenta una captura de múltiples páginas que no existe. El veredicto cubre los ajustes puntuados, sin constituir una nueva auditoría completa de la plataforma. Informes: [revisión inicial](evidencias/redisenio/finish-review.md) y [veredicto](evidencias/redisenio/finish-verdict.md).

El documenter actualizó [DESIGN.md](../DESIGN.md) como autoridad del workspace y [design.json](../idevera-base/.impeccable/design.json) con metadatos y previews. La huella SHA-256 coincide con el documento; las reglas se extrajeron de la fuente actual. El helper canónico no estaba disponible y se verificó la estructura directamente; no se declara que sus previews hayan pasado una revisión visual independiente. Evidencia de extracción en [documentation.md](evidencias/redisenio/documentation.md).

La reconstrucción no modifica las reglas F1/F3 ni conecta el prototipo CLIENT a operaciones reales. No se creó un commit, no se hizo push y no se desplegó. La siguiente fase del plan es F4: solicitudes y revisión.

## Límites de la reconstrucción

Conservar rutas y slugs públicos, nombres/orden de campos de contacto, datos del CMS, textos legales y reglas F1/F3. No cambiar solicitudes, pagos ni proyectos futuros. Los ejemplos seguirán identificados; no se añadirán clientes, resultados o testimonios ficticios como pruebas del estudio.
