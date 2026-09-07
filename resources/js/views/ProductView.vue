<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import ProductCard from '../components/catalog/ProductCard.vue';
import { useI18n } from '../composables/useI18n';
import { jsonRequest } from '../services/http';
import { useCatalogStore } from '../stores/catalog';

const route = useRoute();
const router = useRouter();
const store = useCatalogStore();
const { locale, t } = useI18n();
const product = ref(null);
const loading = ref(true);
const notFound = ref(false);
const activeImageIndex = ref(0);
const selectedSize = ref(null);
const quantity = ref(1);
const addingToCart = ref(false);
const imageDialog = ref(false);
const cartSnackbar = computed({
    get: () => Boolean(store.cartNotice),
    set: (value) => {
        if (!value) store.cartNotice = '';
    },
});

const images = computed(() => product.value?.images?.length ? product.value.images : (product.value?.image ? [{ image_url: product.value.image, alt: product.value.name }] : []));
const activeImage = computed(() => images.value[activeImageIndex.value] ?? images.value[0] ?? null);
const price = computed(() => product.value ? `${Number(product.value.price).toLocaleString('lv-LV', { minimumFractionDigits: Number(product.value.price) % 1 ? 2 : 0 })} €` : '');
const shortDescription = computed(() => {
    const text = product.value?.description || '';

    return text.length > 190 ? `${text.slice(0, 184).trim()}...` : text;
});
const isFavorite = computed(() => product.value ? store.favorites.includes(product.value.id) : false);
const relatedProducts = computed(() => {
    if (!product.value) return [];

    const categoryIds = new Set(product.value.categories?.map((category) => category.id) || []);

    return store.products
        .filter((item) => item.id !== product.value.id && item.categories?.some((category) => categoryIds.has(category.id)))
        .slice(0, 6);
});
const serviceItems = computed(() => t('productPage.serviceItems') || []);
const deliveryOptions = computed(() => t('productPage.deliveryOptions') || []);
const productInfoRows = computed(() => {
    if (!product.value) return [];

    const rows = [
        { label: t('productPage.productId'), value: product.value.id ? `#${product.value.id}` : null },
        { label: t('productPage.categories'), value: product.value.categories?.map((category) => category.name).join(', ') },
        { label: t('product.sizes'), value: product.value.sizes?.join(', ') },
    ];

    return rows.filter((row) => row.value);
});

const loadProduct = async () => {
    loading.value = true;
    notFound.value = false;

    try {
        const payload = await jsonRequest(`/api/catalog/products/${route.params.slug}?locale=${locale.value}`);
        product.value = payload.product;
        activeImageIndex.value = 0;
        selectedSize.value = payload.product?.sizes?.[0] || null;
        quantity.value = 1;

        if (!store.products.length) {
            await store.finishLoading();
        }
    } catch (error) {
        if (error.status === 404) {
            notFound.value = true;
            product.value = null;
            return;
        }

        throw error;
    } finally {
        loading.value = false;
    }
};

const previousImage = () => {
    if (images.value.length < 2) return;
    activeImageIndex.value = activeImageIndex.value === 0 ? images.value.length - 1 : activeImageIndex.value - 1;
};

const nextImage = () => {
    if (images.value.length < 2) return;
    activeImageIndex.value = activeImageIndex.value === images.value.length - 1 ? 0 : activeImageIndex.value + 1;
};

const openImageDialog = () => {
    if (activeImage.value) imageDialog.value = true;
};

const addCurrentProductToCart = async () => {
    if (!product.value) return;

    addingToCart.value = true;
    store.addToCart(product.value, { size: selectedSize.value, quantity: quantity.value });
    window.setTimeout(() => {
        addingToCart.value = false;
    }, 350);
};

watch(() => route.params.slug, loadProduct);
watch(locale, loadProduct);
onMounted(loadProduct);
</script>

