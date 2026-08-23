<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { useI18n } from '../composables/useI18n';

const { t, translate, locale } = useI18n();
const categoryItems = [
    { id: 'birthday', title: 'Dzimšanas dienai', subtitle: 'Apskatīt', image: '/images/Products/день рождения.png', to: '/catalog/dzimsanas-diena' },
    { id: 'personalized', title: 'Personalizētas', subtitle: 'Apskatīt', image: '/images/Hero/hero.png', to: '/souvenirs' },
    { id: 'chocolate', title: 'Šokolādes dāvanas', subtitle: 'Apskatīt', image: '/images/Products/8 марта.png', to: '/souvenirs/chocolate' },
    { id: 'mugs', title: 'Krūzes', subtitle: 'Apskatīt', image: '/images/Products/день учителя.png', to: '/souvenirs/mug' },
    { id: 'women', title: 'Sievietēm', subtitle: 'Apskatīt', image: '/images/Products/8 марта 13.png', to: '/recipient/woman' },
    { id: 'men', title: 'Vīriešiem', subtitle: 'Apskatīt', image: '/images/Products/23 февраля.png', to: '/recipient/man' },
    { id: 'teachers', title: 'Skolotājiem', subtitle: 'Apskatīt', image: '/images/Products/1 сентября.png', to: '/recipient/teacher' },
    { id: 'cards', title: 'Kartītes', subtitle: 'Apskatīt', image: '/images/Products/день матери 4.png', to: '/souvenirs/card' },
];

const bestSellerItems = [
    { id: 'photo-alive-a4', title: 'A4 foto ar QR video', price: '25 €', reviews: '128', image: '/images/Hero/hero.png' },
    { id: 'chocolate-set', title: 'Šokolādes komplekts', price: 'no 18 €', reviews: '96', image: '/images/Products/8 марта.png' },
    { id: 'birthday-gift', title: 'Dāvana dzimšanas dienai', price: 'no 24 €', reviews: '74', image: '/images/Products/день рождения.png' },
    { id: 'mug-alive', title: 'Krūze ar QR video', price: 'pēc saskaņošanas', reviews: '53', image: '/images/Products/день учителя.png' },
    { id: 'mothers-day-gift', title: 'Mātes dienas dāvana', price: 'no 30 €', reviews: '87', image: '/images/Products/день матери 3.png' },
];

const serviceItems = [
    { id: 'delivery', icon: 'mdi-truck-outline', title: 'Piegāde Latvijā', text: 'Saskaņojam termiņu pirms izgatavošanas' },
    { id: 'safe-process', icon: 'mdi-shield-check-outline', title: 'Drošs process', text: 'Materiālus pārbaudām pirms drukas' },
    { id: 'handmade', icon: 'mdi-diamond-stone', title: 'Roku darbs', text: 'Katrs makets tiek pielāgots personīgi' },
    { id: 'revisions', icon: 'mdi-sync', title: 'Labojumi', text: 'Detaļas precizējam līdz rezultātam' },
    { id: 'support', icon: 'mdi-headset', title: 'Atbalsts', text: 'Palīdzam izvēlēties piemērotu formātu' },
];

