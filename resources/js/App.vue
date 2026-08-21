<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { RouterView, useRoute } from 'vue-router';
import logoSrc from '../images/logo/logo.jpg';
import { useLanguage } from './stores/i18n';

const menuOpen = ref(false);
const logoFailed = ref(false);
const isHeaderCompact = ref(false);
const cartCount = ref(0);
const route = useRoute();
const { locale, languageOptions, setLanguage, t } = useLanguage();
const links = [
    { key: 'home', to: '/' },
    { key: 'catalog', to: '/catalog' },
    { key: 'collections', to: '/occasion' },
    { key: 'about', to: '/about' },
    { key: 'faq', to: { path: '/', hash: '#faq' } },
    { key: 'contacts', to: '/contacts' },
];
const footerPageLinks = [
    { key: 'home', to: '/' },
    { key: 'catalog', to: '/catalog' },
    { key: 'personalized', to: '/souvenirs' },
    { key: 'recipients', to: '/recipient' },
    { key: 'corporate', to: '/corporate' },
    { key: 'about', to: '/about' },
    { key: 'contacts', to: '/contacts' },
    { key: 'faq', to: { path: '/', hash: '#faq' } },
];
const footerOccasionLinks = [
    { key: 'all', to: '/occasion' },
    { key: 'christmas', to: '/occasion/christmas' },
    { key: 'newYear', to: '/occasion/new-year' },
    { key: 'valentine', to: '/occasion/valentine' },
    { key: 'womensDay', to: '/occasion/womens-day' },
    { key: 'mothersDay', to: '/occasion/mothers-day' },
    { key: 'teachersDay', to: '/occasion/teachers-day' },
    { key: 'studentsDay', to: '/occasion/students-day' },
    { key: 'septemberFirst', to: '/occasion/september-first' },
    { key: 'fathersDay', to: '/occasion/fathers-day' },
];
const getPath = (to) => (typeof to === 'string' ? to : to.path);
const getHash = (to) => (typeof to === 'string' ? '' : to.hash || '');
const isActive = (to) => {
    const path = getPath(to);
    const hash = getHash(to);

    if (hash) {
        return route.path === path && route.hash === hash;
    }

    if (path === '/') {
        return route.path === path && !route.hash;
    }

    return route.path === path;
};

const updateHeaderState = () => {
    isHeaderCompact.value = window.scrollY > 12;
};

onMounted(() => {
    updateHeaderState();
    window.addEventListener('scroll', updateHeaderState, { passive: true });
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', updateHeaderState);
});
</script>