<template>
    <main class="product-page">
        <section v-if="loading" class="product-loading shell">
            <v-progress-circular indeterminate color="primary" />
        </section>

        <section v-else-if="notFound" class="product-not-found shell">
            <p class="eyebrow">{{ t('productPage.notFoundEyebrow') }}</p>
            <h1>{{ t('productPage.notFoundTitle') }}</h1>
            <p>{{ t('productPage.notFoundText') }}</p>
            <v-btn color="primary" prepend-icon="mdi-arrow-left" @click="router.push('/catalog')">{{ t('productPage.backToCatalog') }}</v-btn>
        </section>

        <template v-else-if="product">
            <section class="product-detail shell">
                <nav class="breadcrumbs" :aria-label="t('catalog.breadcrumbs')">
                    <router-link to="/">{{ t('catalog.home') }}</router-link>
                    <span>/</span>
                    <router-link to="/catalog">{{ t('catalog.title') }}</router-link>
                    <span>/</span>
                    <span>{{ product.name.length > 42 ? `${product.name.slice(0, 39)}...` : product.name }}</span>
                </nav>

                <div class="product-layout">
                    <div class="product-gallery">
                        <div v-if="images.length > 1" class="product-thumbnails" :aria-label="t('productPage.gallery')">
                            <button
                                v-for="(image, index) in images"
                                :key="image.id || image.image_url"
                                type="button"
                                :class="{ active: index === activeImageIndex }"
                                :aria-label="`${t('productPage.showImage')} ${index + 1}`"
                                @click="activeImageIndex = index"
                            >
                                <img :src="image.image_url" :alt="image.alt || product.name">
                            </button>
                        </div>
                        <div class="product-main-image">
                            <v-window v-if="activeImage" v-model="activeImageIndex" touch>
                                <v-window-item v-for="image in images" :key="image.id || image.image_url">
                                    <button type="button" :aria-label="t('productPage.openImage')" @click="openImageDialog">
                                        <img :src="image.image_url" :alt="image.alt || product.name">
                                    </button>
                                </v-window-item>
                            </v-window>
                            <div v-else class="product-image-fallback">QR</div>
                            <button
                                v-if="images.length > 1"
                                class="product-gallery-arrow product-gallery-arrow--prev"
                                type="button"
                                :aria-label="t('productPage.previousImage')"
                                @click="previousImage"
                            >
                                <v-icon icon="mdi-chevron-left" />
                            </button>
                            <button
                                v-if="images.length > 1"
                                class="product-gallery-arrow product-gallery-arrow--next"
                                type="button"
                                :aria-label="t('productPage.nextImage')"
                                @click="nextImage"
                            >
                                <v-icon icon="mdi-chevron-right" />
                            </button>
                            <button
                                class="product-wishlist product-wishlist--gallery"
                                type="button"
                                :aria-label="isFavorite ? t('product.removeFavorite') : t('product.addFavorite')"
                                :aria-pressed="isFavorite"
                                @click="store.toggleFavorite(product.id)"
                            >
                                <v-icon :icon="isFavorite ? 'mdi-heart' : 'mdi-heart-outline'" />
                            </button>
                            <span v-if="images.length > 1" class="product-image-count">{{ activeImageIndex + 1 }} / {{ images.length }}</span>
                            <span v-if="activeImage" class="product-zoom-hint"><v-icon icon="mdi-magnify-plus-outline" size="16" />{{ t('productPage.openImage') }}</span>
                        </div>
                    </div>

                    <aside class="product-summary">
                        <div class="product-summary-head">
                            <div>
                                <v-chip v-if="product.isNew" color="secondary" variant="tonal" size="small">{{ t('product.new') }}</v-chip>
                                <v-chip :color="product.available ? 'success' : undefined" variant="tonal" size="small">{{ product.available ? t('product.available') : t('product.onRequest') }}</v-chip>
                            </div>
                            <button
                                class="product-wishlist"
                                type="button"
                                :aria-label="isFavorite ? t('product.removeFavorite') : t('product.addFavorite')"
                                :aria-pressed="isFavorite"
                                @click="store.toggleFavorite(product.id)"
                            >
                                <v-icon :icon="isFavorite ? 'mdi-heart' : 'mdi-heart-outline'" />
                            </button>
                        </div>
                        <h1>{{ product.name }}</h1>
                        <strong class="product-price">{{ price }}</strong>
                        <p>{{ shortDescription }}</p>

                        <div v-if="product.categories?.length" class="product-category-tags">
                            <router-link v-for="category in product.categories" :key="category.id" :to="{ name: 'catalog-category', params: { categorySlug: category.slug } }">{{ category.name }}</router-link>
                        </div>

                        <div v-if="product.sizes?.length" class="product-option-group">
                            <span>{{ t('product.sizes') }}</span>
                            <div>
                                <button
                                    v-for="size in product.sizes"
                                    :key="size"
                                    type="button"
                                    :class="{ active: selectedSize === size }"
                                    @click="selectedSize = size"
                                >
                                    {{ size }}
                                </button>
                            </div>
                        </div>

                        <div class="product-buy-box">
                            <div class="quantity-stepper" :aria-label="t('productPage.quantity')">
                                <button type="button" :disabled="quantity <= 1" :aria-label="t('productPage.decreaseQuantity')" @click="quantity = Math.max(1, quantity - 1)">−</button>
                                <span>{{ quantity }}</span>
                                <button type="button" :aria-label="t('productPage.increaseQuantity')" @click="quantity += 1">+</button>
                            </div>
                            <v-btn class="product-add-btn" color="primary" size="large" prepend-icon="mdi-cart-outline" block :loading="addingToCart" :disabled="addingToCart" @click="addCurrentProductToCart">{{ t('product.addCart') }}</v-btn>
                        </div>

                        <ul class="product-service-list" :aria-label="t('productPage.serviceTitle')">
                            <li v-for="item in serviceItems" :key="item.title">
                                <v-icon :icon="item.icon" size="18" aria-hidden="true" />
                                <span>{{ item.title }}</span>
                            </li>
                        </ul>
                    </aside>
                </div>
            </section>

            <section class="product-sections shell">
                <article class="product-section product-section--description">
                    <p class="eyebrow">{{ t('productPage.aboutEyebrow') }}</p>
                    <h2>{{ t('productPage.aboutTitle') }}</h2>
                    <p>{{ product.description }}</p>
                </article>

                <article class="product-section product-section--delivery">
                    <p class="eyebrow">{{ t('productPage.deliveryEyebrow') }}</p>
                    <h2>{{ t('productPage.deliveryTitle') }}</h2>
                    <p>{{ t('productPage.deliveryText') }}</p>
                    <div class="product-delivery-list">
                        <div v-for="option in deliveryOptions" :key="option.title">
                            <v-icon :icon="option.icon" size="22" aria-hidden="true" />
                            <span>{{ option.title }}</span>
                            <small>{{ option.text }}</small>
                        </div>
                    </div>
                </article>

                <article v-if="productInfoRows.length" class="product-section product-info-section">
                    <p class="eyebrow">{{ t('productPage.infoEyebrow') }}</p>
                    <h2>{{ t('productPage.infoTitle') }}</h2>
                    <dl class="product-info-list">
                        <template v-for="row in productInfoRows" :key="row.label">
                            <dt>{{ row.label }}</dt>
                            <dd>{{ row.value }}</dd>
                        </template>
                    </dl>
                </article>

                <article v-if="product.categories?.length" class="product-section product-section--categories">
                    <p class="eyebrow">{{ t('productPage.categories') }}</p>
                    <div class="product-category-tags">
                        <router-link v-for="category in product.categories" :key="category.id" :to="{ name: 'catalog-category', params: { categorySlug: category.slug } }">{{ category.name }}</router-link>
                    </div>
                </article>
            </section>

            <section v-if="relatedProducts.length" class="product-related shell">
                <div class="product-related-head">
                    <p class="eyebrow">{{ t('productPage.relatedEyebrow') }}</p>
                    <h2>{{ t('productPage.relatedTitle') }}</h2>
                </div>
                <div class="catalog-grid">
                    <ProductCard v-for="item in relatedProducts" :key="item.id" :product="item" :favorite="store.favorites.includes(item.id)" @favorite="store.toggleFavorite" @add-cart="store.addToCart" />
                </div>
            </section>
        </template>

        <v-dialog v-model="imageDialog" max-width="980">
            <div class="product-image-dialog">
                <v-btn class="product-image-dialog__close" icon="mdi-close" variant="text" :aria-label="product ? t('product.close') : 'Close'" @click="imageDialog = false" />
                <img v-if="activeImage" :src="activeImage.image_url" :alt="activeImage.alt || product?.name">
            </div>
        </v-dialog>

        <div v-if="product" class="product-mobile-cta">
            <div>
                <span>{{ price }}</span>
                <small>{{ quantity }} × {{ product.name }}</small>
            </div>
            <v-btn color="primary" prepend-icon="mdi-cart-outline" :loading="addingToCart" :disabled="addingToCart" @click="addCurrentProductToCart">{{ t('product.addCart') }}</v-btn>
        </div>

        <v-snackbar v-model="cartSnackbar" color="primary" timeout="2200">{{ store.cartNotice }} · {{ t('product.addedToCart') }}</v-snackbar>
    </main>
</template>
