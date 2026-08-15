<script setup>
import { ref } from 'vue';
import { useGsapContext } from '../../composables/useGsapContext';

const root = ref(null);
const steps = [
    ['Выберите подарок', 'Фото, кружка, футболка, шоколад, брелок, открытка или музыкальная пластинка.'],
    ['Отправьте фото и видео', 'Передайте материалы, которые должны стать частью личной истории.'],
    ['Добавьте музыку или пожелание', 'Можно добавить песню, надпись, шарж или короткое поздравление.'],
    ['Согласуйте макет', 'Мы проверяем материалы и согласуем дизайн перед изготовлением.'],
    ['Получите готовый подарок', 'Сувенир приходит с QR-кодом, который открывает ваше послание.'],
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
                    <p class="eyebrow">Как заказать</p>
                    <h2 id="process-title">От идеи до готового подарка без лишней суеты</h2>
                </div>
                <a href="#finder">Подобрать формат <span>→</span></a>
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