<template>
    <v-app>
        <header class="site-header" :class="{ 'site-header--compact': isHeaderCompact }">
            <div class="shell header-inner">
                <router-link class="brand" to="/" :aria-label="t.aria.home">
                    <img v-if="!logoFailed" class="brand-logo" :src="logoSrc" alt="Wish Gift" @error="logoFailed = true">
                    <span v-else class="brand-mark">WISH GIFT</span>
                    <span class="brand-copy">Wish Gift<small>{{ t.brandTagline }}</small></span>
                </router-link>
                <nav class="desktop-nav" :aria-label="t.aria.mainNav">
                    <router-link v-for="link in links" :key="link.key" :class="{ 'router-link-active': isActive(link.to) }" :to="link.to">{{ t.nav[link.key] }}</router-link>
                </nav>
                <div class="header-actions">
                    <div class="language-switch" :aria-label="t.aria.language">
                        <button
                            v-for="language in languageOptions"
                            :key="language.code"
                            type="button"
                            :class="{ active: locale === language.code }"
                            :aria-pressed="locale === language.code"
                            @click="setLanguage(language.code)"
                        >{{ language.label }}</button>
                    </div>
                    <v-btn icon="mdi-magnify" variant="text" :aria-label="t.aria.search" />
                    <v-btn icon="mdi-account-outline" variant="text" :aria-label="t.aria.profile" />
                    <v-btn icon="mdi-heart-outline" variant="text" :aria-label="t.aria.wishlist" />
                    <span class="cart-action">
                        <button class="header-icon-button cart-button" type="button" :aria-label="t.aria.cart">
                            <v-icon icon="mdi-cart-outline" aria-hidden="true" />
                        </button>
                        <span v-if="cartCount" class="cart-badge">{{ cartCount }}</span>
                    </span>
                    <button class="header-icon-button menu-button" type="button" :aria-label="t.aria.menuOpen" :aria-expanded="menuOpen" @click="menuOpen = !menuOpen">
                        <v-icon icon="mdi-menu" aria-hidden="true" />
                    </button>
                </div>
            </div>
            <nav v-if="menuOpen" class="mobile-nav" :aria-label="t.aria.mobileNav">
                <router-link class="mobile-nav-brand" to="/" :aria-label="t.aria.home" @click="menuOpen = false">
                    <img v-if="!logoFailed" class="brand-logo" :src="logoSrc" alt="Wish Gift" @error="logoFailed = true">
                    <span v-else>WISH GIFT</span>
                </router-link>
                <router-link v-for="link in links" :key="link.key" :class="{ 'router-link-active': isActive(link.to) }" :to="link.to" @click="menuOpen = false">{{ t.nav[link.key] }}</router-link>
                <div class="mobile-nav-actions" :aria-label="t.aria.quickActions">
                    <div class="language-switch" :aria-label="t.aria.language">
                        <button
                            v-for="language in languageOptions"
                            :key="language.code"
                            type="button"
                            :class="{ active: locale === language.code }"
                            :aria-pressed="locale === language.code"
                            @click="setLanguage(language.code)"
                        >{{ language.label }}</button>
                    </div>
                    <v-btn icon="mdi-magnify" variant="text" :aria-label="t.aria.search" />
                    <v-btn icon="mdi-account-outline" variant="text" :aria-label="t.aria.profile" />
                    <v-btn icon="mdi-heart-outline" variant="text" :aria-label="t.aria.wishlist" />
                    <span class="cart-action">
                        <button class="header-icon-button cart-button" type="button" :aria-label="t.aria.cart">
                            <v-icon icon="mdi-cart-outline" aria-hidden="true" />
                        </button>
                        <span v-if="cartCount" class="cart-badge">{{ cartCount }}</span>
                    </span>
                </div>
            </nav>
        </header>

        <RouterView />

        <footer class="site-footer">
            <div class="shell footer-grid">
                <div><div class="footer-brand">Wish Gift</div><p>{{ t.footer.description }}</p><div class="footer-socials" :aria-label="t.aria.socials"><v-btn icon="mdi-facebook" variant="text" aria-label="Facebook" /><v-btn icon="mdi-instagram" variant="text" aria-label="Instagram" /><v-btn icon="mdi-pinterest" variant="text" aria-label="Pinterest" /><v-btn icon="mdi-music-note" variant="text" aria-label="TikTok" /></div></div>
                <div><h2>{{ t.footer.pagesTitle }}</h2><router-link v-for="link in footerPageLinks" :key="link.key" :to="link.to">{{ t.footer.pages[link.key] }}</router-link></div>
                <div><h2>{{ t.footer.occasions }}</h2><router-link v-for="link in footerOccasionLinks" :key="link.key" :to="link.to">{{ t.footer.occasionLinks[link.key] }}</router-link></div>
                <div><h2>{{ t.footer.help }}</h2><a href="tel:+37128153310">+371 28153310</a><a href="mailto:ozivajka@inbox.lv">ozivajka@inbox.lv</a><router-link to="/contacts">{{ t.nav.contacts }}</router-link></div>
            </div>
            <div class="shell footer-bottom"><span>© 2026 wish_gift</span><span>{{ t.footer.privacy }}</span></div>
        </footer>
    </v-app>
</template>
