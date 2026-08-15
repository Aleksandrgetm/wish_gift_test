<script setup>
import { ref } from 'vue';
import { useGsapContext } from '../../composables/useGsapContext';

const root = ref(null);
const steps = [
    ['01', 'Подарок', 'Фото, шоколад, открытка, кружка или другой сувенир становятся личной историей.'],
    ['02', 'QR-код', 'На макете появляется аккуратный QR-код, связанный с вашим видео, музыкой или пожеланием.'],
    ['03', 'Телефон', 'Получатель наводит камеру телефона и сразу открывает скрытое послание.'],
    ['04', 'Эмоция', 'Подарок оживает: появляется видео, любимая песня, шарж или тёплое поздравление.'],
];

useGsapContext(root, ({ gsap, media, reduceMotion }) => {
    if (reduceMotion) return;
    media.add('(min-width: 768px)', () => {
        gsap.from('.ozhivayka-visual', { y: 44, opacity: 0, duration: 0.75, ease: 'power2.out', scrollTrigger: { trigger: root.value, start: 'top 72%', once: true } });
        gsap.from('.ozhivayka-step', { y: 18, opacity: 0, duration: 0.45, stagger: 0.08, ease: 'power1.out', scrollTrigger: { trigger: '.ozhivayka-steps', start: 'top 82%', once: true } });
        gsap.to('.ozhivayka-scan-line', { yPercent: 180, repeat: -1, yoyo: true, duration: 2.2, ease: 'sine.inOut' });
    });
});
</script>

<template>
    <section id="ozhivayka" ref="root" class="section ozhivayka-section motion-reveal" aria-labelledby="ozhivayka-title">
        <div class="shell ozhivayka-grid">
            <div class="ozhivayka-copy">
                <p class="eyebrow">Что такое «Оживайка»</p>
                <h2 id="ozhivayka-title">Подарок, который открывает личное воспоминание</h2>
                <p class="section-lead">Мы соединяем реальный сувенир с QR-кодом: человек получает вещь, сканирует её телефоном и видит ваше видео, музыку, шарж или поздравление.</p>
                <div class="ozhivayka-steps">
                    <article v-for="step in steps" :key="step[0]" class="ozhivayka-step">
                        <span>{{ step[0] }}</span>
                        <div>
                            <h3>{{ step[1] }}</h3>
                            <p>{{ step[2] }}</p>
                        </div>
                    </article>
                </div>
            </div>
            <div class="ozhivayka-visual" aria-label="Сувенир с QR-кодом открывает видео на телефоне" role="img">
                <div class="ozhivayka-product">
                    <img :src="'/images/Products/день матери 4.png'" alt="Пример персонализированного подарка с QR-кодом" loading="lazy">
                    <span>REAL GIFT</span>
                </div>
                <div class="ozhivayka-phone">
                    <div class="phone-speaker"></div>
                    <div class="phone-preview">
                        <img :src="'/images/Products/день учителя.png'" alt="" loading="lazy">
                        <div class="phone-play"><v-icon icon="mdi-play" size="24" /></div>
                    </div>
                    <p>Видео + музыка</p>
                </div>
                <div class="ozhivayka-qr" aria-hidden="true">
                    <i v-for="cell in 9" :key="cell"></i>
                    <b class="ozhivayka-scan-line"></b>
                </div>
            </div>
        </div>
    </section>
</template>
