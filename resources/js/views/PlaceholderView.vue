<script setup>
import { useRoute } from 'vue-router';
import { useI18n } from '../composables/useI18n';

const route = useRoute();
const { routeTitle, t } = useI18n();
</script>

<template>
    <main class="placeholder-page shell">
        <p class="eyebrow">{{ t('placeholder.eyebrow') }}</p>
        <h1>{{ routeTitle(route.name, route.meta.title) }}</h1>
        <p>{{ t('placeholder.text') }}</p>
        <v-btn color="primary" to="/">{{ t('placeholder.cta') }}</v-btn>
    </main>
</template>
