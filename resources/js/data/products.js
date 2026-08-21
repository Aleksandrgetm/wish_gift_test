const productPhotosByOccasion = {
    '8. marts': [
        '/images/Products/8 марта.png',
        '/images/Products/8 марта 1.png',
        '/images/Products/8 марта 2.png',
        '/images/Products/8 марта 3.png',
        '/images/Products/8 марта 4.png',
        '/images/Products/8 марта 5.png',
        '/images/Products/8 марта 6.png',
        '/images/Products/8 марта 7.png',
        '/images/Products/8 марта 8.jpeg',
        '/images/Products/8 марта 9.png',
        '/images/Products/8 марта 10.png',
        '/images/Products/8 марта 11.png',
        '/images/Products/8 марта 12.png',
        '/images/Products/8 марта 13.png',
        '/images/Products/8 марта14.png',
        '/images/Products/8 марта 15.png',
        '/images/Products/8 марта 16.png',
        '/images/Products/8 марта 17.png',
        '/images/Products/8 марта 18.png',
        '/images/Products/8 марта 19.png',
        '/images/Products/8 марта 20.png',
        '/images/Products/8 марта 21.png',
        '/images/Products/8 марта 22.png',
    ],
    'Mātes diena': [
        '/images/Products/день матери .png',
        '/images/Products/день матери 2.png',
        '/images/Products/день матери 3.png',
        '/images/Products/день матери 4.png',
        '/images/Products/день матери 5.png',
        '/images/Products/день матери 6.png',
    ],
    'Skolotāju diena': ['/images/Products/день учителя.png'],
    'Studentu diena': [
        '/images/Products/день студента.png',
        '/images/Products/день студента 1.png',
        '/images/Products/день студента 2.png',
    ],
    '1. septembris': ['/images/Products/1 сентября.png'],
    'Tēva diena': ['/images/Products/23 февраля.png'],
    'Dzimšanas diena': ['/images/Products/день рождения.png'],
};

