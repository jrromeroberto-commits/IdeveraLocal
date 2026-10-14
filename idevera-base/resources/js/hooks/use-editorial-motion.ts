import { useEffect, type RefObject } from 'react';

const easeOut = 'cubic-bezier(0.23, 1, 0.32, 1)';
const spring = { stiffness: 100, damping: 10, mass: 1 };
type Axis = { value: number; velocity: number; target: number };

function advance(axis: Axis, dt: number) {
    const force =
        spring.stiffness * (axis.target - axis.value) -
        spring.damping * axis.velocity;
    axis.velocity += (force / spring.mass) * dt;
    axis.value += axis.velocity * dt;
}

/** Marketing motion stays outside React renders and never hides default content. */
export function useEditorialMotion(
    rootRef: RefObject<HTMLDivElement | null>,
    revision: string,
) {
    useEffect(() => {
        const root = rootRef.current;
        if (!root) return;
        const reduced = matchMedia('(prefers-reduced-motion: reduce)');
        const finePointer = matchMedia('(hover: hover) and (pointer: fine)');

        function setup() {
            if (!root || reduced.matches) return () => {};
            const arrivalEase =
                getComputedStyle(root).getPropertyValue('--ease-out').trim() ||
                easeOut;
            const cleanups: (() => void)[] = [];
            const animations = new Set<Animation>();
            const animate = (
                node: HTMLElement,
                frames: Keyframe[],
                delay = 0,
            ) => {
                const animation = node.animate(frames, {
                    duration: 600,
                    easing: arrivalEase,
                    delay,
                    fill: 'backwards',
                });
                animations.add(animation);
                void animation.finished
                    .then(() => animations.delete(animation))
                    .catch(() => animations.delete(animation));
            };

            if (
                'IntersectionObserver' in window &&
                'animate' in HTMLElement.prototype
            ) {
                const observer = new IntersectionObserver(
                    (entries) => {
                        for (const entry of entries) {
                            if (!entry.isIntersecting) continue;
                            const node = entry.target as HTMLElement;
                            observer.unobserve(node);
                            if (node.dataset.motion === 'process') {
                                node.querySelectorAll<HTMLElement>(
                                    'li',
                                ).forEach((step, i) =>
                                    animate(
                                        step,
                                        [
                                            {
                                                transform: 'translateY(28px)',
                                            },
                                            {
                                                transform: 'translateY(0)',
                                            },
                                        ],
                                        i * 70,
                                    ),
                                );
                            } else if (node.dataset.motion === 'statement') {
                                node.querySelectorAll<HTMLElement>(
                                    'em',
                                ).forEach((word, i) =>
                                    animate(
                                        word,
                                        [
                                            {
                                                transform: 'translateX(20px)',
                                                opacity: 0.7,
                                            },
                                            {
                                                transform: 'translateX(0)',
                                                opacity: 1,
                                            },
                                        ],
                                        i * 70,
                                    ),
                                );
                            } else if (node.dataset.motion === 'contact') {
                                node.querySelectorAll<HTMLElement>(
                                    'span',
                                ).forEach((line, i) =>
                                    animate(line, [
                                        {
                                            transform: `translateX(${i ? 24 : -24}px)`,
                                        },
                                        { transform: 'translateX(0)' },
                                    ]),
                                );
                            } else {
                                animate(node, [
                                    { transform: 'scale(1.12)' },
                                    { transform: 'scale(1)' },
                                ]);
                            }
                        }
                    },
                    { threshold: 0.12 },
                );
                root.querySelectorAll('[data-motion]').forEach((node) =>
                    observer.observe(node),
                );
                if (!CSS.supports('animation-timeline', 'view()')) {
                    root.querySelectorAll('.editorial-case-depth').forEach(
                        (node) => observer.observe(node),
                    );
                }
                cleanups.push(() => observer.disconnect());
            }

            if (finePointer.matches) {
                root.querySelectorAll<HTMLElement>(
                    '.editorial-case-visual',
                ).forEach((visual) => {
                    const image = visual.querySelector<HTMLImageElement>('img');
                    const cursor = visual.querySelector<HTMLElement>(
                        '.editorial-case-cursor',
                    );
                    if (!image || !cursor) return;
                    let frame = 0;
                    let lastTime = 0;
                    let hovered = false;
                    let rect: DOMRect | undefined;
                    const x: Axis = { value: 0, target: 0, velocity: 0 };
                    const y: Axis = { value: 0, target: 0, velocity: 0 };
                    const zoom: Axis = {
                        value: 1.04,
                        target: 1.04,
                        velocity: 0,
                    };
                    const cursorX: Axis = {
                        value: 40,
                        target: 40,
                        velocity: 0,
                    };
                    const cursorY: Axis = {
                        value: 40,
                        target: 40,
                        velocity: 0,
                    };

                    const tick = (time: number) => {
                        const dt = Math.min(
                            (time - lastTime) / 1000 || 1 / 60,
                            0.032,
                        );
                        lastTime = time;
                        for (const axis of [x, y, zoom, cursorX, cursorY]) {
                            advance(axis, dt);
                        }
                        image.style.transform = `translate3d(${x.value}px, ${y.value}px, 0) scale(${zoom.value})`;
                        cursor.style.transform = `translate3d(${cursorX.value - 40}px, ${cursorY.value - 40}px, 0)`;
                        const moving =
                            [x, y, cursorX, cursorY].some(
                                (axis) =>
                                    Math.abs(axis.target - axis.value) > 0.1 ||
                                    Math.abs(axis.velocity) > 0.1,
                            ) ||
                            Math.abs(zoom.value - zoom.target) > 0.0002 ||
                            Math.abs(zoom.velocity) > 0.0002;
                        if (moving) frame = requestAnimationFrame(tick);
                        else frame = 0;
                    };
                    const start = () => {
                        if (!frame) {
                            lastTime = 0;
                            frame = requestAnimationFrame(tick);
                        }
                    };
                    const move = (event: PointerEvent) => {
                        if (
                            event.pointerType !== 'mouse' &&
                            event.pointerType !== 'pen'
                        )
                            return;
                        rect ??= visual.getBoundingClientRect();
                        const localX = event.clientX - rect.left;
                        const localY = event.clientY - rect.top;
                        cursorX.target = Math.max(
                            40,
                            Math.min(rect.width - 40, localX),
                        );
                        cursorY.target = Math.max(
                            40,
                            Math.min(rect.height - 40, localY),
                        );
                        x.target = (localX / rect.width - 0.5) * -24;
                        y.target = (localY / rect.height - 0.5) * -16;
                        zoom.target = 1.075;
                        if (!hovered) {
                            hovered = true;
                            cursorX.value = cursorX.target;
                            cursorY.value = cursorY.target;
                            cursorX.velocity = cursorY.velocity = 0;
                            visual.dataset.pointerActive = 'true';
                        }
                        start();
                    };
                    const leave = () => {
                        hovered = false;
                        rect = undefined;
                        delete visual.dataset.pointerActive;
                        x.target = y.target = 0;
                        zoom.target = 1.04;
                        start();
                    };
                    const invalidateRect = () => {
                        rect = undefined;
                    };
                    const suspend = () => {
                        if (!document.hidden) return;
                        cancelAnimationFrame(frame);
                        frame = 0;
                        hovered = false;
                        delete visual.dataset.pointerActive;
                        image.style.removeProperty('transform');
                        cursor.style.removeProperty('transform');
                        x.value = x.target = y.value = y.target = 0;
                        x.velocity = y.velocity = zoom.velocity = 0;
                        zoom.value = zoom.target = 1.04;
                    };
                    visual.addEventListener('pointermove', move);
                    visual.addEventListener('pointerleave', leave);
                    visual.addEventListener('pointercancel', leave);
                    window.addEventListener('scroll', invalidateRect, {
                        passive: true,
                    });
                    window.addEventListener('resize', leave);
                    document.addEventListener('visibilitychange', suspend);
                    cleanups.push(() => {
                        cancelAnimationFrame(frame);
                        visual.removeEventListener('pointermove', move);
                        visual.removeEventListener('pointerleave', leave);
                        visual.removeEventListener('pointercancel', leave);
                        window.removeEventListener('scroll', invalidateRect);
                        window.removeEventListener('resize', leave);
                        document.removeEventListener(
                            'visibilitychange',
                            suspend,
                        );
                        delete visual.dataset.pointerActive;
                        image.style.removeProperty('transform');
                        cursor.style.removeProperty('transform');
                    });
                });
            }
            return () => {
                cleanups.forEach((cleanup) => cleanup());
                animations.forEach((animation) => animation.cancel());
            };
        }

        let cleanup = setup();
        const change = () => {
            cleanup();
            cleanup = setup();
        };
        reduced.addEventListener('change', change);
        finePointer.addEventListener('change', change);
        return () => {
            cleanup();
            reduced.removeEventListener('change', change);
            finePointer.removeEventListener('change', change);
        };
    }, [rootRef, revision]);
}
