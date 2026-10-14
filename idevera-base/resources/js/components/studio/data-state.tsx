import {
    AlertCircle,
    Inbox,
    LockKeyhole,
    RefreshCw,
    WifiOff,
} from 'lucide-react';

export type DataStateKind =
    | 'ready'
    | 'loading'
    | 'empty'
    | 'error'
    | 'restricted'
    | 'conflict'
    | 'offline';
const messages = {
    empty: [
        'Aún no hay contenido compartido',
        'Las entregas aparecerán aquí cuando el equipo las comparta contigo.',
    ],
    error: [
        'No pudimos cargar el contenido',
        'Puedes reintentar sin perder el contexto de tu proyecto.',
    ],
    restricted: [
        'El acceso a este contenido cambió',
        'Vuelve a tu espacio y comprueba los permisos de tu cuenta.',
    ],
    conflict: [
        'Hay una versión más reciente',
        'Conservamos tu borrador. Revisa la versión actual antes de continuar.',
    ],
    offline: [
        'La conexión se interrumpió',
        'Tu borrador permanece en esta pantalla. Reintenta cuando recuperes la conexión.',
    ],
};
export function DataState({
    kind,
    retry,
}: {
    kind: Exclude<DataStateKind, 'ready'>;
    retry: () => void;
}) {
    if (kind === 'loading')
        return (
            <div className="data-state" role="status">
                <div className="studio-skeleton" />
                <div className="studio-skeleton short" />
                <p>Cargando contenido…</p>
            </div>
        );
    const [title, description] = messages[kind];
    const Icon =
        kind === 'restricted'
            ? LockKeyhole
            : kind === 'offline'
              ? WifiOff
              : kind === 'empty'
                ? Inbox
                : AlertCircle;
    return (
        <div className="data-state" role="status">
            <Icon size={30} />
            <h3>{title}</h3>
            <p>{description}</p>
            {kind !== 'empty' && (
                <button className="studio-button secondary" onClick={retry}>
                    <RefreshCw size={16} />
                    {kind === 'conflict'
                        ? 'Ver versión actual'
                        : kind === 'restricted'
                          ? 'Volver al resumen'
                          : 'Reintentar'}
                </button>
            )}
        </div>
    );
}
