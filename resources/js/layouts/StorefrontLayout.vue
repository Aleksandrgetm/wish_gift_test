<script setup>
import { ref } from 'vue';
import { RouterView } from 'vue-router';

const menuOpen = ref(false);
const links = [
    { title: 'Каталог', to: '/catalog' },
    { title: 'Коллекции', to: '/#collections' },
    { title: 'Корпоративным клиентам', to: '/corporate' },
    { title: 'О компании', to: '/about' },
];
</script>

<template>
    <div class="announcement">Бесплатная доставка образцов для корпоративных заказов</div>
    <header class="site-header">
        <div class="shell header-inner">
            <router-link class="brand" to="/" aria-label="Éclat, на главную">
                <span class="brand-mark">É</span>
                <span>ÉCLAT<small>PACKAGING ATELIER</small></span>
            </router-link>
            <nav class="desktop-nav" aria-label="Основная навигация">
                <router-link v-for="link in links" :key="link.title" :to="link.to">{{ link.title }}</router-link>
            </nav>
            <div class="header-actions">
                <v-btn icon="mdi-magnify" variant="text" aria-label="Поиск" />
                <v-btn icon="mdi-heart-outline" variant="text" aria-label="Избранное" />
                <v-btn icon="mdi-shopping-outline" variant="text" aria-label="Корзина" />
                <v-btn class="menu-button" icon="mdi-menu" variant="text" aria-label="Открыть меню" :aria-expanded="menuOpen" @click="menuOpen = !menuOpen" />
            </div>
        </div>
        <nav v-if="menuOpen" class="mobile-nav" aria-label="Мобильная навигация">
            <router-link v-for="link in links" :key="link.title" :to="link.to" @click="menuOpen = false">{{ link.title }}</router-link>
        </nav>
    </header>

    <RouterView v-slot="{ Component, route }">
        <Transition name="page" mode="out-in">
            <component :is="Component" :key="route.path" />
        </Transition>
    </RouterView>

    <footer class="site-footer">
        <div class="shell footer-grid">
            <div><div class="footer-brand">ÉCLAT</div><p>Упаковка, которая становится частью подарка.</p></div>
            <div><h2>Покупателям</h2><a href="/catalog">Каталог</a><a href="#finder">Подбор упаковки</a><a href="#faq">FAQ</a></div>
            <div><h2>Компания</h2><a href="/about">О нас</a><a href="/corporate">Корпоративным клиентам</a><a href="/contacts">Контакты</a></div>
            <div><h2>Связаться</h2><a href="tel:+74950000000">+7 495 000-00-00</a><a href="mailto:hello@eclat-pack.ru">hello@eclat-pack.ru</a><p>Пн–Пт, 9:00–18:00</p></div>
        </div>
        <div class="shell footer-bottom"><span>© 2026 Éclat</span><span>Политика конфиденциальности</span></div>
    </footer>
</template>
