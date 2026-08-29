<script setup>
import { useI18n } from '../../composables/useI18n';

defineProps({
    filters: { type: Object, required: true },
    activeCount: { type: Number, default: 0 },
    options: { type: Object, required: true },
});
defineEmits(['reset']);
const { t, translateOption } = useI18n();
</script>

<template>
    <div class="catalog-filters">
        <div class="filter-heading"><div><span>{{ t('catalog.filters') }}</span><v-chip v-if="activeCount" color="primary" size="x-small">{{ activeCount }}</v-chip></div><button v-if="activeCount" type="button" @click="$emit('reset')">{{ t('filters.reset') }}</button></div>
        <v-expansion-panels multiple variant="accordion" :model-value="[0, 1, 2, 3]">
            <v-expansion-panel :title="t('filters.occasion')"><v-expansion-panel-text><v-checkbox v-for="item in options.occasions" :key="item" v-model="filters.occasions" :label="translateOption(item)" :value="item" density="compact" hide-details color="primary" /></v-expansion-panel-text></v-expansion-panel>
            <v-expansion-panel :title="t('filters.type')"><v-expansion-panel-text><v-checkbox v-for="item in options.categories" :key="item" v-model="filters.categories" :label="translateOption(item)" :value="item" density="compact" hide-details color="primary" /></v-expansion-panel-text></v-expansion-panel>
            <v-expansion-panel :title="t('filters.material')"><v-expansion-panel-text><v-checkbox v-for="item in options.materials" :key="item" v-model="filters.materials" :label="translateOption(item)" :value="item" density="compact" hide-details color="primary" /></v-expansion-panel-text></v-expansion-panel>
            <v-expansion-panel :title="t('filters.price')"><v-expansion-panel-text><div class="price-caption"><span>{{ t('filters.upTo') }}</span><strong>{{ filters.maxPrice }} €</strong></div><v-slider v-model="filters.maxPrice" :min="20" :max="60" :step="5" color="primary" hide-details /><v-switch v-model="filters.available" :label="t('filters.available')" color="secondary" density="compact" hide-details /></v-expansion-panel-text></v-expansion-panel>
        </v-expansion-panels>
    </div>
</template>
