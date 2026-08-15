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
    <section ref="root" class="brand-statement shell" aria-label="Zīmola filozofija">
        <span class="statement-index">01 — Filozofija</span>
        <p><span class="statement-word">Mēs</span> <span class="statement-word">radām</span> <span class="statement-word">ne tikai</span> <span class="statement-word"><em>suvenīrus.</em></span><br><span class="statement-word">Mēs saglabājam</span> <span class="statement-word">personīgus</span> <span class="statement-word">mirkļus</span> <span class="statement-word">dāvanās.</span></p>
        <i class="statement-line"></i>
    </section>
</template>
