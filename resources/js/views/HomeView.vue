<script setup>
import { ref } from 'vue';
import HeroSection from '../components/home/HeroSection.vue';
import OzhivaykaExplainer from '../components/home/OzhivaykaExplainer.vue';
import ProductExamples from '../components/home/ProductExamples.vue';
import GiftChooser from '../components/home/GiftChooser.vue';
import OccasionStory from '../components/home/OccasionStory.vue';
import HowItWorks from '../components/home/HowItWorks.vue';
import PackageFinder from '../components/home/PackageFinder.vue';
import BenefitsSection from '../components/home/BenefitsSection.vue';
import FaqSection from '../components/home/FaqSection.vue';
import FinalCta from '../components/home/FinalCta.vue';
import { useGsapContext } from '../composables/useGsapContext';

const root = ref(null);
useGsapContext(root, ({ gsap, media, reduceMotion }) => {
    if (reduceMotion) return;
    media.add('(min-width: 768px)', () => {
        gsap.utils.toArray('.motion-reveal').forEach((element) => {
            gsap.from(element, { y: 54, opacity: 0, duration: 1, ease: 'power3.out', scrollTrigger: { trigger: element, start: 'top 86%', once: true } });
        });
    });
});
</script>

<template>
    <main ref="root" class="home-experience">
        <HeroSection />
        <OzhivaykaExplainer />
        <ProductExamples />
        <GiftChooser />
        <OccasionStory />
        <HowItWorks />
        <PackageFinder />
        <BenefitsSection />
        <FaqSection />
        <FinalCta />
    </main>
</template>
