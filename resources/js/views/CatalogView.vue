<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { storeToRefs } from 'pinia';
import { useCatalogStore } from '../stores/catalog';
import CatalogFilters from '../components/catalog/CatalogFilters.vue';
import ProductCard from '../components/catalog/ProductCard.vue';
import QuickViewDialog from '../components/catalog/QuickViewDialog.vue';
import { useI18n } from '../composables/useI18n';

const store = useCatalogStore();
const { loading, error, query, sort, page, filters, favorites, filtered, pageCount, visibleProducts, activeFilterCount } = storeToRefs(store);
const { t, localizedProduct } = useI18n();
const mobileFiltersOpen = ref(false);
const quickViewOpen = ref(false);
const selectedProduct = ref(null);
const sortOptions = computed(() => [
    { title: t('catalog.sort.featured'), value: 'featured' },
    { title: t('catalog.sort.new'), value: 'new' },
    { title: t('catalog.sort.priceAsc'), value: 'price-asc' },
    { title: t('catalog.sort.priceDesc'), value: 'price-desc' },
]);
const localizedVisibleProducts = computed(() => visibleProducts.value.map((product) => localizedProduct(product)));
const selectedProductLocalized = computed(() => (selectedProduct.value ? localizedProduct(selectedProduct.value) : null));

const openQuickView = (product) => { selectedProduct.value = visibleProducts.value.find((item) => item.id === product.id) ?? product; quickViewOpen.value = true; };
const applyMobileFilters = () => { mobileFiltersOpen.value = false; page.value = 1; };
watch([query, sort, filters], () => { page.value = 1; }, { deep: true });
watch(page, () => document.querySelector('.catalog-results')?.scrollIntoView({ behavior: 'smooth', block: 'start' }));
onMounted(store.finishLoading);
</script>

<template>
    <main class="catalog-page">
        <section class="catalog-hero">
            <div class="shell"><nav class="breadcrumbs" :aria-label="t('catalog.breadcrumbs')"><router-link to="/">{{ t('catalog.home') }}</router-link><span>/</span><span>{{ t('catalog.title') }}</span></nav><p class="eyebrow">{{ t('catalog.eyebrow') }}</p><h1>{{ t('catalog.heroTitle') }}<br><em>{{ t('catalog.heroEmphasis') }}</em></h1><p>{{ t('catalog.heroText') }}</p></div>
        </section>

        <section class="catalog-results shell">
            <div class="catalog-toolbar">
                <div class="catalog-search"><v-icon icon="mdi-magnify" /><label class="sr-only" for="catalog-search">{{ t('catalog.searchLabel') }}</label><input id="catalog-search" v-model="query" type="search" :placeholder="t('catalog.searchPlaceholder')"><button v-if="query" type="button" :aria-label="t('catalog.clearSearch')" @click="query = ''"><v-icon icon="mdi-close" /></button></div>
                <div class="catalog-toolbar-actions"><v-btn class="mobile-filter-trigger" variant="outlined" prepend-icon="mdi-tune-variant" @click="mobileFiltersOpen = true">{{ t('catalog.filters') }} <span v-if="activeFilterCount">{{ activeFilterCount }}</span></v-btn><span class="result-count">{{ filtered.length }} {{ filtered.length === 1 ? t('catalog.oneItem') : t('catalog.manyItems') }}</span><v-select v-model="sort" class="catalog-sort" :items="sortOptions" item-title="title" item-value="value" :label="t('catalog.sorting')" variant="outlined" density="compact" hide-details /></div>
            </div>

            <div class="catalog-layout">
                <aside class="desktop-filters" :aria-label="t('catalog.filterAria')"><CatalogFilters :filters="filters" :active-count="activeFilterCount" @reset="store.reset" /></aside>
                <div class="catalog-content" aria-live="polite" :aria-busy="loading">
                    <div v-if="loading" class="catalog-grid"><div v-for="item in 6" :key="item" class="catalog-skeleton"><v-skeleton-loader type="image, article" /></div></div>
                    <div v-else-if="error" class="catalog-empty"><div class="empty-icon"><v-icon icon="mdi-cloud-alert-outline" size="42" /></div><p class="eyebrow">{{ t('catalog.loadError') }}</p><h2>{{ t('catalog.retryTitle') }}</h2><p>{{ t('catalog.retryText') }}</p><v-btn color="primary" @click="store.retry">{{ t('catalog.retry') }}</v-btn></div>
                    <div v-else-if="visibleProducts.length" class="catalog-grid">
                        <ProductCard v-for="product in localizedVisibleProducts" :key="product.id" :product="product" :favorite="favorites.includes(product.id)" @favorite="store.toggleFavorite" @quick-view="openQuickView" />
                    </div>
                    <div v-else class="catalog-empty"><div class="empty-icon"><v-icon icon="mdi-package-variant-closed-remove" size="42" /></div><p class="eyebrow">{{ t('catalog.emptyEyebrow') }}</p><h2>{{ t('catalog.emptyTitle') }}</h2><p>{{ t('catalog.emptyText') }}</p><v-btn color="primary" @click="store.reset">{{ t('catalog.reset') }}</v-btn></div>
                    <v-pagination v-if="!loading && filtered.length > store.perPage" v-model="page" class="catalog-pagination" :length="pageCount" rounded="circle" active-color="primary" />
                </div>
            </div>
        </section>

        <v-navigation-drawer v-model="mobileFiltersOpen" location="right" temporary width="360" class="mobile-filter-drawer"><div class="mobile-filter-head"><h2>{{ t('catalog.filters') }}</h2><v-btn icon="mdi-close" variant="text" :aria-label="t('catalog.closeFilters')" @click="mobileFiltersOpen = false" /></div><CatalogFilters :filters="filters" :active-count="activeFilterCount" @reset="store.reset" /><div class="mobile-filter-actions"><v-btn variant="text" @click="store.reset">{{ t('filters.reset') }}</v-btn><v-btn color="primary" @click="applyMobileFilters">{{ t('catalog.show') }} {{ filtered.length }}</v-btn></div></v-navigation-drawer>
        <QuickViewDialog v-model="quickViewOpen" :product="selectedProductLocalized" />
    </main>
</template>
