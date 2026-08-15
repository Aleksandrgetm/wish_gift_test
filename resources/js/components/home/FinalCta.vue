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
                <p class="eyebrow">Ir ideja dāvanai?</p>
                <h2 id="final-cta-title" class="section-title">Ļaujiet tai atdzīvoties.</h2>
                <p>Pastāstiet, kam paredzēta dāvana un kādus foto, video vai mūziku vēlaties pievienot, un mēs palīdzēsim izvēlēties formātu.</p>
            </div>
            <div class="final-cta-actions">
                <span ref="cta" class="magnetic-wrap"><v-btn color="primary" size="large" to="/souvenirs" append-icon="mdi-arrow-right">Izveidot dāvanu</v-btn></span>
                <v-btn variant="text" size="large" to="/contacts">Sazināties</v-btn>
            </div>
        </div>
    </section>
</template>
