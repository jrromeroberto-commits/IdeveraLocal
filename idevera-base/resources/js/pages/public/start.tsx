import { Link, usePage } from '@inertiajs/react';
import { ArrowUpRight } from 'lucide-react';
import { Seo } from '@/components/studio/public-frame';
import type { User } from '@/types';
import type { PublicEntry } from '@/types/content';

export default function Start({ service }: { service: PublicEntry | null }) {
    const { auth } = usePage<{ auth: { user: User | null } }>().props;
    return (
        <main id="contenido" className="studio-container public-page">
            <Seo
                title="Iniciar proyecto"
                description="Empieza a dar forma a tu idea con IDEVERA STUDIO."
            />
            <header className="public-page-heading">
                <h1>
                    Tu idea.
                    <br />
                    Nuestro punto de partida.
                </h1>
                <p>
                    {service
                        ? `Tu interés: ${service.title}.`
                        : 'Empecemos por tu contexto y tus objetivos.'}
                </p>
            </header>
            <div className="start-layout">
                <div className="public-copy">
                    <p>
                        La cuenta reúne el contexto de tu proyecto, sus
                        conversaciones y las revisiones. El brief y las
                        solicitudes se habilitarán en la siguiente fase de
                        desarrollo.
                    </p>
                    {service && (
                        <p className="example-notice">
                            Conservamos tu selección de servicio en esta sesión
                            para que puedas retomarla.
                        </p>
                    )}
                    <div className="hero-cta">
                        {auth.user ? (
                            <Link
                                className="studio-button primary"
                                href="/dashboard"
                            >
                                Ir a mi espacio <ArrowUpRight size={18} />
                            </Link>
                        ) : (
                            <>
                                <Link
                                    className="studio-button primary"
                                    href="/register"
                                >
                                    Crear mi cuenta <ArrowUpRight size={18} />
                                </Link>
                                <Link
                                    className="studio-button secondary"
                                    href="/login"
                                >
                                    Ya tengo una cuenta
                                </Link>
                            </>
                        )}
                        <Link className="text-link" href="/contacto">
                            Prefiero conversar primero{' '}
                            <ArrowUpRight size={17} />
                        </Link>
                    </div>
                </div>
                <aside className="start-steps">
                    <h2>Lo que sigue</h2>
                    <ol>
                        <li>
                            <span>01</span>Tu cuenta y tu contexto
                        </li>
                        <li>
                            <span>02</span>Brief y definición de alcance
                        </li>
                        <li>
                            <span>03</span>Cotización y colaboración
                        </li>
                    </ol>
                </aside>
            </div>
        </main>
    );
}
