# Movimiento de la portada

Implementación del 13 de octubre de 2026, a petición del usuario antes de comenzar F4. Conserva la composición B, datos del CMS, imágenes aprobadas, rutas y reglas privadas.

## Referencias y dirección

- [Studio Mirage](https://studiomirage.io/): se revisó su apertura y el cambio de escena entre trabajos en el navegador. Se toma la idea de presentar el trabajo mediante un recorrido visual.
- [Concrete](https://www.concretemanagement.ch/fr): se revisaron los dos paneles de apertura y el mayor protagonismo del panel bajo el puntero. IDEVERA aplica esa respuesta dentro de sus imágenes, conservando su retícula editorial.

El foco de movimiento es el trabajo ORBIT/FORMA. Servicios subraya las palabras centrales; el proceso aparece como secuencia de cuatro pasos; las dos líneas de contacto se encuentran al llegar al cierre. Los formularios y paneles privados mantienen su comportamiento.

## Implementación

| Elemento | Movimiento | Condición |
| --- | --- | --- |
| Imágenes de casos | Zoom 1.12→1 y traslación vertical 4%→0 ligados al recorrido por el viewport | CSS view timeline; apertura de 600ms una vez como fallback |
| Palabras destacadas de servicios | 20px→0, opacidad 0.7→1, separación 70ms | Primera entrada visible |
| Cuatro pasos del proceso | 28px→0, separación 70ms, texto visible | Primera entrada de la secuencia |
| Cierre de contacto | Líneas desde −24px/+24px hacia su posición final | Primera entrada visible |
| Imagen bajo el mouse | Pan ±12px/±8px, escala 1.04→1.075 | Hover y puntero fino |
| Indicador «Ver caso» | Seguimiento con resorte 100/10/1; fade 140ms | Puntero fino; estático e inmediato con teclado |

El encuadre de scroll y el pan del mouse trabajan en capas separadas para no sobrescribir sus transformaciones. La curva de entrada reutiliza `--ease-out`. Las entradas duran 600ms; el resorte conserva su velocidad al cambiar de destino. El contenido es visible sin JavaScript. Los efectos no cambian dimensiones de layout ni escriben variables CSS heredadas por frame.

La API de timeline tiene disponibilidad limitada: [animation-timeline](https://developer.mozilla.org/en-US/docs/Web/CSS/Reference/Properties/animation-timeline) y [animation-range](https://developer.mozilla.org/en-US/docs/Web/CSS/Reference/Properties/animation-range). Se detecta soporte y se conserva el fallback; no se exige esa API para acceder a contenido o enlaces.

## Accesibilidad y ciclo de vida

- Los enlaces conservan nombre accesible, foco y navegación por teclado. El marcador es decorativo, sin eventos y con `aria-hidden`.
- Punteros táctiles no activan seguimiento; la versión responsive presenta texto fuera de las imágenes.
- Movimiento reducido evita scroll espacial y seguimiento, cancela animaciones activas y mantiene visibles contenido, estados y acciones. La preferencia se escucha también si cambia durante la visita.
- Los observadores se desconectan tras la entrada; los frames paran al asentarse, se cancelan con la pestaña oculta y se eliminan junto a los listeners al desmontar.

Fuente: `idevera-base/resources/js/hooks/use-editorial-motion.ts`, `resources/js/pages/welcome.tsx` y `resources/css/editorial.css`. El sistema actualizado está en [DESIGN.md](../DESIGN.md).

## Verificación

Formato, lint, TypeScript y compilaciones CSR/SSR aprobadas. Comprobación en navegador: hover real sobre ORBIT, retorno a reposo al salir, transformación de encuadre al desplazarse y navegación a ORBIT mediante Tab/Enter. El foco muestra el indicador con transición de 0s. Se confirmó ausencia de desbordamiento en viewport de 390 y 870px durante la primera inspección.

Confirmación final en 1440, 870 y 390px, más el ancho habitual de 1280px: imágenes y textos visibles y sin desbordamiento de página. La matriz del encuadre pasó de escala 1.06854 en la apertura a identidad tras avanzar por la página; al salir del caso no quedaba ningún marcador de puntero activo. Las capturas se abrieron y comprobaron después de la compilación final: [hover](evidencias/movimiento/hover-desktop.jpg), [escritorio](evidencias/movimiento/desktop.jpg), [móvil](evidencias/movimiento/mobile.jpg), [ancho intermedio](evidencias/movimiento/intermediate.jpg) y [foco de teclado](evidencias/movimiento/keyboard.jpg).

La reducción de movimiento y la detección táctil se revisan en fuente; cambiar solo el tamaño del navegador no emula un dispositivo táctil. No se declara un benchmark de FPS ni una prueba en teléfono físico. La huella del sidecar de diseño coincide con DESIGN.md. No cambian los archivos de las imágenes aprobadas ni las reglas de backend.
