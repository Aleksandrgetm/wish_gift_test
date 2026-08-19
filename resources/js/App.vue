<script setup>
import { ref } from 'vue';
import { RouterView, useRoute } from 'vue-router';
import logoSrc from '../images/logo/logo.jpg';
import { useI18n } from './composables/useI18n';

const menuOpen = ref(false);
const logoFailed = ref(false);
const route = useRoute();
const { locale, languages, setLocale, t } = useI18n();
const links = [
    { title: 'nav.home', to: '/' },
    { title: 'nav.catalog', to: '/catalog' },
    { title: 'nav.collections', to: '/occasion' },
    { title: 'nav.about', to: '/about' },
    { title: 'nav.contacts', to: '/contacts' },
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
</script>

<template>
    <v-app>
        <header class="site-header">
            <div class="shell header-inner">
                <router-link class="brand" to="/" :aria-label="t('homeAria')">
                    <img v-if="!logoFailed" class="brand-logo" :src="logoSrc" alt="Wish Gift" @error="logoFailed = true">
                    <span v-else class="brand-mark">WISH GIFT</span>
                    <span class="brand-copy">Wish Gift<small>{{ t('brandTagline') }}</small></span>
                </router-link>
                <nav class="desktop-nav" :aria-label="t('mainNav')">
                    <router-link v-for="link in links" :key="link.title" :class="{ 'router-link-active': isActive(link.to) }" :to="link.to">{{ t(link.title) }}</router-link>
                </nav>
                <div class="header-actions">
                    <div class="language-switcher" role="group" :aria-label="t('languageLabel')">
                        <button
                            v-for="language in languages"
                            :key="language.code"
                            type="button"
                            :class="{ active: locale === language.code }"
                            :aria-pressed="locale === language.code"
                            :title="language.title"
                            @click="setLocale(language.code)"
                        >
                            {{ language.label }}
                        </button>
                    </div>
                    <v-btn icon="mdi-cart-outline" variant="text" :aria-label="t('cart')" />
                    <v-btn class="menu-button" icon="mdi-menu" variant="text" :aria-label="t('openMenu')" :aria-expanded="menuOpen" @click="menuOpen = !menuOpen" />
                </div>
            </div>
            <nav v-if="menuOpen" class="mobile-nav" :aria-label="t('mobileNav')">
                <router-link class="mobile-nav-brand" to="/" :aria-label="t('homeAria')" @click="menuOpen = false">
                    <img v-if="!logoFailed" class="brand-logo" :src="logoSrc" alt="Wish Gift" @error="logoFailed = true">
                    <span v-else>WISH GIFT</span>
                </router-link>
                <router-link v-for="link in links" :key="link.title" :class="{ 'router-link-active': isActive(link.to) }" :to="link.to" @click="menuOpen = false">{{ t(link.title) }}</router-link>
                <div class="mobile-nav-actions" :aria-label="t('quickActions')">
                    <div class="language-switcher language-switcher--mobile" role="group" :aria-label="t('languageLabel')">
                        <button
                            v-for="language in languages"
                            :key="language.code"
                            type="button"
                            :class="{ active: locale === language.code }"
                            :aria-pressed="locale === language.code"
                            :title="language.title"
                            @click="setLocale(language.code)"
                        >
                            {{ language.label }}
                        </button>
                    </div>
                    <v-btn icon="mdi-cart-outline" variant="text" :aria-label="t('cart')" />
                </div>
            </nav>
        </header>

        <RouterView />

        <footer class="site-footer">
            <div class="shell footer-grid">
                <div><div class="footer-brand">Wish Gift</div><p>{{ t('footer.description') }}</p><div class="footer-socials" :aria-label="t('footer.socials')"><v-btn icon="mdi-facebook" variant="text" aria-label="Facebook" /><v-btn icon="mdi-instagram" variant="text" aria-label="Instagram" /><v-btn icon="mdi-pinterest" variant="text" aria-label="Pinterest" /><v-btn icon="mdi-music-note" variant="text" aria-label="TikTok" /></div></div>
                <div><h2>{{ t('footer.shop') }}</h2><router-link to="/catalog">{{ t('footer.allProducts') }}</router-link><router-link to="/souvenirs">{{ t('footer.newItems') }}</router-link><router-link to="/catalog">{{ t('footer.bestSellers') }}</router-link></div>
                <div><h2>{{ t('footer.occasions') }}</h2><router-link to="/occasion">{{ t('footer.birthday') }}</router-link><router-link to="/occasion/mothers-day">{{ t('footer.mothersDay') }}</router-link><router-link to="/occasion/teachers-day">{{ t('footer.teachersDay') }}</router-link><router-link to="/occasion/christmas">{{ t('footer.christmas') }}</router-link></div>
                <div><h2>{{ t('footer.help') }}</h2><a href="tel:+37128153310">+371 28153310</a><a href="mailto:ozivajka@inbox.lv">ozivajka@inbox.lv</a><router-link to="/contacts">{{ t('nav.contacts') }}</router-link></div>
            </div>
            <div class="shell footer-bottom"><span>© 2026 wish_gift</span><span>{{ t('footer.privacy') }}</span></div>
        </footer>
    </v-app>
</template>
