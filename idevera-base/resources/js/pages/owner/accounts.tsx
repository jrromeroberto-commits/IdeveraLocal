import { Head, Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index, store, update } from '@/routes/owner/accounts';

type Account = {
    id: number;
    name: string;
    email: string;
    role: 'CLIENT' | 'STAFF';
    status: string;
    verified: boolean;
    specialties: string[];
};
type AuditEvent = {
    id: number;
    event: string;
    target_user_id: number;
    actor_id: number | null;
    created_at: string;
};
type Props = {
    accounts: {
        data: Account[];
        current_page: number;
        last_page: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
    clientStatuses: string[];
    staffStatuses: string[];
    specialtyOptions: string[];
    auditEvents: AuditEvent[];
};
const labels: Record<string, string> = {
    PENDING_VERIFICATION: 'Pendiente de verificar correo',
    ACTIVE: 'Activo',
    SUSPENDED: 'Suspendido',
    DEACTIVATED: 'Desactivado',
    INACTIVE: 'Inactivo',
    DEVELOPER: 'Desarrollo',
    ARTIST: 'Arte',
    DESIGNER: 'Diseño',
    ANIMATOR: 'Animación',
    DATA: 'Datos',
    AUTOMATION: 'Automatización',
    OTHER: 'Otra',
    'account.staff_created': 'Incorporación al equipo',
    'account.access_updated': 'Cambio de acceso o especialidades',
    'account.deactivated': 'Desactivación de cuenta',
    'account.owner_provisioned': 'Creación de OWNER',
};
const selectClass =
    'h-10 w-full rounded-md border border-input bg-background px-3 text-sm';

function Specialties({
    id,
    options,
    value,
    onChange,
}: {
    id: string;
    options: string[];
    value: string[];
    onChange: (value: string[]) => void;
}) {
    return (
        <fieldset className="space-y-2">
            <legend className="text-sm font-medium">Especialidades</legend>
            <div className="flex flex-wrap gap-3">
                {options.map((option) => (
                    <label
                        key={option}
                        htmlFor={`${id}-${option}`}
                        className="flex items-center gap-2 text-sm"
                    >
                        <input
                            id={`${id}-${option}`}
                            type="checkbox"
                            checked={value.includes(option)}
                            onChange={(event) =>
                                onChange(
                                    event.target.checked
                                        ? [...value, option]
                                        : value.filter(
                                              (item) => item !== option,
                                          ),
                                )
                            }
                        />
                        {labels[option]}
                    </label>
                ))}
            </div>
        </fieldset>
    );
}

function AccountCard({
    account,
    statuses,
    specialtyOptions,
}: {
    account: Account;
    statuses: string[];
    specialtyOptions: string[];
}) {
    const form = useForm({
        status: account.status,
        ...(account.role === 'STAFF'
            ? { specialties: account.specialties }
            : {}),
    });
    function submit(event: FormEvent) {
        event.preventDefault();
        form.patch(update.url(account.id), { preserveScroll: true });
    }
    const allowed =
        account.role === 'CLIENT'
            ? statuses.filter(
                  (status) =>
                      status !==
                      (account.verified ? 'PENDING_VERIFICATION' : 'ACTIVE'),
              )
            : statuses;
    return (
        <section className="space-y-4 rounded-xl border p-5">
            <div>
                <h3 className="font-semibold">{account.name}</h3>
                <p className="text-sm break-all text-muted-foreground">
                    {account.email} · {account.role} ·{' '}
                    {account.verified
                        ? 'Correo verificado'
                        : 'Correo pendiente'}
                </p>
            </div>
            <form onSubmit={submit} className="space-y-4">
                <div className="max-w-sm space-y-2">
                    <Label htmlFor={`status-${account.id}`}>
                        Estado de cuenta
                    </Label>
                    <select
                        id={`status-${account.id}`}
                        className={selectClass}
                        value={form.data.status}
                        onChange={(event) =>
                            form.setData('status', event.target.value)
                        }
                    >
                        {allowed.map((status) => (
                            <option key={status} value={status}>
                                {labels[status]}
                            </option>
                        ))}
                    </select>
                    <InputError message={form.errors.status} />
                </div>
                {account.role === 'STAFF' && (
                    <>
                        <Specialties
                            id={`staff-${account.id}`}
                            options={specialtyOptions}
                            value={form.data.specialties ?? []}
                            onChange={(value) =>
                                form.setData('specialties', value)
                            }
                        />
                        <InputError message={form.errors.specialties} />
                    </>
                )}
                <Button type="submit" disabled={form.processing}>
                    Guardar acceso
                </Button>
            </form>
        </section>
    );
}

export default function Accounts({
    accounts,
    clientStatuses,
    staffStatuses,
    specialtyOptions,
    auditEvents,
}: Props) {
    const form = useForm({ name: '', email: '', specialties: [] as string[] });
    function create(event: FormEvent) {
        event.preventDefault();
        form.post(store.url(), {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        });
    }
    return (
        <>
            <Head title="Cuentas" />
            <main className="mx-auto w-full max-w-5xl space-y-8 p-6">
                <Heading
                    title="Cuentas y acceso"
                    description="Administra clientes y equipo. Los cambios de estado cierran sus sesiones; las especialidades no conceden permisos adicionales."
                />
                <section className="space-y-4 rounded-xl border p-5">
                    <h2 className="text-lg font-semibold">
                        Incorporar una persona al equipo
                    </h2>
                    <p className="text-sm text-muted-foreground">
                        Después de crear la cuenta, la persona debe usar
                        Recuperar contraseña para elegir la suya y verificar su
                        correo.
                    </p>
                    <form onSubmit={create} className="space-y-4">
                        <div className="grid gap-4 sm:grid-cols-2">
                            <div className="space-y-2">
                                <Label htmlFor="staff-name">Nombre</Label>
                                <Input
                                    id="staff-name"
                                    required
                                    maxLength={255}
                                    value={form.data.name}
                                    onChange={(event) =>
                                        form.setData('name', event.target.value)
                                    }
                                />
                                <InputError message={form.errors.name} />
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="staff-email">Correo</Label>
                                <Input
                                    id="staff-email"
                                    type="email"
                                    required
                                    maxLength={255}
                                    value={form.data.email}
                                    onChange={(event) =>
                                        form.setData(
                                            'email',
                                            event.target.value,
                                        )
                                    }
                                />
                                <InputError message={form.errors.email} />
                            </div>
                        </div>
                        <Specialties
                            id="new-staff"
                            options={specialtyOptions}
                            value={form.data.specialties}
                            onChange={(value) =>
                                form.setData('specialties', value)
                            }
                        />
                        <InputError message={form.errors.specialties} />
                        <Button type="submit" disabled={form.processing}>
                            Crear cuenta STAFF
                        </Button>
                    </form>
                </section>
                <section className="space-y-4" aria-label="Cuentas existentes">
                    <h2 className="text-lg font-semibold">Clientes y equipo</h2>
                    {accounts.data.length === 0 && (
                        <p className="text-sm text-muted-foreground">
                            Todavía no hay cuentas de clientes o equipo.
                        </p>
                    )}
                    {accounts.data.map((account) => (
                        <AccountCard
                            key={`${account.id}-${account.status}-${account.specialties.join(',')}`}
                            account={account}
                            statuses={
                                account.role === 'CLIENT'
                                    ? clientStatuses
                                    : staffStatuses
                            }
                            specialtyOptions={specialtyOptions}
                        />
                    ))}
                    <nav
                        aria-label="Paginación de cuentas"
                        className="flex items-center gap-4"
                    >
                        {accounts.prev_page_url && (
                            <Link
                                href={accounts.prev_page_url}
                                className="underline"
                            >
                                Anterior
                            </Link>
                        )}
                        <span className="text-sm">
                            Página {accounts.current_page} de{' '}
                            {accounts.last_page}
                        </span>
                        {accounts.next_page_url && (
                            <Link
                                href={accounts.next_page_url}
                                className="underline"
                            >
                                Siguiente
                            </Link>
                        )}
                    </nav>
                </section>
                <section className="space-y-3">
                    <h2 className="text-lg font-semibold">
                        Actividad reciente de cuentas
                    </h2>
                    <ul className="space-y-2 text-sm">
                        {auditEvents.map((event) => (
                            <li
                                key={event.id}
                                className="rounded-lg border p-3"
                            >
                                {labels[event.event] ?? event.event} · Cuenta #
                                {event.target_user_id} ·{' '}
                                {event.actor_id
                                    ? `Autor #${event.actor_id}`
                                    : 'Consola local'}{' '}
                                ·{' '}
                                <time dateTime={event.created_at}>
                                    {new Date(event.created_at).toLocaleString(
                                        'es-PE',
                                    )}
                                </time>
                            </li>
                        ))}
                    </ul>
                </section>
            </main>
        </>
    );
}

Accounts.layout = { breadcrumbs: [{ title: 'Cuentas', href: index() }] };
