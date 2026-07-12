<script setup>
import { ref } from 'vue';

const email = ref('');
const subscribed = ref(false);
const submit = () => { if (email.value) subscribed.value = true; };
</script>

<template>
    <section class="section shell motion-reveal">
        <div class="quote"><v-icon icon="mdi-format-quote-open" size="36" /><blockquote>«Коробки получились именно такими, как мы представляли: тактильными, сдержанными и очень праздничными. Клиенты спрашивали, можно ли заказать их отдельно.»</blockquote><p><strong>Анна Воронова</strong><span>бренд-менеджер, Atelier Cacao</span></p></div>
        <div id="faq" class="newsletter"><div><p class="eyebrow">Письма, которые хочется открыть</p><h2>Новые коллекции и идеи для подарков</h2></div><form v-if="!subscribed" @submit.prevent="submit"><label class="sr-only" for="newsletter-email">Электронная почта</label><input id="newsletter-email" v-model="email" type="email" required placeholder="Ваш email"><v-btn type="submit" color="primary">Подписаться</v-btn></form><p v-else class="success-message"><v-icon icon="mdi-check-circle" /> Спасибо! Первое письмо уже готовим.</p></div>
    </section>
</template>
