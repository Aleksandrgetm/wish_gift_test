<script setup>
defineProps({ modelValue: { type: Boolean, default: false }, product: { type: Object, default: null } });
defineEmits(['update:modelValue']);
</script>

<template>
    <v-dialog :model-value="modelValue" max-width="880" @update:model-value="$emit('update:modelValue', $event)">
        <v-card v-if="product" class="quick-view-card">
            <v-btn class="quick-close" icon="mdi-close" variant="text" aria-label="Закрыть" @click="$emit('update:modelValue', false)" />
            <div class="quick-view-grid">
                <div class="quick-view-art" :style="{ '--product-color': product.palette[0], '--product-accent': product.palette[1] }"><div class="catalog-package"><span>É</span><small>{{ product.collection }}</small></div></div>
                <div class="quick-view-copy"><p class="eyebrow">{{ product.collection }}</p><h2>{{ product.name }}</h2><p>Премиальная упаковка с продуманной конструкцией, точной геометрией и аккуратной ручной сборкой.</p><dl><div><dt>Материал</dt><dd>{{ product.material }}</dd></div><div><dt>Вместимость</dt><dd>{{ product.capacity }} конфет</dd></div><div><dt>Размеры</dt><dd>{{ product.sizes.join(', ') }}</dd></div></dl><strong class="quick-price">{{ product.price.toLocaleString('ru-RU') }} ₽</strong><v-btn block color="primary" size="large" prepend-icon="mdi-shopping-outline">Добавить в корзину</v-btn></div>
            </div>
        </v-card>
    </v-dialog>
</template>
