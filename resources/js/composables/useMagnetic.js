import { onBeforeUnmount, onMounted } from 'vue';

export function useMagnetic(target, strength = 0.22) {
    let move;
    let leave;

    onMounted(async () => {
        if (!target.value || window.matchMedia('(prefers-reduced-motion: reduce), (pointer: coarse)').matches) return;
        const { default: gsap } = await import('gsap');
        const xTo = gsap.quickTo(target.value, 'x', { duration: 0.45, ease: 'power3.out' });
        const yTo = gsap.quickTo(target.value, 'y', { duration: 0.45, ease: 'power3.out' });
        move = (event) => {
            const rect = target.value.getBoundingClientRect();
            xTo((event.clientX - rect.left - rect.width / 2) * strength);
            yTo((event.clientY - rect.top - rect.height / 2) * strength);
        };
        leave = () => { xTo(0); yTo(0); };
        target.value.addEventListener('pointermove', move);
        target.value.addEventListener('pointerleave', leave);
    });

    onBeforeUnmount(() => {
        target.value?.removeEventListener('pointermove', move);
        target.value?.removeEventListener('pointerleave', leave);
    });
}
