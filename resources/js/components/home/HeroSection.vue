<script setup>
import { ref } from 'vue';
import { useGsapContext } from '../../composables/useGsapContext';
import { useMagnetic } from '../../composables/useMagnetic';

const root = ref(null);
const stage = ref(null);
const primaryCta = ref(null);
useMagnetic(primaryCta);

useGsapContext(root, ({ gsap, media, reduceMotion }) => {
    if (reduceMotion) return;
    const intro = gsap.timeline({ defaults: { ease: 'power3.out' } });
    intro.from('.hero-eyebrow', { yPercent: 120, opacity: 0, duration: 0.65 })
        .from('.hero-line > span', { yPercent: 115, rotate: 2, duration: 1.05, stagger: 0.1 }, '-=.35')
        .from('.hero-lead, .hero-actions, .hero-proof', { y: 24, opacity: 0, duration: 0.75, stagger: 0.1 }, '-=.6')
        .from('.gift-box', { y: 90, scale: 0.82, opacity: 0, rotate: 0, duration: 1.2, stagger: 0.12 }, '-=1')
        .from('.stage-note, .hero-orbit', { opacity: 0, duration: 0.7 }, '-=.5');

    media.add('(min-width: 1001px)', () => {
        gsap.timeline({ scrollTrigger: { trigger: root.value, start: 'top top', end: 'bottom top', scrub: 1.2 } })
            .to('.hero-copy', { yPercent: 28, opacity: 0.25 }, 0)
            .to('.box-tall', { yPercent: -24, rotate: 8 }, 0)
            .to('.box-round', { yPercent: -8, rotate: -11 }, 0)
            .to('.box-flat', { yPercent: -34, scale: 1.08 }, 0)
            .to('.hero-wipe', { scaleY: 1 }, 0.45);

        const x = gsap.quickTo('.gift-stage-inner', 'x', { duration: 1.1, ease: 'power3.out' });
        const y = gsap.quickTo('.gift-stage-inner', 'y', { duration: 1.1, ease: 'power3.out' });
        const move = (event) => {
            const rect = stage.value.getBoundingClientRect();
            x(((event.clientX - rect.left) / rect.width - 0.5) * 18);
            y(((event.clientY - rect.top) / rect.height - 0.5) * 14);
        };
        stage.value.addEventListener('pointermove', move);
        return () => stage.value?.removeEventListener('pointermove', move);
    });
});
</script>

<template>
    <section ref="root" class="hero">
        <div class="hero-orbit orbit-one"></div><div class="hero-orbit orbit-two"></div>
        <div class="shell hero-grid">
            <div class="hero-copy">
                <div class="hero-eyebrow-mask"><p class="eyebrow hero-eyebrow">Personalizēti suvenīri · QR video</p></div>
                <h1 aria-label="Dāvanas, kas atdzīvojas">
                    <span class="hero-line"><span>Dāvanas,</span></span>
                    <span class="hero-line"><span>kas</span></span>
                    <span class="hero-line"><span><em>atdzīvojas</em></span></span>
                </h1>
                <p class="hero-lead">Foto, video un personīgi mirkļi pārtop suvenīros ar īstu emociju. Pavērsiet kameru pret QR kodu, un fotogrāfija atdzīvosies.</p>
                <div class="hero-actions">
                    <span ref="primaryCta" class="magnetic-wrap"><v-btn color="primary" size="large" to="/souvenirs" append-icon="mdi-arrow-right">Izveidot dāvanu</v-btn></span>
                    <v-btn class="link-underline" variant="text" size="large" href="#collections">Apskatīt dāvanas</v-btn>
                </div>
                <div class="hero-proof"><strong>QR</strong><span>Foto + video<small>Personalizēta dāvana tiek saskaņota pirms noformēšanas</small></span></div>
            </div>
            <div ref="stage" class="gift-stage" aria-label="Dāvanu kartīte ar QR kodu un personīgu video" role="img">
                <div class="gift-stage-inner">
                    <div class="stage-note">Jūsu foto<br><strong>kļūst par stāstu</strong></div>
                    <div class="gift-box box-tall"><span>QR VIDEO</span><i></i></div>
                    <div class="gift-box box-round qr-orb"><span>play</span></div>
                    <div class="gift-box box-flat qr-card"><span>SCAN<br>MEMORY</span><i></i><b></b></div>
                </div>
            </div>
        </div>
        <div class="scroll-cue"><span>RITINIET, LAI ATKLĀTU</span><i></i></div>
        <div class="hero-wipe"></div>
    </section>
</template>
