<script setup>
import { useRoute } from 'vue-router';

const route = useRoute();
</script>

<template>
    <main class="placeholder-page shell">
        <p class="eyebrow">Следующий этап</p>
        <h1>{{ route.meta.title }}</h1>
        <p>Раздел уже включён в навигацию и будет наполнен на следующем этапе разработки.</p>
        <v-btn color="primary" to="/">Вернуться на главную</v-btn>
    </main>
</template>
