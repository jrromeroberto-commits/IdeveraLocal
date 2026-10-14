import orbitImage from '../../../../assets/plates/orbit-image.webp';
import formaImage from '../../../../assets/plates/forma-image.webp';

/** Studio concepts, never a substitute for a real client's published media. */
export function ConceptArtwork({ kind }: { kind: 'orbit' | 'forma' }) {
    return (
        <img
            className="concept-image"
            src={kind === 'forma' ? formaImage : orbitImage}
            width={kind === 'forma' ? 1448 : 1879}
            height={kind === 'forma' ? 1086 : 837}
            loading="lazy"
            alt={
                kind === 'forma'
                    ? 'Exploración FORMA: piedra, luz y espacio arquitectónico'
                    : 'Exploración ORBIT: escultura orbital con luz violeta'
            }
        />
    );
}
