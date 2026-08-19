<script setup>
import { useI18n } from '../../composables/useI18n';

defineProps({ modelValue: { type: Boolean, default: false }, product: { type: Object, default: null } });
defineEmits(['update:modelValue']);
const { t } = useI18n();
</script>

<template>
    <v-dialog :model-value="modelValue" max-width="880" @update:model-value="$emit('update:modelValue', $event)">
        <v-card v-if="product" class="quick-view-card">
            <v-btn class="quick-close" icon="mdi-close" variant="text" :aria-label="t('product.close')" @click="$emit('update:modelValue', false)" />
            <div class="quick-view-grid">
                <div class="quick-view-art" :style="{ '--product-color': product.palette[0], '--product-accent': product.palette[1] }"><div class="catalog-package"><span>QR</span><small>{{ product.collection }}</small></div></div>
                <div class="quick-view-copy"><p class="eyebrow">{{ product.collection }}</p><h2>{{ product.name }}</h2><p>{{ product.description }}</p><dl><div><dt>{{ t('product.material') }}</dt><dd>{{ product.material }}</dd></div><div><dt>{{ t('product.format') }}</dt><dd>{{ product.detailLine }}</dd></div><div><dt>{{ t('product.sizes') }}</dt><dd>{{ product.sizes.join(', ') }}</dd></div></dl><strong class="quick-price">{{ product.priceLabel }}</strong><v-btn block color="primary" size="large" prepend-icon="mdi-email-outline" href="mailto:ozivajka@inbox.lv">{{ t('product.order') }}</v-btn></div>
            </div>
        </v-card>
    </v-dialog>
</template>
