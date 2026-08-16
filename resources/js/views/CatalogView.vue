<script setup>
import { onMounted, ref, watch } from 'vue';
import { storeToRefs } from 'pinia';
import { useCatalogStore } from '../stores/catalog';
import CatalogFilters from '../components/catalog/CatalogFilters.vue';
import ProductCard from '../components/catalog/ProductCard.vue';
import QuickViewDialog from '../components/catalog/QuickViewDialog.vue';

const store = useCatalogStore();
const { loading, error, query, sort, page, filters, filtered, pageCount, visibleProducts, activeFilterCount } = storeToRefs(store);
const mobileFiltersOpen = ref(false);
const quickViewOpen = ref(false);
const selectedProduct = ref(null);
const sortOptions = [
    { title: 'Pēc popularitātes', value: 'featured' },
    { title: 'Vispirms jaunumi', value: 'new' },
    { title: 'Vispirms lētākie', value: 'price-asc' },
    { title: 'Vispirms dārgākie', value: 'price-desc' },
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
            <div class="shell"><nav class="breadcrumbs" aria-label="Atpakaļceļa navigācija"><router-link to="/">Sākumlapa</router-link><span>/</span><span>Katalogs</span></nav><p class="eyebrow">Suvenīri ar QR video</p><h1>Dāvanas ar foto,<br><em>video un QR kodu</em></h1><p>Izvēlieties virzienu un saskaņojiet ar menedžeri foto, video, mūziku un dāvanas gala dizainu.</p></div>
        </section>

        <section class="catalog-results shell">
            <div class="catalog-toolbar">
                <div class="catalog-search"><v-icon icon="mdi-magnify" /><label class="sr-only" for="catalog-search">Meklēt katalogā</label><input id="catalog-search" v-model="query" type="search" placeholder="Atrast QR dāvanu vai suvenīru"><button v-if="query" type="button" aria-label="Notīrīt meklēšanu" @click="query = ''"><v-icon icon="mdi-close" /></button></div>
                <div class="catalog-toolbar-actions"><v-btn class="mobile-filter-trigger" variant="outlined" prepend-icon="mdi-tune-variant" @click="mobileFiltersOpen = true">Filtri <span v-if="activeFilterCount">{{ activeFilterCount }}</span></v-btn><span class="result-count">{{ filtered.length }} {{ filtered.length === 1 ? 'prece' : 'preces' }}</span><v-select v-model="sort" class="catalog-sort" :items="sortOptions" item-title="title" item-value="value" label="Kārtošana" variant="outlined" density="compact" hide-details /></div>
            </div>

            <div class="catalog-layout">
                <aside class="desktop-filters" aria-label="Kataloga filtri"><CatalogFilters :filters="filters" :active-count="activeFilterCount" @reset="store.reset" /></aside>
                <div class="catalog-content" aria-live="polite" :aria-busy="loading">
                    <div v-if="loading" class="catalog-grid"><div v-for="item in 6" :key="item" class="catalog-skeleton"><v-skeleton-loader type="image, article" /></div></div>
                    <div v-else-if="error" class="catalog-empty"><div class="empty-icon"><v-icon icon="mdi-cloud-alert-outline" size="42" /></div><p class="eyebrow">Neizdevās ielādēt katalogu</p><h2>Mēģināsim vēlreiz?</h2><p>Pārbaudiet savienojumu un atkārtojiet ielādi. Izvēlētie filtri tiks saglabāti.</p><v-btn color="primary" @click="store.retry">Atkārtot</v-btn></div>
                    <div v-else-if="visibleProducts.length" class="catalog-grid">
                        <ProductCard v-for="product in visibleProducts" :key="product.id" :product="product" @quick-view="openQuickView" />
                    </div>
                    <div v-else class="catalog-empty"><div class="empty-icon"><v-icon icon="mdi-package-variant-closed-remove" size="42" /></div><p class="eyebrow">Viss kārtībā</p><h2>Piemērotu variantu pagaidām nav</h2><p>Pamēģiniet noņemt kādu filtru vai mainīt meklēšanas vaicājumu.</p><v-btn color="primary" @click="store.reset">Atiestatīt filtrus</v-btn></div>
                    <v-pagination v-if="!loading && filtered.length > store.perPage" v-model="page" class="catalog-pagination" :length="pageCount" rounded="circle" active-color="primary" />
                </div>
            </div>
        </section>

        <v-navigation-drawer v-model="mobileFiltersOpen" location="right" temporary width="360" class="mobile-filter-drawer"><div class="mobile-filter-head"><h2>Filtri</h2><v-btn icon="mdi-close" variant="text" aria-label="Aizvērt filtrus" @click="mobileFiltersOpen = false" /></div><CatalogFilters :filters="filters" :active-count="activeFilterCount" @reset="store.reset" /><div class="mobile-filter-actions"><v-btn variant="text" @click="store.reset">Atiestatīt</v-btn><v-btn color="primary" @click="applyMobileFilters">Parādīt {{ filtered.length }}</v-btn></div></v-navigation-drawer>
        <QuickViewDialog v-model="quickViewOpen" :product="selectedProduct" />
    </main>
</template>
