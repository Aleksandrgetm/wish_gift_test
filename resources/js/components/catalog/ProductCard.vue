<script setup>
defineProps({ product: { type: Object, required: true }, favorite: { type: Boolean, default: false } });
defineEmits(['favorite', 'quick-view']);
</script>

<template>
    <article class="catalog-product-card">
        <div class="catalog-product-art" :style="{ '--product-color': product.palette[0], '--product-accent': product.palette[1] }">
            <div class="product-badges"><v-chip v-if="product.isNew" size="small" color="secondary">Jaunums</v-chip><v-chip v-if="product.oldPrice" size="small">−{{ Math.round((1 - product.price / product.oldPrice) * 100) }}%</v-chip></div>
            <button class="favorite-button" type="button" :aria-label="favorite ? 'Noņemt no izlases' : 'Pievienot izlasei'" :aria-pressed="favorite" @click="$emit('favorite', product.id)"><v-icon :icon="favorite ? 'mdi-heart' : 'mdi-heart-outline'" /></button>
            <div class="catalog-package"><span>QR</span><small>{{ product.collection }}</small></div>
            <button class="quick-view-button" type="button" @click="$emit('quick-view', product)"><v-icon icon="mdi-eye-outline" /> Ātrais skats</button>
        </div>
        <div class="catalog-product-content">
            <p>{{ product.collection }} · {{ product.detailLine }}</p>
            <h2>{{ product.name }}</h2>
            <div class="catalog-product-meta"><div><strong>{{ product.priceLabel }}</strong><del v-if="product.oldPrice">{{ product.oldPrice.toLocaleString('lv-LV') }} €</del></div><span :class="{ unavailable: !product.available }">{{ product.available ? 'Var saskaņot' : 'Pēc pasūtījuma' }}</span></div>
            <div class="catalog-card-footer"><div class="color-dots" aria-label="Krāsas"><i v-for="color in product.palette" :key="color" :style="{ backgroundColor: color }"></i></div><v-btn color="primary" size="small" icon="mdi-plus" aria-label="Pievienot grozam" /></div>
        </div>
    </article>
</template>
