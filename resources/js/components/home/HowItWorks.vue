<script setup>
import { ref } from 'vue';
import { useGsapContext } from '../../composables/useGsapContext';

const root = ref(null);
const steps = [
    'Выберите сувенир',
    'Загрузите фото',
    'Загрузите видео',
    'При необходимости добавьте музыку',
    'Согласуйте заказ с менеджером',
    'Получите готовый подарок с QR-кодом',
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
                <div><p class="eyebrow">04 — Как это работает</p><h2>От личного момента<br>до подарка с QR-кодом</h2></div>
                <a href="/souvenirs">Выбрать формат <span>→</span></a>
            </div>
            <div class="process-line" aria-hidden="true"><i></i></div>
            <div class="process-track">
                <article v-for="(step, index) in steps" :key="step" class="process-card">
                    <span>0{{ index + 1 }}</span>
                    <h3>{{ step }}</h3>
                    <p v-if="index === 0">Начните с фото, кружки, майки, шоколада или трек-пластинки.</p>
                    <p v-else-if="index === 4">Менеджер проверит материалы и подтвердит детали до оформления.</p>
                    <p v-else>Шаг собирает вашу историю в аккуратный персональный подарок.</p>
                </article>
            </div>
        </div>
    </section>
</template>
