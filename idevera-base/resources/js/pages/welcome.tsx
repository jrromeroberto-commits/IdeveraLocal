import orbitImage from '../../../assets/plates/orbit-image.webp';
import formaImage from '../../../assets/plates/forma-image.webp';
import { PublicFrame, Seo } from '@/components/studio/public-frame';
import { Link } from '@inertiajs/react';
import { ArrowDown, ArrowRight, ArrowUpRight } from 'lucide-react';
import { useRef } from 'react';
import { useEditorialMotion } from '@/hooks/use-editorial-motion';
import type { PublicEntry } from '@/types/content';

const process = [
    [
        'Escuchamos tu visión.',
        'El brief reúne tu idea, objetivos y referencias. Empezamos por entender qué necesitas.',
    ],
    [
        'Damos forma al plan.',
        'Definimos alcance, entregables y condiciones en una cotización que puedes revisar.',
    ],
    [
        'Construimos contigo.',
        'Trabajo, conversaciones y versiones comparten un espacio. Sabes qué sigue y qué necesita tu atención.',
    ],
    [
        'Lo hacemos realidad.',
        'Revisamos los entregables, recogemos tus comentarios y conservamos el historial del proyecto.',
    ],
];

export default function Welcome({
    portfolio,
    services,
    designPreview,
}: {
    portfolio: PublicEntry[];
    services: PublicEntry[];
    designPreview: boolean;
}) {
    const root = useRef<HTMLDivElement>(null);
    useEditorialMotion(
        root,
        `${portfolio.map((entry) => entry.id).join(':')}:${services.length}`,
    );
    return (
        <div ref={root} className="studio-site editorial-home">
            <Seo
                title="Diseño, tecnología y creatividad"
                description="IDEVERA STUDIO. Diseño, desarrollo y arte digital. From vision to reality."
            />
            <PublicFrame>
                <main id="contenido">
                    <section
                        className="editorial-intro"
                        aria-labelledby="hero-title"
                    >
                        <h1 id="hero-title">
                            <span>From vision</span>{' '}
                            <span>
                                to <em>reality.</em>
                            </span>
                        </h1>
                        <div className="editorial-offer">
                            <p>
                                Diseño, desarrollo y arte digital
                                <br />
                                para tu siguiente idea.
                            </p>
                            <div className="hero-cta">
                                <Link
                                    href="/iniciar-proyecto"
                                    className="studio-button primary"
                                >
                                    Iniciar proyecto <ArrowUpRight size={20} />
                                </Link>
                                <a
                                    href="#trabajo"
                                    className="studio-button secondary"
                                >
                                    Explorar trabajo <ArrowDown size={20} />
                                </a>
                            </div>
                        </div>
                    </section>
                    <section
                        id="trabajo"
                        className="editorial-work"
                        aria-labelledby="work-title"
                    >
                        <h2 id="work-title" className="sr-only">
                            Trabajo y exploraciones
                        </h2>
                        {portfolio.map((entry) => (
                            <article
                                key={entry.id}
                                className={`editorial-case case-${entry.art_style}`}
                            >
                                <Link
                                    href={entry.url}
                                    className="editorial-case-link"
                                    aria-label={`Ver ${entry.title}${entry.is_example ? ', exploración conceptual' : ''}`}
                                >
                                    <div className="editorial-case-visual">
                                        <div className="editorial-case-depth">
                                            {entry.media[0] ? (
                                                <img
                                                    src={entry.media[0].url}
                                                    width={entry.media[0].width}
                                                    height={
                                                        entry.media[0].height
                                                    }
                                                    alt={entry.media[0].alt}
                                                />
                                            ) : entry.is_example ? (
                                                <img
                                                    src={
                                                        entry.art_style ===
                                                        'forma'
                                                            ? formaImage
                                                            : orbitImage
                                                    }
                                                    width={
                                                        entry.art_style ===
                                                        'forma'
                                                            ? 1448
                                                            : 1879
                                                    }
                                                    height={
                                                        entry.art_style ===
                                                        'forma'
                                                            ? 1086
                                                            : 837
                                                    }
                                                    alt={
                                                        entry.art_style ===
                                                        'forma'
                                                            ? 'Escultura de piedra en un espacio arquitectónico'
                                                            : 'Escultura orbital oscura con luz violeta'
                                                    }
                                                />
                                            ) : null}
                                        </div>
                                        <span
                                            className="editorial-case-cursor"
                                            aria-hidden="true"
                                        >
                                            <ArrowUpRight size={22} />
                                            <span>Ver caso</span>
                                        </span>
                                    </div>
                                    <div className="editorial-case-copy">
                                        <h3>{entry.title}</h3>
                                        {entry.is_example && (
                                            <p className="editorial-example">
                                                Exploración conceptual
                                            </p>
                                        )}
                                        <div className="editorial-case-description">
                                            <p>{entry.category}</p>
                                            <p>{entry.excerpt}</p>
                                        </div>
                                        <span
                                            className="editorial-case-arrow"
                                            aria-hidden="true"
                                        >
                                            <ArrowRight size={26} />
                                        </span>
                                    </div>
                                </Link>
                            </article>
                        ))}
                        {!portfolio.length && (
                            <p className="public-empty">
                                El portfolio está en preparación.{' '}
                                <Link href="/contacto">Cuéntanos tu idea.</Link>
                            </p>
                        )}
                    </section>
                    <section
                        id="servicios"
                        className="editorial-services"
                        aria-labelledby="services-title"
                    >
                        <div className="editorial-services-heading">
                            <h2 id="services-title" data-motion="statement">
                                Creatividad con <em>propósito.</em>
                                <br />
                                Tecnología con <em>sentido.</em>
                            </h2>
                            <p>
                                Un estudio independiente que une diseño,
                                tecnología y arte para convertir ideas en
                                experiencias reales.
                            </p>
                        </div>
                        <div className="editorial-service-list">
                            {services.map((service) => (
                                <Link key={service.id} href={service.url}>
                                    <div>
                                        <h3>{service.title}</h3>
                                        <p>{service.excerpt}</p>
                                    </div>
                                    <ArrowUpRight size={22} />
                                </Link>
                            ))}
                            {!services.length && (
                                <p>
                                    Conversemos sobre lo que necesitas.{' '}
                                    <Link
                                        href="/contacto"
                                        className="text-link"
                                    >
                                        Contactar al estudio{' '}
                                        <ArrowUpRight size={18} />
                                    </Link>
                                </p>
                            )}
                        </div>
                        <Link
                            href="/servicios"
                            className="text-link editorial-all-services"
                        >
                            Ver todos los servicios <ArrowUpRight size={18} />
                        </Link>
                    </section>
                    <section
                        id="proceso"
                        className="editorial-process studio-container"
                        aria-labelledby="process-title"
                    >
                        <div className="editorial-section-heading">
                            <h2 id="process-title">
                                Un proceso claro.
                                <br />
                                Una visión compartida.
                            </h2>
                            <Link href="/proceso" className="text-link">
                                Conoce el proceso <ArrowUpRight size={18} />
                            </Link>
                        </div>
                        <ol data-motion="process">
                            {process.map(([title, description], i) => (
                                <li key={title}>
                                    <span
                                        className="editorial-step"
                                        aria-hidden="true"
                                    >
                                        {String(i + 1).padStart(2, '0')}
                                    </span>
                                    <h3>{title}</h3>
                                    <p>{description}</p>
                                </li>
                            ))}
                        </ol>
                    </section>
                    <section
                        id="estudio"
                        className="editorial-about studio-container"
                        aria-labelledby="about-title"
                    >
                        <h2 id="about-title">
                            Entre lo que imaginas
                            <br />y lo que <em>puede ser.</em>
                        </h2>
                        <div>
                            <p>
                                IDEVERA STUDIO une desarrollo, diseño y arte
                                digital. Un espacio para convertir ideas en
                                experiencias con intención, identidad y una
                                forma de funcionar.
                            </p>
                            <Link href="/about" className="text-link">
                                Conoce el estudio <ArrowUpRight size={18} />
                            </Link>
                            {designPreview && (
                                <Link
                                    href="/design/project"
                                    className="text-link"
                                >
                                    Explorar el espacio de colaboración{' '}
                                    <ArrowUpRight size={18} />
                                    <span className="tiny-label">
                                        Vista de ejemplo
                                    </span>
                                </Link>
                            )}
                        </div>
                    </section>
                    <section
                        id="contacto"
                        className="editorial-contact"
                        aria-labelledby="contact-title"
                    >
                        <div className="studio-container">
                            <h2 id="contact-title" data-motion="contact">
                                <span>Hagamos</span> <span>que suceda.</span>
                            </h2>
                            <div>
                                <p>
                                    Cuéntanos qué tienes en mente.
                                    <br />
                                    Todo empieza por una conversación.
                                </p>
                                <Link
                                    href="/contacto"
                                    className="studio-button light"
                                >
                                    Hablemos de tu idea{' '}
                                    <ArrowUpRight size={22} />
                                </Link>
                            </div>
                        </div>
                    </section>
                </main>
            </PublicFrame>
        </div>
    );
}
