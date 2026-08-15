<script setup>
import { ref } from 'vue';

const email = ref('');
const subscribed = ref(false);
const submit = () => { if (email.value) subscribed.value = true; };
</script>

<template>
    <section class="section shell motion-reveal">
        <div class="quote"><v-icon icon="mdi-format-quote-open" size="36" /><blockquote>“Mēs nosūtījām ģimenes foto un video, bet saņēmām dāvanu, kas patiešām atdzīvojās. Tas bija ļoti personīgs apsveikums, pilnīgi citāds nekā parasts suvenīrs.”</blockquote><p><strong>Marija L.</strong><span>foto dāvana mammai</span></p></div>
        <div class="newsletter"><div><p class="eyebrow">Idejas, ko gribas saglabāt</p><h2>Jauni QR dāvanu formāti un dāvanu idejas</h2></div><form v-if="!subscribed" @submit.prevent="submit"><label class="sr-only" for="newsletter-email">E-pasts</label><input id="newsletter-email" v-model="email" type="email" required placeholder="Jūsu e-pasts"><v-btn type="submit" color="primary">Pierakstīties</v-btn></form><p v-else class="success-message"><v-icon icon="mdi-check-circle" /> Paldies! Pirmo ideju jau gatavojam.</p></div>
    </section>
</template>
