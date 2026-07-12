export const products = [
    { id: 1, slug: 'heritage-violet', name: 'Шкатулка «Наследие»', category: 'Коробки', occasion: 'Свадьба', material: 'Картон', capacity: 24, price: 1890, oldPrice: 2190, available: true, isNew: false, collection: 'Heritage', color: 'violet', sizes: ['M', 'L'], palette: ['#67349B', '#B89146'] },
    { id: 2, slug: 'botanica-teal', name: 'Коллекция «Ботаника»', category: 'Наборы', occasion: '8 Марта', material: 'Картон', capacity: 16, price: 1490, oldPrice: null, available: true, isNew: true, collection: 'Botanica', color: 'teal', sizes: ['S', 'M'], palette: ['#1EA1B5', '#F0FAFB'] },
    { id: 3, slug: 'monogram-corporate', name: 'Коробка «Монограмма»', category: 'Коробки', occasion: 'Корпоративный', material: 'Переплётный картон', capacity: 12, price: 990, oldPrice: null, available: true, isNew: false, collection: 'Atelier', color: 'plum', sizes: ['S'], palette: ['#4D2478', '#D8F1F4'] },
    { id: 4, slug: 'apres-midi', name: 'Набор «Après-midi»', category: 'Наборы', occasion: 'День рождения', material: 'Картон', capacity: 36, price: 2590, oldPrice: 2890, available: true, isNew: false, collection: 'Signature', color: 'peach', sizes: ['L'], palette: ['#F2C8C2', '#67349B'] },
    { id: 5, slug: 'noel-etoile', name: 'Шкатулка «Étoile»', category: 'Шкатулки', occasion: 'Новый год', material: 'Дерево', capacity: 24, price: 3290, oldPrice: null, available: true, isNew: true, collection: 'Noël', color: 'night', sizes: ['M', 'L'], palette: ['#351B50', '#1EA1B5'] },
    { id: 6, slug: 'amour-ivory', name: 'Коробка «Amour»', category: 'Коробки', occasion: 'Свадьба', material: 'Картон', capacity: 9, price: 1290, oldPrice: null, available: true, isNew: false, collection: 'Mariage', color: 'ivory', sizes: ['S'], palette: ['#F7F1E8', '#B89146'] },
    { id: 7, slug: 'paques-douce', name: 'Набор «Pâques Douce»', category: 'Наборы', occasion: 'Пасха', material: 'Картон', capacity: 20, price: 1690, oldPrice: null, available: false, isNew: true, collection: 'Pâques', color: 'aqua', sizes: ['M'], palette: ['#D8F1F4', '#67349B'] },
    { id: 8, slug: 'velours-round', name: 'Коробка «Velours»', category: 'Шкатулки', occasion: 'День рождения', material: 'Бархат', capacity: 16, price: 2390, oldPrice: 2690, available: true, isNew: false, collection: 'Signature', color: 'rose', sizes: ['M'], palette: ['#D98F9D', '#4D2478'] },
    { id: 9, slug: 'prisme-chocolate', name: 'Футляр «Prisme»', category: 'Для шоколада', occasion: 'Корпоративный', material: 'Картон', capacity: 6, price: 790, oldPrice: null, available: true, isNew: false, collection: 'Atelier', color: 'teal-dark', sizes: ['S', 'M'], palette: ['#126B7A', '#E9DDF4'] },
    { id: 10, slug: 'fleur-lavande', name: 'Коробка «Fleur»', category: 'Коробки', occasion: '8 Марта', material: 'Картон', capacity: 12, price: 1190, oldPrice: null, available: true, isNew: true, collection: 'Fleur', color: 'lavender', sizes: ['S', 'M'], palette: ['#E9DDF4', '#67349B'] },
    { id: 11, slug: 'grand-geste', name: 'Набор «Grand Geste»', category: 'Наборы', occasion: 'Корпоративный', material: 'Дерево', capacity: 48, price: 4990, oldPrice: null, available: true, isNew: false, collection: 'Atelier', color: 'corporate', sizes: ['XL'], palette: ['#26153A', '#1EA1B5'] },
    { id: 12, slug: 'petit-bonheur', name: 'Мини-коробка «Bonheur»', category: 'Для шоколада', occasion: 'День рождения', material: 'Картон', capacity: 4, price: 590, oldPrice: null, available: true, isNew: false, collection: 'Essentials', color: 'milk', sizes: ['XS'], palette: ['#FBF9FC', '#B89146'] },
];

export const filterOptions = {
    occasions: ['Новый год', '8 Марта', 'Пасха', 'Свадьба', 'День рождения', 'Корпоративный'],
    categories: ['Коробки', 'Шкатулки', 'Наборы', 'Для шоколада'],
    materials: ['Картон', 'Переплётный картон', 'Дерево', 'Бархат'],
    capacities: [12, 24, 48],
};