const heroSlides = [
    { image: '/images/Hero/hero.png', alt: 'Personalizēta Wish Gift dāvana ar QR video' },
    { image: '/images/Hero/hero1.png', alt: 'Wish Gift hero kolekcijas foto' },
    { image: '/images/Hero/hero2.png', alt: 'Wish Gift personalizētas dāvanas foto' },
];
const categories = computed(() => categoryItems.map((item) => ({ ...item, title: translate('categories', item.title), subtitle: translate('categorySubtitle', item.subtitle) })));
const bestSellers = computed(() => bestSellerItems.map((item) => ({ ...item, title: translate('products', item.title), price: translate('prices', item.price) })));
const services = computed(() => serviceItems.map((item) => ({ ...item, title: translate('services', item.title), text: translate('services', item.text) })));
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
    activeHeroSlide.value = (activeHeroSlide.value + 1) % heroSlides.length;
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
    if (!root.value) return;

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
                    <strong>{{ t('home.heroBadgeTitle') }}</strong>
                    <span>{{ t('home.heroBadgeText') }}</span>
                </figcaption>
            </figure>
            <div class="shell store-hero-grid">
                <div class="store-hero-copy home-motion home-motion-hero">
                    <p class="store-eyebrow">{{ t('home.eyebrow') }}</p>
                    <h1 id="hero-title" :class="{ 'hero-title--ru': locale === 'ru' }">
                        <span v-if="t('home.heroTitlePrefix') || t('home.heroTitleAccent')">
                            <strong v-if="t('home.heroTitlePrefix')">{{ t('home.heroTitlePrefix') }}</strong>
                            <em v-if="t('home.heroTitleAccent')">{{ t('home.heroTitleAccent') }}</em>
                        </span>
                        <span v-if="t('home.heroEmphasisPrefix') || t('home.heroEmphasisAccent')">
                            <strong v-if="t('home.heroEmphasisPrefix')">{{ t('home.heroEmphasisPrefix') }}</strong>
                            <em v-if="t('home.heroEmphasisAccent')">{{ t('home.heroEmphasisAccent') }}</em>
                        </span>
                        <span v-if="t('home.heroThirdPrefix') || t('home.heroThirdAccent')">
                            <strong v-if="t('home.heroThirdPrefix')">{{ t('home.heroThirdPrefix') }}</strong>
                            <em v-if="t('home.heroThirdAccent')">{{ t('home.heroThirdAccent') }}</em>
                        </span>
                    </h1>
                    <p>{{ t('home.heroText') }}</p>
                    <div class="store-hero-actions">
                        <v-btn color="primary" size="large" to="/catalog">{{ t('home.catalogCta') }}</v-btn>
                        <v-btn variant="outlined" size="large" to="/souvenirs">{{ t('home.personalizedCta') }}</v-btn>
                    </div>
                    <div class="store-dots" role="tablist" :aria-label="t('home.heroTabs')">
                        <button
                            v-for="(slide, index) in heroSlides"
                            :key="slide.image"
                            type="button"
                            :class="{ active: activeHeroSlide === index }"
                            :aria-label="`${t('home.showHero')} ${index + 1}`"
                            :aria-selected="activeHeroSlide === index"
                            role="tab"
                            @click="selectHeroSlide(index)"
                        ></button>
                    </div>
                </div>
            </div>
        </section>

        <section class="service-strip" :aria-label="t('home.services')">
            <div class="shell service-strip-grid">
                <article v-for="(service, index) in services" :key="service.id" class="home-motion" :style="{ '--motion-delay': `${index * 70}ms` }">
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
                <h2 id="category-title">{{ t('home.categoryTitle') }}</h2>
                <span></span>
            </div>
            <div class="category-row">
                <router-link v-for="(category, index) in categories" :key="category.id" class="category-card home-motion" :style="{ '--motion-delay': `${index * 55}ms` }" :to="category.to">
                    <img :src="category.image" :alt="category.title">
                    <strong>{{ category.title }}</strong>
                    <small>{{ category.subtitle }}</small>
                </router-link>
            </div>
        </section>

        <section class="store-section shell" aria-labelledby="best-title">
            <div class="store-section-heading home-motion">
                <div class="store-section-title">
                    <span></span>
                    <h2 id="best-title">{{ t('home.bestTitle') }}</h2>
                    <span></span>
                </div>
                <router-link to="/catalog">{{ t('home.allProducts') }} <v-icon icon="mdi-arrow-right" size="18" aria-hidden="true" /></router-link>
            </div>
            <div class="best-grid">
                <article v-for="(product, index) in bestSellers" :key="product.id" class="best-card home-motion" :style="{ '--motion-delay': `${index * 75}ms` }">
                    <img :src="product.image" :alt="product.title">
                    <div class="best-card-body">
                        <h3>{{ product.title }}</h3>
                        <div class="rating" :aria-label="t('home.rating')">
                            <v-icon v-for="index in 5" :key="index" icon="mdi-star" size="14" aria-hidden="true" />
                            <small>({{ product.reviews }})</small>
                        </div>
                        <div class="best-card-footer">
                            <strong>{{ product.price }}</strong>
                            <v-btn icon="mdi-cart-outline" variant="outlined" size="small" :aria-label="`${t('home.addToCart')}: ${product.title}`" />
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <section class="promo-grid shell" :aria-label="t('home.offers')">
            <article class="promo-card promo-card-rose home-motion">
                <div>
                    <p>{{ t('home.offerLabel') }}</p>
                    <h2>{{ t('home.offerTitle') }}</h2>
                    <v-btn color="primary" to="/catalog">{{ t('home.offerCta') }}</v-btn>
                </div>
                <img :src="dealImage" alt="Personalizēta dāvanu kolekcija ar ziediem">
            </article>
            <article class="promo-card promo-card-green home-motion" style="--motion-delay: 90ms">
                <div>
                    <p>{{ t('home.arrivalsLabel') }}</p>
                    <h2>{{ t('home.arrivalsTitle') }}</h2>
                    <v-btn color="secondary" to="/souvenirs">{{ t('home.arrivalsCta') }}</v-btn>
                </div>
                <img :src="newArrivalImage" alt="Jauna personalizētu dāvanu kolekcija">
            </article>
        </section>

        <section class="store-newsletter" aria-labelledby="newsletter-title">
            <div class="shell store-newsletter-inner home-motion">
                <div>
                    <v-icon icon="mdi-email-outline" size="32" aria-hidden="true" />
                    <div>
                        <h2 id="newsletter-title">{{ t('home.newsletterTitle') }}</h2>
                        <p>{{ t('home.newsletterText') }}</p>
                    </div>
                </div>
                <form>
                    <label class="sr-only" for="newsletter-email">{{ t('home.email') }}</label>
                    <input id="newsletter-email" type="email" :placeholder="t('home.email')">
                    <v-btn color="secondary" type="submit">{{ t('home.subscribe') }}</v-btn>
                </form>
            </div>
        </section>
    </main>
</template>
