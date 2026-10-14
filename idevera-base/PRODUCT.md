# IDEVERA STUDIO

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

La web pública se dirige con la misma importancia a negocios/emprendedores y a personas con proyectos creativos o tecnológicos propios, confirmado por el usuario el 11 de octubre de 2026. CLIENT reúne su contexto y revisa su proyecto; STAFF colabora en trabajo asignado; OWNER administra cuentas, contenido y decisiones del estudio.

## Product Purpose

Presentar los servicios y el trabajo de IDEVERA y ofrecer un recorrido desde la idea hasta solicitudes, cotizaciones, trabajo compartido, entregables y pagos. El desarrollo se valida en local con datos de prueba.

## Operating Context

Laravel 13, React, TypeScript, Inertia, Tailwind 4, pnpm y PostgreSQL ya están instalados. F1 implementa roles y restricciones. F2 es la base visual y un prototipo CLIENT. F3 publica servicios, portfolio y páginas desde OWNER y recibe contacto en una bandeja privada local. Las solicitudes reales del brief comienzan en F4.

## Capabilities and Constraints

- Es una página web, no una PWA; ofrecer PWA como servicio del estudio no cambia el tipo de esta plataforma.
- Desarrollo y validación locales hasta completar el proyecto. Sin dominio comprado ni despliegue en esta fase.
- Correo local de desarrollo; el formulario de contacto no envía mensajes externos.
- PayPal Sandbox previsto en F8. Culqi real y pagos Live no están activados.
- Mantener autenticación, dos factores, permisos por rol y separación entre borradores y publicación.
- Los adjuntos privados de proyectos no son recursos de portfolio. Los medios del CMS requieren selección y publicación explícitas.
- Requisitos y mapa de fases: [plan](../docs/PLAN_DE_IMPLEMENTACION.md), [matriz](../docs/MATRIZ_DE_REQUISITOS.md), [dominio](../docs/ARQUITECTURA_Y_DOMINIO.md).

## Brand Commitments

IDEVERA STUDIO; slogan «From vision to reality.». Interfaz en español. El documento de requisitos fija una identidad oscura, con índigo y violeta contenidos. Logo y sistema de componentes propios ya presentes. Las referencias aportadas por el usuario incluyen Awwwards, Vengeance UI, Skiper UI, Animmaster y getdesign.md. La autoridad visual existente está en [DESIGN.md](../DESIGN.md), en la raíz del espacio de trabajo.

## Evidence on Hand

- Servicios y páginas editables en PostgreSQL local.
- ORBIT y FORMA son exploraciones conceptuales identificadas, no encargos de clientes.
- Capturas de la versión anterior: `../docs/evidencias/f2/` y `../docs/evidencias/f3/`.
- Contacto y responsable públicos aportados por el usuario; no se ha inferido jurisdicción.
- No hay métricas comerciales, clientes, testimonios o resultados verificados para inventar como prueba social.
- Las páginas legales son provisionales; su existencia no acredita revisión legal.

## Product Principles

- Mostrar capacidades y contenido concreto antes que decoración repetitiva.
- Explicar la siguiente acción con lenguaje claro y conservar el contexto del cliente.
- Separar la presentación pública del trabajo privado y de la operación administrativa.
- Mantener el backend como autoridad para permisos, estados y publicación.
- Identificar ejemplos y límites del entorno de pruebas sin presentar operaciones futuras como disponibles.

## Accessibility & Inclusion

Responsive, teclado, foco visible, contraste legible, texto alternativo y movimiento reducido según los requisitos existentes. Los controles esenciales no dependen de hover ni de animación.
