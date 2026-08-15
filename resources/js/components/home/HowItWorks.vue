<script setup>
import { ref } from 'vue';
import { useGsapContext } from '../../composables/useGsapContext';

const root = ref(null);
const steps = [
    ['Izvēlies dāvanu', 'Foto, krūze, T-krekls, šokolāde, piekariņš, kartīte vai dziesmas plāksne.'],
    ['Nosūti foto un video', 'Atsūtiet materiālus, kas kļūs par daļu no personīga stāsta.'],
    ['Pievieno mūziku vai novēlējumu', 'Var pievienot dziesmu, uzrakstu, karikatūru vai īsu apsveikumu.'],
    ['Saskaņo maketu', 'Pirms izgatavošanas pārbaudām materiālus un saskaņojam dizainu.'],
    ['Saņem gatavo dāvanu', 'Suvenīrs tiek sagatavots ar QR kodu, kas atver jūsu vēstījumu.'],
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
    <section id="how-it-works" ref="root" class="section process-section motion-reveal" aria-labelledby="process-title">
        <div class="shell">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Kā pasūtīt</p>
                    <h2 id="process-title" class="section-title">No idejas līdz gatavai dāvanai bez liekas steigas</h2>
                </div>
                <a href="#finder">Izvēlēties formātu <span>→</span></a>
            </div>
            <div class="process-line" aria-hidden="true"><i></i></div>
            <div class="process-track process-journey">
                <article v-for="(step, index) in steps" :key="step[0]" class="process-card">
                    <span>0{{ index + 1 }}</span>
                    <h3>{{ step[0] }}</h3>
                    <p>{{ step[1] }}</p>
                </article>
            </div>
        </div>
    </section>
</template>
