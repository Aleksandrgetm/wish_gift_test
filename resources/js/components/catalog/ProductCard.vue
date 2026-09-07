<script setup>
import { useI18n } from '../../composables/useI18n';

defineProps({ product: { type: Object, required: true }, favorite: { type: Boolean, default: false } });
defineEmits(['favorite', 'add-cart']);
const { t } = useI18n();
</script>

<template>
    <article class="catalog-product-card">
        <div class="catalog-product-art" :class="{ 'has-photo': product.image }" :style="{ '--product-color': product.palette[0], '--product-accent': product.palette[1] }">
            <router-link class="catalog-product-link" :to="{ name: 'product', params: { slug: product.slug } }" :aria-label="product.name">
                <img v-if="product.image" class="catalog-product-photo" :src="product.image" :alt="product.name" loading="lazy">
                <div v-else class="catalog-package"><span>QR</span><small>{{ product.category || 'Wish Gift' }}</small></div>
            </router-link>
            <div class="product-badges"><v-chip v-if="product.isNew" size="small" color="secondary">{{ t('product.new') }}</v-chip><v-chip v-if="product.oldPrice" size="small">−{{ Math.round((1 - product.price / product.oldPrice) * 100) }}%</v-chip></div>
            <button class="favorite-button" type="button" :aria-label="favorite ? t('product.removeFavorite') : t('product.addFavorite')" :aria-pressed="favorite" @click="$emit('favorite', product.id)"><v-icon :icon="favorite ? 'mdi-heart' : 'mdi-heart-outline'" /></button>
        </div>
        <div class="catalog-product-content">
            <p>{{ [product.collection, product.detailLine || product.category].filter(Boolean).join(' · ') }}</p>
            <h2><router-link :to="{ name: 'product', params: { slug: product.slug } }">{{ product.name }}</router-link></h2>
            <div class="catalog-product-meta"><div><strong>{{ product.priceLabel }}</strong><del v-if="product.oldPrice">{{ product.oldPrice.toLocaleString('lv-LV') }} €</del></div><span :class="{ unavailable: !product.available }">{{ product.available ? t('product.available') : t('product.onRequest') }}</span></div>
            <div class="catalog-card-footer"><v-btn color="primary" size="small" icon="mdi-plus" :aria-label="t('product.addCart')" @click="$emit('add-cart', product)" /></div>
        </div>
    </article>
</template>
