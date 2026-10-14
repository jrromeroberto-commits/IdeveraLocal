import { Head, Link } from '@inertiajs/react';
import {
    ArrowLeft,
    ArrowUpRight,
    Check,
    CheckCircle2,
    FileText,
    Folder,
    LayoutGrid,
    MessageSquare,
    ShieldCheck,
} from 'lucide-react';
import { useState, type KeyboardEvent } from 'react';
import { Brand } from '@/components/studio/brand';
import { ConceptArtwork } from '@/components/studio/artwork';
import { DataState, type DataStateKind } from '@/components/studio/data-state';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';

const tabs = [
    'Resumen',
    'Trabajo',
    'Entregables',
    'Archivos',
    'Conversación',
    'Pagos',
    'Actividad',
];
const states: [DataStateKind, string][] = [
    ['ready', 'Con contenido'],
    ['loading', 'Cargando'],
    ['empty', 'Vacío'],
    ['error', 'Error'],
    ['restricted', 'Sin permiso'],
    ['conflict', 'Conflicto de versión'],
    ['offline', 'Sin conexión'],
];

export default function ProjectPreview() {
    const [active, setActive] = useState(0);
    const [state, setState] = useState<DataStateKind>('ready');
    const [draft, setDraft] = useState(
        'Me gusta la dirección visual. Quisiera revisar la versión móvil.',
    );
    const [notice, setNotice] = useState(false);
    const [reviewError, setReviewError] = useState(false);
    const [messageDraft, setMessageDraft] = useState('');
    function tabKey(event: KeyboardEvent<HTMLButtonElement>, index: number) {
        const next =
            event.key === 'ArrowRight'
                ? (index + 1) % tabs.length
                : event.key === 'ArrowLeft'
                  ? (index + tabs.length - 1) % tabs.length
                  : event.key === 'Home'
                    ? 0
                    : event.key === 'End'
                      ? tabs.length - 1
                      : null;
        if (next === null) return;
        event.preventDefault();
        setActive(next);
        document.getElementById(`project-tab-${next}`)?.focus();
    }
    const deliverable = (
        <Dialog>
            <DialogTrigger className="deliverable-row">
                <span className="file-icon">
                    <FileText />
                </span>
                <span>
                    <strong>Dirección visual / ORBIT</strong>
                    <small>Versión 02 · Compartida para revisión</small>
                </span>
                <span className="studio-status warning">Por revisar</span>
                <ArrowUpRight size={18} />
            </DialogTrigger>
            <DialogContent className="studio-dialog deliverable-dialog">
                <DialogTitle>Dirección visual — versión 02</DialogTitle>
                <DialogDescription>
                    Entregable de ejemplo · SUBMITTED. Esta vista permite
                    explorar el diseño; no aprueba ni modifica un proyecto.
                </DialogDescription>
                <ConceptArtwork kind="orbit" />
                <label htmlFor="review-draft">
                    Tus comentarios · borrador de ejemplo
                </label>
                <textarea
                    id="review-draft"
                    value={draft}
                    onChange={(event) => {
                        setDraft(event.target.value);
                        setReviewError(false);
                        setNotice(false);
                    }}
                    aria-invalid={reviewError}
                    aria-describedby={reviewError ? 'review-error' : undefined}
                    rows={3}
                />
                <button
                    className="studio-button primary"
                    onClick={() => {
                        const invalid = !draft.trim();
                        setReviewError(invalid);
                        setNotice(!invalid);
                        if (invalid)
                            document.getElementById('review-draft')?.focus();
                    }}
                >
                    Probar revisión <Check size={17} />
                </button>
                {reviewError && (
                    <p id="review-error" role="alert" className="review-error">
                        Escribe un comentario para explorar la confirmación.
                    </p>
                )}
                {notice && (
                    <p role="status" className="prototype-notice">
                        Vista de confirmación: el borrador se conserva en esta
                        pantalla. No se ha enviado ni aprobado el entregable.
                    </p>
                )}
            </DialogContent>
        </Dialog>
    );
    return (
        <div className="studio-site project-preview">
            <Head title="ORBIT · Prototipo de proyecto">
                <meta name="robots" content="noindex,nofollow" />
            </Head>
            <a className="studio-skip" href="#proyecto">
                Saltar al proyecto
            </a>
            <div className="preview-banner">
                <span>PROYECTO DE EJEMPLO</span> Datos de ejemplo · sin
                operaciones reales{' '}
                <Link href="/">
                    Volver al estudio <ArrowUpRight size={14} />
                </Link>
            </div>
            <div className="project-frame">
                <aside className="project-sidebar">
                    <Link href="/" aria-label="IDEVERA STUDIO, inicio">
                        <Brand />
                    </Link>
                    <p className="sidebar-caption">ESPACIO DEL CLIENTE</p>
                    <a href="#proyecto" className="preview-nav active">
                        <Folder size={19} />
                        Proyecto de ejemplo
                    </a>
                    <Link href="/dashboard" className="preview-nav">
                        <LayoutGrid size={19} />
                        Ir a mi panel real
                    </Link>
                    <div className="sidebar-bottom">
                        <ShieldCheck size={20} />
                        <span>
                            Un espacio compartido.
                            <br />
                            Una visión en común.
                        </span>
                    </div>
                </aside>
                <main id="proyecto" className="project-main">
                    <div className="project-breadcrumb">
                        <Link href="/">
                            <ArrowLeft size={15} />
                            Estudio
                        </Link>
                        <span>/</span>
                        <span>Proyecto de ejemplo</span>
                    </div>
                    <header className="project-title">
                        <div>
                            <h1>
                                ORBIT — experiencia digital
                                <span>Diseño web & desarrollo</span>
                            </h1>
                        </div>
                        <div className="project-statuses">
                            <span>
                                <small>Operacional</small>
                                <span className="studio-status warning">
                                    Esperando al cliente
                                </span>
                            </span>
                            <span>
                                <small>Financiero</small>
                                <span className="studio-status success">
                                    Al día
                                </span>
                            </span>
                        </div>
                    </header>
                    <section className="next-action">
                        <span className="action-icon">
                            <MessageSquare size={23} />
                        </span>
                        <div>
                            <h2>Revisa la dirección visual.</h2>
                            <p>
                                Revisa la dirección visual y comparte tus
                                comentarios sobre la versión 02.
                            </p>
                        </div>
                        <button
                            className="studio-button primary"
                            onClick={() => setActive(2)}
                        >
                            Revisar entregable <ArrowUpRight size={17} />
                        </button>
                    </section>
                    <div className="preview-controls">
                        <label htmlFor="preview-state">
                            Explorar estados de interfaz
                        </label>
                        <select
                            id="preview-state"
                            value={state}
                            onChange={(event) =>
                                setState(event.target.value as DataStateKind)
                            }
                        >
                            {states.map(([value, label]) => (
                                <option key={value} value={value}>
                                    {label}
                                </option>
                            ))}
                        </select>
                    </div>
                    <div
                        className="project-tabs"
                        role="tablist"
                        aria-label="Contenido del proyecto"
                    >
                        {tabs.map((label, i) => (
                            <button
                                key={label}
                                id={`project-tab-${i}`}
                                role="tab"
                                aria-selected={active === i}
                                aria-controls={`project-panel-${i}`}
                                tabIndex={active === i ? 0 : -1}
                                onKeyDown={(event) => tabKey(event, i)}
                                onClick={() => setActive(i)}
                            >
                                {label}
                                {i === 2 && (
                                    <span className="tab-count">1</span>
                                )}
                            </button>
                        ))}
                    </div>
                    <section
                        id={`project-panel-${active}`}
                        role="tabpanel"
                        aria-labelledby={`project-tab-${active}`}
                        tabIndex={0}
                        className="project-panel"
                    >
                        {state !== 'ready' ? (
                            <DataState
                                kind={state}
                                retry={() => {
                                    setState('ready');
                                    if (state === 'restricted') setActive(0);
                                }}
                            />
                        ) : (
                            <>
                                {active === 0 && (
                                    <>
                                        <div className="project-overview">
                                            <article className="project-card">
                                                <h2>
                                                    Una presencia digital
                                                    <br />
                                                    con identidad propia.
                                                </h2>
                                                <p>
                                                    Diseñar una experiencia
                                                    editorial para ORBIT: una
                                                    portada expresiva, contenido
                                                    claro y una navegación
                                                    sencilla en móvil y
                                                    escritorio.
                                                </p>
                                                <div className="scope-tags">
                                                    <span>
                                                        Dirección visual
                                                    </span>
                                                    <span>
                                                        Diseño responsive
                                                    </span>
                                                    <span>Desarrollo web</span>
                                                </div>
                                                <div className="scope-note">
                                                    <ShieldCheck size={16} />
                                                    Alcance ilustrativo · no es
                                                    una cotización aceptada.
                                                </div>
                                            </article>
                                            <article className="project-card milestone-card">
                                                <h2>
                                                    De la idea a la entrega.
                                                </h2>
                                                <ol className="milestone-list">
                                                    <li className="completed">
                                                        <CheckCircle2
                                                            size={18}
                                                        />
                                                        <span>
                                                            Brief y alcance
                                                            <small>
                                                                Contexto de
                                                                partida definido
                                                            </small>
                                                        </span>
                                                    </li>
                                                    <li className="current">
                                                        <span className="milestone-dot" />
                                                        <span>
                                                            Dirección visual
                                                            <small>
                                                                Versión 02 lista
                                                                para revisar
                                                            </small>
                                                        </span>
                                                    </li>
                                                    <li>
                                                        <span className="milestone-dot" />
                                                        <span>
                                                            Diseño & desarrollo
                                                            <small>
                                                                Siguiente etapa
                                                            </small>
                                                        </span>
                                                    </li>
                                                    <li>
                                                        <span className="milestone-dot" />
                                                        <span>
                                                            Revisión y entrega
                                                            <small>
                                                                Después de la
                                                                construcción
                                                            </small>
                                                        </span>
                                                    </li>
                                                </ol>
                                            </article>
                                        </div>
                                        <article className="project-card shared-delivery">
                                            <div className="card-heading">
                                                <h2>Compartido contigo</h2>
                                                <button
                                                    className="text-link"
                                                    onClick={() => setActive(2)}
                                                >
                                                    Ver entregables{' '}
                                                    <ArrowUpRight size={16} />
                                                </button>
                                            </div>
                                            {deliverable}
                                        </article>
                                    </>
                                )}
                                {active === 1 && (
                                    <article className="project-card">
                                        <h2>Trabajo compartido</h2>
                                        <p>
                                            Solo las tareas visibles para el
                                            cliente aparecen en esta vista de
                                            ejemplo.
                                        </p>
                                        {[
                                            ['Dirección visual', 'En revisión'],
                                            [
                                                'Diseño de pantallas',
                                                'Pendiente',
                                            ],
                                            [
                                                'Desarrollo de la experiencia',
                                                'Pendiente',
                                            ],
                                        ].map(([name, status]) => (
                                            <div
                                                className="work-list-row"
                                                key={name}
                                            >
                                                <span>{name}</span>
                                                <span className="studio-status neutral">
                                                    {status}
                                                </span>
                                            </div>
                                        ))}
                                    </article>
                                )}
                                {active === 2 && (
                                    <article className="project-card">
                                        <h2>Entregables</h2>
                                        <p>
                                            Revisa la versión compartida; el
                                            historial permanece asociado a cada
                                            entrega.
                                        </p>
                                        {deliverable}
                                        <div className="work-list-row">
                                            <span>
                                                Versión 01 — referencia
                                                histórica
                                            </span>
                                            <span className="studio-status neutral">
                                                Histórico
                                            </span>
                                        </div>
                                    </article>
                                )}
                                {active === 3 && (
                                    <DataState
                                        kind="empty"
                                        retry={() => setState('ready')}
                                    />
                                )}
                                {active === 4 && (
                                    <article className="project-card conversation-preview">
                                        <h2>Conversación del proyecto</h2>
                                        <div className="sample-message">
                                            <span className="message-avatar">
                                                I
                                            </span>
                                            <div>
                                                <strong>
                                                    IDEVERA · Mensaje de ejemplo
                                                </strong>
                                                <p>
                                                    Compartimos la segunda
                                                    dirección visual. Puedes
                                                    dejar tus comentarios en el
                                                    entregable para conservar el
                                                    contexto de esta versión.
                                                </p>
                                            </div>
                                        </div>
                                        <label htmlFor="message-draft">
                                            Borrador de ejemplo
                                        </label>
                                        <textarea
                                            id="message-draft"
                                            rows={3}
                                            value={messageDraft}
                                            onChange={(event) =>
                                                setMessageDraft(
                                                    event.target.value,
                                                )
                                            }
                                        />
                                        <p className="scope-note">
                                            Este borrador solo vive en la
                                            pantalla. El chat persistente se
                                            conecta en F7.
                                        </p>
                                    </article>
                                )}
                                {active === 5 && (
                                    <article className="project-card">
                                        <h2>Pagos del proyecto</h2>
                                        <span className="studio-status success">
                                            Al día · ejemplo visual
                                        </span>
                                        <p>
                                            Los importes e hitos provendrán del
                                            backend. Esta vista no contiene una
                                            transacción ni un botón de cobro.
                                        </p>
                                        <div className="scope-note">
                                            <ShieldCheck size={16} />
                                            PayPal Sandbox se integra en F8.
                                        </div>
                                    </article>
                                )}
                                {active === 6 && (
                                    <article className="project-card">
                                        <h2>Actividad compartida</h2>
                                        <ol className="activity-list">
                                            <li>
                                                <span className="signal-dot" />
                                                <div>
                                                    <strong>
                                                        Dirección visual,
                                                        versión 02 compartida
                                                    </strong>
                                                    <p>
                                                        Entrega de ejemplo
                                                        disponible para
                                                        revisión.
                                                    </p>
                                                </div>
                                            </li>
                                            <li>
                                                <CheckCircle2 size={16} />
                                                <div>
                                                    <strong>
                                                        Brief y alcance
                                                        definidos
                                                    </strong>
                                                    <p>
                                                        Evento ilustrativo del
                                                        recorrido del proyecto.
                                                    </p>
                                                </div>
                                            </li>
                                        </ol>
                                    </article>
                                )}
                            </>
                        )}
                    </section>
                    <footer className="project-footer">
                        <span>IDEVERA STUDIO</span>
                        <span>From vision to reality.</span>
                    </footer>
                </main>
            </div>
        </div>
    );
}
