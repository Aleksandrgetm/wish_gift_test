<script setup>
import { ref } from 'vue';
import { RouterView, useRoute } from 'vue-router';
import logoSrc from '../images/logo/logo.jpg';

const menuOpen = ref(false);
const logoFailed = ref(false);
const route = useRoute();
const links = [
    { title: 'Home', to: '/' },
    { title: 'Katalogs', to: '/catalog' },
    { title: 'Kolekcijas', to: '/occasion' },
    { title: 'Personalizētas', to: '/souvenirs' },
    { title: 'Piedāvājumi', to: '/catalog' },
    { title: 'Par mums', to: '/about' },
    { title: 'Kontakti', to: '/contacts' },
];
const isActive = (to) => route.path === to || route.path.startsWith(`${to}/`);
</script>

<template>
    <v-app>
        <header class="site-header">
            <div class="shell header-inner">
                <router-link class="brand" to="/" aria-label="Wish Gift — sākumlapa">
                    <img v-if="!logoFailed" class="brand-logo" :src="logoSrc" alt="Wish Gift" @error="logoFailed = true">
                    <span v-else class="brand-mark">WISH GIFT</span>
                    <span class="brand-copy">Wish Gift<small>Dāvanas, kas atdzīvojas</small></span>
                </router-link>
                <nav class="desktop-nav" aria-label="Galvenā navigācija">
                    <router-link v-for="link in links" :key="link.title" :class="{ 'router-link-active': isActive(link.to) }" :to="link.to">{{ link.title }}</router-link>
                </nav>
                <div class="header-actions">
                    <v-btn icon="mdi-magnify" variant="text" aria-label="Meklēt" />
                    <v-btn icon="mdi-account-outline" variant="text" aria-label="Profils" />
                    <v-btn icon="mdi-heart-outline" variant="text" aria-label="Izlase" />
                    <v-btn icon="mdi-cart-outline" variant="text" aria-label="Grozs" />
                    <v-btn class="menu-button" icon="mdi-menu" variant="text" aria-label="Atvērt izvēlni" :aria-expanded="menuOpen" @click="menuOpen = !menuOpen" />
                </div>
            </div>
            <nav v-if="menuOpen" class="mobile-nav" aria-label="Mobilā navigācija">
                <router-link class="mobile-nav-brand" to="/" aria-label="Wish Gift — sākumlapa" @click="menuOpen = false">
                    <img v-if="!logoFailed" class="brand-logo" :src="logoSrc" alt="Wish Gift" @error="logoFailed = true">
                    <span v-else>WISH GIFT</span>
                </router-link>
                <router-link v-for="link in links" :key="link.title" :class="{ 'router-link-active': isActive(link.to) }" :to="link.to" @click="menuOpen = false">{{ link.title }}</router-link>
                <div class="mobile-nav-actions" aria-label="Ātrās darbības">
                    <v-btn icon="mdi-magnify" variant="text" aria-label="Meklēt" />
                    <v-btn icon="mdi-account-outline" variant="text" aria-label="Profils" />
                    <v-btn icon="mdi-heart-outline" variant="text" aria-label="Izlase" />
                    <v-btn icon="mdi-cart-outline" variant="text" aria-label="Grozs" />
                </div>
            </nav>
        </header>

        <RouterView />

        <footer class="site-footer">
            <div class="shell footer-grid">
                <div><div class="footer-brand">Wish Gift</div><p>Dāvanas, kas atdzīvojas: foto, video, mūzika un QR kods personalizētā suvenīrā.</p><div class="footer-socials" aria-label="Sociālie tīkli"><v-btn icon="mdi-facebook" variant="text" aria-label="Facebook" /><v-btn icon="mdi-instagram" variant="text" aria-label="Instagram" /><v-btn icon="mdi-pinterest" variant="text" aria-label="Pinterest" /><v-btn icon="mdi-music-note" variant="text" aria-label="TikTok" /></div></div>
                <div><h2>Shop</h2><router-link to="/catalog">Visi produkti</router-link><router-link to="/souvenirs">Jaunumi</router-link><router-link to="/catalog">Best sellers</router-link><router-link to="/catalog">Piedāvājumi</router-link></div>
                <div><h2>Svētki</h2><router-link to="/occasion">Dzimšanas diena</router-link><router-link to="/occasion/mothers-day">Mātes diena</router-link><router-link to="/occasion/teachers-day">Skolotāju diena</router-link><router-link to="/occasion/christmas">Ziemassvētki</router-link></div>
                <div><h2>Palīdzība</h2><a href="tel:+37128153310">+371 28153310</a><a href="mailto:ozivajka@inbox.lv">ozivajka@inbox.lv</a><router-link to="/contacts">Kontakti</router-link></div>
            </div>
            <div class="shell footer-bottom"><span>© 2026 wish_gift</span><span>Privātuma politika</span></div>
        </footer>
    </v-app>
</template>
