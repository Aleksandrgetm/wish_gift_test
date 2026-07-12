<script setup>
import { ref } from 'vue';
import { useGsapContext } from '../../composables/useGsapContext';

const root = ref(null);
const track = ref(null);
const collections = [
    { label: 'Бестселлер', number: '01', title: 'Шкатулка «Наследие»', detail: '24 конфеты · дизайнерский картон', price: 'от 1 890 ₽', style: 'heritage' },
    { label: 'Новинка', number: '02', title: 'Коллекция «Ботаника»', detail: '16 конфет · тиснение золотом', price: 'от 1 490 ₽', style: 'botanica' },
    { label: 'Для бизнеса', number: '03', title: 'Коробка «Монограмма»', detail: '12 конфет · персонализация', price: 'от 990 ₽', style: 'mono' },
    { label: 'Лимитированная', number: '04', title: 'Набор «Après-midi»', detail: '36 конфет · ручная сборка', price: 'от 2 590 ₽', style: 'afternoon' },
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
        <div class="shell collection-header"><div><p class="eyebrow">03 — Выбор клиентов</p><h2>Коллекции,<br><em>созданные впечатлять</em></h2></div><p>Четыре характера. Одна философия: упаковку хочется сохранить даже после того, как подарок открыт.</p></div>
        <div ref="track" class="collection-track">
            <article v-for="item in collections" :key="item.title" class="product-card tilt-card">
                <div class="product-visual" :class="item.style"><span>{{ item.label }}</span><span class="product-number">{{ item.number }}</span><div class="mini-package"><b>É</b><small>{{ item.title }}</small></div></div>
                <div class="product-info"><div><h3>{{ item.title }}</h3><p>{{ item.detail }}</p></div><strong>{{ item.price }}</strong></div>
                <v-btn class="product-action" icon="mdi-arrow-top-right" variant="outlined" aria-label="Открыть коллекцию" />
            </article>
            <a class="collection-end" href="/catalog"><span>Смотреть всю<br>коллекцию</span><i>→</i></a>
        </div>
    </section>
</template>
