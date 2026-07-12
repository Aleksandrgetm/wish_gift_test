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
                <div class="hero-eyebrow-mask"><p class="eyebrow hero-eyebrow">Весенняя коллекция · 2026</p></div>
                <h1 aria-label="Упаковка, которая делает подарок незабываемым">
                    <span class="hero-line"><span>Упаковка, которая</span></span>
                    <span class="hero-line"><span>делает подарок</span></span>
                    <span class="hero-line"><span><em>незабываемым</em></span></span>
                </h1>
                <p class="hero-lead">Продуманные коробки для конфет и шоколада — от одного особенного подарка до брендированного тиража.</p>
                <div class="hero-actions">
                    <span ref="primaryCta" class="magnetic-wrap"><v-btn color="primary" size="large" to="/catalog" append-icon="mdi-arrow-right">Выбрать упаковку</v-btn></span>
                    <v-btn class="link-underline" variant="text" size="large" href="#collections">Смотреть коллекции</v-btn>
                </div>
                <div class="hero-proof"><strong>4,9</strong><span aria-label="Рейтинг 4,9 из 5">★★★★★<small>Более 800 заказов с заботой о деталях</small></span></div>
            </div>
            <div ref="stage" class="gift-stage" aria-label="Композиция из подарочных коробок шоколадного, кремового и зелёного цвета" role="img">
                <div class="gift-stage-inner">
                    <div class="stage-note">Собрано вручную<br><strong>в нашей мастерской</strong></div>
                    <div class="gift-box box-tall"><span>ÉCLAT</span><i></i></div>
                    <div class="gift-box box-round"><span>pour vous</span></div>
                    <div class="gift-box box-flat"><span>COLLECTION<br>PRINTEMPS</span><i></i></div>
                </div>
            </div>
        </div>
        <div class="scroll-cue"><span>SCROLL TO DISCOVER</span><i></i></div>
        <div class="hero-wipe"></div>
    </section>
</template>
