<script setup>
import { ref } from 'vue';

const email = ref('');
const subscribed = ref(false);
const submit = () => { if (email.value) subscribed.value = true; };
</script>

<template>
    <section class="section shell motion-reveal">
        <div class="quote"><v-icon icon="mdi-format-quote-open" size="36" /><blockquote>«Мы отправили семейное фото и видео, а получили подарок, который действительно ожил. Это было очень личное поздравление, не похожее на обычный сувенир.»</blockquote><p><strong>Мария Л.</strong><span>заказ фото-оживайки для мамы</span></p></div>
        <div id="faq" class="newsletter"><div><p class="eyebrow">Идеи, которые хочется сохранить</p><h2>Новые форматы оживаек и идеи для подарков</h2></div><form v-if="!subscribed" @submit.prevent="submit"><label class="sr-only" for="newsletter-email">Электронная почта</label><input id="newsletter-email" v-model="email" type="email" required placeholder="Ваш email"><v-btn type="submit" color="primary">Подписаться</v-btn></form><p v-else class="success-message"><v-icon icon="mdi-check-circle" /> Спасибо! Первую идею уже готовим.</p></div>
    </section>
</template>
