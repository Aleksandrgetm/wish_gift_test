<script setup>
import { ref } from 'vue';
import { useGsapContext } from '../../composables/useGsapContext';

const root = ref(null);
useGsapContext(root, ({ gsap, media, reduceMotion }) => {
    if (reduceMotion) return;
    media.add('(min-width: 768px)', () => {
        gsap.fromTo('.statement-word', { color: '#B4AFBA' }, { color: '#2B1740', stagger: 0.16, scrollTrigger: { trigger: root.value, start: 'top 72%', end: 'bottom 52%', scrub: 1 } });
        gsap.fromTo('.statement-line', { scaleX: 0 }, { scaleX: 1, transformOrigin: 'left', scrollTrigger: { trigger: root.value, start: 'top 70%', end: 'bottom 60%', scrub: 1 } });
    });
});
</script>

<template>
    <section ref="root" class="brand-statement shell" aria-label="Философия бренда">
        <span class="statement-index">01 — Философия</span>
        <p><span class="statement-word">Мы</span> <span class="statement-word">создаём</span> <span class="statement-word">не просто</span> <span class="statement-word"><em>сувениры.</em></span><br><span class="statement-word">Мы сохраняем</span> <span class="statement-word">личные</span> <span class="statement-word">моменты</span> <span class="statement-word">в подарках.</span></p>
        <i class="statement-line"></i>
    </section>
</template>
