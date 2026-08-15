<script setup>
import { ref } from 'vue';
import { useGsapContext } from '../../composables/useGsapContext';

const root = ref(null);
const steps = [
    ['01', 'Dāvana', 'Foto, šokolāde, kartīte, krūze vai cits suvenīrs kļūst par personīgu stāstu.'],
    ['02', 'QR kods', 'Maketa dizainā iekļaujam QR kodu, kas atver jūsu video, mūziku vai novēlējumu.'],
    ['03', 'Telefons', 'Saņēmējs noskenē QR kodu ar telefonu un uzreiz atver paslēpto vēstījumu.'],
    ['04', 'Emocija', 'Dāvana atdzīvojas: parādās video, mīļākā dziesma, karikatūra vai silts apsveikums.'],
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
                <p class="eyebrow">Kas ir dzīvā dāvana</p>
                <h2 id="ozhivayka-title" class="section-title">Dāvana, kas atver personīgu atmiņu</h2>
                <p class="section-lead">Mēs savienojam īstu suvenīru ar QR kodu: cilvēks saņem dāvanu, noskenē to ar telefonu un redz jūsu video, mūziku, karikatūru vai apsveikumu.</p>
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
            <div class="ozhivayka-visual" aria-label="Suvenīrs ar QR kodu atver video telefonā" role="img">
                <div class="ozhivayka-product">
                    <img :src="'/images/Products/день матери 4.png'" alt="Personalizētas dāvanas piemērs ar QR kodu" loading="lazy">
                    <span>Īsta dāvana</span>
                </div>
                <div class="ozhivayka-phone">
                    <div class="phone-speaker"></div>
                    <div class="phone-preview">
                        <img :src="'/images/Products/день учителя.png'" alt="" loading="lazy">
                        <div class="phone-play"><v-icon icon="mdi-play" size="24" /></div>
                    </div>
                    <p>Video + mūzika</p>
                </div>
                <div class="ozhivayka-qr" aria-hidden="true">
                    <i v-for="cell in 9" :key="cell"></i>
                    <b class="ozhivayka-scan-line"></b>
                </div>
            </div>
        </div>
    </section>
</template>
