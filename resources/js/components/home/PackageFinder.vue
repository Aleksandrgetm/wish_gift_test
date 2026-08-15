<script setup>
import { computed, nextTick, ref } from 'vue';

const steps = [
    { key: 'recipient', question: 'Kam paredzēta dāvana?', options: ['vīrietim', 'sievietei', 'bērnam', 'skolotājam', 'ārstam', 'citam saņēmējam'] },
    { key: 'occasion', question: 'Kādiem svētkiem?', options: ['Jaunais gads', '14. februāris', '8. marts', 'Mātes diena', 'Skolotāju diena', '1. septembris', 'cits notikums'] },
    { key: 'souvenir', question: 'Kāds suvenīrs?', options: ['foto', 'krūze', 'T-krekls', 'atslēgu piekariņš', 'kartīte', 'šokolāde', 'dziesmas plāksne'] },
    { key: 'media', question: 'Ko vēlaties pievienot?', options: ['foto', 'video', 'mūziku', 'šaržu', 'personalizētu uzrakstu'] },
    { key: 'budget', question: 'Kāds ir budžets?', options: ['līdz 25 €', '25-40 €', '40-60 €', 'individuāls aprēķins'] },
];
const current = ref(0);
const answers = ref({});
const questionHeading = ref(null);
const completed = ref(false);
const selected = computed(() => answers.value[steps[current.value].key]);
const resultCategories = computed(() => [
    answers.value.souvenir ? `${answers.value.souvenir} ar QR video` : 'suvenīri ar QR video',
    answers.value.recipient ? `dāvana ${answers.value.recipient}` : 'personalizēta dāvana',
    answers.value.media ? `pievienot ${answers.value.media}` : 'QR video',
]);
const select = (option) => { answers.value[steps[current.value].key] = option; };
const next = async () => {
    if (current.value === steps.length - 1) {
        completed.value = true;
        return;
    }
    current.value += 1;
    await nextTick();
    questionHeading.value?.focus();
};
</script>

<template>
    <section id="finder" class="section shell motion-reveal">
        <div class="finder finder-motion">
            <div class="finder-intro"><p class="eyebrow">Nezināt, ar ko sākt?</p><h2>Minūtes laikā atradīsim piemērotu dāvanu</h2><p>Atbildiet uz pieciem vienkāršiem jautājumiem, un mēs parādīsim kategorijas pēc saņēmēja, svētkiem un formāta.</p><div class="finder-count"><strong>0{{ current + 1 }}</strong><span>/ 05</span></div></div>
            <div class="finder-panel">
                <div class="finder-progress"><i :style="{ width: `${((current + 1) / steps.length) * 100}%` }"></i></div>
                <Transition name="question" mode="out-in">
                    <div v-if="!completed" :key="steps[current].key"><h3 ref="questionHeading" tabindex="-1">{{ steps[current].question }}</h3><div class="answer-grid"><button v-for="option in steps[current].options" :key="option" type="button" :class="{ selected: selected === option }" @click="select(option)"><span>{{ option }}</span><v-icon :icon="selected === option ? 'mdi-check-circle' : 'mdi-circle-outline'" /></button></div></div>
                    <div v-else key="result" class="finder-result"><h3 ref="questionHeading" tabindex="-1">Jums piemērotās kategorijas</h3><div class="finder-result-list"><span v-for="category in resultCategories" :key="category">{{ category }}</span></div><p>Pirms noformēšanas menedžeris saskaņos foto, video, mūziku un galīgo cenu.</p></div>
                </Transition>
                <div class="finder-actions"><v-btn v-if="current || completed" variant="text" prepend-icon="mdi-arrow-left" @click="completed ? completed = false : current -= 1">Atpakaļ</v-btn><span v-else></span><v-btn color="primary" :disabled="!selected && !completed" append-icon="mdi-arrow-right" :to="completed ? '/souvenirs' : undefined" @click="!completed && next()">{{ completed ? 'Pāriet pie izvēles' : current === steps.length - 1 ? 'Parādīt variantus' : 'Turpināt' }}</v-btn></div>
            </div>
        </div>
    </section>
</template>
