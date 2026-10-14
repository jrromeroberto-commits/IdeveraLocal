# F3 · Web pública y contenido administrable

Implementada y comprobada en local el 11 de octubre de 2026. La reconstrucción visual del 12 de octubre sigue la dirección B aprobada, conservando contenido, rutas y restricciones OWNER de F1. La aplicación sigue siendo una web Laravel + React/Inertia; no se ha desplegado. Ver [revisión actual de F2/F3](REVISION_VISUAL_F2_F3.md).

## Lo disponible

| Pantalla | Dirección |
| --- | --- |
| Portada con servicios y casos destacados publicados | `/` |
| Servicios y detalle | `/servicios`, `/servicios/{slug}` |
| Portfolio, filtro por servicio y caso | `/portfolio`, `/portfolio/{slug}` |
| Estudio y proceso | `/about`, `/proceso` |
| Contacto | `/contacto` |
| Documentos provisionales | `/legal/privacidad`, `/legal/terminos`, `/legal/cancelaciones` |
| Páginas adicionales publicadas | `/paginas/{slug}` |
| Entrada desde un servicio | `/iniciar-proyecto?servicio={slug}` |
| Editor OWNER | `/owner/content` |
| Datos públicos del estudio | `/owner/content/settings` |
| Bandeja privada de contacto | `/owner/contact` |

Se inicializaron nueve servicios, dos exploraciones conceptuales (ORBIT y FORMA) y seis páginas institucionales/legales en PostgreSQL. Los conceptos no representan encargos de clientes. Los textos legales están identificados como provisionales; disponer de estas pantallas no acredita cumplimiento legal ni define políticas comerciales definitivas.

Los datos públicos proporcionados por el usuario son John Romero, `jr.romero.berto@gmail.com` y `prolongación lucanas 1244`. Se pueden modificar desde OWNER. No se ha inferido ciudad, jurisdicción o entidad legal.

## Editar y publicar

1. Entrar con OWNER, correo verificado y dos factores activos.
2. Elegir Servicios, Portfolio o Páginas; crear o abrir una entrada.
3. Completar título, dirección, introducción, texto y, si corresponde, imágenes/servicios relacionados. Guardar borrador.
4. Revisar la vista previa del texto y publicar la versión guardada. Publicar, retirar y cambiar datos públicos pueden pedir confirmar la contraseña conforme a F1.
5. Una edición posterior permanece privada hasta volver a publicar. Retirar la publicación conserva el borrador y devuelve 404 en la dirección pública.

El editor controla versiones para rechazar guardados/publicaciones obsoletos; pide conservar la edición antes de recargar cuando hay un conflicto. Repetir la misma publicación no duplica el evento. La web lee una copia de los campos publicados, incluidos dirección, orden y destacado. El historial registra actor, evento y versión; no guarda el texto ni datos personales de consultas en sus metadatos.

Los cuerpos de texto se renderizan como párrafos, sin ejecutar HTML. SEO permite título y descripción propios. La vista previa del editor es textual; para revisar la composición final se abre la entrada publicada.

## Imágenes y privacidad

Se admiten hasta ocho JPG, PNG o WebP de 6 MB y 6000 × 6000 px por entrada, con descripción alternativa. SVG no se admite en el upload. Un caso que deje de ser conceptual requiere una imagen elegida expresamente.

Los originales están en el disco privado `local`, bajo `storage/app/private/content-drafts`. Al publicar se crean copias independientes en `storage/app/content-public`, servidas por `/media/{entry}/{token}`. Este directorio no necesita un enlace público de `storage:link`. El controlador comprueba que el token pertenezca a la publicación vigente; las URLs anteriores dejan de funcionar al republicar/retirar. Un archivo ya descargado por un visitante no puede recuperarse de su dispositivo.

Esta infraestructura solo gestiona medios del CMS. No convierte adjuntos de proyectos en recursos públicos ni sustituye los archivos privados de F4–F7.

## Contacto e inicio de proyecto

