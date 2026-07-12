<script setup>
import { ref } from 'vue';
import { RouterView } from 'vue-router';

const menuOpen = ref(false);
const links = [
    { title: 'Каталог', to: '/catalog' },
    { title: 'Сувениры-оживайки', to: '/souvenirs' },
    { title: 'По получателю', to: '/recipient' },
    { title: 'По празднику', to: '/occasion' },
    { title: 'Как это работает', to: '/#how-it-works' },
    { title: 'Контакты', to: '/contacts' },
];
</script>

<template>
    <v-app>
        <div class="announcement">Персональные подарки с фото, видео, музыкой и QR-кодом — согласуйте заказ перед оформлением</div>
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
                <div><div class="footer-brand">ÉCLAT</div><p>Подарки, которые оживают: фото, видео, музыка и QR-код в персональном сувенире.</p><v-btn color="secondary" size="small" href="mailto:ozivajka@inbox.lv">Создать свой подарок</v-btn></div>
                <div><h2>Категории</h2><a href="/souvenirs/photo">Фото-оживайка</a><a href="/souvenirs/mug">Оживи кружку</a><a href="/souvenirs/chocolate">Шоколад</a><a href="/souvenirs/track-record">Трек-пластинка</a></div>
                <div><h2>Получатели и праздники</h2><a href="/recipient/man">Подарок мужчине</a><a href="/recipient/woman">Подарок женщине</a><a href="/occasion/new-year">Новый год</a><a href="/occasion/womens-day">8 марта</a></div>
                <div><h2>Контакты</h2><a href="tel:+37128153310">+371 28153310</a><a href="mailto:ozivajka@inbox.lv">ozivajka@inbox.lv</a><p>Сначала согласуйте фото, видео и музыку с менеджером.</p></div>
            </div>
            <div class="shell footer-bottom"><span>© 2026 Éclat</span><span>Политика конфиденциальности</span></div>
        </footer>
    </v-app>
</template>
