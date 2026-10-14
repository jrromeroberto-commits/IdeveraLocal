disposition: fix

No se seleccionó QUALITY BAR independiente. Fuente muestreada: portada, catálogo, contacto, editor OWNER, prototipo CLIENT, componentes de presentación y CSS; detalle/inicio/error/settings/messages no se leyeron completos. DESIGN.md se actualizará por el documenter para este mundo nuevo.

## persistence

Pass: PRODUCT.md existe y describe web local, F2/F3, audiencias equivalentes, permisos, borrador/publicación y límites de CLIENT. El contrato identifica candidato 3 y seed `2cd13d51`, corroborado por BUILD_PATH_DECISION.md. La instrucción literal «Usa B como guía visual editable; no exijo una copia exacta del mockup» autoriza code-led; no corresponden gates ni diff obligatorios de la vía comp-led archivada. B vive en `mocks/decision/` y se usa como referencia de crítica. FINISH sigue requiriendo documentación y procedencia antes de cerrar la entrega.

Las 14 capturas requeridas se abrieron: desktop, desktop-opening, mobile, user-870, portfolio/contacto desktop, project desktop/user/mobile, OWNER lista/editor desktop/editor mobile, y portfolio/contacto mobile en docs/evidencias/redisenio. Todas muestran el contenido y tamaño esperados, con inicio visible cuando son páginas completas y sin regiones negras o vacías inválidas. Los anchos de imagen 1424/855/375 son compatibles con viewport 1440/870/390 y su scrollbar.

## fidelity

Faithful al contrato code-led, con dos incumplimientos del floor y uno de contraste consignados abajo. No hay matriz de reproducción exacta ni obligaciones de activos derivadas del mockup B.

TYPE: la voz display Pontano Sans está autoalojada, tiene escala editorial, énfasis lila sólido y tracking contenido en los titulares principales muestreados; mantiene el slogan inglés y la lectura española del resto. MATERIAL: ORBIT y FORMA son raster visibles y dominantes, con piedra, iluminación y volumen reales dentro del activo; no se sustituyen por simulaciones CSS. GROUND: `#09090f` cumple la superficie casi negra teñida de violeta descrita en OWN-WORLD, sin deriva hacia azul pizarra.

THESIS se cumple: el trabajo aparece inmediatamente después del slogan. OWN-WORLD se cumple en el campo oscuro, reglas discretas y jerarquía tipográfica; la legibilidad de dos enlaces OWNER necesita corrección. STORY se cumple mediante servicio, caso, inicio y contacto reconocibles. FIRST VIEWPORT se cumple en desktop: marca/navegación, slogan ancho, descripción izquierda, acciones derecha, ORBIT dominante/FORMA menor y encabezado de servicios visible; mobile reorganiza imágenes y textos sin superposición ni overflow de página. FORM mantiene el índice editorial y contenido real del CMS, con títulos y controles HTML. El primer viewport se recuerda por el slogan y la pareja material ORBIT/FORMA.

B proponía una relación más compacta entre letras y trabajo; el render conserva esa ambición y no necesita una reparación adicional de composición. OWNER adopta escala de tarea y listas, con separación visible entre borrador y publicación. CLIENT mantiene etiquetas de ejemplo, estados operacionales/financieros separados y tabs desplazables en móvil; no se confunde con F4 conectado. Contacto conserva labels, consentimiento, error asociado, foco al primer error, estado de envío y aviso de bandeja local. No aparecen métricas, testimonios ni encargos comerciales inventados. El motion muestreado parte de contenido visible, concentra la entrada en el slogan y respeta movimiento reducido; no se evaluó su reproducción temporal con navegador. El detector entregado contiene cero hallazgos y no se ejecutó otro.

## ceiling

Sin card seleccionado, no hay techo externo verificable. El mundo editorial emplea escala, asimetría, reglas y contraste de materiales; no exige ornamentación o profundidad adicionales. La densidad operativa del espacio privado conserva el mismo idioma visual sin trasladar el tamaño de marketing.

## material_fixes

1. Contrast/floor: cambiar el color de texto de `.cms-back` (`resources/css/content.css:383`) y `.cms-publication-link` (`resources/css/workspace.css`) a un lila legible como `#b4a7ff`; `#6d4aff` sobre `#09090f` mide 3.85:1 y los textos de 13/14px requieren 4.5:1. Conservar el violeta de fondo de los botones.
2. Floor: eliminar el eyebrow «ESTADO FINANCIERO INDEPENDIENTE» situado antes de «Pagos del proyecto» (`resources/js/pages/design/project.tsx:470`); integrar su información en texto normal después del título si hace falta explicar la independencia del estado financiero.
3. Floor: sustituir flechas Unicode usadas como iconos de regreso y paginación en `public/listing.tsx` y `owner/content/{index,edit,settings,messages}.tsx` por `ArrowLeft`/`ArrowRight` de Lucide con el mismo tratamiento que la iconografía existente, ocultas al árbol accesible cuando acompañan la etiqueta.

## keep

Mantener el slogan ancho, ORBIT dominante/FORMA menor, materiales raster aprobados, fondo violeta casi negro, índice de servicios, labels conceptuales y separación explícita entre borrador/publicación y ejemplo/operación real.
