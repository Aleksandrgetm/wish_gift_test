<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { useI18n } from '../composables/useI18n';

const { t } = useI18n();

const storyImage = '/images/Products/день матери 5.png';
const root = ref(null);
let motionObserver;

const facets = computed(() => t('about.facets'));

const setupMotion = () => {
    if (!root.value) return;

    const motionElements = [...root.value.querySelectorAll('.about-motion')];
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (reduceMotion || !('IntersectionObserver' in window)) {
        motionElements.forEach((element) => element.classList.add('is-visible'));
        return;
    }

    motionObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            motionObserver.unobserve(entry.target);
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.14 });

    motionElements.forEach((element) => motionObserver.observe(element));
};

onMounted(setupMotion);

onBeforeUnmount(() => {
    motionObserver?.disconnect();
});
</script>

<template>
    <main ref="root" class="about-page about-femine-page">
        <section class="about-femine-hero" aria-labelledby="about-hero-title">
            <div class="shell about-femine-hero-grid">
                <div class="about-femine-copy about-motion is-visible">
                    <p class="store-eyebrow">{{ t('about.hero.eyebrow') }}</p>
                    <h1 id="about-hero-title">{{ t('about.hero.title') }}</h1>
                    <p>{{ t('about.hero.text') }}</p>
                    <div class="about-femine-actions">
                        <v-btn color="primary" size="large" to="/catalog" append-icon="mdi-arrow-right">{{ t('about.cta.catalog') }}</v-btn>
                        <v-btn class="about-femine-play" icon="mdi-play" variant="outlined" :aria-label="t('about.cta.faq')" to="/faq" />
                    </div>
                </div>
            </div>
        </section>

        <section class="about-femine-welcome" aria-labelledby="about-idea-title">
            <div class="shell about-femine-welcome-grid">
                <figure class="about-femine-portrait about-femine-portrait--left about-motion">
                    <span class="about-femine-shape"></span>
                    <img :src="storyImage" :alt="t('about.idea.imageAlt')" loading="lazy">
                    <span class="about-femine-stem"></span>
                    <span class="about-femine-tape"></span>
                    <span class="about-femine-dots about-femine-dots--left" aria-hidden="true"><i></i><i></i><i></i></span>
                </figure>

                <div class="about-femine-welcome-copy about-motion" style="--motion-delay: 120ms">
                    <p class="eyebrow">{{ t('about.idea.eyebrow') }}</p>
                    <h2 id="about-idea-title">{{ t('about.idea.title') }}</h2>
                    <p>{{ t('about.idea.text') }}</p>
                    <div class="about-femine-facets" aria-label="Wish Gift personalization">
                        <span v-for="facet in facets" :key="facet.title">
                            <strong>{{ facet.title }}</strong>
                            <small>{{ facet.text }}</small>
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-femine-cta shell about-motion" aria-labelledby="about-cta-title">
            <p class="eyebrow">{{ t('about.final.eyebrow') }}</p>
            <h2 id="about-cta-title">{{ t('about.final.title') }}</h2>
            <p>{{ t('about.final.text') }}</p>
            <v-btn color="primary" size="large" to="/catalog" append-icon="mdi-arrow-right">{{ t('about.cta.catalog') }}</v-btn>
        </section>
    </main>
</template>
