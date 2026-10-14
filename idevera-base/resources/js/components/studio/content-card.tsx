import { Link } from '@inertiajs/react';
import { ArrowUpRight } from 'lucide-react';
import { ConceptArtwork } from '@/components/studio/artwork';
import type { PublicEntry } from '@/types/content';

export function ContentCard({ entry }: { entry: PublicEntry }) {
    return (
        <article className="work-item">
            <Link href={entry.url} className="work-trigger content-card-link">
                {entry.media[0] ? (
                    <img
                        className="case-cover"
                        src={entry.media[0].url}
                        alt={entry.media[0].alt}
                        width={entry.media[0].width}
                        height={entry.media[0].height}
                        loading="lazy"
                    />
                ) : entry.is_example ? (
                    <ConceptArtwork kind={entry.art_style} />
                ) : null}
                <div className="work-meta">
                    <div>
                        <h3>{entry.title}</h3>
                        <p>{entry.category || entry.excerpt}</p>
                    </div>
                    <span className="round-arrow">
                        <ArrowUpRight />
                    </span>
                </div>
            </Link>
            {entry.is_example && (
                <p className="concept-label">
                    EXPLORACIÓN VISUAL · PROYECTO CONCEPTUAL
                </p>
            )}
        </article>
    );
}
