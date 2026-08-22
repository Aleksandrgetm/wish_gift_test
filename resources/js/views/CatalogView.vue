<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { storeToRefs } from 'pinia';
import { useRoute } from 'vue-router';
import { useCatalogStore } from '../stores/catalog';
import CatalogFilters from '../components/catalog/CatalogFilters.vue';
import ProductCard from '../components/catalog/ProductCard.vue';
import QuickViewDialog from '../components/catalog/QuickViewDialog.vue';
import { useI18n } from '../composables/useI18n';
import { catalogFilterBySlug, catalogFilterGroups, products } from '../data/products';

const store = useCatalogStore();
const { loading, error, query, sort, page, filters, favorites, filtered, pageCount, visibleProducts, activeFilterCount } = storeToRefs(store);
const route = useRoute();
const { t, localizedProduct, translateOption } = useI18n();
const mobileFiltersOpen = ref(false);
const quickViewOpen = ref(false);
const selectedProduct = ref(null);
const productFilterFields = { occasions: 'occasion', categories: 'category', materials: 'material' };
const otherOccasion = 'Citi';
const otherCategory = 'Citi';
const sortOptions = computed(() => [
    { title: t('catalog.sort.featured'), value: 'featured' },
    { title: t('catalog.sort.new'), value: 'new' },
    { title: t('catalog.sort.priceAsc'), value: 'price-asc' },
    { title: t('catalog.sort.priceDesc'), value: 'price-desc' },
]);
const catalogSlug = computed(() => route.params.categorySlug);
const isCategoryPicker = computed(() => !catalogSlug.value);
const selectedCatalogEntry = computed(() => catalogFilterBySlug[catalogSlug.value] ?? null);
const groupTitleKeys = { occasions: 'occasionGroup', categories: 'categoryGroup', materials: 'materialGroup' };
const listedOccasions = catalogFilterGroups.find((group) => group.id === 'occasions')?.items.filter((item) => item.value !== otherOccasion).map((item) => item.value) ?? [];
const listedCategories = catalogFilterGroups.find((group) => group.id === 'categories')?.items.filter((item) => item.value !== otherCategory).map((item) => item.value) ?? [];
const catalogItemCount = (group, item) => {
    if (group.filterKey === 'occasions' && item.value === otherOccasion) {
        return products.filter((product) => !listedOccasions.includes(product.occasion)).length;
    }

    if (group.filterKey === 'categories' && item.value === otherCategory) {
        return products.filter((product) => !listedCategories.includes(product.category)).length;
    }

    return products.filter((product) => product[productFilterFields[group.filterKey]] === item.value).length;
};
const categoryGroups = computed(() => catalogFilterGroups.map((group) => ({
    ...group,
    title: t(`catalog.${groupTitleKeys[group.id]}`),
    items: group.items.map((item) => ({
        ...item,
        title: translateOption(item.value),
        count: catalogItemCount(group, item),
        to: { name: 'catalog-category', params: { categorySlug: item.slug } },
    })),
})));
const localizedVisibleProducts = computed(() => visibleProducts.value.map((product) => localizedProduct(product)));
const selectedProductLocalized = computed(() => (selectedProduct.value ? localizedProduct(selectedProduct.value) : null));
const selectedCatalogTitle = computed(() => (selectedCatalogEntry.value ? translateOption(selectedCatalogEntry.value.value) : t('catalog.title')));
const selectedCatalogFilterActive = computed(() => {
    if (!selectedCatalogEntry.value) return false;

    const selected = filters.value[selectedCatalogEntry.value.filterKey];
    return Array.isArray(selected) && selected.includes(selectedCatalogEntry.value.value);
});

