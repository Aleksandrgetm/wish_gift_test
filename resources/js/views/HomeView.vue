<script setup>
import { ref } from 'vue';
import HeroSection from '../components/home/HeroSection.vue';
import BrandStatement from '../components/home/BrandStatement.vue';
import OccasionStory from '../components/home/OccasionStory.vue';
import FeaturedCollections from '../components/home/FeaturedCollections.vue';
import EditorialTicker from '../components/home/EditorialTicker.vue';
import HowItWorks from '../components/home/HowItWorks.vue';
import PackageFinder from '../components/home/PackageFinder.vue';
import ProductExamples from '../components/home/ProductExamples.vue';
import ServiceNotice from '../components/home/ServiceNotice.vue';
import BenefitsSection from '../components/home/BenefitsSection.vue';
import CorporateSection from '../components/home/CorporateSection.vue';
import TrustSection from '../components/home/TrustSection.vue';
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
        <BrandStatement />
        <OccasionStory />
        <FeaturedCollections />
        <EditorialTicker />
        <HowItWorks />
        <PackageFinder />
        <ProductExamples />
        <ServiceNotice />
        <BenefitsSection />
        <CorporateSection />
        <TrustSection />
    </main>
</template>
