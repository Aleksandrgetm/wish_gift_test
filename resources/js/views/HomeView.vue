<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { useLanguage } from '../stores/i18n';

const { locale, t } = useLanguage();

const categoryMeta = [
    { image: '/images/Products/день рождения.png', to: '/occasion' },
    { image: '/images/Hero/hero.png', to: '/souvenirs' },
    { image: '/images/Products/8 марта.png', to: '/souvenirs/chocolate' },
    { image: '/images/Products/день учителя.png', to: '/souvenirs/mug' },
    { image: '/images/Products/8 марта 13.png', to: '/recipient/woman' },
    { image: '/images/Products/23 февраля.png', to: '/recipient/man' },
    { image: '/images/Products/1 сентября.png', to: '/recipient/teacher' },
    { image: '/images/Products/день матери 4.png', to: '/souvenirs/card' },
];

const bestSellerMeta = [
    { reviews: '128', image: '/images/Hero/hero.png' },
    { reviews: '96', image: '/images/Products/8 марта.png' },
    { reviews: '74', image: '/images/Products/день рождения.png' },
    { reviews: '53', image: '/images/Products/день учителя.png' },
    { reviews: '87', image: '/images/Products/день матери 3.png' },
];

const serviceIcons = [
    { icon: 'mdi-truck-outline' },
    { icon: 'mdi-shield-check-outline' },
    { icon: 'mdi-diamond-stone' },
    { icon: 'mdi-sync' },
    { icon: 'mdi-headset' },
];

const heroSlideImages = [
    { image: '/images/Hero/hero.png' },
    { image: '/images/Hero/hero1.png' },
    { image: '/images/Hero/hero2.png' },
];
const categories = computed(() => t.value.home.categories.map((category, index) => ({ ...categoryMeta[index], ...category })));
const bestSellers = computed(() => t.value.home.bestSellers.map((product, index) => ({ ...bestSellerMeta[index], ...product })));
const services = computed(() => t.value.home.services.map((service, index) => ({ ...serviceIcons[index], ...service })));
const heroSlides = computed(() => heroSlideImages.map((slide, index) => ({ ...slide, alt: t.value.home.hero.slideAlts[index] })));
const dealImage = '/images/Products/8 марта14.png';
const newArrivalImage = '/images/Products/день матери 5.png';
const activeHeroSlide = ref(0);
const root = ref(null);
let heroSlideTimer;
let homeMotionObserver;
let reduceHeroMotion = false;

const selectHeroSlide = (index) => {
    activeHeroSlide.value = index;
    restartHeroSlider();
};

const showNextHeroSlide = () => {
    activeHeroSlide.value = (activeHeroSlide.value + 1) % heroSlides.value.length;
};

const startHeroSlider = () => {
    if (reduceHeroMotion) return;
    heroSlideTimer = window.setInterval(showNextHeroSlide, 10000);
};

const restartHeroSlider = () => {
    window.clearInterval(heroSlideTimer);
    startHeroSlider();
};

const setupHomeMotion = () => {
    const motionElements = [...root.value.querySelectorAll('.home-motion')];

    if (reduceHeroMotion || !('IntersectionObserver' in window)) {
        motionElements.forEach((element) => element.classList.add('is-visible'));
        return;
    }

    homeMotionObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            homeMotionObserver.unobserve(entry.target);
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.1 });

    motionElements.forEach((element) => homeMotionObserver.observe(element));
};

onMounted(() => {
    reduceHeroMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    startHeroSlider();
    setupHomeMotion();
});

onBeforeUnmount(() => {
    window.clearInterval(heroSlideTimer);
    homeMotionObserver?.disconnect();
});
</script>

