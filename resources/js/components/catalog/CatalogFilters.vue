<script setup>
import { filterOptions } from '../../data/products';

defineProps({ filters: { type: Object, required: true }, activeCount: { type: Number, default: 0 } });
defineEmits(['reset']);
</script>

<template>
    <div class="catalog-filters">
        <div class="filter-heading"><div><span>Filtri</span><v-chip v-if="activeCount" color="primary" size="x-small">{{ activeCount }}</v-chip></div><button v-if="activeCount" type="button" @click="$emit('reset')">Atiestatīt</button></div>
        <v-expansion-panels multiple variant="accordion" :model-value="[0, 1, 2, 3, 4]">
            <v-expansion-panel title="Notikums"><v-expansion-panel-text><v-checkbox v-for="item in filterOptions.occasions" :key="item" v-model="filters.occasions" :label="item" :value="item" density="compact" hide-details color="primary" /></v-expansion-panel-text></v-expansion-panel>
            <v-expansion-panel title="Suvenīra tips"><v-expansion-panel-text><v-checkbox v-for="item in filterOptions.categories" :key="item" v-model="filters.categories" :label="item" :value="item" density="compact" hide-details color="primary" /></v-expansion-panel-text></v-expansion-panel>
            <v-expansion-panel title="Materiāls"><v-expansion-panel-text><v-checkbox v-for="item in filterOptions.materials" :key="item" v-model="filters.materials" :label="item" :value="item" density="compact" hide-details color="primary" /></v-expansion-panel-text></v-expansion-panel>
            <v-expansion-panel title="Mediji"><v-expansion-panel-text><v-chip-group v-model="filters.capacity" selected-class="catalog-chip-selected"><v-chip v-for="item in filterOptions.capacities" :key="item" :value="item" filter>{{ item === 1 ? 'QR' : item === 2 ? 'foto + video' : 'foto + video + mūzika' }}</v-chip></v-chip-group></v-expansion-panel-text></v-expansion-panel>
            <v-expansion-panel title="Cena un saskaņošana"><v-expansion-panel-text><div class="price-caption"><span>Līdz</span><strong>{{ filters.maxPrice }} €</strong></div><v-slider v-model="filters.maxPrice" :min="20" :max="60" :step="5" color="primary" hide-details /><v-switch v-model="filters.available" label="Var saskaņot tagad" color="secondary" density="compact" hide-details /></v-expansion-panel-text></v-expansion-panel>
        </v-expansion-panels>
    </div>
</template>
