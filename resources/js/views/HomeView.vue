<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

const categories = [
    { title: 'Dzimšanas dienai', subtitle: 'Apskatīt', image: '/images/Products/день рождения.png', to: '/occasion' },
    { title: 'Personalizētas', subtitle: 'Apskatīt', image: '/images/Hero/hero.png', to: '/souvenirs' },
    { title: 'Šokolādes dāvanas', subtitle: 'Apskatīt', image: '/images/Products/8 марта.png', to: '/souvenirs/chocolate' },
    { title: 'Krūzes', subtitle: 'Apskatīt', image: '/images/Products/день учителя.png', to: '/souvenirs/mug' },
    { title: 'Sievietēm', subtitle: 'Apskatīt', image: '/images/Products/8 марта 13.png', to: '/recipient/woman' },
    { title: 'Vīriešiem', subtitle: 'Apskatīt', image: '/images/Products/23 февраля.png', to: '/recipient/man' },
    { title: 'Skolotājiem', subtitle: 'Apskatīt', image: '/images/Products/1 сентября.png', to: '/recipient/teacher' },
    { title: 'Kartītes', subtitle: 'Apskatīt', image: '/images/Products/день матери 4.png', to: '/souvenirs/card' },
];

const bestSellers = [
    { title: 'A4 foto ar QR video', price: '25 €', reviews: '128', image: '/images/Hero/hero.png' },
    { title: 'Šokolādes komplekts', price: 'no 18 €', reviews: '96', image: '/images/Products/8 марта.png' },
    { title: 'Dāvana dzimšanas dienai', price: 'no 24 €', reviews: '74', image: '/images/Products/день рождения.png' },
    { title: 'Krūze ar QR video', price: 'pēc saskaņošanas', reviews: '53', image: '/images/Products/день учителя.png' },
    { title: 'Mātes dienas dāvana', price: 'no 30 €', reviews: '87', image: '/images/Products/день матери 3.png' },
];

const services = [
    { icon: 'mdi-truck-outline', title: 'Piegāde Latvijā', text: 'Saskaņojam termiņu pirms izgatavošanas' },
    { icon: 'mdi-shield-check-outline', title: 'Drošs process', text: 'Materiālus pārbaudām pirms drukas' },
    { icon: 'mdi-diamond-stone', title: 'Roku darbs', text: 'Katrs makets tiek pielāgots personīgi' },
    { icon: 'mdi-sync', title: 'Labojumi', text: 'Detaļas precizējam līdz rezultātam' },
    { icon: 'mdi-headset', title: 'Atbalsts', text: 'Palīdzam izvēlēties piemērotu formātu' },
];

const heroSlides = [
    { image: '/images/Hero/hero.png', alt: 'Personalizēta Wish Gift dāvana ar QR video' },
    { image: '/images/Hero/hero1.png', alt: 'Wish Gift hero kolekcijas foto' },
    { image: '/images/Hero/hero2.png', alt: 'Wish Gift personalizētas dāvanas foto' },
];
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
                    <strong>Kurētas dāvanas</strong>
                    <span>katram cilvēkam un notikumam</span>
                </figcaption>
            </figure>
            <div class="shell store-hero-grid">
                <div class="store-hero-copy home-motion home-motion-hero">
                    <p class="store-eyebrow">Pārdomātas dāvanas</p>
                    <h1 id="hero-title">Made with love, meant to be <em>cherished.</em></h1>
                    <p>Personalizētas dāvanas ar foto, video, mūziku un QR kodu katram svarīgam mirklim.</p>
                    <div class="store-hero-actions">
                        <v-btn color="primary" size="large" to="/catalog">Apskatīt katalogu</v-btn>
                        <v-btn variant="outlined" size="large" to="/souvenirs">Personalizētas dāvanas</v-btn>
                    </div>
                    <div class="store-dots" role="tablist" aria-label="Hero fotoattēli">
                        <button
                            v-for="(slide, index) in heroSlides"
                            :key="slide.image"
                            type="button"
                            :class="{ active: activeHeroSlide === index }"
                            :aria-label="`Rādīt hero foto ${index + 1}`"
                            :aria-selected="activeHeroSlide === index"
                            role="tab"
                            @click="selectHeroSlide(index)"
                        ></button>
                    </div>
                </div>
            </div>
        </section>

        <section class="service-strip" aria-label="Servisa priekšrocības">
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
                <h2 id="category-title">Shop by category</h2>
                <span></span>
            </div>
            <div class="category-row">
                <router-link v-for="(category, index) in categories" :key="category.title" class="category-card home-motion" :style="{ '--motion-delay': `${index * 55}ms` }" :to="category.to">
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
                    <h2 id="best-title">Best sellers</h2>
                    <span></span>
                </div>
                <router-link to="/catalog">Visi produkti <v-icon icon="mdi-arrow-right" size="18" aria-hidden="true" /></router-link>
            </div>
            <div class="best-grid">
                <article v-for="(product, index) in bestSellers" :key="product.title" class="best-card home-motion" :style="{ '--motion-delay': `${index * 75}ms` }">
                    <img :src="product.image" :alt="product.title">
                    <div class="best-card-body">
                        <h3>{{ product.title }}</h3>
                        <div class="rating" aria-label="Piecu zvaigžņu vērtējums">
                            <v-icon v-for="index in 5" :key="index" icon="mdi-star" size="14" aria-hidden="true" />
                            <small>({{ product.reviews }})</small>
                        </div>
                        <div class="best-card-footer">
                            <strong>{{ product.price }}</strong>
                            <v-btn icon="mdi-cart-outline" variant="outlined" size="small" :aria-label="`Pievienot grozam: ${product.title}`" />
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <section class="promo-grid shell" aria-label="Īpašie piedāvājumi">
            <article class="promo-card promo-card-rose home-motion">
                <div>
                    <p>Limited time offer</p>
                    <h2>Up to 20% off on selected gifts</h2>
                    <v-btn color="primary" to="/catalog">Apskatīt piedāvājumus</v-btn>
                </div>
                <img :src="dealImage" alt="Personalizēta dāvanu kolekcija ar ziediem">
            </article>
            <article class="promo-card promo-card-green home-motion" style="--motion-delay: 90ms">
                <div>
                    <p>New arrivals</p>
                    <h2>Discover our new collection</h2>
                    <v-btn color="secondary" to="/souvenirs">Jaunumi</v-btn>
                </div>
                <img :src="newArrivalImage" alt="Jauna personalizētu dāvanu kolekcija">
            </article>
        </section>

        <section class="store-newsletter" aria-labelledby="newsletter-title">
            <div class="shell store-newsletter-inner home-motion">
                <div>
                    <v-icon icon="mdi-email-outline" size="32" aria-hidden="true" />
                    <div>
                        <h2 id="newsletter-title">Stay in the loop</h2>
                        <p>Saņemiet idejas, jaunumus un īpašos piedāvājumus savā e-pastā.</p>
                    </div>
                </div>
                <form>
                    <label class="sr-only" for="newsletter-email">E-pasta adrese</label>
                    <input id="newsletter-email" type="email" placeholder="E-pasta adrese">
                    <v-btn color="secondary" type="submit">Pieteikties</v-btn>
                </form>
            </div>
        </section>
    </main>
</template>
