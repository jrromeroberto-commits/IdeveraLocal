import { Head, Link, usePage } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { index as accounts } from '@/routes/owner/accounts';
import { edit as profile } from '@/routes/profile';
import type { Auth, User } from '@/types';

const areas = {
    CLIENT: {
        title: 'Mi espacio',
        description:
            'Aquí podrás consultar tus solicitudes y proyectos cuando estén disponibles.',
    },
    STAFF: {
        title: 'Espacio del equipo',
        description:
            'Aquí aparecerá el trabajo de los proyectos a los que estés asignado.',
    },
    OWNER: {
        title: 'Administración',
        description:
            'Gestiona las cuentas de clientes y las incorporaciones al equipo.',
    },
};

export default function Dashboard({ role }: { role: User['role'] }) {
    const { auth } = usePage<{ auth: Auth }>().props;
    const area = areas[role];
    return (
        <>
            <Head title={area.title} />
            <main className="flex flex-1 flex-col gap-6 p-6">
                <Heading title={area.title} description={area.description} />
                <section className="max-w-2xl space-y-4 rounded-xl border p-6">
                    <h2 className="text-lg font-semibold">
                        Hola, {auth.user.name}
                    </h2>
                    <p className="text-sm text-muted-foreground">
                        Tu cuenta está activa y tu correo está verificado.
                    </p>
                    <div className="flex flex-wrap gap-3">
                        <Button asChild variant="outline">
                            <Link href={profile()}>Mi perfil</Link>
                        </Button>
                        {role === 'OWNER' && (
                            <Button asChild>
                                <Link href={accounts()}>Gestionar cuentas</Link>
                            </Button>
                        )}
                    </div>
                </section>
            </main>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: dashboard(),
        },
    ],
};