const openQuickView = (product) => { selectedProduct.value = visibleProducts.value.find((item) => item.id === product.id) ?? product; quickViewOpen.value = true; };
const applyMobileFilters = () => { mobileFiltersOpen.value = false; page.value = 1; };
const applyInitialRouteFilter = () => {
    if (!catalogSlug.value) return;

    store.reset();
    if (!selectedCatalogEntry.value) return;

    const selected = filters.value[selectedCatalogEntry.value.filterKey];
    if (Array.isArray(selected) && !selected.includes(selectedCatalogEntry.value.value)) {
        filters.value[selectedCatalogEntry.value.filterKey] = [...selected, selectedCatalogEntry.value.value];
    }

    page.value = 1;
};
const resetFilters = () => {
    store.reset();
};

watch(catalogSlug, applyInitialRouteFilter, { immediate: true });
watch([query, sort, filters], () => { page.value = 1; }, { deep: true });
watch(page, () => document.querySelector('.catalog-results')?.scrollIntoView({ behavior: 'smooth', block: 'start' }));
onMounted(store.finishLoading);
</script>

<template>
    <main class="catalog-page">
        <section v-if="!isCategoryPicker" class="catalog-hero">
            <div class="shell catalog-hero-layout">
                <div class="catalog-hero-copy">
                    <nav class="breadcrumbs" :aria-label="t('catalog.breadcrumbs')"><router-link to="/">{{ t('catalog.home') }}</router-link><span>/</span><router-link to="/catalog">{{ t('catalog.title') }}</router-link><span>/</span><span>{{ selectedCatalogTitle }}</span></nav>
                    <p class="eyebrow">{{ t('catalog.eyebrow') }}</p>
                    <h1>{{ t('catalog.heroTitle') }}<br><em>{{ t('catalog.heroEmphasis') }}</em></h1>
                    <p>{{ t('catalog.heroText') }}</p>
                </div>
                <div class="catalog-hero-art" aria-hidden="true">
                    <span class="qr-detail"><i></i>QR / VIDEO</span>
                    <span class="qr-frame qr-frame-a"></span>
                    <span class="qr-frame qr-frame-b"></span>
                    <span class="qr-frame qr-frame-c"></span>
                    <span class="qr-cell qr-cell-a"></span>
                    <span class="qr-cell qr-cell-b"></span>
                    <span class="qr-cell qr-cell-c"></span>
                    <span class="qr-cell qr-cell-d"></span>
                    <span class="qr-cell qr-cell-e"></span>
                    <span class="qr-cell qr-cell-f"></span>
                    <span class="qr-line qr-line-a"></span>
                    <span class="qr-line qr-line-b"></span>
                    <span class="qr-corner qr-corner-a"></span>
                    <span class="qr-corner qr-corner-b"></span>
                    <span class="qr-guide"></span>
                </div>
            </div>
        </section>

        <section v-if="isCategoryPicker" class="catalog-category-choice shell" aria-labelledby="catalog-choice-title">
            <div class="catalog-choice-heading">
                <p class="eyebrow">{{ t('catalog.chooseEyebrow') }}</p>
                <h2 id="catalog-choice-title">{{ t('catalog.title') }}</h2>
            </div>

            <div v-for="group in categoryGroups" :key="group.id" class="catalog-choice-group">
                <div class="catalog-choice-group-head">
                    <h3>{{ group.title }}</h3>
                    <span>{{ group.items.length }}</span>
                </div>
                <div class="catalog-choice-grid">
                    <router-link v-for="item in group.items" :key="item.slug" class="catalog-choice-card" :class="`catalog-choice-card--${item.tone}`" :to="item.to" :aria-label="`${t('catalog.openCategory')}: ${item.title}`">
                        <img :src="item.image" :alt="item.title" loading="lazy">
                        <span class="catalog-choice-icon"><v-icon :icon="item.icon" size="22" aria-hidden="true" /></span>
                        <span class="catalog-choice-copy">
                            <strong>{{ item.title }}</strong>
                            <small>{{ item.count }} {{ item.count === 1 ? t('catalog.oneItem') : t('catalog.manyItems') }} <v-icon class="catalog-choice-arrow" icon="mdi-arrow-right" size="20" aria-hidden="true" /></small>
                        </span>
                    </router-link>
                </div>
            </div>
        </section>

        <section v-else class="catalog-results shell">
            <div class="catalog-toolbar">
                <div class="catalog-search"><v-icon icon="mdi-magnify" /><label class="sr-only" for="catalog-search">{{ t('catalog.searchLabel') }}</label><input id="catalog-search" v-model="query" type="search" :placeholder="t('catalog.searchPlaceholder')"><button v-if="query" type="button" :aria-label="t('catalog.clearSearch')" @click="query = ''"><v-icon icon="mdi-close" /></button></div>
                <div class="catalog-toolbar-actions"><v-chip v-if="selectedCatalogFilterActive" class="catalog-route-chip" color="primary" variant="tonal" size="small">{{ t('catalog.selectedCategory') }}: {{ selectedCatalogTitle }}</v-chip><v-btn class="mobile-filter-trigger" variant="outlined" prepend-icon="mdi-tune-variant" @click="mobileFiltersOpen = true">{{ t('catalog.filters') }} <span v-if="activeFilterCount">{{ activeFilterCount }}</span></v-btn><span class="result-count">{{ filtered.length }} {{ filtered.length === 1 ? t('catalog.oneItem') : t('catalog.manyItems') }}</span><v-select v-model="sort" class="catalog-sort" :items="sortOptions" item-title="title" item-value="value" :label="t('catalog.sorting')" variant="outlined" density="compact" hide-details /></div>
            </div>

            <div class="catalog-layout">
                <aside class="desktop-filters" :aria-label="t('catalog.filterAria')"><CatalogFilters :filters="filters" :active-count="activeFilterCount" @reset="resetFilters" /></aside>
                <div class="catalog-content" aria-live="polite" :aria-busy="loading">
                    <div v-if="loading" class="catalog-grid"><div v-for="item in 6" :key="item" class="catalog-skeleton"><v-skeleton-loader type="image, article" /></div></div>
                    <div v-else-if="error" class="catalog-empty"><div class="empty-icon"><v-icon icon="mdi-cloud-alert-outline" size="42" /></div><p class="eyebrow">{{ t('catalog.loadError') }}</p><h2>{{ t('catalog.retryTitle') }}</h2><p>{{ t('catalog.retryText') }}</p><v-btn color="primary" @click="store.retry">{{ t('catalog.retry') }}</v-btn></div>
                    <div v-else-if="visibleProducts.length" class="catalog-grid">
                        <ProductCard v-for="product in localizedVisibleProducts" :key="product.id" :product="product" :favorite="favorites.includes(product.id)" @favorite="store.toggleFavorite" @quick-view="openQuickView" />
                    </div>
                    <div v-else class="catalog-empty"><div class="empty-icon"><v-icon icon="mdi-package-variant-closed-remove" size="42" /></div><p class="eyebrow">{{ t('catalog.emptyEyebrow') }}</p><h2>{{ t('catalog.emptyTitle') }}</h2><p>{{ t('catalog.emptyText') }}</p><v-btn color="primary" @click="resetFilters">{{ t('catalog.reset') }}</v-btn></div>
                    <v-pagination v-if="!loading && filtered.length > store.perPage" v-model="page" class="catalog-pagination" :length="pageCount" rounded="circle" active-color="primary" />
                </div>
            </div>
        </section>

        <v-navigation-drawer v-model="mobileFiltersOpen" location="right" temporary width="360" class="mobile-filter-drawer"><div class="mobile-filter-head"><h2>{{ t('catalog.filters') }}</h2><v-btn icon="mdi-close" variant="text" :aria-label="t('catalog.closeFilters')" @click="mobileFiltersOpen = false" /></div><CatalogFilters :filters="filters" :active-count="activeFilterCount" @reset="resetFilters" /><div class="mobile-filter-actions"><v-btn variant="text" @click="resetFilters">{{ t('filters.reset') }}</v-btn><v-btn color="primary" @click="applyMobileFilters">{{ t('catalog.show') }} {{ filtered.length }}</v-btn></div></v-navigation-drawer>
        <QuickViewDialog v-model="quickViewOpen" :product="selectedProductLocalized" />
    </main>
</template>
