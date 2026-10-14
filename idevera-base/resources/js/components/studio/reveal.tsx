import { useEffect, useRef, type ReactNode } from 'react';

export function Reveal({
    children,
    className = '',
}: {
    children: ReactNode;
    className?: string;
}) {
    const ref = useRef<HTMLDivElement>(null);
    useEffect(() => {
        const node = ref.current;
        if (
            !node ||
            !('IntersectionObserver' in window) ||
            window.matchMedia('(prefers-reduced-motion: reduce)').matches
        )
            return;
        if (node.getBoundingClientRect().top > window.innerHeight)
            node.classList.add('reveal-wait');
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    node.classList.remove('reveal-wait');
                    observer.disconnect();
                }
            },
            { threshold: 0.08 },
        );
        observer.observe(node);
        return () => observer.disconnect();
    }, []);
    return (
        <div ref={ref} className={`studio-reveal ${className}`}>
            {children}
        </div>
    );
}