<template>
    <main ref="root" class="store-home">
        <section class="store-hero" aria-labelledby="hero-title">
            <figure class="store-hero-media">
                <img
                    v-for="(slide, index) in heroSlides"
                    :key="slide.image"
                    :src="slide.image"
                    :alt="activeHeroSlide === index ? slide.alt : ''"
                    :aria-hidden="activeHeroSlide !== index"
                    :class="{ active: activeHeroSlide === index }"
                >
                <figcaption>
                    <v-icon icon="mdi-gift-outline" aria-hidden="true" />
                    <strong>{{ t.home.hero.captionTitle }}</strong>
                    <span>{{ t.home.hero.captionText }}</span>
                </figcaption>
            </figure>
            <div class="shell store-hero-grid">
                <div class="store-hero-copy home-motion home-motion-hero">
                    <p class="store-eyebrow">{{ t.home.hero.eyebrow }}</p>
                    <h1 id="hero-title" :class="{ 'hero-title--ru': locale === 'ru' }">
                        <span>
                            <strong>{{ t.home.hero.titleLine1 }}</strong>
                            <em v-if="t.home.hero.titleLove"> {{ t.home.hero.titleLove }}</em>
                        </span>
                        <span v-if="t.home.hero.titleLine2">
                            <em v-if="t.home.hero.titleLine2Accent">{{ t.home.hero.titleLine2 }}</em>
                            <template v-else>{{ t.home.hero.titleLine2 }}</template>
                        </span>
                        <span v-if="t.home.hero.titleCherished">
                            <em v-if="t.home.hero.titleCherishedAccent !== false">{{ t.home.hero.titleCherished }}</em>
                            <template v-else>{{ t.home.hero.titleCherished }}</template>
                        </span>
                    </h1>
                    <p>{{ t.home.hero.description }}</p>
                    <div class="store-hero-actions">
                        <v-btn color="primary" size="large" to="/catalog">{{ t.home.hero.primaryCta }}</v-btn>
                        <v-btn variant="outlined" size="large" to="/souvenirs">{{ t.home.hero.secondaryCta }}</v-btn>
                    </div>
                    <div class="store-dots" role="tablist" :aria-label="t.home.hero.dotsLabel">
                        <button
                            v-for="(slide, index) in heroSlides"
                            :key="slide.image"
                            type="button"
                            :class="{ active: activeHeroSlide === index }"
                            :aria-label="`${t.home.hero.dotLabel} ${index + 1}`"
                            :aria-selected="activeHeroSlide === index"
                            role="tab"
                            @click="selectHeroSlide(index)"
                        ></button>
                    </div>
                </div>
            </div>
        </section>

        <section class="service-strip" :aria-label="t.home.servicesLabel">
            <div class="shell service-strip-grid">
                <article v-for="(service, index) in services" :key="service.title" class="home-motion" :style="{ '--motion-delay': `${index * 70}ms` }">
                    <v-icon :icon="service.icon" size="28" aria-hidden="true" />
                    <div>
                        <h2>{{ service.title }}</h2>
                        <p>{{ service.text }}</p>
                    </div>
                </article>
            </div>
        </section>

        <section class="store-section shell" aria-labelledby="category-title">
            <div class="store-section-title home-motion">
                <span></span>
                <h2 id="category-title">{{ t.home.categoriesTitle }}</h2>
                <span></span>
            </div>
            <div class="category-row">
                <router-link v-for="(category, index) in categories" :key="category.title" class="category-card home-motion" :style="{ '--motion-delay': `${index * 55}ms` }" :to="category.to">
                    <img :src="category.image" :alt="category.title">
                    <strong>{{ category.title }}</strong>
                </router-link>
            </div>
        </section>

        <section class="store-section shell" aria-labelledby="best-title">
            <div class="store-section-heading home-motion">
                <div class="store-section-title">
                    <span></span>
                    <h2 id="best-title">{{ t.home.bestTitle }}</h2>
                    <span></span>
                </div>
                <router-link to="/catalog">{{ t.home.allProducts }} <v-icon icon="mdi-arrow-right" size="18" aria-hidden="true" /></router-link>
            </div>
            <div class="best-grid">
                <article v-for="(product, index) in bestSellers" :key="product.title" class="best-card home-motion" :style="{ '--motion-delay': `${index * 75}ms` }">
                    <img :src="product.image" :alt="product.title">
                    <div class="best-card-body">
                        <h3>{{ product.title }}</h3>
                        <p>{{ product.detail }}</p>
                        <div class="rating" :aria-label="t.home.ratingLabel">
                            <v-icon v-for="index in 5" :key="index" icon="mdi-star" size="14" aria-hidden="true" />
                            <small>({{ product.reviews }})</small>
                        </div>
                        <div class="best-card-footer">
                            <strong>{{ product.price }}</strong>
                            <v-btn color="primary" size="small" :aria-label="`${t.home.addToCartLabel}: ${product.title}`">{{ t.home.addToCart }}</v-btn>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <section class="promo-grid shell" :aria-label="t.home.promoLabel">
            <article class="promo-card promo-card-rose home-motion">
                <div>
                    <p>{{ t.home.promo.offerEyebrow }}</p>
                    <h2>{{ t.home.promo.offerTitle }}</h2>
                    <v-btn color="primary" to="/catalog">{{ t.home.promo.offerCta }}</v-btn>
                </div>
                <img :src="dealImage" :alt="t.home.promo.offerAlt">
            </article>
            <article class="promo-card promo-card-green home-motion" style="--motion-delay: 90ms">
                <div>
                    <p>{{ t.home.promo.newEyebrow }}</p>
                    <h2>{{ t.home.promo.newTitle }}</h2>
                    <v-btn color="secondary" to="/souvenirs">{{ t.home.promo.newCta }}</v-btn>
                </div>
                <img :src="newArrivalImage" :alt="t.home.promo.newAlt">
            </article>
        </section>

        <section class="store-newsletter" aria-labelledby="newsletter-title">
            <div class="shell store-newsletter-inner home-motion">
                <div>
                    <v-icon icon="mdi-email-outline" size="32" aria-hidden="true" />
                    <div>
                        <h2 id="newsletter-title">{{ t.home.newsletter.title }}</h2>
                        <p>{{ t.home.newsletter.text }}</p>
                    </div>
                </div>
                <form>
                    <label class="sr-only" for="newsletter-email">{{ t.home.newsletter.email }}</label>
                    <input id="newsletter-email" type="email" :placeholder="t.home.newsletter.email">
                    <v-btn color="secondary" type="submit">{{ t.home.newsletter.submit }}</v-btn>
                </form>
            </div>
        </section>
    </main>
</template>
