<script setup>
import { computed, nextTick, ref } from 'vue';

const steps = [
    { key: 'recipient', question: 'Kam paredzēta dāvana?', options: ['vīrietim', 'sievietei', 'bērnam', 'skolotājam', 'ārstam', 'citam cilvēkam'] },
    { key: 'occasion', question: 'Kāds ir notikums?', options: ['Ziemassvētki', 'Jaunais gads', 'Valentīndiena', '8. marts', 'Mātes diena', 'Skolotāju diena', '1. septembris', 'cits notikums'] },
    { key: 'souvenir', question: 'Ko vēlaties dāvināt?', options: ['foto ar QR', 'krūzi', 'T-kreklu', 'piekariņu', 'šokolādi', 'dziesmas plāksni'] },
    { key: 'media', question: 'Ko pievienot dāvanai?', options: ['foto', 'video', 'mūziku', 'karikatūru', 'personīgu uzrakstu'] },
];
const current = ref(0);
const answers = ref({});
const questionHeading = ref(null);
const completed = ref(false);
const selected = computed(() => answers.value[steps[current.value].key]);
const resultCategories = computed(() => [
    answers.value.souvenir ? `${answers.value.souvenir} ar QR` : 'dzīvie suvenīri',
    answers.value.recipient ? `dāvana ${answers.value.recipient}` : 'personalizēta dāvana',
    answers.value.media ? `pievienot ${answers.value.media}` : 'video vai mūzika',
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
            <div class="finder-intro"><p class="eyebrow">Nezināt, ar ko sākt?</p><h2>Minūtes laikā atradīsim piemērotu formātu</h2><p>Atbildiet uz četriem jautājumiem, un mēs parādīsim virzienu pēc saņēmēja, notikuma, suvenīra un satura.</p><div class="finder-count"><strong>0{{ current + 1 }}</strong><span>/ 04</span></div></div>
            <div class="finder-panel">
                <div class="finder-progress"><i :style="{ width: `${((current + 1) / steps.length) * 100}%` }"></i></div>
                <Transition name="question" mode="out-in">
                    <div v-if="!completed" :key="steps[current].key"><h3 ref="questionHeading" tabindex="-1">{{ steps[current].question }}</h3><div class="answer-grid"><button v-for="option in steps[current].options" :key="option" type="button" :class="{ selected: selected === option }" @click="select(option)"><span>{{ option }}</span><v-icon :icon="selected === option ? 'mdi-check-circle' : 'mdi-circle-outline'" /></button></div></div>
                    <div v-else key="result" class="finder-result"><h3 ref="questionHeading" tabindex="-1">Jums varētu derēt šie virzieni</h3><div class="finder-result-list"><span v-for="category in resultCategories" :key="category">{{ category }}</span></div><p>Pirms izgatavošanas saskaņosim foto, video, mūziku, maketu un galīgo dāvanas formātu.</p></div>
                </Transition>
                <div class="finder-actions"><v-btn v-if="current || completed" variant="text" prepend-icon="mdi-arrow-left" @click="completed ? completed = false : current -= 1">Atpakaļ</v-btn><span v-else></span><v-btn color="primary" :disabled="!selected && !completed" append-icon="mdi-arrow-right" :to="completed ? '/catalog' : undefined" @click="!completed && next()">{{ completed ? 'Pāriet uz katalogu' : current === steps.length - 1 ? 'Parādīt variantus' : 'Turpināt' }}</v-btn></div>
            </div>
        </div>
    </section>
</template>
