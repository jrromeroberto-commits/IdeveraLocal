# Documentación del sistema implementado

Estado: documentación de fuente completa; el cierre visual depende de la revisión independiente y su veredicto. Este registro no declara ship.

## Autoridad y cambio autorizado

Se actualizó `../../DESIGN.md`, autoridad existente del workspace. La propuesta inicial quedó supersedida por el mundo aprobado «Índice editorial» B, siguiendo la instrucción «Usa B como guía visual editable; no exijo una copia exacta del mockup». Se conservan marca, slogan, español, audiencias equivalentes, nueve servicios CMS, ejemplos identificados y separación de estados/publicación. PRODUCT.md, requisitos, fases y código no se editaron en este pase.

Definición aplicada: `.agents/skills/impeccable/agents/impeccable_documenter.toml`, con `.agents/skills/impeccable/reference/document.md`. El acuerdo de actualización ya estaba autorizado; no se reabrió una elección de mundo.

## Evidencia muestreada

- `resources/css/app.css`: familia de lectura, variables claras/oscuras, radios derivados, integración de hojas.
- `resources/css/editorial.css`: Pontano autoalojada, retícula B, colores, escalas, breakpoints 1099/767px, @starting-style, pulsación, hover fino y reduced motion.
- `resources/css/public-pages.css`: catálogo, títulos públicos, campos, lectura y responsive.
- `resources/css/workspace.css`: OWNER, barra sticky, listas, editor, escala CLIENT, enlaces lila y responsive.
- `resources/css/content.css` y `studio.css`: fuente base de controles, paneles, estados, container, sidebar y breakpoints 1100/767px; las reglas anteriores se interpretaron con el orden de importación y overrides posteriores.
- `resources/js/pages/welcome.tsx`, `public/contact.tsx`, `components/studio/{public-frame,content-card}.tsx` y `components/ui/button.tsx`: composición real, contenido HTML/CMS, ejemplos, navegación Radix, estados y componentes base.
- `PRODUCT.md`, contrato de portada y `review/finish-review.md`: restricciones de producto, autorización code-led, dirección y correcciones requeridas. El review leído aún tenía disposition fix; no se asumió su aprobación posterior.

## Artefactos y validación

`../../DESIGN.md` lleva frontmatter de tokens y las ocho secciones canónicas. `.impeccable/design.json` usa schemaVersion 2: metadatos, movimiento, breakpoints, ocho componentes con HTML/CSS autocontenido, clases ds-, SVG inline y foco/hover cuando corresponde. Los primitivos permanecen en el frontmatter; no se añadió un bloque tokens al sidecar.

El ejecutable instalado responde `Unknown command: canonical-design-sidecar` al pedir su help. Se generó el sidecar directamente según document.md y se verificó con Node mediante lectura de los archivos reales: estructura YAML restringida a scalars/objetos, grupos admitidos, 21 colores hex, seis roles tipográficos, ocho componentes de preview, ocho secciones en orden, referencias válidas, metadatos con claves coincidentes, ocho pasos por cada una de las 21 rampas, clases ds-, foco en previews interactivos y narrativa literal. Resultado: pass. Esta comprobación estructural no es una ejecución del linter Stitch ni una revisión visual de los previews.

La huella `extensions.sourceFingerprint` enlaza el sidecar con los bytes del DESIGN.md de autoridad mediante SHA-256:

`a4d9c8d47b3d3e3a3ddef310860adda1a6bf731ae03bcd51ce7718b6228a0cdb`

Las rampas OKLCH son previews sintetizados de luminosidad 0.15–0.95, identificados como tales; no son nuevos tokens de producción. No se inventó vocabulario de sombras: la lista de extensiones está vacía para el mundo editorial, mientras el texto conserva la excepción de sombras mínimas de los controles base privados.

## Drift no canonizado

El CSS anterior todavía contiene escultura flotante, arte procedural y reveal con opacidad cero. Esas clases no definen la portada reconstruida ni su motion; tampoco se convierten en reglas del nuevo mundo. Los rótulos auxiliares y medidas heredadas de 9–11px no forman la nueva escala de labels. La documentación no los repara, porque el write boundary de este pase excluye código. Los tokens iniciales de tipo, forma y motion se actualizaron por la reconstrucción autorizada, conservando las restricciones durables del producto.

Movimiento documentado desde la fuente: transform 12px/600ms, easing cubic-bezier(0.23, 1, 0.32, 1), stagger final 70ms, contenido visible; reduced motion anula entrada/pulsación/flechas y las transiciones públicas. No se declara reproducción temporal comprobada por este pase.
