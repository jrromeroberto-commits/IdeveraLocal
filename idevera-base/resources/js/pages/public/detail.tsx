import { Link, usePage } from '@inertiajs/react';
import { ArrowLeft, ArrowUpRight } from 'lucide-react';
import { ConceptArtwork } from '@/components/studio/artwork';
import { Seo } from '@/components/studio/public-frame';
import type { PublicEntry, SiteData } from '@/types/content';

export default function Detail({
    entry,
    relatedServices,
    seo,
}: {
    entry: PublicEntry;
    relatedServices: PublicEntry[];
    seo: { title: string; description: string; image: string | null };
}) {
    const { site } = usePage<{ site: SiteData }>().props;
    const legal = entry.slug.startsWith('legal-');
    const provisional = entry.is_example || (legal && site.provisional_legal);
    return (
        <main id="contenido" className="studio-container public-page">
            <Seo {...seo} />
            <Link
                className="text-link"
                href={
                    entry.kind === 'SERVICE'
                        ? '/servicios'
                        : entry.kind === 'PORTFOLIO'
                          ? '/portfolio'
                          : '/'
                }
            >
                <ArrowLeft size={17} />
                {entry.kind === 'SERVICE'
                    ? 'Servicios'
                    : entry.kind === 'PORTFOLIO'
                      ? 'Portfolio'
                      : 'El estudio'}
            </Link>
            <header className="public-page-heading">
                <h1>{entry.title}</h1>
                <p>{entry.excerpt}</p>
                {entry.category && (
                    <p className="detail-category">{entry.category}</p>
                )}
                {provisional && (
                    <p className="example-notice">
                        {legal
                            ? 'Texto provisional · entorno de pruebas'
                            : entry.kind === 'PORTFOLIO'
                              ? 'Exploración conceptual · no representa un encargo de cliente'
                              : 'Contenido provisional del estudio'}
                    </p>
                )}
            </header>
            {(entry.kind === 'PORTFOLIO' || entry.media.length > 0) && (
                <div className="case-hero">
                    {entry.media[0] ? (
                        <img
                            src={entry.media[0].url}
                            alt={entry.media[0].alt}
                            width={entry.media[0].width}
                            height={entry.media[0].height}
                        />
                    ) : entry.is_example ? (
                        <ConceptArtwork kind={entry.art_style} />
                    ) : null}
                </div>
            )}
            <div
                className={`detail-layout ${relatedServices.length ? '' : 'no-related'}`}
            >
                {relatedServices.length > 0 && (
                    <aside className="detail-label">
                        <h2>Servicios relacionados</h2>
                        {relatedServices.map((service) => (
                            <Link
                                className="text-link"
                                key={service.id}
                                href={service.url}
                            >
                                {service.title}
                                <ArrowUpRight size={14} />
                            </Link>
                        ))}
                    </aside>
                )}
                <div className="public-copy">
                    {entry.body.split(/\n\s*\n/).map((paragraph, i) => (
                        <p key={i}>{paragraph}</p>
                    ))}
                    {legal && (
                        <dl className="public-contact-details">
                            <dt>Responsable</dt>
                            <dd>{site.responsible_name || 'Por completar'}</dd>
                            <dt>Contacto</dt>
                            <dd>
                                {site.contact_email ? (
                                    <a href={`mailto:${site.contact_email}`}>
                                        {site.contact_email}
                                    </a>
                                ) : (
                                    'Por completar'
                                )}
                            </dd>
                            {site.contact_address && (
                                <>
                                    <dt>Dirección de contacto</dt>
                                    <dd>{site.contact_address}</dd>
                                </>
                            )}
                        </dl>
                    )}
                </div>
            </div>
            {entry.media.length > 1 && (
                <section className="case-gallery" aria-label="Galería del caso">
                    {entry.media.slice(1).map((media) => (
                        <img
                            key={media.url}
                            src={media.url}
                            alt={media.alt}
                            width={media.width}
                            height={media.height}
                            loading="lazy"
                        />
                    ))}
                </section>
            )}
            {!legal && (
                <section className="detail-cta">
                    <h2>
                        Tu visión es el siguiente
                        <br />
                        punto de partida.
                    </h2>
                    <Link
                        href={
                            entry.kind === 'SERVICE'
                                ? `/iniciar-proyecto?servicio=${entry.slug}`
                                : '/iniciar-proyecto'
                        }
                        className="studio-button primary"
                    >
                        Iniciar proyecto <ArrowUpRight size={18} />
                    </Link>
                </section>
            )}
        </main>
    );
}
