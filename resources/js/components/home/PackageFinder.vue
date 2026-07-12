<script setup>
import { computed, nextTick, ref } from 'vue';

const steps = [
    { key: 'occasion', question: 'Для какого события?', options: ['День рождения', 'Свадьба', 'Новый год', 'Корпоративный подарок'] },
    { key: 'type', question: 'Что будем упаковывать?', options: ['Конфеты', 'Шоколад', 'Набор сладостей', 'Смешанный подарок'] },
    { key: 'capacity', question: 'Какая вместимость нужна?', options: ['До 12 конфет', '13–24 конфеты', '25–48 конфет', 'Большой набор'] },
    { key: 'style', question: 'Какой стиль ближе?', options: ['Сдержанный', 'Романтичный', 'Праздничный', 'Фирменный'] },
    { key: 'budget', question: 'Бюджет на упаковку?', options: ['до 1 000 ₽', '1 000–2 000 ₽', '2 000–4 000 ₽', 'от 4 000 ₽'] },
];
const current = ref(0);
const answers = ref({});
const questionHeading = ref(null);
const selected = computed(() => answers.value[steps[current.value].key]);
const select = (option) => { answers.value[steps[current.value].key] = option; };
const next = async () => {
    if (current.value === steps.length - 1) {
        window.location.href = '/catalog';
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
            <div class="finder-intro"><p class="eyebrow">Не знаете, с чего начать?</p><h2>Найдём идеальную упаковку за минуту</h2><p>Ответьте на пять простых вопросов — мы покажем варианты, которые подходят по формату, стилю и бюджету.</p><div class="finder-count"><strong>0{{ current + 1 }}</strong><span>/ 05</span></div></div>
            <div class="finder-panel">
                <div class="finder-progress"><i :style="{ width: `${((current + 1) / steps.length) * 100}%` }"></i></div>
                <Transition name="question" mode="out-in">
                    <div :key="steps[current].key"><h3 ref="questionHeading" tabindex="-1">{{ steps[current].question }}</h3><div class="answer-grid"><button v-for="option in steps[current].options" :key="option" type="button" :class="{ selected: selected === option }" @click="select(option)"><span>{{ option }}</span><v-icon :icon="selected === option ? 'mdi-check-circle' : 'mdi-circle-outline'" /></button></div></div>
                </Transition>
                <div class="finder-actions"><v-btn v-if="current" variant="text" prepend-icon="mdi-arrow-left" @click="current -= 1">Назад</v-btn><span v-else></span><v-btn color="primary" :disabled="!selected" append-icon="mdi-arrow-right" @click="next">{{ current === steps.length - 1 ? 'Показать варианты' : 'Продолжить' }}</v-btn></div>
            </div>
        </div>
    </section>
</template>
