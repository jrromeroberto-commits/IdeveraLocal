import { Head, Link, router } from '@inertiajs/react';
import { ArrowLeft, ArrowRight } from 'lucide-react';
import { Button } from '@/components/ui/button';
import type { Pagination } from '@/types/content';

type Message = {
    id: number;
    reference: string;
    name: string;
    email: string;
    subject: string;
    message: string;
    read_at: string | null;
    created_at: string;
};
export default function Messages({
    messages,
    unreadCount,
}: {
    messages: Pagination<Message>;
    unreadCount: number;
}) {
    return (
        <div className="cms-page">
            <Head title="Mensajes de contacto" />
            <Link href="/owner/content" className="cms-back">
                <ArrowLeft size={16} aria-hidden="true" /> Contenido del estudio
            </Link>
            <header className="cms-heading">
                <div>
                    <h1>Mensajes de contacto</h1>
                    <p>
                        {unreadCount} mensajes sin revisar. Bandeja privada de
                        OWNER.
                    </p>
                </div>
            </header>
            <div className="cms-list">
                {messages.data.map((message) => (
                    <article className="cms-message" key={message.id}>
                        <header>
                            <div>
                                <h2>{message.subject}</h2>
                                <p>
                                    {message.name} ·{' '}
                                    <a href={`mailto:${message.email}`}>
                                        {message.email}
                                    </a>
                                </p>
                            </div>
                            <span className="cms-status">
                                {message.read_at ? 'Revisado' : 'Nuevo'}
                            </span>
                        </header>
                        <p className="cms-message-body">{message.message}</p>
                        <footer>
                            <span>
                                {new Date(
                                    message.created_at,
                                ).toLocaleDateString('es', { timeZone: 'UTC' })}
                            </span>
                            {!message.read_at && (
                                <Button
                                    variant="outline"
                                    onClick={() =>
                                        router.post(
                                            `/owner/contact/${message.id}/read`,
                                            {},
                                            { preserveScroll: true },
                                        )
                                    }
                                >
                                    Marcar como revisado
                                </Button>
                            )}
                        </footer>
                    </article>
                ))}
                {!messages.data.length && (
                    <div className="cms-empty">
                        <h2>El próximo mensaje empieza una conversación.</h2>
                        <p>
                            Los mensajes enviados desde Contacto aparecerán
                            aquí.
                        </p>
                    </div>
                )}
            </div>
            <nav className="cms-pagination" aria-label="Paginación">
                {messages.prev_page_url && (
                    <Link href={messages.prev_page_url}>
                        <ArrowLeft size={16} aria-hidden="true" /> Anterior
                    </Link>
                )}
                <span>
                    {messages.current_page} / {messages.last_page}
                </span>
                {messages.next_page_url && (
                    <Link href={messages.next_page_url}>
                        Siguiente <ArrowRight size={16} aria-hidden="true" />
                    </Link>
                )}
            </nav>
        </div>
    );
}
