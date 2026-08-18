<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { RouterView, useRoute } from 'vue-router';
import logoSrc from '../images/logo/logo.jpg';
import { useGsapContext } from './composables/useGsapContext';

const menuOpen = ref(false);
const logoFailed = ref(false);
const header = ref(null);
const headerCompact = ref(false);
const route = useRoute();
const selectedLanguage = ref('RU');
const links = [
    { title: 'Sākums', to: '/' },
    { title: 'Katalogs', to: '/catalog' },
    { title: 'Kontakti', to: '/contacts' },
];
const languages = [
    { title: 'Русский', code: 'RU' },
    { title: 'English', code: 'EN' },
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
const updateHeaderState = () => { headerCompact.value = window.scrollY > 12; };

onMounted(() => {
    updateHeaderState();
    window.addEventListener('scroll', updateHeaderState, { passive: true });
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', updateHeaderState);
});

useGsapContext(header, ({ gsap, reduceMotion }) => {
    if (reduceMotion) return;
    gsap.from(header.value, { y: -14, opacity: 0, duration: 0.7, ease: 'power3.out' });
});
</script>

<template>
    <v-app>
        <div class="announcement">Personalizētas dāvanas ar foto, video, mūziku un QR kodu — detaļas saskaņojam pirms izgatavošanas</div>
        <header ref="header" class="site-header" :class="{ 'site-header--compact': headerCompact }">
            <div class="shell header-inner">
                <router-link class="brand" to="/" aria-label="Wish Gift — sākumlapa">
                    <img v-if="!logoFailed" class="brand-logo" :src="logoSrc" alt="Wish Gift" @error="logoFailed = true">
                    <span v-else class="brand-mark">WISH GIFT</span>
                    <span class="brand-copy">Wish Gift<small>Dāvanas, kas atdzīvojas</small></span>
                </router-link>
                <nav class="desktop-nav" aria-label="Galvenā navigācija">
                    <router-link
                        v-for="link in links"
                        :key="link.title"
                        active-class="vue-router-link-active"
                        exact-active-class="vue-router-link-exact-active"
                        :class="{ 'router-link-active': isActive(link.to) }"
                        :to="link.to"
                    >
                        {{ link.title }}
                    </router-link>
                </nav>
                <div class="header-actions">
                    <v-menu location="bottom end" content-class="language-menu">
                        <template #activator="{ props }">
                            <v-btn v-bind="props" icon="mdi-earth" variant="text" aria-label="Izvēlēties valodu" />
                        </template>
                        <v-list density="compact" min-width="150">
                            <v-list-item
                                v-for="language in languages"
                                :key="language.code"
                                :active="selectedLanguage === language.code"
                                @click="selectedLanguage = language.code"
                            >
                                <v-list-item-title>{{ language.title }}</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                    <v-btn icon="mdi-shopping-outline" variant="text" aria-label="Grozs" />
                    <v-btn class="menu-button" icon="mdi-menu" variant="text" aria-label="Atvērt izvēlni" :aria-expanded="menuOpen" @click="menuOpen = !menuOpen" />
                </div>
            </div>
            <nav v-if="menuOpen" class="mobile-nav" aria-label="Mobilā navigācija">
                <router-link class="mobile-nav-brand" to="/" aria-label="Wish Gift — sākumlapa" @click="menuOpen = false">
                    <img v-if="!logoFailed" class="brand-logo" :src="logoSrc" alt="Wish Gift" @error="logoFailed = true">
                    <span v-else>WISH GIFT</span>
                </router-link>
                <router-link
                    v-for="link in links"
                    :key="link.title"
                    active-class="vue-router-link-active"
                    exact-active-class="vue-router-link-exact-active"
                    :class="{ 'router-link-active': isActive(link.to) }"
                    :to="link.to"
                    @click="menuOpen = false"
                >
                    {{ link.title }}
                </router-link>
                <div class="mobile-nav-actions" aria-label="Ātrās darbības">
                    <v-menu location="bottom start" content-class="language-menu">
                        <template #activator="{ props }">
                            <v-btn v-bind="props" icon="mdi-earth" variant="text" aria-label="Izvēlēties valodu" />
                        </template>
                        <v-list density="compact" min-width="150">
                            <v-list-item
                                v-for="language in languages"
                                :key="language.code"
                                :active="selectedLanguage === language.code"
                                @click="selectedLanguage = language.code"
                            >
                                <v-list-item-title>{{ language.title }}</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                    <v-btn icon="mdi-shopping-outline" variant="text" aria-label="Grozs" />
                </div>
            </nav>
        </header>

        <RouterView v-slot="{ Component, route }">
            <Transition name="page" mode="out-in">
                <component :is="Component" :key="route.path" />
            </Transition>
        </RouterView>

        <footer class="site-footer">
            <div class="shell footer-grid">
                <div><div class="footer-brand">wish_gift</div><p>Dāvanas, kas atdzīvojas: foto, video, mūzika un QR kods personalizētā suvenīrā.</p><v-btn color="secondary" size="small" href="mailto:ozivajka@inbox.lv">Izveidot dāvanu</v-btn></div>
                <div><h2>Navigācija</h2><router-link to="/catalog">Katalogs</router-link><router-link to="/souvenirs">Dzīvie suvenīri</router-link><router-link to="/contacts">Kontakti</router-link></div>
                <div><h2>Kontakti</h2><a href="tel:+37128153310">+371 28153310</a><a href="mailto:ozivajka@inbox.lv">ozivajka@inbox.lv</a><p>Vispirms saskaņojam foto, video, mūziku un dāvanas maketu.</p></div>
            </div>
            <div class="shell footer-bottom"><span>© 2026 wish_gift</span><span>Privātuma politika</span></div>
        </footer>
    </v-app>
</template>
