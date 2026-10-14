import { Head, Link } from '@inertiajs/react';
import { ArrowUpRight } from 'lucide-react';

export default function PublicError() {
    return (
        <main id="contenido" className="studio-container public-error">
            <Head title="Página no encontrada">
                <meta name="robots" content="noindex,nofollow" />
            </Head>
            <h1>
                Esta idea aún
                <br />
                no tiene página.
            </h1>
            <p>Página no encontrada · 404</p>
            <p>
                El enlace puede haber cambiado o el contenido ya no está
                disponible.
            </p>
            <div className="hero-cta">
                <Link href="/" className="studio-button primary">
                    Volver al estudio <ArrowUpRight size={18} />
                </Link>
                <Link href="/servicios" className="studio-button secondary">
                    Explorar servicios
                </Link>
            </div>
        </main>
    );
}
