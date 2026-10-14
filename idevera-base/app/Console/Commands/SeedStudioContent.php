<?php

namespace App\Console\Commands;

use App\Actions\Content\PublishContent;
use App\Enums\ContentKind;
use App\Models\ContentEntry;
use App\Models\SiteSetting;
use Illuminate\Console\Command;

class SeedStudioContent extends Command
{
    protected $signature = 'idevera:content-demo';

    protected $description = 'Prepara contenido de ejemplo publicado, exclusivamente en local/testing, sin sobrescribir ediciones.';

    public function handle(PublishContent $publish): int
    {
        if (! app()->environment(['local', 'testing'])) {
            $this->error('Este comando solo funciona en local/testing.');

            return self::FAILURE;
        }
        SiteSetting::query()->firstOrCreate(['key' => 'studio'], ['responsible_name' => config('studio.responsible_name'), 'contact_email' => config('studio.contact_email'), 'contact_address' => config('studio.contact_address'), 'provisional_legal' => true]);
        $services = [
            ['desarrollo-web', 'Desarrollo Web', 'Sitios que expresan tu marca y funcionan en cada pantalla.'],
            ['software-aplicaciones', 'Software & Aplicaciones', 'Herramientas a medida para convertir tus procesos en productos digitales.'],
            ['pwa-web-apps', 'PWA & Web Apps', 'Experiencias web con capacidades de aplicación según tu proyecto.'],
            ['datos-bi', 'Datos & BI', 'Organización, visualización y análisis para entender tus datos.'],
            ['automatizacion-integraciones', 'Automatización & Integraciones', 'Conexiones entre herramientas que reducen el trabajo repetitivo.'],
            ['branding-diseno', 'Branding & Diseño', 'Identidades e interfaces con un lenguaje visual coherente.'],
            ['digital-art-animation', 'Digital Art & Animation', 'Dirección de arte y movimiento para dar forma a una idea.'],
            ['soporte-consultoria', 'Soporte / Consultoría', 'Acompañamiento técnico y creativo para mejorar lo que ya existe.'],
            ['custom-other', 'Custom / Other', 'Un punto de partida para ideas que combinan disciplinas.'],
        ];
        $ids = [];
        foreach ($services as $position => [$slug, $title, $excerpt]) {
            $entry = $this->entry($publish, ContentKind::SERVICE, $slug, $title, $excerpt, $excerpt."\n\nEmpezamos por tus objetivos, el contexto y las referencias. Definimos el alcance antes de construir, y revisamos contigo los entregables de cada etapa.\n\nEl alcance, cronograma y condiciones se acuerdan en una cotización específica para tu proyecto.", ['position' => $position, 'is_example' => false]);
            $ids[] = $entry->id;
        }
        $this->entry($publish, ContentKind::PORTFOLIO, 'orbit', 'ORBIT', 'Una exploración de identidad digital y diseño web.', "El punto de partida\nExplorar una presencia digital editorial que combine tipografía y formas orbitales.\n\nLa dirección\nUna composición amplia, materiales violetas y un recorrido de contenido claro.\n\nEl resultado visual\nUn concepto propio de IDEVERA para estudiar la relación entre marca, interacción y contenido. No representa un encargo de cliente ni resultados comerciales.", ['category' => 'Dirección digital · Diseño web', 'featured' => true, 'art_style' => 'orbit', 'service_ids' => [$ids[0], $ids[5]]]);
        $this->entry($publish, ContentKind::PORTFOLIO, 'forma', 'FORMA', 'Una exploración de identidad visual y dirección de arte.', "El punto de partida\nUn sistema de identidad centrado en la geometría, el espacio y el contraste.\n\nLa dirección\nFormas escultóricas, color cálido y una marca tipográfica protagonista.\n\nEl resultado visual\nUn concepto original preparado para esta web de pruebas. No representa un cliente ni una campaña comercial.", ['category' => 'Identidad visual · Dirección de arte', 'featured' => true, 'position' => 1, 'art_style' => 'forma', 'service_ids' => [$ids[5], $ids[6]]]);
        $pages = [
            ['about', 'Entre lo que imaginas y lo que puede ser.', 'Diseño, desarrollo y arte digital con una visión compartida.', "IDEVERA STUDIO une desarrollo, diseño y arte digital. Un espacio para convertir ideas en experiencias con intención, identidad y una forma de funcionar.\n\nLa colaboración también forma parte del diseño: un lugar común para seguir el trabajo, conversar y revisar cada entrega."],
            ['proceso', 'Un proceso claro. Una visión compartida.', 'Del primer brief a la última revisión.', "01 — Escuchamos tu visión\nReunimos objetivos, contexto y referencias.\n\n02 — Damos forma al plan\nDefinimos alcance, entregables y condiciones en una cotización.\n\n03 — Construimos contigo\nOrganizamos el trabajo y las conversaciones alrededor del proyecto.\n\n04 — Lo hacemos realidad\nRevisamos entregables, recogemos comentarios y conservamos el historial."],
            ['contacto', 'La siguiente idea puede ser la tuya.', 'Cuéntanos qué tienes en mente.', 'Comparte tu idea, contexto y preguntas. El formulario guarda tu mensaje en el espacio de contacto del estudio.'],
            ['legal-privacidad', 'Privacidad', 'Documento provisional para el entorno de pruebas.', "Este texto está en preparación. El responsable y el canal de contacto aparecen debajo.\n\nEl formulario de contacto solicita nombre, correo, asunto y mensaje, y los guarda en la bandeja privada de OWNER para gestionar la consulta.\n\nAntes de una publicación externa se deben completar y validar la identidad legal, jurisdicción, base aplicable, plazos de conservación, derechos y procedimiento de atención de solicitudes."],
            ['legal-terminos', 'Términos de uso', 'Documento provisional para el entorno de pruebas.', 'Esta es una versión local de demostración. Los términos definitivos de uso y contratación se prepararán para el contexto real antes de publicar una operación comercial. Esta página no sustituye una cotización ni establece condiciones comerciales definitivas.'],
            ['legal-cancelaciones', 'Cancelaciones y reembolsos', 'Documento provisional para el entorno de pruebas.', 'Las condiciones comerciales, cancelaciones y reembolsos se definirán y validarán junto con el flujo de cotizaciones y pagos. No se presenta una política comercial definitiva en este prototipo.'],
        ];
        foreach ($pages as [$slug, $title, $excerpt, $body]) {
            $this->entry($publish, ContentKind::PAGE, $slug, $title, $excerpt, $body, ['is_example' => true]);
        }
        $this->info('Contenido de prueba preparado. Los registros existentes y sus ediciones se conservaron.');

        return self::SUCCESS;
    }

    /** @param array<string, mixed> $extra */
    private function entry(PublishContent $publish, ContentKind $kind, string $slug, string $title, string $excerpt, string $body, array $extra): ContentEntry
    {
        $entry = ContentEntry::query()->firstOrCreate(['kind' => $kind, 'slug' => $slug], ['title' => $title, 'excerpt' => $excerpt, 'body' => $body, 'is_example' => true, ...$extra]);
        if ($entry->wasRecentlyCreated) {
            $publish->handle(null, $entry, $entry->lock_version, true);
        }

        return $entry;
    }
}
