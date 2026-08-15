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
const links = [
    { title: 'Каталог', to: '/catalog' },
    { title: 'Сувениры-оживайки', to: '/souvenirs' },
    { title: 'Контакты', to: '/contacts' },
];
const isActive = (to) => route.path === to || route.path.startsWith(`${to}/`);
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
        <div class="announcement">Персонализированные подарки с фото, видео, музыкой и QR-кодом — детали согласуем перед изготовлением</div>
        <header ref="header" class="site-header" :class="{ 'site-header--compact': headerCompact }">
            <div class="shell header-inner">
                <router-link class="brand" to="/" aria-label="Wish Gift — главная">
                    <img v-if="!logoFailed" class="brand-logo" :src="logoSrc" alt="Wish Gift" @error="logoFailed = true">
                    <span v-else class="brand-mark">WISH GIFT</span>
                    <span class="brand-copy">Wish Gift<small>Подарки, которые оживают</small></span>
                </router-link>
                <nav class="desktop-nav" aria-label="Главная навигация">
                    <router-link v-for="link in links" :key="link.title" :class="{ 'router-link-active': isActive(link.to) }" :to="link.to">{{ link.title }}</router-link>
                </nav>
                <div class="header-actions">
                    <v-btn icon="mdi-magnify" variant="text" aria-label="Поиск" />
                    <v-btn icon="mdi-heart-outline" variant="text" aria-label="Избранное" />
                    <v-btn icon="mdi-shopping-outline" variant="text" aria-label="Корзина" />
                    <v-btn class="menu-button" icon="mdi-menu" variant="text" aria-label="Открыть меню" :aria-expanded="menuOpen" @click="menuOpen = !menuOpen" />
                </div>
            </div>
            <nav v-if="menuOpen" class="mobile-nav" aria-label="Мобильная навигация">
                <router-link class="mobile-nav-brand" to="/" aria-label="Wish Gift — главная" @click="menuOpen = false">
                    <img v-if="!logoFailed" class="brand-logo" :src="logoSrc" alt="Wish Gift" @error="logoFailed = true">
                    <span v-else>WISH GIFT</span>
                </router-link>
                <router-link v-for="link in links" :key="link.title" :class="{ 'router-link-active': isActive(link.to) }" :to="link.to" @click="menuOpen = false">{{ link.title }}</router-link>
                <div class="mobile-nav-actions" aria-label="Быстрые действия">
                    <v-btn icon="mdi-magnify" variant="text" aria-label="Поиск" />
                    <v-btn icon="mdi-heart-outline" variant="text" aria-label="Избранное" />
                    <v-btn icon="mdi-shopping-outline" variant="text" aria-label="Корзина" />
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
                <div><div class="footer-brand">wish_gift</div><p>Подарки, которые оживают: фото, видео, музыка и QR-код в персонализированном сувенире.</p><v-btn color="secondary" size="small" href="mailto:ozivajka@inbox.lv">Создать подарок</v-btn></div>
                <div><h2>Навигация</h2><router-link to="/catalog">Каталог</router-link><router-link to="/souvenirs">Сувениры-оживайки</router-link><router-link to="/contacts">Контакты</router-link></div>
                <div><h2>Контакты</h2><a href="tel:+37128153310">+371 28153310</a><a href="mailto:ozivajka@inbox.lv">ozivajka@inbox.lv</a><p>Сначала согласуем фото, видео, музыку и макет подарка.</p></div>
            </div>
            <div class="shell footer-bottom"><span>© 2026 wish_gift</span><span>Политика конфиденциальности</span></div>
        </footer>
    </v-app>
</template>
