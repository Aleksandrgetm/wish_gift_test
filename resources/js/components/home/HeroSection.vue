<script setup>
import { ref } from 'vue';
import { useGsapContext } from '../../composables/useGsapContext';
import { useMagnetic } from '../../composables/useMagnetic';

const root = ref(null);
const primaryCta = ref(null);
useMagnetic(primaryCta);

useGsapContext(root, ({ gsap, media, reduceMotion }) => {
    if (reduceMotion) return;
    const intro = gsap.timeline({ defaults: { ease: 'power3.out' } });
    intro.from('.hero-eyebrow', { yPercent: 120, opacity: 0, duration: 0.65 })
        .from('.hero-line > span', { yPercent: 115, rotate: 2, duration: 1.05, stagger: 0.1 }, '-=.35')
        .from('.hero-lead, .hero-actions, .hero-proof', { y: 24, opacity: 0, duration: 0.75, stagger: 0.1 }, '-=.6')
        .from('.hero-image-wrap', { y: 42, scale: 0.96, opacity: 0, duration: 1.05 }, '-=.85');

    media.add('(min-width: 1001px)', () => {
        gsap.timeline({ scrollTrigger: { trigger: root.value, start: 'top top', end: 'bottom top', scrub: 1.2 } })
            .to('.hero-copy', { yPercent: 28, opacity: 0.25 }, 0)
            .to('.hero-image-wrap', { yPercent: -10, scale: 1.02 }, 0)
            .to('.hero-wipe', { scaleY: 1 }, 0.45);
    });
});
</script>

<template>
    <section ref="root" class="hero">
        <div class="shell hero-grid">
            <div class="hero-copy">
                <div class="hero-eyebrow-mask"><p class="eyebrow hero-eyebrow">Personalizētas dāvanas · QR video</p></div>
                <h1 class="section-title hero-title">
                    <span class="hero-line"><span>Dāvanas, </span></span>
                    <span class="hero-line"><span>kas </span></span>
                    <span class="hero-line"><span><em>atdzīvojas</em></span></span>
                </h1>
                <p class="hero-lead">Foto, video un īpaši mirkļi pārtop personalizētā dāvanā. Noskenē QR kodu, un dāvana atdzīvojas.</p>
                <div class="hero-actions">
                    <span ref="primaryCta" class="magnetic-wrap"><v-btn color="primary" size="large" to="/souvenirs" append-icon="mdi-arrow-right">Izveidot dāvanu</v-btn></span>
                    <v-btn class="hero-secondary-cta" variant="outlined" size="large" to="/catalog">Apskatīt katalogu</v-btn>
                </div>
                <div class="hero-proof"><strong>QR</strong><span>Foto + video + mūzika<small>Maketu saskaņojam pirms izgatavošanas</small></span></div>
            </div>
            <figure class="hero-image-stage">
                <img class="hero-image-wrap" :src="'/images/Hero/hero.png'" alt="Wish Gift personalizēta dāvana ar QR video">
            </figure>
        </div>
        <div class="scroll-cue"><span>RITINIET, LAI REDZĒTU, KĀ TAS STRĀDĀ</span><i></i></div>
        <div class="hero-wipe"></div>
    </section>
</template>
