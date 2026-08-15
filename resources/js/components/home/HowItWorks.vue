<script setup>
import { ref } from 'vue';
import { useGsapContext } from '../../composables/useGsapContext';

const root = ref(null);
const steps = [
    'Izvēlieties suvenīru',
    'Augšupielādējiet foto',
    'Augšupielādējiet video',
    'Ja nepieciešams, pievienojiet mūziku',
    'Saskaņojiet pasūtījumu ar menedžeri',
    'Saņemiet gatavu dāvanu ar QR kodu',
];

useGsapContext(root, ({ gsap, media, reduceMotion }) => {
    if (reduceMotion) return;
    media.add('(min-width: 768px)', () => {
        gsap.from('.process-card', {
            y: 48,
            opacity: 0,
            duration: 0.85,
            stagger: 0.08,
            ease: 'power3.out',
            scrollTrigger: { trigger: root.value, start: 'top 78%', once: true },
        });
        gsap.from('.process-line i', {
            scaleX: 0,
            transformOrigin: 'left',
            ease: 'none',
            scrollTrigger: { trigger: root.value, start: 'top 82%', end: 'bottom 70%', scrub: 1 },
        });
    });
});
</script>

<template>
    <section id="how-it-works" ref="root" class="section process-section motion-reveal">
        <div class="shell">
            <div class="section-heading">
                <div><p class="eyebrow">04 — Kā tas darbojas</p><h2>No personīga mirkļa<br>līdz dāvanai ar QR kodu</h2></div>
                <a href="/souvenirs">Izvēlēties formātu <span>→</span></a>
            </div>
            <div class="process-line" aria-hidden="true"><i></i></div>
            <div class="process-track">
                <article v-for="(step, index) in steps" :key="step" class="process-card">
                    <span>0{{ index + 1 }}</span>
                    <h3>{{ step }}</h3>
                    <p v-if="index === 0">Sāciet ar foto, krūzi, T-kreklu, šokolādi vai dziesmas plāksni.</p>
                    <p v-else-if="index === 4">Menedžeris pārbaudīs materiālus un apstiprinās detaļas pirms noformēšanas.</p>
                    <p v-else>Katrs solis veido jūsu stāstu rūpīgi sagatavotā personalizētā dāvanā.</p>
                </article>
            </div>
        </div>
    </section>
</template>
