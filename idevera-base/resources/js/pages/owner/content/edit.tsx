import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import { useEffect, useState, type FormEvent } from 'react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { ConceptArtwork } from '@/components/studio/artwork';
import type { ContentKind, PublicEntry } from '@/types/content';

type Draft = Omit<PublicEntry, 'url' | 'media'> & {
    position: number;
    featured: boolean;
    service_ids: number[] | null;
    lock_version: number;
    published_at: string | null;
    published_version: number | null;
};
type Media = { id: number; alt: string; url: string };
type Service = { id: number; title: string; published_at: string | null };
type Change = {
    id: number;
    event: string;
    created_at: string;
    metadata: { version: number };
};

export default function ContentEditor({
    entry,
    kind,
    media,
    services,
    changes,
}: {
    entry: Draft | null;
    kind: ContentKind;
    media: Media[];
    services: Service[];
    changes: Change[];
}) {
    const [fileKey, setFileKey] = useState(0);
    const form = useForm({
        kind,
        title: entry?.title ?? '',
        slug: entry?.slug ?? '',
        excerpt: entry?.excerpt ?? '',
        body: entry?.body ?? '',
        category: entry?.category ?? '',
        art_style: entry?.art_style ?? 'orbit',
        seo_title: entry?.seo_title ?? '',
        seo_description: entry?.seo_description ?? '',
        position: entry?.position ?? 0,
        featured: entry?.featured ?? false,
        is_example: entry?.is_example ?? true,
        service_ids: entry?.service_ids ?? [],
        lock_version: entry?.lock_version ?? 1,
        images: [] as File[],
        image_alt: '',
        remove_media_ids: [] as number[],
        media_alt: Object.fromEntries(
            media.map((image) => [String(image.id), image.alt]),
        ) as Record<string, string>,
    });
    const publication = useForm({
        lock_version: entry?.lock_version ?? 1,
        publish: true,
    });
    useEffect(() => {
        if (Object.keys(form.errors).length > 0) {
            document
                .querySelector<HTMLElement>('[aria-invalid="true"]')
                ?.focus();
        }
    }, [form.errors]);
    function save(event: FormEvent) {
        event.preventDefault();
        form.post(entry ? `/owner/content/${entry.id}` : '/owner/content', {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: (page) => {
                const updated = page.props.entry as Draft | undefined;
                if (!updated) return;
                form.setData('lock_version', updated.lock_version);
                form.setData('images', []);
                form.setData('remove_media_ids', []);
                form.setDefaults({
                    ...form.data,
                    lock_version: updated.lock_version,
                    images: [],
                    remove_media_ids: [],
                });
                setFileKey(fileKey + 1);
            },
        });
    }
    function publish(value: boolean) {
        if (!entry) return;
        publication.transform((data) => ({
            ...data,
            lock_version: entry.lock_version,
            publish: value,
        }));
        publication.post(`/owner/content/${entry.id}/publication`, {
            preserveScroll: true,
            onSuccess: (page) => {
                const updated = page.props.entry as Draft | undefined;
                if (!updated) return;
                form.setData('lock_version', updated.lock_version);
                form.setDefaults('lock_version', updated.lock_version);
            },
        });
    }
    const textFields = [
        ['title', 'Título', 180],
        ['slug', 'Identificador de la dirección', 120],
        ['excerpt', 'Introducción', 500],
        ['category', 'Categoría o disciplina', 100],
        ['seo_title', 'Título para buscadores (opcional)', 100],
        ['seo_description', 'Descripción para buscadores (opcional)', 180],
    ] as const;
    return (
        <div className="cms-page">
            <Head title={entry ? `Editar ${entry.title}` : 'Crear contenido'} />
            <Link href={`/owner/content?kind=${kind}`} className="cms-back">
                <ArrowLeft size={16} aria-hidden="true" /> Contenido del estudio
            </Link>
            <header className="cms-heading">
                <div>
                    <h1>
                        {entry
                            ? `Editar ${kind === 'SERVICE' ? 'servicio' : kind === 'PORTFOLIO' ? 'caso' : 'página'}`
                            : `Crear ${kind === 'SERVICE' ? 'servicio' : kind === 'PORTFOLIO' ? 'caso' : 'página'}`}
                    </h1>
                    <p>
                        El borrador y la versión publicada se mantienen
                        separados.
                    </p>
                </div>
                <Dialog>
                    <DialogTrigger asChild>
                        <Button variant="outline">
                            Vista previa del texto
                        </Button>
                    </DialogTrigger>
                    <DialogContent className="studio-dialog">
                        <DialogTitle>
                            {form.data.title || 'Título del contenido'}
                        </DialogTitle>
                        <DialogDescription>
                            Vista previa privada del borrador. Guardar no lo
                            publica.
                        </DialogDescription>
                        <p>{form.data.excerpt}</p>
                        {kind === 'PORTFOLIO' && form.data.is_example && (
                            <ConceptArtwork kind={form.data.art_style} />
                        )}
                        {kind === 'PORTFOLIO' &&
                            !form.data.is_example &&
                            media[0] && (
                                <img
                                    src={media[0].url}
                                    alt={
                                        form.data.media_alt[media[0].id] ||
                                        media[0].alt
                                    }
                                    width={600}
                                    height={400}
                                    className="cms-preview-image"
                                />
                            )}
                        <div className="cms-preview-body">{form.data.body}</div>
                    </DialogContent>
                </Dialog>
            </header>
            <div className="cms-editor-toolbar">
                <div role="status" aria-live="polite">
                    <strong>
                        {form.processing
                            ? 'Guardando…'
                            : form.isDirty
                              ? 'Cambios sin guardar'
                              : entry
                                ? `Borrador guardado · v${entry.lock_version}`
                                : 'Nuevo borrador'}
                    </strong>
                    <span>
                        {entry?.published_at
                            ? `Web pública · v${entry.published_version}`
                            : 'Todavía no está publicado'}
                    </span>
                </div>
                <Button
                    type="submit"
                    form="content-editor-form"
                    disabled={form.processing || publication.processing}
                >
                    {form.processing
                        ? 'Guardando borrador…'
                        : 'Guardar borrador'}
                </Button>
                <a className="cms-publication-link" href="#content-publication">
                    Publicación
                </a>
            </div>
            <div className="cms-editor-layout">
                <form
                    id="content-editor-form"
                    onSubmit={save}
                    className="cms-form"
                    noValidate
                >
                    {textFields.map(([key, label, max]) => (
                        <div className={`cms-field cms-field-${key}`} key={key}>
                            <Label htmlFor={`content-${key}`}>{label}</Label>
                            <Input
                                id={`content-${key}`}
                                value={form.data[key]}
                                maxLength={max}
                                onChange={(event) =>
                                    form.setData(key, event.target.value)
                                }
                                aria-invalid={!!form.errors[key]}
                                aria-describedby={`content-${key}-error`}
                            />
                            <div id={`content-${key}-error`}>
                                <InputError message={form.errors[key]} />
                            </div>
                            {key === 'slug' && (
                                <p className="cms-help">
                                    Letras minúsculas, números y guiones.
                                    Cambiar una dirección publicada puede dejar
                                    el enlace anterior sin contenido.
                                </p>
                            )}
                        </div>
                    ))}
                    <div className="cms-field">
                        <Label htmlFor="content-body">Contenido</Label>
                        <textarea
                            id="content-body"
                            rows={12}
                            maxLength={30000}
                            value={form.data.body}
                            onChange={(event) =>
                                form.setData('body', event.target.value)
                            }
                            aria-invalid={!!form.errors.body}
                            aria-describedby="content-body-error"
                        />
                        <p className="cms-help">
                            Texto con párrafos separados por una línea en
                            blanco. Se muestra como texto, sin ejecutar HTML.
                        </p>
                        <div id="content-body-error">
                            <InputError message={form.errors.body} />
                        </div>
                    </div>
                    {kind === 'PORTFOLIO' && (
                        <fieldset className="cms-field">
                            <legend>Servicios relacionados</legend>
                            {services.map((service) => (
                                <label
                                    className="cms-checkbox"
                                    key={service.id}
                                >
                                    <input
                                        type="checkbox"
                                        checked={form.data.service_ids.includes(
                                            service.id,
                                        )}
                                        onChange={(event) =>
                                            form.setData(
                                                'service_ids',
                                                event.target.checked
                                                    ? [
                                                          ...form.data
                                                              .service_ids,
                                                          service.id,
                                                      ]
                                                    : form.data.service_ids.filter(
                                                          (id) =>
                                                              id !== service.id,
                                                      ),
                                            )
                                        }
                                    />
                                    <span>
                                        {service.title}
                                        {!service.published_at && ' · Borrador'}
                                    </span>
                                </label>
                            ))}
                            <InputError message={form.errors.service_ids} />
                        </fieldset>
                    )}
                    <fieldset className="cms-media">
                        <legend>Imágenes elegidas para la web pública</legend>
                        <p className="cms-help">
                            Hasta ocho JPG, PNG o WebP; máximo 6 MB y 6000 ×
                            6000 px por imagen. Las imágenes nuevas permanecen
                            privadas hasta publicar.
                        </p>
                        {media.map((image) => (
                            <div className="cms-media-item" key={image.id}>
                                <img
                                    src={image.url}
                                    alt={image.alt}
                                    width={120}
                                    height={90}
                                />
                                <div>
                                    <Label htmlFor={`media-alt-${image.id}`}>
                                        Descripción de la imagen
                                    </Label>
                                    <Input
                                        id={`media-alt-${image.id}`}
                                        value={
                                            form.data.media_alt[image.id] ??
                                            image.alt
                                        }
                                        onChange={(event) =>
                                            form.setData('media_alt', {
                                                ...form.data.media_alt,
                                                [image.id]: event.target.value,
                                            })
                                        }
                                    />
                                    <label className="cms-checkbox">
                                        <input
                                            type="checkbox"
                                            checked={form.data.remove_media_ids.includes(
                                                image.id,
                                            )}
                                            onChange={(event) =>
                                                form.setData(
                                                    'remove_media_ids',
                                                    event.target.checked
                                                        ? [
                                                              ...form.data
                                                                  .remove_media_ids,
                                                              image.id,
                                                          ]
                                                        : form.data.remove_media_ids.filter(
                                                              (id) =>
                                                                  id !==
                                                                  image.id,
                                                          ),
                                                )
                                            }
                                        />
                                        Retirar del borrador
                                    </label>
                                </div>
                            </div>
                        ))}
                        <Label htmlFor="content-images">Añadir imágenes</Label>
                        <Input
                            key={fileKey}
                            id="content-images"
                            type="file"
                            accept="image/jpeg,image/png,image/webp"
                            multiple
                            onChange={(event) =>
                                form.setData(
                                    'images',
                                    Array.from(event.target.files ?? []),
                                )
                            }
                            aria-invalid={!!form.errors.images}
                        />
                        <Label htmlFor="content-image-alt">
                            Descripción inicial de las nuevas imágenes
                        </Label>
                        <Input
                            id="content-image-alt"
                            value={form.data.image_alt}
                            onChange={(event) =>
                                form.setData('image_alt', event.target.value)
                            }
                        />
                        <InputError
                            message={
                                form.errors.images ||
                                form.errors[
                                    'images.0' as keyof typeof form.errors
                                ] ||
                                form.errors.image_alt
                            }
                        />
                    </fieldset>
                    <InputError message={form.errors.lock_version} />
                </form>
                <aside className="cms-editor-aside">
                    <section className="cms-panel">
                        <h2>Presentación</h2>
                        <Label htmlFor="content-position">Orden</Label>
                        <Input
                            id="content-position"
                            type="number"
                            min={0}
                            max={10000}
                            value={form.data.position}
                            onChange={(event) =>
                                form.setData(
                                    'position',
                                    Number(event.target.value),
                                )
                            }
                        />
                        <label className="cms-checkbox">
                            <input
                                type="checkbox"
                                checked={form.data.featured}
                                onChange={(event) =>
                                    form.setData(
                                        'featured',
                                        event.target.checked,
                                    )
                                }
                            />
                            Destacar en la portada
                        </label>
                        <label className="cms-checkbox">
                            <input
                                type="checkbox"
                                checked={form.data.is_example}
                                onChange={(event) =>
                                    form.setData(
                                        'is_example',
                                        event.target.checked,
                                    )
                                }
                            />
                            Identificar como contenido provisional
                        </label>
                        {kind === 'PORTFOLIO' && (
                            <>
                                <Label htmlFor="content-art">
                                    Arte de ejemplo
                                </Label>
                                <select
                                    id="content-art"
                                    value={form.data.art_style}
                                    onChange={(event) =>
                                        form.setData(
                                            'art_style',
                                            event.target.value as
                                                | 'orbit'
                                                | 'forma',
                                        )
                                    }
                                >
                                    <option value="orbit">
                                        Orbital / violeta
                                    </option>
                                    <option value="forma">
                                        Escultórico / cálido
                                    </option>
                                </select>
                                <p className="cms-help">
                                    Los casos reales necesitan al menos una
                                    imagen elegida para publicación.
                                </p>
                            </>
                        )}
                    </section>
                    <section
                        className="cms-panel"
                        id="content-publication"
                        tabIndex={-1}
                    >
                        <h2>Publicación</h2>
                        <p className="cms-help">
                            {entry?.published_at
                                ? entry.published_version === entry.lock_version
                                    ? 'La versión guardada está publicada.'
                                    : 'La web conserva la versión anterior. Hay cambios en borrador.'
                                : 'Este contenido todavía no aparece en la web.'}
                        </p>
                        <Button
                            disabled={
                                !entry ||
                                publication.processing ||
                                form.processing ||
                                form.isDirty
                            }
                            onClick={() => publish(true)}
                        >
                            Publicar versión guardada
                        </Button>
                        {entry?.published_at && (
                            <Button
                                variant="outline"
                                disabled={
                                    publication.processing || form.processing
                                }
                                onClick={() => publish(false)}
                            >
                                Retirar publicación
                            </Button>
                        )}
                        <p className="cms-help">
                            Guarda primero. Publicar y retirar pueden pedir
                            confirmar tu contraseña.
                        </p>
                        {Object.values(publication.errors).map((error) => (
                            <p role="alert" className="cms-error" key={error}>
                                {error}
                            </p>
                        ))}
                    </section>
                    <section className="cms-panel">
                        <h2>Historial</h2>
                        {changes.map((change) => (
                            <p className="cms-help" key={change.id}>
                                {
                                    (
                                        {
                                            'draft.created': 'Borrador creado',
                                            'draft.updated':
                                                'Borrador actualizado',
                                            'content.published': 'Publicado',
                                            'content.unpublished':
                                                'Publicación retirada',
                                        } as Record<string, string>
                                    )[change.event]
                                }{' '}
                                · v{change.metadata.version}
                            </p>
                        ))}
                    </section>
                </aside>
            </div>
        </div>
    );
}
