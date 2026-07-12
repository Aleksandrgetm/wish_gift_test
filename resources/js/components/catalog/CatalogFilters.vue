<script setup>
import { filterOptions } from '../../data/products';

defineProps({ filters: { type: Object, required: true }, activeCount: { type: Number, default: 0 } });
defineEmits(['reset']);
</script>

<template>
    <div class="catalog-filters">
        <div class="filter-heading"><div><span>Фильтры</span><v-chip v-if="activeCount" color="primary" size="x-small">{{ activeCount }}</v-chip></div><button v-if="activeCount" type="button" @click="$emit('reset')">Сбросить</button></div>
        <v-expansion-panels multiple variant="accordion" :model-value="[0, 1, 2, 3, 4]">
            <v-expansion-panel title="Повод"><v-expansion-panel-text><v-checkbox v-for="item in filterOptions.occasions" :key="item" v-model="filters.occasions" :label="item" :value="item" density="compact" hide-details color="primary" /></v-expansion-panel-text></v-expansion-panel>
            <v-expansion-panel title="Тип сувенира"><v-expansion-panel-text><v-checkbox v-for="item in filterOptions.categories" :key="item" v-model="filters.categories" :label="item" :value="item" density="compact" hide-details color="primary" /></v-expansion-panel-text></v-expansion-panel>
            <v-expansion-panel title="Материал"><v-expansion-panel-text><v-checkbox v-for="item in filterOptions.materials" :key="item" v-model="filters.materials" :label="item" :value="item" density="compact" hide-details color="primary" /></v-expansion-panel-text></v-expansion-panel>
            <v-expansion-panel title="Медиа"><v-expansion-panel-text><v-chip-group v-model="filters.capacity" selected-class="catalog-chip-selected"><v-chip v-for="item in filterOptions.capacities" :key="item" :value="item" filter>{{ item === 1 ? 'QR' : item === 2 ? 'фото + видео' : 'фото + видео + музыка' }}</v-chip></v-chip-group></v-expansion-panel-text></v-expansion-panel>
            <v-expansion-panel title="Цена и согласование"><v-expansion-panel-text><div class="price-caption"><span>До</span><strong>{{ filters.maxPrice }} €</strong></div><v-slider v-model="filters.maxPrice" :min="20" :max="60" :step="5" color="primary" hide-details /><v-switch v-model="filters.available" label="Можно согласовать сейчас" color="secondary" density="compact" hide-details /></v-expansion-panel-text></v-expansion-panel>
        </v-expansion-panels>
    </div>
</template>
