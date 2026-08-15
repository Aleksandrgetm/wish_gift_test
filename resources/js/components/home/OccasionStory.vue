<script setup>
import { ref } from 'vue';
import { useGsapContext } from '../../composables/useGsapContext';

const root = ref(null);
const stories = [
    { number: '01', title: 'Foto ar QR video', subtitle: 'Vairāk nekā viens mirklis', text: 'Fotogrāfija, kas glabā vairāk nekā vienu mirkli.', tone: 'winter', mark: 'PHOTO', cta: 'Apskatīt foto dāvanas' },
    { number: '02', title: 'Šokolāde ar QR video', subtitle: 'Apsveikums dizaina iekšpusē', text: 'Personalizēta dāvana ar jūsu foto, video un apsveikumu.', tone: 'march', mark: 'CHOCO', cta: 'Apskatīt šokolādi' },
    { number: '03', title: 'Krūze ar QR video', subtitle: 'Rīts ar atmiņu', text: 'Katrs rīts sākas ar atmiņu.', tone: 'easter', mark: 'MUG', cta: 'Apskatīt krūzes' },
    { number: '04', title: 'T-krekls ar QR video', subtitle: 'Stāsts, ko nēsā līdzi', text: 'Personīgs stāsts, ko var nēsāt.', tone: 'wedding', mark: 'TEE', cta: 'Apskatīt T-kreklus' },
    { number: '05', title: 'Dziesmas plāksne', subtitle: 'Mūzika, foto un video', text: 'Foto, video un mīļākā mūzika vienā dāvanā.', tone: 'business', mark: 'TRACK', cta: 'Apskatīt plāksnes' },
];

useGsapContext(root, ({ gsap, media, reduceMotion }) => {
    if (reduceMotion) return;
    media.add('(min-width: 1001px)', () => {
        const panels = gsap.utils.toArray('.occasion-panel');
        const timeline = gsap.timeline({ scrollTrigger: { trigger: root.value, start: 'top top', end: `+=${panels.length * 110}%`, pin: '.occasion-pin', scrub: 1, anticipatePin: 1 } });
        panels.forEach((panel, index) => {
            if (index === 0) return;
            timeline.fromTo(panel, { clipPath: 'inset(100% 0 0 0)' }, { clipPath: 'inset(0% 0 0 0)', duration: 1 }, index - 0.12)
                .from(panel.querySelectorAll('.occasion-kicker, h2, h3, p'), { y: 48, opacity: 0, stagger: 0.08, duration: 0.55 }, index)
                .from(panel.querySelector('.occasion-package'), { y: 100, rotate: -12, scale: 0.78, duration: 0.8 }, index - 0.05);
        });
        gsap.to('.occasion-progress i', { scaleX: 1, transformOrigin: 'left', ease: 'none', scrollTrigger: { trigger: root.value, start: 'top top', end: `+=${panels.length * 110}%`, scrub: true } });
    });
    media.add('(max-width: 1000px)', () => {
        gsap.utils.toArray('.occasion-panel').forEach((panel) => gsap.from(panel, { y: 50, opacity: 0, duration: 0.8, scrollTrigger: { trigger: panel, start: 'top 84%', once: true } }));
    });
});
</script>

<template>
    <section ref="root" class="occasion-story">
        <div class="occasion-pin">
            <div class="occasion-label">02 — Suvenīri ar QR video</div>
            <article v-for="story in stories" :key="story.title" class="occasion-panel" :class="`occasion-${story.tone}`">
                <div class="shell occasion-layout">
                    <div class="occasion-copy"><span class="occasion-kicker">{{ story.number }} / 05</span><h2>{{ story.title }}</h2><h3>{{ story.subtitle }}</h3><p>{{ story.text }}</p><a class="story-link" href="/souvenirs">{{ story.cta }} <span>→</span></a></div>
                    <div class="occasion-scene" aria-hidden="true"><span class="occasion-ghost">{{ story.mark }}</span><div class="occasion-package"><i></i><b>QR</b><small>{{ story.mark }}</small></div><div class="occasion-disc"></div></div>
                </div>
            </article>
            <div class="occasion-progress"><i></i></div>
        </div>
    </section>
</template>
