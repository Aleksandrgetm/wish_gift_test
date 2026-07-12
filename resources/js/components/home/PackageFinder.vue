<script setup>
import { computed, nextTick, ref } from 'vue';

const steps = [
    { key: 'recipient', question: 'Для кого подарок?', options: ['мужчине', 'женщине', 'ребёнку', 'учителю', 'врачу', 'другое'] },
    { key: 'occasion', question: 'К какому празднику?', options: ['Новый год', '14 февраля', '8 марта', 'День матери', 'День учителя', '1 сентября', 'другое'] },
    { key: 'souvenir', question: 'Какой сувенир?', options: ['фото', 'кружка', 'майка', 'брелок', 'открытка', 'шоколад', 'трек-пластинка'] },
    { key: 'media', question: 'Что нужно добавить?', options: ['фото', 'видео', 'музыка', 'шарж', 'персональная надпись'] },
    { key: 'budget', question: 'Какой бюджет?', options: ['до 25 €', '25–40 €', '40–60 €', 'индивидуальный расчёт'] },
];
const current = ref(0);
const answers = ref({});
const questionHeading = ref(null);
const completed = ref(false);
const selected = computed(() => answers.value[steps[current.value].key]);
const resultCategories = computed(() => [
    answers.value.souvenir ? `${answers.value.souvenir}-оживайка` : 'сувениры-оживайки',
    answers.value.recipient ? `подарок ${answers.value.recipient}` : 'персональный подарок',
    answers.value.media ? `добавить ${answers.value.media}` : 'QR-видео',
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
            <div class="finder-intro"><p class="eyebrow">Не знаете, с чего начать?</p><h2>Найдём подходящую оживайку за минуту</h2><p>Ответьте на пять простых вопросов — мы покажем категории, которые подходят по получателю, празднику и формату.</p><div class="finder-count"><strong>0{{ current + 1 }}</strong><span>/ 05</span></div></div>
            <div class="finder-panel">
                <div class="finder-progress"><i :style="{ width: `${((current + 1) / steps.length) * 100}%` }"></i></div>
                <Transition name="question" mode="out-in">
                    <div v-if="!completed" :key="steps[current].key"><h3 ref="questionHeading" tabindex="-1">{{ steps[current].question }}</h3><div class="answer-grid"><button v-for="option in steps[current].options" :key="option" type="button" :class="{ selected: selected === option }" @click="select(option)"><span>{{ option }}</span><v-icon :icon="selected === option ? 'mdi-check-circle' : 'mdi-circle-outline'" /></button></div></div>
                    <div v-else key="result" class="finder-result"><h3 ref="questionHeading" tabindex="-1">Ваши подходящие категории</h3><div class="finder-result-list"><span v-for="category in resultCategories" :key="category">{{ category }}</span></div><p>Перед оформлением менеджер согласует фото, видео, музыку и финальную стоимость.</p></div>
                </Transition>
                <div class="finder-actions"><v-btn v-if="current || completed" variant="text" prepend-icon="mdi-arrow-left" @click="completed ? completed = false : current -= 1">Назад</v-btn><span v-else></span><v-btn color="primary" :disabled="!selected && !completed" append-icon="mdi-arrow-right" :to="completed ? '/souvenirs' : undefined" @click="!completed && next()">{{ completed ? 'Перейти к выбору' : current === steps.length - 1 ? 'Показать варианты' : 'Продолжить' }}</v-btn></div>
            </div>
        </div>
    </section>
</template>
