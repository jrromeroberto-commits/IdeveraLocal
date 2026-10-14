import { useForm, usePage } from '@inertiajs/react';
import { ArrowUpRight, CheckCircle2 } from 'lucide-react';
import { useEffect, useState, type FormEvent } from 'react';
import InputError from '@/components/input-error';
import { Seo } from '@/components/studio/public-frame';
import type { PublicEntry, SiteData } from '@/types/content';

export default function Contact({ content }: { content: PublicEntry | null }) {
    const { site } = usePage<{ site: SiteData }>().props;
    const [sent, setSent] = useState(false);
    const form = useForm({
        name: '',
        email: '',
        subject: '',
        message: '',
        privacy_acknowledged: false,
        website: '',
    });
    useEffect(() => {
        if (Object.keys(form.errors).length > 0) {
            document
                .querySelector<HTMLElement>('[aria-invalid="true"]')
                ?.focus();
        }
    }, [form.errors]);
    function submit(event: FormEvent) {
        event.preventDefault();
        setSent(false);
        form.post('/contacto', {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                setSent(true);
            },
        });
    }
    const title = content?.title || 'La siguiente idea puede ser la tuya.';
    return (
        <main
            id="contenido"
            className="studio-container public-page public-contact"
        >
            <Seo
                title={content?.seo_title || 'Contacto'}
                description={
                    content?.seo_description ||
                    'Comparte tu idea con IDEVERA STUDIO.'
                }
                image={content?.media[0]?.url}
            />
            <header className="public-page-heading">
                <h1>{title}</h1>
                <p>{content?.excerpt || 'Cuéntanos qué tienes en mente.'}</p>
            </header>
            <div className="contact-layout">
                <form className="contact-form" onSubmit={submit} noValidate>
                    {sent && (
                        <div className="contact-success" role="status">
                            <CheckCircle2 size={22} />
                            <div>
                                <strong>Gracias por escribirnos.</strong>
                                <p>
                                    Tu mensaje quedó registrado en el espacio de
                                    contacto del estudio.
                                </p>
                            </div>
                        </div>
                    )}
                    {(['name', 'email', 'subject'] as const).map((key) => (
                        <div className="public-field" key={key}>
                            <label htmlFor={`contact-${key}`}>
                                {key === 'name'
                                    ? 'Tu nombre'
                                    : key === 'email'
                                      ? 'Tu correo'
                                      : '¿De qué hablamos?'}
                            </label>
                            <input
                                id={`contact-${key}`}
                                name={key}
                                type={key === 'email' ? 'email' : 'text'}
                                autoComplete={
                                    key === 'name'
                                        ? 'name'
                                        : key === 'email'
                                          ? 'email'
                                          : 'off'
                                }
                                value={form.data[key]}
                                onChange={(event) =>
                                    form.setData(key, event.target.value)
                                }
                                required
                                maxLength={key === 'name' ? 120 : 180}
                                aria-invalid={!!form.errors[key]}
                                aria-describedby={`contact-${key}-error`}
                            />
                            <div id={`contact-${key}-error`}>
                                <InputError message={form.errors[key]} />
                            </div>
                        </div>
                    ))}
                    <div className="public-field">
                        <label htmlFor="contact-message">
                            Tu idea, contexto o pregunta
                        </label>
                        <textarea
                            id="contact-message"
                            name="message"
                            rows={6}
                            required
                            minLength={10}
                            maxLength={5000}
                            value={form.data.message}
                            onChange={(event) =>
                                form.setData('message', event.target.value)
                            }
                            aria-invalid={!!form.errors.message}
                            aria-describedby="contact-message-error"
                        />
                        <div id="contact-message-error">
                            <InputError message={form.errors.message} />
                        </div>
                    </div>
                    <div className="contact-honeypot" aria-hidden="true">
                        <label htmlFor="contact-website">Website</label>
                        <input
                            id="contact-website"
                            tabIndex={-1}
                            autoComplete="off"
                            value={form.data.website}
                            onChange={(event) =>
                                form.setData('website', event.target.value)
                            }
                        />
                    </div>
                    <label className="public-checkbox">
                        <input
                            type="checkbox"
                            checked={form.data.privacy_acknowledged}
                            onChange={(event) =>
                                form.setData(
                                    'privacy_acknowledged',
                                    event.target.checked,
                                )
                            }
                            aria-invalid={!!form.errors.privacy_acknowledged}
                            aria-describedby="contact-privacy-error"
                        />
                        <span>
                            Entiendo que mi nombre, correo y mensaje se
                            guardarán para gestionar esta consulta.{' '}
                            <a href="/legal/privacidad">Leer privacidad</a>.
                        </span>
                    </label>
                    <div id="contact-privacy-error">
                        <InputError
                            message={form.errors.privacy_acknowledged}
                        />
                    </div>
                    <button
                        className="studio-button primary"
                        disabled={form.processing}
                    >
                        {form.processing
                            ? 'Guardando mensaje…'
                            : 'Enviar mensaje'}
                        <ArrowUpRight size={18} />
                    </button>
                    <p className="form-note">
                        Entorno de pruebas · bandeja local del estudio.
                    </p>
                </form>
                <aside>
                    <h2>
                        Todo empieza
                        <br />
                        con una conversación.
                    </h2>
                    <p>
                        {content?.body ||
                            'Comparte tu idea, contexto y preguntas.'}
                    </p>
                    <dl className="public-contact-details">
                        <dt>Correo</dt>
                        <dd>
                            {site.contact_email && (
                                <a href={`mailto:${site.contact_email}`}>
                                    {site.contact_email}
                                </a>
                            )}
                        </dd>
                        <dt>Responsable</dt>
                        <dd>{site.responsible_name}</dd>
                        {site.contact_address && (
                            <>
                                <dt>Dirección de contacto</dt>
                                <dd>{site.contact_address}</dd>
                            </>
                        )}
                    </dl>
                </aside>
            </div>
        </main>
    );
}
