import { Head, Link, usePage } from '@inertiajs/react';
import { ArrowUpRight, Plus } from 'lucide-react';
import type { ReactNode } from 'react';
import { Brand } from '@/components/studio/brand';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import type { User } from '@/types';
import type { SiteData } from '@/types/content';

const links = [
    ['Trabajo', '/portfolio'],
    ['Servicios', '/servicios'],
    ['Estudio', '/about'],
    ['Proceso', '/proceso'],
];

export function Seo({
    title,
    description,
    image,
}: {
    title: string;
    description: string;
    image?: string | null;
}) {
    const { canonical, origin } = usePage<{
        canonical: string;
        origin: string;
    }>().props;
    return (
        <Head title={title}>
            <meta
                head-key="description"
                name="description"
                content={description}
            />
            <link head-key="canonical" rel="canonical" href={canonical} />
            <meta
                head-key="og:title"
                property="og:title"
                content={`${title} · IDEVERA STUDIO`}
            />
            <meta
                head-key="og:description"
                property="og:description"
                content={description}
            />
            <meta head-key="og:url" property="og:url" content={canonical} />
            <meta head-key="og:type" property="og:type" content="website" />
            <meta
                name="twitter:card"
                content={image ? 'summary_large_image' : 'summary'}
            />
            {image && (
                <meta
                    head-key="og:image"
                    property="og:image"
                    content={`${origin}${image}`}
                />
            )}
        </Head>
    );
}

export function PublicFrame({ children }: { children: ReactNode }) {
    const { auth, site, publicPageSlugs } = usePage<{
        auth: { user: User | null };
        site: SiteData;
        publicPageSlugs: string[];
    }>().props;
    const visibleLinks = links.filter(
        ([, href]) =>
            ['/portfolio', '/servicios'].includes(href) ||
            publicPageSlugs.includes(href.slice(1)),
    );
    const path = usePage().url.split('?')[0];
    return (
        <>
            <a className="studio-skip" href="#contenido">
                Saltar al contenido
            </a>
            <header className="studio-header studio-container">
                <Link href="/" aria-label="IDEVERA STUDIO, inicio">
                    <Brand />
                </Link>
                <nav className="desktop-nav" aria-label="Navegación principal">
                    {visibleLinks.map(([label, href]) => (
                        <Link
                            key={href}
                            href={href}
                            aria-current={
                                path === href
                                    ? 'page'
                                    : path.startsWith(`${href}/`)
                                      ? 'location'
                                      : undefined
                            }
                        >
                            {label}
                        </Link>
                    ))}
                </nav>
                <div className="header-actions">
                    <Link
                        href={auth.user ? '/dashboard' : '/login'}
                        className="access-link"
                    >
                        {auth.user ? 'Mi panel' : 'Acceder'}
                        <ArrowUpRight size={15} />
                    </Link>
                    <Dialog>
                        <DialogTrigger className="mobile-menu studio-button secondary">
                            Menú <Plus size={16} />
                        </DialogTrigger>
                        <DialogContent className="studio-dialog">
                            <DialogTitle>Explora IDEVERA</DialogTitle>
                            <DialogDescription>
                                From vision to reality.
                            </DialogDescription>
                            <nav
                                className="mobile-links"
                                aria-label="Navegación móvil"
                            >
                                {[
                                    ...visibleLinks,
                                    ['Contacto', '/contacto'],
                                ].map(([label, href]) => (
                                    <DialogClose asChild key={href}>
                                        <Link href={href}>
                                            {label}
                                            <ArrowUpRight />
                                        </Link>
                                    </DialogClose>
                                ))}
                            </nav>
                        </DialogContent>
                    </Dialog>
                </div>
            </header>
            {children}
            <footer className="studio-container studio-footer">
                <div className="footer-top">
                    <Brand />
                    <nav aria-label="Navegación de pie de página">
                        <Link href="/portfolio">Trabajo</Link>
                        <Link href="/servicios">Servicios</Link>
                        <Link href="/contacto">Contacto</Link>
                        {publicPageSlugs.includes('legal-privacidad') && (
                            <Link href="/legal/privacidad">Privacidad</Link>
                        )}
                        {publicPageSlugs.includes('legal-cancelaciones') && (
                            <Link href="/legal/cancelaciones">
                                Cancelaciones
                            </Link>
                        )}
                    </nav>
                </div>
                <div className="footer-wordmark" aria-hidden="true">
                    IDEVERA
                </div>
                <div className="footer-bottom">
                    <span>© {new Date().getFullYear()} IDEVERA STUDIO</span>
                    {site?.contact_email && (
                        <a href={`mailto:${site.contact_email}`}>
                            {site.contact_email}
                        </a>
                    )}
                    <span>From vision to reality.</span>
                    {publicPageSlugs.includes('legal-terminos') && (
                        <Link href="/legal/terminos">Términos</Link>
                    )}
                </div>
            </footer>
        </>
    );
}

export function PublicLayout({ children }: { children: ReactNode }) {
    return (
        <div className="studio-site">
            <PublicFrame>{children}</PublicFrame>
        </div>
    );
}
