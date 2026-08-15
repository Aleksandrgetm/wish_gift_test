<script setup>
import { computed, nextTick, ref } from 'vue';

const steps = [
    { key: 'recipient', question: 'Для кого подарок?', options: ['мужчине', 'женщине', 'ребёнку', 'учителю', 'врачу', 'другому человеку'] },
    { key: 'occasion', question: 'Какой повод?', options: ['Рождество', 'Новый год', '14 февраля', '8 марта', 'День матери', 'День учителя', '1 сентября', 'другой повод'] },
    { key: 'souvenir', question: 'Что хотите подарить?', options: ['фото-оживайку', 'кружку', 'майку', 'брелок', 'шоколад', 'трек-пластинку'] },
    { key: 'media', question: 'Что добавить внутрь?', options: ['фото', 'видео', 'музыку', 'шарж', 'персональную надпись'] },
];
const current = ref(0);
const answers = ref({});
const questionHeading = ref(null);
const completed = ref(false);
const selected = computed(() => answers.value[steps[current.value].key]);
const resultCategories = computed(() => [
    answers.value.souvenir ? `${answers.value.souvenir} с QR` : 'сувениры-оживайки',
    answers.value.recipient ? `подарок ${answers.value.recipient}` : 'персональный подарок',
    answers.value.media ? `добавить ${answers.value.media}` : 'видео или музыку',
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
            <div class="finder-intro"><p class="eyebrow">Не знаете, с чего начать?</p><h2>За минуту подберём подходящий формат</h2><p>Ответьте на четыре вопроса, и мы покажем направление по получателю, поводу, сувениру и наполнению.</p><div class="finder-count"><strong>0{{ current + 1 }}</strong><span>/ 04</span></div></div>
            <div class="finder-panel">
                <div class="finder-progress"><i :style="{ width: `${((current + 1) / steps.length) * 100}%` }"></i></div>
                <Transition name="question" mode="out-in">
                    <div v-if="!completed" :key="steps[current].key"><h3 ref="questionHeading" tabindex="-1">{{ steps[current].question }}</h3><div class="answer-grid"><button v-for="option in steps[current].options" :key="option" type="button" :class="{ selected: selected === option }" @click="select(option)"><span>{{ option }}</span><v-icon :icon="selected === option ? 'mdi-check-circle' : 'mdi-circle-outline'" /></button></div></div>
                    <div v-else key="result" class="finder-result"><h3 ref="questionHeading" tabindex="-1">Вам подойдут эти направления</h3><div class="finder-result-list"><span v-for="category in resultCategories" :key="category">{{ category }}</span></div><p>Перед изготовлением мы согласуем фото, видео, музыку, макет и финальный формат подарка.</p></div>
                </Transition>
                <div class="finder-actions"><v-btn v-if="current || completed" variant="text" prepend-icon="mdi-arrow-left" @click="completed ? completed = false : current -= 1">Назад</v-btn><span v-else></span><v-btn color="primary" :disabled="!selected && !completed" append-icon="mdi-arrow-right" :to="completed ? '/catalog' : undefined" @click="!completed && next()">{{ completed ? 'Перейти в каталог' : current === steps.length - 1 ? 'Показать варианты' : 'Продолжить' }}</v-btn></div>
            </div>
        </div>
    </section>
</template>