const baseProducts = [
    { id: 1, slug: 'photo-alive-a4', name: 'A4 foto ar QR video', category: 'Foto ar QR video', occasion: 'Mātes diena', material: 'Druka + QR', capacity: 2, price: 25, priceLabel: '25 €', oldPrice: null, available: true, isNew: false, collection: 'Photo Alive', color: 'violet', sizes: ['A4'], palette: ['#67349B', '#B89146'], detailLine: 'foto + video · A4', description: 'Drukāta fotogrāfija ar QR kodu, kas atver jūsu personīgo video.' },
    { id: 2, slug: 'photo-alive-a3', name: 'A3 foto ar QR video', category: 'Foto ar QR video', occasion: 'Jaunais gads', material: 'Druka + QR', capacity: 2, price: 30, priceLabel: '30 €', oldPrice: null, available: true, isNew: true, collection: 'Photo Alive', color: 'teal', sizes: ['A3'], palette: ['#1EA1B5', '#F0FAFB'], detailLine: 'foto + video · A3', description: 'Lielāks formāts ģimenes stāstiem, svētku portretiem un svarīgiem mirkļiem.' },
    { id: 3, slug: 'digital-alive-basic', name: 'Digitāla QR dāvana bez apstrādes', category: 'Digitāla QR dāvana', occasion: 'Dzimšanas diena', material: 'Digitāls QR', capacity: 2, price: 20, priceLabel: '20 €', oldPrice: null, available: true, isNew: false, collection: 'Digital', color: 'plum', sizes: ['digitāls'], palette: ['#4D2478', '#D8F1F4'], detailLine: 'gatavs QR · bez apstrādes', description: 'Gatavs QR kods jūsu foto un video bez papildu materiālu apstrādes.' },
    { id: 4, slug: 'digital-alive-retouch', name: 'Digitāla QR dāvana ar apstrādi', category: 'Digitāla QR dāvana', occasion: '14. februāris', material: 'Digitāls QR', capacity: 3, price: 35, priceLabel: '35 €', oldPrice: null, available: true, isNew: true, collection: 'Digital Plus', color: 'peach', sizes: ['digitāls'], palette: ['#F2C8C2', '#67349B'], detailLine: 'QR + apstrāde · video', description: 'Foto, video un dizains tiek rūpīgi apvienoti personīgā dāvanas stāstā.' },
    { id: 5, slug: 'track-record-a5', name: 'A5 dziesmas plāksne ar statīvu', category: 'Dziesmas plāksne', occasion: 'Tēva diena', material: 'Druka + statīvs', capacity: 3, price: 30, priceLabel: '30 €', oldPrice: null, available: true, isNew: true, collection: 'Track Memory', color: 'night', sizes: ['A5'], palette: ['#351B50', '#1EA1B5'], detailLine: 'foto + video + mūzika', description: 'Foto, video un mīļākā mūzika vienā dāvanā ar glītu statīvu.' },
    { id: 6, slug: 'tshirt-alive', name: 'T-krekls ar QR video', category: 'T-krekls', occasion: 'Studentu diena', material: 'Tekstils + QR', capacity: 2, price: 60, priceLabel: 'Cena pēc saskaņošanas', oldPrice: null, available: true, isNew: false, collection: 'Wear Story', color: 'ivory', sizes: ['S', 'M', 'L'], palette: ['#F7F1E8', '#B89146'], detailLine: 'tekstils · QR video', description: 'Personīgs stāsts, ko var nēsāt. Cena ir atkarīga no maketa un tirāžas.' },
    { id: 7, slug: 'mug-alive', name: 'Krūze ar QR video', category: 'Krūze', occasion: 'Skolotāju diena', material: 'Keramika + QR', capacity: 2, price: 60, priceLabel: 'Cena pēc saskaņošanas', oldPrice: null, available: true, isNew: false, collection: 'Morning Story', color: 'aqua', sizes: ['330 ml'], palette: ['#D8F1F4', '#67349B'], detailLine: 'keramika · QR video', description: 'Katrs rīts sākas ar atmiņu, kas atveras caur QR kodu.' },
    { id: 8, slug: 'keychain-alive', name: 'Atslēgu piekariņš ar QR video', category: 'Atslēgu piekariņš', occasion: '1. septembris', material: 'Akrils + QR', capacity: 1, price: 60, priceLabel: 'Cena pēc saskaņošanas', oldPrice: null, available: true, isNew: false, collection: 'Pocket Story', color: 'rose', sizes: ['mini'], palette: ['#D98F9D', '#4D2478'], detailLine: 'mini suvenīrs · QR', description: 'Mazs suvenīrs ar personīgu video, ko ērti nēsāt līdzi ikdienā.' },
    { id: 9, slug: 'chocolate-alive', name: 'Šokolāde ar QR video', category: 'Šokolāde', occasion: '8. marts', material: 'Šokolāde + iesaiņojums', capacity: 3, price: 60, priceLabel: 'Cena pēc saskaņošanas', oldPrice: null, available: true, isNew: true, collection: 'Choco Story', color: 'teal-dark', sizes: ['individuāli'], palette: ['#126B7A', '#E9DDF4'], detailLine: 'dizains + apsveikums', description: 'Personalizēta šokolāde ar jūsu dizainu, apsveikumu un QR video.' },
    { id: 10, slug: 'postcard-alive', name: 'Kartīte ar QR video', category: 'Kartīte', occasion: 'Ziemassvētki', material: 'Kartons + QR', capacity: 2, price: 60, priceLabel: 'Cena pēc saskaņošanas', oldPrice: null, available: true, isNew: false, collection: 'Card Story', color: 'lavender', sizes: ['A6', 'A5'], palette: ['#E9DDF4', '#67349B'], detailLine: 'kartīte · QR video', description: 'Drukāta kartīte, kas atver video, mūziku vai personīgu apsveikumu.' },
    { id: 11, slug: 'mug-teacher-set', name: 'Dāvana skolotājam ar QR video', category: 'Krūze', occasion: 'Skolotāju diena', material: 'Keramika + QR', capacity: 3, price: 60, priceLabel: 'Cena pēc saskaņošanas', oldPrice: null, available: true, isNew: false, collection: 'Teacher Gift', color: 'corporate', sizes: ['komplekts'], palette: ['#26153A', '#1EA1B5'], detailLine: 'klases apsveikums · QR', description: 'Formāts kopīgam klases apsveikumam ar video un personalizētu uzrakstu.' },
    { id: 12, slug: 'family-photo-alive', name: 'Ģimenes foto ar QR video', category: 'Foto ar QR video', occasion: 'Mātes diena', material: 'Druka + QR', capacity: 3, price: 35, priceLabel: 'no 35 €', oldPrice: null, available: true, isNew: true, collection: 'Family Memory', color: 'milk', sizes: ['A4', 'A3'], palette: ['#FBF9FC', '#B89146'], detailLine: 'foto + video + mūzika', description: 'Silta ģimenes dāvana ar foto, video un muzikālu pavadījumu.' },
];

const photoProductTemplates = Object.fromEntries(
    baseProducts
        .filter((product) => productPhotosByOccasion[product.occasion]?.length)
        .map((product) => [product.occasion, product]),
);

const photoProducts = Object.entries(productPhotosByOccasion).flatMap(([occasion, photos], occasionIndex) => {
    const template = photoProductTemplates[occasion];

    if (!template) {
        return [];
    }

    return photos.map((image, photoIndex) => ({
        ...template,
        id: 1000 + occasionIndex * 100 + photoIndex,
        slug: `${template.slug}-photo-${photoIndex + 1}`,
        image,
        isNew: photoIndex === 0 && template.isNew,
        collection: template.collection,
    }));
});

export const products = [
    ...baseProducts.filter((product) => !productPhotosByOccasion[product.occasion]?.length),
    ...photoProducts,
];

export const filterOptions = {
    occasions: ['Ziemassvētki', 'Jaunais gads', '14. februāris', '8. marts', 'Mātes diena', 'Skolotāju diena', 'Studentu diena', '1. septembris', 'Tēva diena', 'Dzimšanas diena'],
    categories: ['Foto ar QR video', 'Digitāla QR dāvana', 'Dziesmas plāksne', 'T-krekls', 'Krūze', 'Atslēgu piekariņš', 'Šokolāde', 'Kartīte'],
    materials: ['Druka + QR', 'Digitāls QR', 'Druka + statīvs', 'Tekstils + QR', 'Keramika + QR', 'Akrils + QR', 'Šokolāde + iesaiņojums', 'Kartons + QR'],
    capacities: [1, 2, 3],
};
