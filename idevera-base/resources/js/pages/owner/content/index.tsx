import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, ArrowRight, ArrowUpRight, Plus } from 'lucide-react';
import { Button } from '@/components/ui/button';
import type { ContentKind, Pagination } from '@/types/content';

type Entry = {
    id: number;
    kind: ContentKind;
    title: string;
    slug: string;
    is_example: boolean;
    lock_version: number;
    published_at: string | null;
    published_version: number | null;
};
const labels: Record<ContentKind, string> = {
    SERVICE: 'Servicios',
    PORTFOLIO: 'Portfolio',
    PAGE: 'Páginas',
};

export default function ContentIndex({
    entries,
    kind,
}: {
    entries: Pagination<Entry>;
    kind: ContentKind;
}) {
    return (
        <div className="cms-page">
            <Head title="Contenido del estudio" />
            <header className="cms-heading">
                <div>
                    <h1>Contenido público</h1>
                    <p>
                        Guarda borradores, revisa su vista previa y publica
                        cuando estén listos.
                    </p>
                </div>
                <Button asChild>
                    <Link href={`/owner/content/create?kind=${kind}`}>
                        <Plus size={16} />
                        Crear{' '}
                        {kind === 'SERVICE'
                            ? 'servicio'
                            : kind === 'PORTFOLIO'
                              ? 'caso'
                              : 'página'}
                    </Link>
                </Button>
            </header>
            <nav className="cms-tabs" aria-label="Contenido del estudio">
                {Object.entries(labels).map(([value, label]) => (
                    <Link
                        key={value}
                        href={`/owner/content?kind=${value}`}
                        aria-current={kind === value ? 'page' : undefined}
                    >
                        {label}
                    </Link>
                ))}
                <Link href="/owner/content/settings">Datos públicos</Link>
                <Link href="/owner/contact">Mensajes de contacto</Link>
            </nav>
            <div className="cms-list-heading">
                <h2>{labels[kind]}</h2>
                <span>
                    {entries.total}{' '}
                    {entries.total === 1 ? 'entrada' : 'entradas'}
                </span>
            </div>
            {entries.data.length ? (
                <div className="cms-list">
                    {entries.data.map((entry) => (
                        <Link
                            key={entry.id}
                            href={`/owner/content/${entry.id}/edit`}
                            className="cms-entry"
                        >
                            <div>
                                <h3>{entry.title}</h3>
                                <p>
                                    /{entry.slug}{' '}
                                    {entry.is_example &&
                                        '· Contenido de ejemplo'}
                                </p>
                            </div>
                            <span
                                className={`cms-status ${entry.published_at ? 'published' : ''}`}
                            >
                                {entry.published_at
                                    ? entry.published_version ===
                                      entry.lock_version
                                        ? 'Publicado'
                                        : 'Publicado · cambios en borrador'
                                    : 'Borrador'}
                            </span>
                            <ArrowUpRight size={17} />
                        </Link>
                    ))}
                </div>
            ) : (
                <div className="cms-empty">
                    <h2>Este espacio empieza con una idea.</h2>
                    <p>
                        Crea el primer contenido. Se guardará como borrador
                        hasta que lo publiques.
                    </p>
                </div>
            )}
            <nav className="cms-pagination" aria-label="Paginación">
                {entries.prev_page_url && (
                    <Link href={entries.prev_page_url}>
                        <ArrowLeft size={16} aria-hidden="true" /> Anterior
                    </Link>
                )}
                <span>
                    {entries.current_page} / {entries.last_page}
                </span>
                {entries.next_page_url && (
                    <Link href={entries.next_page_url}>
                        Siguiente <ArrowRight size={16} aria-hidden="true" />
                    </Link>
                )}
            </nav>
        </div>
    );
}
