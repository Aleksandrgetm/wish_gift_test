import { onBeforeUnmount, onMounted } from 'vue';

export function useGsapContext(root, setup) {
    let context;
    let media;
    let cancelled = false;

    onMounted(async () => {
        const [{ default: gsap }, { ScrollTrigger }] = await Promise.all([
            import('gsap'),
            import('gsap/ScrollTrigger'),
        ]);

        if (cancelled || !root.value) return;

        gsap.registerPlugin(ScrollTrigger);
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        media = gsap.matchMedia();
        context = gsap.context(() => setup({ gsap, ScrollTrigger, media, reduceMotion }), root.value);
    });

    onBeforeUnmount(() => {
        cancelled = true;
        media?.revert();
        context?.revert();
    });
}
