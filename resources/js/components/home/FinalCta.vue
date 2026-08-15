<script setup>
import { ref } from 'vue';
import { useMagnetic } from '../../composables/useMagnetic';

const cta = ref(null);
useMagnetic(cta, 0.16);
</script>

<template>
    <section class="section final-cta-section motion-reveal" aria-labelledby="final-cta-title">
        <div class="shell final-cta">
            <div>
                <p class="eyebrow">Есть идея для подарка?</p>
                <h2 id="final-cta-title">Давайте оживим её.</h2>
                <p>Расскажите, кому предназначен подарок, какие фото, видео или музыку хотите добавить, и мы поможем выбрать формат.</p>
            </div>
            <div class="final-cta-actions">
                <span ref="cta" class="magnetic-wrap"><v-btn color="primary" size="large" to="/souvenirs" append-icon="mdi-arrow-right">Создать подарок</v-btn></span>
                <v-btn variant="text" size="large" to="/contacts">Связаться</v-btn>
            </div>
        </div>
    </section>
</template>
