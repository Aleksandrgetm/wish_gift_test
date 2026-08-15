<script setup>
import { ref } from 'vue';
import { useGsapContext } from '../../composables/useGsapContext';

const root = ref(null);
const track = ref(null);
const collections = [
    { label: 'Galvenā kolekcija', number: '01', title: 'Suvenīri ar QR video', detail: 'Foto, krūzes, T-krekli, šokolāde un citas dāvanas ar QR video.', price: 'no 20 €', style: 'heritage' },
    { label: 'Ātra izvēle', number: '02', title: 'Dāvanas pēc notikuma', detail: 'Izvēlieties dāvanu pēc svētkiem vai saņēmēja.', price: 'atlase', style: 'botanica' },
    { label: 'Tuvajiem', number: '03', title: 'Dāvanas tuvajiem', detail: 'Vīrietim, sievietei, bērnam, skolotājam vai ārstam.', price: 'individuāli', style: 'mono' },
    { label: 'Notikumi', number: '04', title: 'Svētku notikumi', detail: 'Jaunais gads, 14. februāris, 8. marts, Mātes diena un citi datumi.', price: 'pēc pieprasījuma', style: 'afternoon' },
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
        <div class="shell collection-header"><div><p class="eyebrow">03 — Klientu izvēle</p><h2>Dāvanas,<br><em>radītas atdzīvoties</em></h2></div><p>Četri virzieni palīdz ātri izvēlēties formātu: pēc suvenīra, notikuma, saņēmēja vai personīgā stāsta.</p></div>
        <div ref="track" class="collection-track">
            <article v-for="item in collections" :key="item.title" class="product-card tilt-card">
                <div class="product-visual" :class="item.style"><span>{{ item.label }}</span><span class="product-number">{{ item.number }}</span><div class="mini-package"><b>QR</b><small>{{ item.title }}</small></div></div>
                <div class="product-info"><div><h3>{{ item.title }}</h3><p>{{ item.detail }}</p></div><strong>{{ item.price }}</strong></div>
                <v-btn class="product-action" icon="mdi-arrow-top-right" variant="outlined" aria-label="Atvērt kolekciju" />
            </article>
            <a class="collection-end" href="/souvenirs"><span>Apskatīt visus<br>QR suvenīrus</span><i>→</i></a>
        </div>
    </section>
</template>