El formulario guarda consultas en la bandeja privada de OWNER. Incluye validación en español, aviso de almacenamiento, campo antispam y límite de tres intentos por minuto y treinta por día. No envía correo externo. OWNER puede marcar una consulta como revisada; esa acción no envía una respuesta.

El CTA de servicio conserva la selección publicada en la sesión y la ofrece al entrar en CLIENT. El brief, sus adjuntos y el envío de solicitudes corresponden a F4; esta pantalla explica esa disponibilidad y permite registrarse, acceder o contactar.

## SEO y ejecución local

Las páginas públicas incluyen título, descripción, canonical y Open Graph; usan imagen social cuando hay una imagen publicada. `/sitemap.xml` incluye contenido publicado y excluye borradores/áreas privadas. `/robots.txt` restringe rutas de cuentas y paneles. Las entradas privadas, autenticación, prototipo y página de inicio de proyecto reciben `noindex,nofollow`; estos indicadores no reemplazan autorización.

En la carpeta `idevera-base`, iniciar con `composer dev`. Para una nueva base local: `php artisan migrate` y, opcionalmente, `php artisan idevera:content-demo`. El comando de contenido solo admite entornos local/testing, es idempotente y conserva entradas ya existentes y sus ediciones. No es un instalador de datos comerciales para producción.

`pnpm run build:ssr` genera navegador y SSR. El paquete SSR usa Node; para comprobar el paquete compilado se puede ejecutar `php artisan inertia:start-ssr`. La elección de hosting seguirá pendiente hasta terminar el proyecto local.

## Evidencia y límites

- `composer ci:check`: 83 pruebas y 587 aserciones; formato, lint, TypeScript, Pint y PHPStan aprobados. Las pruebas automatizadas usan la base aislada de tests; migración, datos iniciales y recorrido manual se ejecutaron en PostgreSQL local.
- Compilación de navegador y SSR aprobada. Se verificó HTML inicial con encabezado, canonical y Open Graph; el paquete SSR compilado también renderizó el caso ORBIT con encabezado y metadata.
- Backend: exclusión de CLIENT/STAFF, exigencia de dos factores/confirmación, edición obsoleta, publicación repetida, borradores fuera de listados/filtros/sitemap, archivos privados y URLs revocadas, rechazo de SVG y medios ajenos, límites de contacto y semilla que conserva cambios.
- Navegador OWNER autenticado por el usuario: crear borrador → 404 pública → publicar → editar/guardar conservando la versión pública → republicar → retirar → 404. La entrada «Validación F3 · contenido de prueba» quedó como borrador, sin publicación.
- Consulta «Validación local F3» enviada desde el formulario y marcada como revisada en OWNER. Se conserva como dato de prueba local.
- Portfolio: filtro sin resultados y retorno al listado, menú móvil y navegación, caso conceptual y selección de servicio comprobados. Contacto muestra errores en español y enfoca `contact-name` tras enviar vacío.
- Revisión visual pública en 390 px, inicio en 320 px, caso en 768 px y escritorio habitual de 1265 px; sin desbordamiento de página en esas vistas. CMS observado a 614 px sin desbordamiento. Esto no sustituye la auditoría integral de dispositivos, zoom, rendimiento y accesibilidad de F9.
- No se han añadido dependencias. La reconstrucción B concentra la entrada en el slogan, conserva respuesta de controles y reducción de movimiento. El editor incorpora barra de estado, campos de 16 px, enlaces con contraste mejorado y publicación bloqueada mientras hay cambios sin guardar. La siguiente fase es F4: solicitudes y revisión.

Capturas del navegador local:

- [Servicios en escritorio](evidencias/f3/services-desktop.jpg).
- [Portfolio en escritorio](evidencias/f3/portfolio-desktop.jpg).
- [Portfolio en móvil](evidencias/f3/portfolio-mobile.jpg).
- [Validación de contacto en móvil](evidencias/f3/contact-validation-mobile.jpg).
- [Contenido OWNER](evidencias/f3/owner-content.jpg).
