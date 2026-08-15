<script setup>
import { useRoute } from 'vue-router';

const route = useRoute();
</script>

<template>
    <main class="placeholder-page shell">
        <p class="eyebrow">Nākamais posms</p>
        <h1>{{ route.meta.title }}</h1>
        <p>Sadaļa jau ir iekļauta navigācijā un tiks papildināta nākamajā izstrādes posmā.</p>
        <v-btn color="primary" to="/">Atgriezties sākumlapā</v-btn>
    </main>
</template>
