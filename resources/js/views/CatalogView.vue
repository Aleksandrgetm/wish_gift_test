<script setup>
import { onMounted, ref, watch } from 'vue';
import { storeToRefs } from 'pinia';
import { useCatalogStore } from '../stores/catalog';
import CatalogFilters from '../components/catalog/CatalogFilters.vue';
import ProductCard from '../components/catalog/ProductCard.vue';
import QuickViewDialog from '../components/catalog/QuickViewDialog.vue';

const store = useCatalogStore();
const { loading, error, query, sort, page, filters, favorites, filtered, pageCount, visibleProducts, activeFilterCount } = storeToRefs(store);
const mobileFiltersOpen = ref(false);
const quickViewOpen = ref(false);
const selectedProduct = ref(null);
const sortOptions = [
    { title: 'По популярности', value: 'featured' },
    { title: 'Сначала новинки', value: 'new' },
    { title: 'Сначала дешевле', value: 'price-asc' },
    { title: 'Сначала дороже', value: 'price-desc' },
];

const openQuickView = (product) => { selectedProduct.value = product; quickViewOpen.value = true; };
const applyMobileFilters = () => { mobileFiltersOpen.value = false; page.value = 1; };
watch([query, sort, filters], () => { page.value = 1; }, { deep: true });
watch(page, () => document.querySelector('.catalog-results')?.scrollIntoView({ behavior: 'smooth', block: 'start' }));
onMounted(store.finishLoading);
</script>

<template>
    <main class="catalog-page">
        <section class="catalog-hero">
            <div class="shell"><nav class="breadcrumbs" aria-label="Хлебные крошки"><router-link to="/">Главная</router-link><span>/</span><span>Каталог</span></nav><p class="eyebrow">Сувениры-оживайки</p><h1>Подарки с фото,<br><em>видео и QR-кодом</em></h1><p>Выберите направление и согласуйте с менеджером фото, видео, музыку и финальный дизайн подарка.</p></div>
        </section>

        <section class="catalog-results shell">
            <div class="catalog-toolbar">
                <div class="catalog-search"><v-icon icon="mdi-magnify" /><label class="sr-only" for="catalog-search">Поиск по каталогу</label><input id="catalog-search" v-model="query" type="search" placeholder="Найти оживайку или сувенир"><button v-if="query" type="button" aria-label="Очистить поиск" @click="query = ''"><v-icon icon="mdi-close" /></button></div>
                <div class="catalog-toolbar-actions"><v-btn class="mobile-filter-trigger" variant="outlined" prepend-icon="mdi-tune-variant" @click="mobileFiltersOpen = true">Фильтры <span v-if="activeFilterCount">{{ activeFilterCount }}</span></v-btn><span class="result-count">{{ filtered.length }} {{ filtered.length === 1 ? 'товар' : 'товаров' }}</span><v-select v-model="sort" class="catalog-sort" :items="sortOptions" item-title="title" item-value="value" label="Сортировка" variant="outlined" density="compact" hide-details /></div>
            </div>

            <div class="catalog-layout">
                <aside class="desktop-filters" aria-label="Фильтры каталога"><CatalogFilters :filters="filters" :active-count="activeFilterCount" @reset="store.reset" /></aside>
                <div class="catalog-content" aria-live="polite" :aria-busy="loading">
                    <div v-if="loading" class="catalog-grid"><div v-for="item in 6" :key="item" class="catalog-skeleton"><v-skeleton-loader type="image, article" /></div></div>
                    <div v-else-if="error" class="catalog-empty"><div class="empty-icon"><v-icon icon="mdi-cloud-alert-outline" size="42" /></div><p class="eyebrow">Не удалось загрузить каталог</p><h2>Попробуем ещё раз?</h2><p>Проверьте соединение и повторите загрузку. Выбранные фильтры сохранятся.</p><v-btn color="primary" @click="store.retry">Повторить</v-btn></div>
                    <div v-else-if="visibleProducts.length" class="catalog-grid">
                        <ProductCard v-for="product in visibleProducts" :key="product.id" :product="product" :favorite="favorites.includes(product.id)" @favorite="store.toggleFavorite" @quick-view="openQuickView" />
                    </div>
                    <div v-else class="catalog-empty"><div class="empty-icon"><v-icon icon="mdi-package-variant-closed-remove" size="42" /></div><p class="eyebrow">Ничего не потеряно</p><h2>Подходящих вариантов пока нет</h2><p>Попробуйте убрать один из фильтров или изменить поисковый запрос.</p><v-btn color="primary" @click="store.reset">Сбросить фильтры</v-btn></div>
                    <v-pagination v-if="!loading && filtered.length > store.perPage" v-model="page" class="catalog-pagination" :length="pageCount" rounded="circle" active-color="primary" />
                </div>
            </div>
        </section>

        <v-navigation-drawer v-model="mobileFiltersOpen" location="right" temporary width="360" class="mobile-filter-drawer"><div class="mobile-filter-head"><h2>Фильтры</h2><v-btn icon="mdi-close" variant="text" aria-label="Закрыть фильтры" @click="mobileFiltersOpen = false" /></div><CatalogFilters :filters="filters" :active-count="activeFilterCount" @reset="store.reset" /><div class="mobile-filter-actions"><v-btn variant="text" @click="store.reset">Сбросить</v-btn><v-btn color="primary" @click="applyMobileFilters">Показать {{ filtered.length }}</v-btn></div></v-navigation-drawer>
        <QuickViewDialog v-model="quickViewOpen" :product="selectedProduct" />
    </main>
</template>
