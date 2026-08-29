import { computed, ref, watch } from 'vue';
import { defineStore } from 'pinia';
import { jsonRequest } from '../services/http';
import { useI18n } from '../composables/useI18n';

const otherOccasion = 'Citi';
const otherCategory = 'Citi';

export const useCatalogStore = defineStore('catalog', () => {
    const { locale } = useI18n();
    const loading = ref(true);
    const error = ref(false);
    const products = ref([]);
    const categoryGroups = ref([]);
    const query = ref('');
    const sort = ref('featured');
    const page = ref(1);
    const perPage = 12;
    const filters = ref({ occasions: [], categories: [], materials: [], maxPrice: 60, available: false });
    const favorites = ref([]);
    const filterOptions = computed(() => ({
        occasions: categoryGroups.value.find((group) => group.filter_key === 'occasions')?.items.map((item) => item.name_lv) ?? [],
        categories: categoryGroups.value.find((group) => group.filter_key === 'categories')?.items.map((item) => item.name_lv) ?? [],
        materials: categoryGroups.value.find((group) => group.filter_key === 'materials')?.items.map((item) => item.name_lv) ?? [],
    }));
    const listedOccasions = computed(() => filterOptions.value.occasions.filter((occasion) => occasion !== otherOccasion));
    const listedCategories = computed(() => filterOptions.value.categories.filter((category) => category !== otherCategory));

    const filtered = computed(() => {
        const term = query.value.trim().toLocaleLowerCase('lv-LV');
        const result = products.value.filter((product) => {
            const matchesQuery = !term || `${product.name} ${product.collection} ${product.category} ${product.description}`.toLocaleLowerCase('lv-LV').includes(term);
            const matchesOccasion = !filters.value.occasions.length
                || filters.value.occasions.includes(product.occasion)
                || (filters.value.occasions.includes(otherOccasion) && !listedOccasions.value.includes(product.occasion));
            const matchesCategory = !filters.value.categories.length
                || filters.value.categories.includes(product.category)
                || (filters.value.categories.includes(otherCategory) && !listedCategories.value.includes(product.category));
            const matchesMaterial = !filters.value.materials.length || filters.value.materials.includes(product.material);
            return matchesQuery && matchesOccasion && matchesCategory && matchesMaterial && product.price <= filters.value.maxPrice && (!filters.value.available || product.available);
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
    const activeFilterCount = computed(() => filters.value.occasions.length + filters.value.categories.length + filters.value.materials.length + Number(filters.value.maxPrice < 60) + Number(filters.value.available));

    const reset = () => { filters.value = { occasions: [], categories: [], materials: [], maxPrice: 60, available: false }; query.value = ''; page.value = 1; };
    const toggleFavorite = (id) => { favorites.value = favorites.value.includes(id) ? favorites.value.filter((item) => item !== id) : [...favorites.value, id]; };
    const load = async () => {
        loading.value = true;
        error.value = false;

        try {
            const payload = await jsonRequest(`/api/catalog?locale=${locale.value}`);
            products.value = payload.products || [];
            categoryGroups.value = payload.categories || [];
        } catch {
            error.value = true;
        } finally {
            window.setTimeout(() => { loading.value = false; }, 250);
        }
    };
    const finishLoading = load;
    const retry = load;

    watch(locale, load);

    return { loading, error, products, categoryGroups, filterOptions, query, sort, page, perPage, filters, favorites, filtered, pageCount, visibleProducts, activeFilterCount, reset, toggleFavorite, finishLoading, retry };
});
