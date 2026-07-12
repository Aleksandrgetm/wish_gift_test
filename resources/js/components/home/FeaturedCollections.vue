<script setup>
import { ref } from 'vue';
import { useGsapContext } from '../../composables/useGsapContext';

const root = ref(null);
const track = ref(null);
const collections = [
    { label: 'Главная коллекция', number: '01', title: 'Сувениры-оживайки', detail: 'Фото, кружки, майки, шоколад и другие подарки с QR-видео.', price: 'от 20 €', style: 'heritage' },
    { label: 'Быстрый выбор', number: '02', title: 'Подарки по случаю', detail: 'Подберите подарок по празднику или получателю.', price: 'подбор', style: 'botanica' },
    { label: 'Для близких', number: '03', title: 'Подарки для близких', detail: 'Мужчине, женщине, ребёнку, учителю или врачу.', price: 'индивидуально', style: 'mono' },
    { label: 'Поводы', number: '04', title: 'Праздничные поводы', detail: 'Новый год, 14 февраля, 8 марта, День матери и другие даты.', price: 'по запросу', style: 'afternoon' },
];

useGsapContext(root, ({ gsap, media, reduceMotion }) => {
    if (reduceMotion) return;
    media.add('(min-width: 1001px)', () => {
        const amount = () => Math.max(0, track.value.scrollWidth - window.innerWidth + 80);
        const scrollTween = gsap.to(track.value, { x: () => -amount(), ease: 'none', scrollTrigger: { trigger: root.value, start: 'top top', end: () => `+=${amount() + window.innerHeight}`, pin: true, scrub: 1, invalidateOnRefresh: true, anticipatePin: 1 } });
        gsap.utils.toArray('.product-card').forEach((card) => {
            gsap.from(card.querySelector('.mini-package'), { scale: 1.16, yPercent: 12, scrollTrigger: { trigger: card, containerAnimation: scrollTween, start: 'left 90%', end: 'right 35%', scrub: 1 } });
        });
    });
    media.add('(max-width: 1000px)', () => {
        gsap.utils.toArray('.product-card').forEach((card) => gsap.from(card, { y: 45, opacity: 0, duration: 0.8, scrollTrigger: { trigger: card, start: 'top 86%', once: true } }));
    });
});
</script>

<template>
    <section id="collections" ref="root" class="collection-section collection-story">
        <div class="shell collection-header"><div><p class="eyebrow">03 — Выбор клиентов</p><h2>Подарки,<br><em>созданные оживать</em></h2></div><p>Четыре направления помогают быстро выбрать формат: по сувениру, поводу, получателю или личной истории.</p></div>
        <div ref="track" class="collection-track">
            <article v-for="item in collections" :key="item.title" class="product-card tilt-card">
                <div class="product-visual" :class="item.style"><span>{{ item.label }}</span><span class="product-number">{{ item.number }}</span><div class="mini-package"><b>QR</b><small>{{ item.title }}</small></div></div>
                <div class="product-info"><div><h3>{{ item.title }}</h3><p>{{ item.detail }}</p></div><strong>{{ item.price }}</strong></div>
                <v-btn class="product-action" icon="mdi-arrow-top-right" variant="outlined" aria-label="Открыть коллекцию" />
            </article>
            <a class="collection-end" href="/souvenirs"><span>Смотреть все<br>оживайки</span><i>→</i></a>
        </div>
    </section>
</template>
