import { Form, Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { home, logout } from '@/routes';

export default function AccountRestricted({
    accountStatus,
}: {
    accountStatus: string | null;
}) {
    return (
        <>
            <Head title="Acceso restringido" />
            <div className="space-y-5 text-center">
                <h1 className="text-2xl font-semibold">
                    {accountStatus === 'SUSPENDED'
                        ? 'Cuenta suspendida'
                        : 'Cuenta inactiva'}
                </h1>
                <p className="text-sm text-muted-foreground">
                    Tu cuenta no tiene acceso al área privada. Para recuperar el
                    acceso, contacta con la administración de IDEVERA STUDIO.
                </p>
                <Form {...logout.form()}>
                    <Button className="w-full" type="submit">
                        Cerrar sesión
                    </Button>
                </Form>
                <Link
                    href={home()}
                    className="text-sm underline underline-offset-4"
                >
                    Volver al inicio
                </Link>
            </div>
        </>
    );
}
