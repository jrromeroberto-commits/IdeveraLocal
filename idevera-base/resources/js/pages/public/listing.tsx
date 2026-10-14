import { Link } from '@inertiajs/react';
import { ArrowLeft, ArrowRight, ArrowUpRight } from 'lucide-react';
import { ContentCard } from '@/components/studio/content-card';
import { Seo } from '@/components/studio/public-frame';
import type { ContentKind, Pagination, PublicEntry } from '@/types/content';

export default function Listing({
    kind,
    title,
    intro,
    entries,
    filters,
    selected,
}: {
    kind: ContentKind;
    title: string;
    intro: string;
    entries: Pagination<PublicEntry>;
    filters: PublicEntry[];
    selected: string | null;
}) {
    return (
        <main
            id="contenido"
            className={`studio-container public-page public-catalog ${kind === 'PORTFOLIO' ? 'portfolio-catalog' : 'services-catalog'}`}
        >
            <Seo title={title} description={intro} />
            <div className="public-page-heading">
                <h1>{title}</h1>
                <p>{intro}</p>
            </div>
            {kind === 'PORTFOLIO' && (
                <form
                    className="portfolio-filter"
                    action="/portfolio"
                    method="get"
                >
                    <label htmlFor="portfolio-service">Servicio</label>
                    <select
                        id="portfolio-service"
                        name="servicio"
                        defaultValue={selected ?? ''}
                        key={selected}
                    >
                        <option value="">Todo el trabajo</option>
                        {filters.map((service) => (
                            <option key={service.id} value={service.slug}>
                                {service.title}
                            </option>
                        ))}
                    </select>
                    <button type="submit" className="studio-button secondary">
                        Filtrar
                    </button>
                    {selected && (
                        <Link className="text-link" href="/portfolio">
                            Ver todo
                        </Link>
                    )}
                </form>
            )}
            {entries.data.length ? (
                kind === 'PORTFOLIO' ? (
                    <div className="work-grid public-work-grid">
                        {entries.data.map((entry) => (
                            <div key={entry.id}>
                                <ContentCard entry={entry} />
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="service-catalog">
                        {entries.data.map((entry) => (
                            <div key={entry.id}>
                                <Link className="catalog-row" href={entry.url}>
                                    <div>
                                        <h2>{entry.title}</h2>
                                        <p>{entry.excerpt}</p>
                                    </div>
                                    <ArrowUpRight />
                                </Link>
                            </div>
                        ))}
                    </div>
                )
            ) : (
                <div className="public-empty">
                    <h2>La siguiente idea puede ser la tuya.</h2>
                    <p>
                        {selected
                            ? 'Todavía no hay casos publicados en esta selección.'
                            : 'Explora una idea con el estudio mientras este espacio toma forma.'}
                    </p>
                    <Link href="/contacto" className="studio-button primary">
                        Hablemos <ArrowUpRight size={18} />
                    </Link>
                </div>
            )}
            {entries.last_page > 1 && (
                <nav className="public-pagination" aria-label="Paginación">
                    {entries.prev_page_url && (
                        <Link href={entries.prev_page_url}>
                            <ArrowLeft size={16} aria-hidden="true" /> Anterior
                        </Link>
                    )}
                    <span>
                        Página {entries.current_page} de {entries.last_page}
                    </span>
                    {entries.next_page_url && (
                        <Link href={entries.next_page_url}>
                            Siguiente{' '}
                            <ArrowRight size={16} aria-hidden="true" />
                        </Link>
                    )}
                </nav>
            )}
        </main>
    );
}
