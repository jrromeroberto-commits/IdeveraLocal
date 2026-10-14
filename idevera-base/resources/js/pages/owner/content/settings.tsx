import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import type { FormEvent } from 'react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { SiteData } from '@/types/content';

export default function Settings({
    settings,
}: {
    settings: SiteData & { lock_version: number };
}) {
    const form = useForm({
        responsible_name: settings.responsible_name || '',
        contact_email: settings.contact_email || '',
        contact_address: settings.contact_address || '',
        provisional_legal: settings.provisional_legal ?? true,
        lock_version: settings.lock_version,
    });
    function submit(event: FormEvent) {
        event.preventDefault();
        form.post('/owner/content/settings', {
            preserveScroll: true,
            onSuccess: (page) => {
                const updated = page.props.settings as
                    | { lock_version: number }
                    | undefined;
                if (updated) form.setData('lock_version', updated.lock_version);
            },
        });
    }
    return (
        <div className="cms-page">
            <Head title="Datos públicos del estudio" />
            <Link className="cms-back" href="/owner/content">
                <ArrowLeft size={16} aria-hidden="true" /> Contenido
            </Link>
            <header className="cms-heading">
                <div>
                    <h1>Datos públicos del estudio</h1>
                    <p>
                        Estos datos se muestran en la web. La actualización
                        puede pedir confirmar tu contraseña.
                    </p>
                </div>
            </header>
            <form className="cms-form cms-settings" onSubmit={submit}>
                {[
                    ['responsible_name', 'Nombre del responsable'],
                    ['contact_email', 'Correo público'],
                    ['contact_address', 'Dirección de contacto (opcional)'],
                ].map(([key, label]) => (
                    <div className="cms-field" key={key}>
                        <Label htmlFor={key}>{label}</Label>
                        <Input
                            id={key}
                            type={key === 'contact_email' ? 'email' : 'text'}
                            value={
                                form.data[
                                    key as
                                        | 'responsible_name'
                                        | 'contact_email'
                                        | 'contact_address'
                                ]
                            }
                            onChange={(event) =>
                                form.setData(
                                    key as
                                        | 'responsible_name'
                                        | 'contact_email'
                                        | 'contact_address',
                                    event.target.value,
                                )
                            }
                        />
                        <InputError
                            message={
                                form.errors[key as keyof typeof form.errors]
                            }
                        />
                    </div>
                ))}
                <label className="cms-checkbox">
                    <input
                        type="checkbox"
                        checked={form.data.provisional_legal}
                        onChange={(event) =>
                            form.setData(
                                'provisional_legal',
                                event.target.checked,
                            )
                        }
                    />
                    Mantener los documentos legales identificados como
                    provisionales
                </label>
                <InputError message={form.errors.lock_version} />
                <Button disabled={form.processing}>
                    {form.processing
                        ? 'Actualizando…'
                        : 'Actualizar datos públicos'}
                </Button>
            </form>
        </div>
    );
}
