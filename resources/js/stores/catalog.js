import { computed, ref } from 'vue';
import { defineStore } from 'pinia';
import { products } from '../data/products';
import { useI18n } from '../composables/useI18n';

export const useCatalogStore = defineStore('catalog', () => {
    const { locale, localizedProduct } = useI18n();
    const loading = ref(true);
    const error = ref(false);
    const query = ref('');
    const sort = ref('featured');
    const page = ref(1);
    const perPage = 12;
    const filters = ref({ occasions: [], categories: [], materials: [], capacity: null, maxPrice: 60, available: false });
    const favorites = ref([]);

    const filtered = computed(() => {
        const searchLocale = locale.value === 'lv' ? 'lv-LV' : locale.value;
        const term = query.value.trim().toLocaleLowerCase(searchLocale);
        const result = products.filter((product) => {
            const translatedProduct = localizedProduct(product);
            const matchesQuery = !term || `${product.name} ${product.collection} ${product.category} ${product.description} ${translatedProduct.name} ${translatedProduct.category} ${translatedProduct.description}`.toLocaleLowerCase(searchLocale).includes(term);
            const matchesOccasion = !filters.value.occasions.length || filters.value.occasions.includes(product.occasion);
            const matchesCategory = !filters.value.categories.length || filters.value.categories.includes(product.category);
            const matchesMaterial = !filters.value.materials.length || filters.value.materials.includes(product.material);
            const matchesCapacity = !filters.value.capacity || product.capacity <= filters.value.capacity;
            return matchesQuery && matchesOccasion && matchesCategory && matchesMaterial && matchesCapacity && product.price <= filters.value.maxPrice && (!filters.value.available || product.available);
        });
        return [...result].sort((a, b) => {
            if (sort.value === 'price-asc') return a.price - b.price;
            if (sort.value === 'price-desc') return b.price - a.price;
            if (sort.value === 'new') return Number(b.isNew) - Number(a.isNew);
            return a.id - b.id;
        });
    });
    const pageCount = computed(() => Math.max(1, Math.ceil(filtered.value.length / perPage)));
    const visibleProducts = computed(() => filtered.value.slice((page.value - 1) * perPage, page.value * perPage));
    const activeFilterCount = computed(() => filters.value.occasions.length + filters.value.categories.length + filters.value.materials.length + Number(Boolean(filters.value.capacity)) + Number(filters.value.maxPrice < 60) + Number(filters.value.available));

    const reset = () => { filters.value = { occasions: [], categories: [], materials: [], capacity: null, maxPrice: 60, available: false }; query.value = ''; page.value = 1; };
    const toggleFavorite = (id) => { favorites.value = favorites.value.includes(id) ? favorites.value.filter((item) => item !== id) : [...favorites.value, id]; };
    const finishLoading = () => { error.value = false; window.setTimeout(() => { loading.value = false; }, 450); };
    const retry = () => { loading.value = true; finishLoading(); };

    return { loading, error, query, sort, page, perPage, filters, favorites, filtered, pageCount, visibleProducts, activeFilterCount, reset, toggleFavorite, finishLoading, retry };
});
