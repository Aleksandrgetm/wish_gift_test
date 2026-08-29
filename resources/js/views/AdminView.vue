<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { homeCategoryItems } from '../data/homeContent';
import { formRequest, jsonRequest } from '../services/http';

const loginForm = reactive({ email: '', password: '' });
const session = ref({ authenticated: false, user: null });
const loadingSession = ref(true);
const loadingContent = ref(false);
const loadingCatalog = ref(false);
const loginLoading = ref(false);
const adminTab = ref('homepage');
const heroEditing = ref(false);
const categoryEditing = ref(false);
const savingHero = ref(false);
const savingCategories = ref(false);
const draggedHeroKey = ref(null);
const draggedCategoryKey = ref(null);
const heroFileInputs = ref({});
const categoryFileInputs = ref({});
const heroSlides = ref([]);
const categoryDrafts = ref([]);
const catalogCategoryGroups = ref([]);
const catalogProducts = ref([]);
const productPagination = ref({ current_page: 1, last_page: 1, total: 0 });
const productSearch = ref('');
const productCategoryFilter = ref(null);
const categoryDialog = ref(false);
const productDialog = ref(false);
const deletingProductId = ref(null);
const savingCatalogCategory = ref(false);
const savingProduct = ref(false);
const notices = reactive({ success: '', error: '' });
const categoryForm = reactive({
    id: null,
    type: 'occasion',
    name_lv: '',
    name_ru: '',
    name_en: '',
    slug: '',
    image: null,
    imagePreview: null,
    image_url: null,
    delete_image: false,
    icon: '',
    tone: '',
    is_active: true,
    sort_order: 0,
});
const productForm = reactive({
    id: null,
    name_lv: '',
    name_ru: '',
    name_en: '',
    description_lv: '',
    description_ru: '',
    description_en: '',
    price: 0,
    capacity: 1,
    color: '',
    palette: ['#67349B', '#B89146'],
    sizesText: '',
    is_active: true,
    is_new: false,
    sort_order: 0,
    category_ids: [],
    images: [],
    imagePreviews: [],
    existingImages: [],
    delete_image_ids: [],
});

let fileKeyCounter = 0;

const visibleHeroSlides = computed(() => heroSlides.value.filter((slide) => !slide.delete));
const visibleCategories = computed(() => categoryDrafts.value);
const allCatalogCategories = computed(() => catalogCategoryGroups.value.flatMap((group) => group.items));
const productCategoryOptions = computed(() => allCatalogCategories.value.map((category) => ({
    title: `${category.name_lv} · ${category.type}`,
    value: category.id,
})));
const transliterateSlug = (value) => value
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/-{2,}/g, '-')
    .replace(/^-|-$/g, '') || 'product';
const productSlugPreview = computed(() => transliterateSlug(productForm.name_lv));
const productHasImages = computed(() => (
    productForm.existingImages.some((image) => !productForm.delete_image_ids.includes(image.id))
    || productForm.images.length > 0
));
const heroDirty = computed(() => heroEditing.value && heroSlides.value.some((slide) => slide.dirty || slide.file || slide.delete));
const categoryDirty = computed(() => categoryEditing.value && categoryDrafts.value.some((category) => category.dirty || category.file || category.delete_image));

const nextFileKey = () => `file_${Date.now()}_${fileKeyCounter++}`;

const revokePreview = (item) => {
    if (item.previewUrl) {
        URL.revokeObjectURL(item.previewUrl);
    }
};

const setError = (error) => {
    const validation = error.response?.errors;
    notices.success = '';
    notices.error = validation ? Object.values(validation).flat().join(' ') : error.message;
};

const setSuccess = (message) => {
    notices.error = '';
    notices.success = message;
};

const currentTitle = computed(() => ({
    homepage: 'Главная страница',
    categories: 'Категории',
    products: 'Товары',
}[adminTab.value]));

const normalizeHeroSlide = (slide, index) => ({
    uid: `hero-${slide.id}`,
    id: slide.id,
    image_url: slide.image_url,
    image_path: slide.image_path,
    disk: slide.disk,
    alt: slide.alt || '',
    is_active: Boolean(slide.is_active),
    sort_order: slide.sort_order ?? index,
    file: null,
    file_key: null,
    previewUrl: null,
    delete: false,
    dirty: false,
    isNew: false,
});

const normalizeCategory = (item, index, settingsByKey) => {
    const settings = settingsByKey[item.id];

    return {
        category_key: item.id,
        title: item.title,
        to: item.to,
        fallback_image: item.image,
        image_url: settings?.image_url || item.image,
        image_path: settings?.image_path || null,
        disk: settings?.disk || null,
        hasCustomImage: Boolean(settings?.image_url),
        alt: settings?.alt || item.title,
        is_active: settings?.is_active ?? true,
        sort_order: settings?.sort_order ?? index,
        file: null,
        file_key: null,
        previewUrl: null,
        delete_image: false,
        dirty: false,
    };
};

const syncContent = (payload) => {
    heroSlides.value.forEach(revokePreview);
    categoryDrafts.value.forEach(revokePreview);

    heroSlides.value = (payload.hero_slides || [])
        .map(normalizeHeroSlide)
        .sort((a, b) => a.sort_order - b.sort_order);

    const settingsByKey = Object.fromEntries((payload.category_images || []).map((item) => [item.category_key, item]));
    categoryDrafts.value = homeCategoryItems
        .map((item, index) => normalizeCategory(item, index, settingsByKey))
        .sort((a, b) => a.sort_order - b.sort_order);
};

const loadContent = async () => {
    loadingContent.value = true;

    try {
        syncContent(await jsonRequest('/admin/api/homepage'));
    } catch (error) {
        setError(error);
    } finally {
        loadingContent.value = false;
    }
};

const loadCatalogAdmin = async () => {
    loadingCatalog.value = true;

    try {
        const [categoriesPayload, productsPayload] = await Promise.all([
            jsonRequest('/admin/api/catalog-categories'),
            jsonRequest(`/admin/api/products?search=${encodeURIComponent(productSearch.value)}&page=${productPagination.value.current_page || 1}${productCategoryFilter.value ? `&category_id=${productCategoryFilter.value}` : ''}`),
        ]);
        catalogCategoryGroups.value = categoriesPayload.category_groups || [];
        catalogProducts.value = productsPayload.data || [];
        productPagination.value = productsPayload;
    } catch (error) {
        setError(error);
    } finally {
        loadingCatalog.value = false;
    }
};

const checkSession = async () => {
    loadingSession.value = true;

    try {
        session.value = await jsonRequest('/admin/session');

        if (session.value.authenticated) {
            await loadContent();
            await loadCatalogAdmin();
        }
    } catch (error) {
        setError(error);
    } finally {
        loadingSession.value = false;
    }
};

const login = async () => {
    loginLoading.value = true;

    try {
        session.value = await jsonRequest('/admin/login', {
            method: 'POST',
            body: JSON.stringify(loginForm),
        });
        await loadContent();
        await loadCatalogAdmin();
        setSuccess('Вы вошли в админку.');
    } catch (error) {
        setError(error);
    } finally {
        loginLoading.value = false;
    }
};

const resetCategoryForm = () => {
    if (categoryForm.imagePreview) URL.revokeObjectURL(categoryForm.imagePreview);
    Object.assign(categoryForm, {
        id: null,
        type: 'occasion',
        name_lv: '',
        name_ru: '',
        name_en: '',
        slug: '',
        image: null,
        imagePreview: null,
        image_url: null,
        delete_image: false,
        icon: '',
        tone: '',
        is_active: true,
        sort_order: allCatalogCategories.value.length,
    });
};

const openCategoryDialog = (category = null) => {
    resetCategoryForm();
    if (category) {
        Object.assign(categoryForm, {
            ...category,
            image: null,
            imagePreview: null,
            image_url: category.image_url,
            delete_image: false,
        });
    }
    categoryDialog.value = true;
};

const onCatalogCategoryImage = (event) => {
    const [file] = event.target.files;
    event.target.value = '';
    if (!file) return;
    if (categoryForm.imagePreview) URL.revokeObjectURL(categoryForm.imagePreview);
    categoryForm.image = file;
    categoryForm.imagePreview = URL.createObjectURL(file);
    categoryForm.delete_image = false;
};

const saveCatalogCategory = async () => {
    savingCatalogCategory.value = true;

    try {
        const formData = new FormData();
        ['type', 'name_lv', 'name_ru', 'name_en', 'slug', 'icon', 'tone', 'sort_order'].forEach((key) => {
            formData.append(key, categoryForm[key] ?? '');
        });
        formData.append('is_active', categoryForm.is_active ? '1' : '0');
        formData.append('delete_image', categoryForm.delete_image ? '1' : '0');
        if (categoryForm.image) formData.append('image', categoryForm.image);
        if (categoryForm.id) formData.append('_method', 'PUT');

        await formRequest(
            categoryForm.id ? `/admin/api/catalog-categories/${categoryForm.id}` : '/admin/api/catalog-categories',
            formData,
        );
        categoryDialog.value = false;
        resetCategoryForm();
        await loadCatalogAdmin();
        setSuccess('Категория сохранена.');
    } catch (error) {
        setError(error);
    } finally {
        savingCatalogCategory.value = false;
    }
};

const deleteCatalogCategory = async (category) => {
    if (!window.confirm(`Удалить категорию "${category.name_lv}"?`)) return;

    try {
        await jsonRequest(`/admin/api/catalog-categories/${category.id}`, { method: 'DELETE' });
        await loadCatalogAdmin();
        setSuccess('Категория удалена.');
    } catch (error) {
        setError(error);
    }
};

const resetProductForm = () => {
    productForm.imagePreviews.forEach((preview) => URL.revokeObjectURL(preview.url));
    Object.assign(productForm, {
        id: null,
        name_lv: '',
        name_ru: '',
        name_en: '',
        description_lv: '',
        description_ru: '',
        description_en: '',
        price: 0,
        capacity: 1,
        color: '',
        palette: ['#67349B', '#B89146'],
        sizesText: '',
        is_active: true,
        is_new: false,
        sort_order: catalogProducts.value.length,
        category_ids: [],
        images: [],
        imagePreviews: [],
        existingImages: [],
        delete_image_ids: [],
    });
};

const openProductDialog = (product = null) => {
    resetProductForm();
    if (product) {
        Object.assign(productForm, {
            ...product,
            palette: product.palette || ['#67349B', '#B89146'],
            sizesText: (product.sizes || []).join(', '),
            category_ids: (product.categories || []).map((category) => category.id),
            existingImages: product.images || [],
            images: [],
            imagePreviews: [],
            delete_image_ids: [],
        });
    }
    productDialog.value = true;
};

const onProductImages = (event) => {
    const files = [...event.target.files];
    event.target.value = '';
    productForm.images = [...productForm.images, ...files];
    productForm.imagePreviews = [
        ...productForm.imagePreviews,
        ...files.map((file) => ({ name: file.name, url: URL.createObjectURL(file) })),
    ];
};

const markProductImageDeleted = (image) => {
    productForm.delete_image_ids = [...new Set([...productForm.delete_image_ids, image.id])];
};

const saveProduct = async () => {
    if (!productForm.name_lv.trim() || !productForm.description_lv.trim() || !productForm.price || !productForm.category_ids.length || !productHasImages.value) {
        notices.success = '';
        notices.error = 'Заполните обязательные поля: название LV, описание LV, цена, категории и главное изображение.';
        return;
    }

    savingProduct.value = true;

    try {
        const formData = new FormData();
        [
            'name_lv', 'name_ru', 'name_en', 'description_lv', 'description_ru', 'description_en',
            'price', 'capacity', 'color', 'sort_order',
        ].forEach((key) => {
            if (productForm[key] !== null && productForm[key] !== undefined) formData.append(key, productForm[key]);
        });
        formData.append('is_active', productForm.is_active ? '1' : '0');
        formData.append('is_new', productForm.is_new ? '1' : '0');
        productForm.palette.filter(Boolean).forEach((color, index) => formData.append(`palette[${index}]`, color));
        productForm.sizesText.split(',').map((size) => size.trim()).filter(Boolean).forEach((size, index) => formData.append(`sizes[${index}]`, size));
        productForm.category_ids.forEach((id, index) => formData.append(`category_ids[${index}]`, id));
        productForm.images.forEach((image, index) => formData.append(`images[${index}]`, image));
        productForm.delete_image_ids.forEach((id, index) => formData.append(`delete_image_ids[${index}]`, id));
        if (productForm.id) formData.append('_method', 'PUT');

        await formRequest(
            productForm.id ? `/admin/api/products/${productForm.id}` : '/admin/api/products',
            formData,
        );
        productDialog.value = false;
        resetProductForm();
        await loadCatalogAdmin();
        setSuccess('Товар сохранён.');
    } catch (error) {
        setError(error);
    } finally {
        savingProduct.value = false;
    }
};

const deleteProduct = async (product) => {
    if (!window.confirm(`Удалить товар "${product.name_lv}"?`)) return;
    deletingProductId.value = product.id;

    try {
        await jsonRequest(`/admin/api/products/${product.id}`, { method: 'DELETE' });
        await loadCatalogAdmin();
        setSuccess('Товар удалён.');
    } catch (error) {
        setError(error);
    } finally {
        deletingProductId.value = null;
    }
};

const logout = async () => {
    await jsonRequest('/admin/logout', { method: 'POST' });
    session.value = { authenticated: false, user: null };
};

const startHeroEditing = () => {
    heroEditing.value = true;
};

const cancelHeroEditing = () => {
    heroEditing.value = false;
    loadContent();
};

const startCategoryEditing = () => {
    categoryEditing.value = true;
};

const cancelCategoryEditing = () => {
    categoryEditing.value = false;
    loadContent();
};

const addHeroFiles = (event) => {
    const files = [...event.target.files];
    event.target.value = '';

    if (!heroEditing.value || !files.length) return;

    const nextOrder = visibleHeroSlides.value.length;
    const newSlides = files.map((file, index) => {
        const fileKey = nextFileKey();
        const previewUrl = URL.createObjectURL(file);

        return {
            uid: `hero-new-${fileKey}`,
            id: null,
            image_url: previewUrl,
            image_path: file.name,
            disk: null,
            alt: file.name.replace(/\.[^.]+$/, ''),
            is_active: true,
            sort_order: nextOrder + index,
            file,
            file_key: fileKey,
            previewUrl,
            delete: false,
            dirty: true,
            isNew: true,
        };
    });

    heroSlides.value = [...visibleHeroSlides.value, ...newSlides, ...heroSlides.value.filter((slide) => slide.delete)];
};

const replaceHero = (slide, event) => {
    const [file] = event.target.files;
    event.target.value = '';

    if (!heroEditing.value || !file) return;

    revokePreview(slide);
    const previewUrl = URL.createObjectURL(file);
    Object.assign(slide, {
        file,
        file_key: nextFileKey(),
        image_url: previewUrl,
        previewUrl,
        image_path: file.name,
        alt: slide.alt || file.name.replace(/\.[^.]+$/, ''),
        dirty: true,
    });
};

const deleteHero = (slide) => {
    if (!heroEditing.value) return;

    if (slide.isNew) {
        revokePreview(slide);
        heroSlides.value = heroSlides.value.filter((item) => item.uid !== slide.uid);
        return;
    }

    slide.delete = true;
    slide.dirty = true;
};

const moveHero = (fromIndex, toIndex) => {
    const visible = [...visibleHeroSlides.value];

    if (!heroEditing.value || fromIndex < 0 || toIndex < 0 || toIndex >= visible.length) return;

    const [item] = visible.splice(fromIndex, 1);
    visible.splice(toIndex, 0, item);
    visible.forEach((slide, index) => {
        slide.sort_order = index;
        slide.dirty = true;
    });
    heroSlides.value = [...visible, ...heroSlides.value.filter((slide) => slide.delete)];
};

const onHeroDrop = (targetKey) => {
    const visible = visibleHeroSlides.value;
    const fromIndex = visible.findIndex((item) => item.uid === draggedHeroKey.value);
    const toIndex = visible.findIndex((item) => item.uid === targetKey);
    draggedHeroKey.value = null;
    moveHero(fromIndex, toIndex);
};

const saveHeroChanges = async () => {
    savingHero.value = true;

    try {
        const formData = new FormData();
        const slides = heroSlides.value.map((slide, index) => ({
            id: slide.id,
            alt: slide.alt,
            is_active: slide.is_active,
            sort_order: slide.delete ? slide.sort_order : index,
            delete: slide.delete,
            file_key: slide.file ? slide.file_key : null,
        }));

        formData.append('slides', JSON.stringify(slides));
        heroSlides.value.forEach((slide) => {
            if (slide.file) {
                formData.append(`files[${slide.file_key}]`, slide.file);
            }
        });

        await formRequest('/admin/api/homepage/hero-slides/batch', formData);
        heroEditing.value = false;
        await loadContent();
        setSuccess('Hero carousel сохранён.');
    } catch (error) {
        setError(error);
    } finally {
        savingHero.value = false;
    }
};

const replaceCategory = (category, event) => {
    const [file] = event.target.files;
    event.target.value = '';

    if (!categoryEditing.value || !file) return;

    revokePreview(category);
    const previewUrl = URL.createObjectURL(file);
    Object.assign(category, {
        file,
        file_key: nextFileKey(),
        image_url: previewUrl,
        previewUrl,
        image_path: file.name,
        hasCustomImage: true,
        delete_image: false,
        dirty: true,
    });
};

const deleteCategoryImage = (category) => {
    if (!categoryEditing.value || (!category.hasCustomImage && !category.file)) return;

    revokePreview(category);
    Object.assign(category, {
        file: null,
        file_key: null,
        previewUrl: null,
        image_url: category.fallback_image,
        image_path: null,
        disk: null,
        hasCustomImage: false,
        delete_image: true,
        dirty: true,
    });
};

const moveCategory = (fromIndex, toIndex) => {
    const visible = [...visibleCategories.value];

    if (!categoryEditing.value || fromIndex < 0 || toIndex < 0 || toIndex >= visible.length) return;

    const [item] = visible.splice(fromIndex, 1);
    visible.splice(toIndex, 0, item);
    visible.forEach((category, index) => {
        category.sort_order = index;
        category.dirty = true;
    });
    categoryDrafts.value = visible;
};

const onCategoryDrop = (targetKey) => {
    const visible = visibleCategories.value;
    const fromIndex = visible.findIndex((item) => item.category_key === draggedCategoryKey.value);
    const toIndex = visible.findIndex((item) => item.category_key === targetKey);
    draggedCategoryKey.value = null;
    moveCategory(fromIndex, toIndex);
};

const saveCategoryChanges = async () => {
    savingCategories.value = true;

    try {
        const formData = new FormData();
        const categories = categoryDrafts.value.map((category, index) => ({
            category_key: category.category_key,
            alt: category.alt,
            is_active: category.is_active,
            sort_order: index,
            delete_image: category.delete_image,
            file_key: category.file ? category.file_key : null,
        }));

        formData.append('categories', JSON.stringify(categories));
        categoryDrafts.value.forEach((category) => {
            if (category.file) {
                formData.append(`files[${category.file_key}]`, category.file);
            }
        });

        await formRequest('/admin/api/homepage/categories/batch', formData);
        categoryEditing.value = false;
        await loadContent();
        setSuccess('Категории главной страницы сохранены.');
    } catch (error) {
        setError(error);
    } finally {
        savingCategories.value = false;
    }
};

onMounted(checkSession);
</script>

<template>
    <main class="admin-page">
        <div v-if="loadingSession" class="admin-loading">
            <v-progress-circular indeterminate color="primary" />
        </div>

        <section v-else-if="!session.authenticated" class="admin-login">
            <form class="admin-login-card" @submit.prevent="login">
                <div>
                    <p>Wish Gift admin</p>
                    <h1>Вход в админку</h1>
                </div>
                <v-alert v-if="notices.error" type="error" variant="tonal" density="comfortable">{{ notices.error }}</v-alert>
                <v-text-field v-model="loginForm.email" label="Email" type="email" autocomplete="email" prepend-inner-icon="mdi-email-outline" />
                <v-text-field v-model="loginForm.password" label="Пароль" type="password" autocomplete="current-password" prepend-inner-icon="mdi-lock-outline" />
                <v-btn type="submit" color="primary" size="large" :loading="loginLoading" block>Войти</v-btn>
            </form>
        </section>

        <section v-else class="admin-shell">
            <aside class="admin-sidebar">
                <div class="admin-brand">
                    <strong>Wish Gift</strong>
                    <span>Admin</span>
                </div>
                <nav>
                    <button type="button" :class="{ active: adminTab === 'homepage' }" @click="adminTab = 'homepage'"><v-icon icon="mdi-home-edit-outline" />Главная страница</button>
                    <button type="button" :class="{ active: adminTab === 'categories' }" @click="adminTab = 'categories'"><v-icon icon="mdi-shape-outline" />Категории</button>
                    <button type="button" :class="{ active: adminTab === 'products' }" @click="adminTab = 'products'"><v-icon icon="mdi-package-variant-closed" />Товары</button>
                </nav>
                <v-btn variant="tonal" prepend-icon="mdi-logout" @click="logout">Выйти</v-btn>
            </aside>

            <div class="admin-content">
                <header class="admin-topbar">
                    <div>
                        <p>Content management</p>
                        <h1>{{ currentTitle }}</h1>
                    </div>
                    <v-btn prepend-icon="mdi-refresh" variant="outlined" :loading="loadingContent || loadingCatalog" :disabled="heroEditing || categoryEditing" @click="adminTab === 'homepage' ? loadContent() : loadCatalogAdmin()">Обновить</v-btn>
                </header>

                <v-alert v-if="notices.success" type="success" variant="tonal" closable @click:close="notices.success = ''">{{ notices.success }}</v-alert>
                <v-alert v-if="notices.error" type="error" variant="tonal" closable @click:close="notices.error = ''">{{ notices.error }}</v-alert>

                <template v-if="adminTab === 'homepage'">
                <section class="admin-panel" :class="{ 'admin-panel--editing': heroEditing }">
                    <div class="admin-panel-header">
                        <div>
                            <div class="admin-panel-title-row">
                                <h2>Hero carousel</h2>
                                <v-chip v-if="heroEditing" color="primary" size="small" variant="tonal">Режим редактирования</v-chip>
                            </div>
                            <p>Изображения показываются на главной в указанном порядке.</p>
                        </div>
                        <div class="admin-panel-actions">
                            <v-btn v-if="!heroEditing" prepend-icon="mdi-pencil-outline" color="primary" @click="startHeroEditing">Редактировать</v-btn>
                            <template v-else>
                                <label class="admin-upload-btn">
                                    <input type="file" accept="image/png,image/jpeg,image/webp" multiple @change="addHeroFiles">
                                    <v-icon icon="mdi-image-plus-outline" />
                                    <span>Добавить фото</span>
                                </label>
                                <v-btn prepend-icon="mdi-content-save-outline" color="primary" :loading="savingHero" :disabled="!heroDirty" @click="saveHeroChanges">Сохранить изменения</v-btn>
                                <v-btn prepend-icon="mdi-close" variant="outlined" :disabled="savingHero" @click="cancelHeroEditing">Отмена</v-btn>
                            </template>
                        </div>
                    </div>

                    <div v-if="!visibleHeroSlides.length" class="admin-empty">
                        <v-icon icon="mdi-image-multiple-outline" size="34" />
                        <p>В базе пока нет hero-изображений. Главная покажет технический fallback, пока вы не добавите фото.</p>
                    </div>

                    <div class="hero-admin-list">
                        <article
                            v-for="(slide, index) in visibleHeroSlides"
                            :key="slide.uid"
                            class="hero-admin-item"
                            :class="{ 'is-disabled': !slide.is_active, 'is-editing': heroEditing }"
                            :draggable="heroEditing"
                            @dragstart="draggedHeroKey = slide.uid"
                            @dragover.prevent
                            @drop="onHeroDrop(slide.uid)"
                        >
                            <img :src="slide.image_url" :alt="slide.alt || 'Hero image preview'">
                            <div class="hero-admin-body">
                                <div>
                                    <strong>Slide {{ index + 1 }}</strong>
                                    <small>{{ slide.image_path }}</small>
                                </div>
                                <v-text-field
                                    v-model="slide.alt"
                                    label="Alt text"
                                    density="compact"
                                    hide-details
                                    :readonly="!heroEditing"
                                    @update:model-value="slide.dirty = true"
                                />
                                <div class="admin-row-actions">
                                    <v-switch
                                        v-model="slide.is_active"
                                        color="primary"
                                        hide-details
                                        density="compact"
                                        label="Показывать"
                                        :readonly="!heroEditing"
                                        @update:model-value="slide.dirty = true"
                                    />
                                    <v-tooltip text="Переместить выше">
                                        <template #activator="{ props }">
                                            <v-btn v-bind="props" icon="mdi-arrow-up" variant="text" :disabled="!heroEditing || index === 0" aria-label="Переместить выше" @click="moveHero(index, index - 1)" />
                                        </template>
                                    </v-tooltip>
                                    <v-tooltip text="Переместить ниже">
                                        <template #activator="{ props }">
                                            <v-btn v-bind="props" icon="mdi-arrow-down" variant="text" :disabled="!heroEditing || index === visibleHeroSlides.length - 1" aria-label="Переместить ниже" @click="moveHero(index, index + 1)" />
                                        </template>
                                    </v-tooltip>
                                    <input :ref="(el) => { if (el) heroFileInputs[slide.uid] = el; }" class="admin-hidden-input" type="file" accept="image/png,image/jpeg,image/webp" @change="replaceHero(slide, $event)">
                                    <v-btn prepend-icon="mdi-image-sync-outline" variant="tonal" :disabled="!heroEditing" @click="heroFileInputs[slide.uid]?.click()">Заменить</v-btn>
                                    <v-btn prepend-icon="mdi-delete-outline" color="error" variant="text" :disabled="!heroEditing" @click="deleteHero(slide)">Удалить</v-btn>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>

                <section class="admin-panel" :class="{ 'admin-panel--editing': categoryEditing }">
                    <div class="admin-panel-header">
                        <div>
                            <div class="admin-panel-title-row">
                                <h2>Категории главной страницы</h2>
                                <v-chip v-if="categoryEditing" color="primary" size="small" variant="tonal">Режим редактирования</v-chip>
                            </div>
                            <p>Названия и переходы берутся из текущей логики сайта, здесь меняются изображение, порядок и видимость.</p>
                        </div>
                        <div class="admin-panel-actions">
                            <v-chip prepend-icon="mdi-database-outline" variant="tonal">{{ visibleCategories.length }} категорий</v-chip>
                            <v-btn v-if="!categoryEditing" prepend-icon="mdi-pencil-outline" color="primary" @click="startCategoryEditing">Редактировать</v-btn>
                            <template v-else>
                                <v-btn prepend-icon="mdi-content-save-outline" color="primary" :loading="savingCategories" :disabled="!categoryDirty" @click="saveCategoryChanges">Сохранить изменения</v-btn>
                                <v-btn prepend-icon="mdi-close" variant="outlined" :disabled="savingCategories" @click="cancelCategoryEditing">Отмена</v-btn>
                            </template>
                        </div>
                    </div>

                    <div class="category-admin-list">
                        <article
                            v-for="(category, index) in visibleCategories"
                            :key="category.category_key"
                            class="category-admin-item"
                            :class="{ 'is-disabled': !category.is_active, 'is-editing': categoryEditing }"
                            :draggable="categoryEditing"
                            @dragstart="draggedCategoryKey = category.category_key"
                            @dragover.prevent
                            @drop="onCategoryDrop(category.category_key)"
                        >
                            <img :src="category.image_url" :alt="category.title">
                            <div>
                                <strong>{{ category.title }}</strong>
                                <small>{{ category.to }}</small>
                                <v-chip :color="category.hasCustomImage ? 'primary' : undefined" size="small" variant="tonal">
                                    {{ category.hasCustomImage ? 'Картинка из БД' : 'Fallback-картинка' }}
                                </v-chip>
                            </div>
                            <div class="admin-row-actions">
                                <v-switch
                                    v-model="category.is_active"
                                    color="primary"
                                    hide-details
                                    density="compact"
                                    label="На главной"
                                    :readonly="!categoryEditing"
                                    @update:model-value="category.dirty = true"
                                />
                                <v-tooltip text="Переместить выше">
                                    <template #activator="{ props }">
                                        <v-btn v-bind="props" icon="mdi-arrow-up" variant="text" :disabled="!categoryEditing || index === 0" aria-label="Переместить выше" @click="moveCategory(index, index - 1)" />
                                    </template>
                                </v-tooltip>
                                <v-tooltip text="Переместить ниже">
                                    <template #activator="{ props }">
                                        <v-btn v-bind="props" icon="mdi-arrow-down" variant="text" :disabled="!categoryEditing || index === visibleCategories.length - 1" aria-label="Переместить ниже" @click="moveCategory(index, index + 1)" />
                                    </template>
                                </v-tooltip>
                                <input :ref="(el) => { if (el) categoryFileInputs[category.category_key] = el; }" class="admin-hidden-input" type="file" accept="image/png,image/jpeg,image/webp" @change="replaceCategory(category, $event)">
                                <v-btn prepend-icon="mdi-upload-outline" variant="tonal" :disabled="!categoryEditing" @click="categoryFileInputs[category.category_key]?.click()">Загрузить</v-btn>
                                <v-btn prepend-icon="mdi-backup-restore" color="error" variant="text" :disabled="!categoryEditing || (!category.hasCustomImage && !category.file)" @click="deleteCategoryImage(category)">Вернуть fallback</v-btn>
                            </div>
                        </article>
                    </div>
                </section>
                </template>

                <template v-else-if="adminTab === 'categories'">
                    <section class="admin-panel">
                        <div class="admin-panel-header">
                            <div>
                                <h2>Категории каталога</h2>
                                <p>Категории и группы фильтров, которые используются на странице каталога и в карточках.</p>
                            </div>
                            <div class="admin-panel-actions">
                                <v-chip prepend-icon="mdi-database-outline" variant="tonal">{{ allCatalogCategories.length }} категорий</v-chip>
                                <v-btn color="primary" prepend-icon="mdi-plus" @click="openCategoryDialog()">Добавить категорию</v-btn>
                            </div>
                        </div>

                        <div v-for="group in catalogCategoryGroups" :key="group.type" class="admin-table-block">
                            <div class="admin-table-title">
                                <h3>{{ group.type }}</h3>
                                <span>{{ group.items.length }}</span>
                            </div>
                            <div class="admin-table-scroll">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>Фото</th>
                                            <th>Название</th>
                                            <th>Slug</th>
                                            <th>Товары</th>
                                            <th>Статус</th>
                                            <th>Порядок</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="category in group.items" :key="category.id">
                                            <td><img class="admin-thumb" :src="category.image_url" :alt="category.name_lv"></td>
                                            <td><strong>{{ category.name_lv }}</strong><small>{{ category.name_ru || 'RU не задан' }} · {{ category.name_en || 'EN не задан' }}</small></td>
                                            <td>{{ category.slug }}</td>
                                            <td>{{ category.products_count }}</td>
                                            <td><v-chip :color="category.is_active ? 'success' : undefined" size="small" variant="tonal">{{ category.is_active ? 'active' : 'inactive' }}</v-chip></td>
                                            <td>{{ category.sort_order }}</td>
                                            <td class="admin-table-actions">
                                                <v-btn size="small" variant="tonal" prepend-icon="mdi-pencil-outline" @click="openCategoryDialog(category)">Редактировать</v-btn>
                                                <v-btn size="small" color="error" variant="text" prepend-icon="mdi-delete-outline" :disabled="category.products_count > 0" @click="deleteCatalogCategory(category)">Удалить</v-btn>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </template>

                <template v-else>
                    <section class="admin-panel">
                        <div class="admin-panel-header">
                            <div>
                                <h2>Товары</h2>
                                <p>Список товаров из PostgreSQL. Эти данные используются каталогом, поиском и фильтрами.</p>
                            </div>
                            <v-btn color="primary" prepend-icon="mdi-plus" @click="openProductDialog()">Добавить товар</v-btn>
                        </div>

                        <div class="admin-toolbar">
                            <v-text-field v-model="productSearch" label="Поиск" prepend-inner-icon="mdi-magnify" density="compact" hide-details @keyup.enter="loadCatalogAdmin" />
                            <v-select v-model="productCategoryFilter" :items="productCategoryOptions" label="Категория" density="compact" hide-details clearable />
                            <v-btn variant="outlined" :loading="loadingCatalog" @click="loadCatalogAdmin">Применить</v-btn>
                        </div>

                        <div class="admin-table-scroll">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Фото</th>
                                        <th>Название</th>
                                        <th>Цена</th>
                                        <th>Категории</th>
                                        <th>Статус</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in catalogProducts" :key="product.id">
                                        <td><img v-if="product.image" class="admin-thumb" :src="product.image" :alt="product.name_lv"><span v-else class="admin-thumb admin-thumb--empty">QR</span></td>
                                        <td><strong>{{ product.name_lv }}</strong><small>{{ product.slug }}</small></td>
                                        <td>{{ product.priceLabel }}</td>
                                        <td><v-chip v-for="category in product.categories" :key="category.id" class="mr-1 mb-1" size="x-small" variant="tonal">{{ category.name_lv }}</v-chip></td>
                                        <td><v-chip :color="product.is_active ? 'success' : undefined" size="small" variant="tonal">{{ product.is_active ? 'active' : 'inactive' }}</v-chip></td>
                                        <td class="admin-table-actions">
                                            <v-btn size="small" variant="tonal" prepend-icon="mdi-pencil-outline" @click="openProductDialog(product)">Редактировать</v-btn>
                                            <v-btn size="small" color="error" variant="text" prepend-icon="mdi-delete-outline" :loading="deletingProductId === product.id" @click="deleteProduct(product)">Удалить</v-btn>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="admin-pagination">
                            <span>{{ productPagination.total }} товаров</span>
                            <v-pagination v-model="productPagination.current_page" :length="productPagination.last_page" density="comfortable" @update:model-value="loadCatalogAdmin" />
                        </div>
                    </section>
                </template>
            </div>

            <v-dialog v-model="categoryDialog" max-width="760">
                <form class="admin-dialog" @submit.prevent="saveCatalogCategory">
                    <header>
                        <h2>{{ categoryForm.id ? 'Редактировать категорию' : 'Добавить категорию' }}</h2>
                        <v-btn icon="mdi-close" variant="text" aria-label="Закрыть" @click="categoryDialog = false" />
                    </header>
                    <div class="admin-form-grid">
                        <v-select v-model="categoryForm.type" :items="['occasion', 'category', 'material']" label="Группа" />
                        <v-text-field v-model="categoryForm.sort_order" type="number" label="Порядок" />
                        <v-text-field v-model="categoryForm.name_lv" label="Название LV" />
                        <v-text-field v-model="categoryForm.slug" label="Slug" />
                        <v-text-field v-model="categoryForm.name_ru" label="Название RU" />
                        <v-text-field v-model="categoryForm.name_en" label="Название EN" />
                        <v-text-field v-model="categoryForm.icon" label="Material icon" />
                        <v-text-field v-model="categoryForm.tone" label="Tone" />
                    </div>
                    <div class="admin-image-editor">
                        <img v-if="categoryForm.imagePreview || categoryForm.image_url" :src="categoryForm.imagePreview || categoryForm.image_url" :alt="categoryForm.name_lv">
                        <span v-else class="admin-thumb admin-thumb--empty">IMG</span>
                        <label class="admin-upload-btn">
                            <input type="file" accept="image/png,image/jpeg,image/webp" @change="onCatalogCategoryImage">
                            <v-icon icon="mdi-upload-outline" />
                            <span>Загрузить изображение</span>
                        </label>
                        <v-checkbox v-model="categoryForm.delete_image" label="Удалить кастомное изображение / вернуть fallback" hide-details />
                        <v-switch v-model="categoryForm.is_active" label="active" color="primary" hide-details />
                    </div>
                    <footer>
                        <v-btn variant="outlined" :disabled="savingCatalogCategory" @click="categoryDialog = false">Отмена</v-btn>
                        <v-btn type="submit" color="primary" :loading="savingCatalogCategory">Сохранить</v-btn>
                    </footer>
                </form>
            </v-dialog>

            <v-dialog v-model="productDialog" max-width="980">
                <form class="admin-dialog admin-dialog--product" @submit.prevent="saveProduct">
                    <header>
                        <div>
                            <h2>{{ productForm.id ? 'Редактировать товар' : 'Добавить товар' }}</h2>
                            <p>URL: /product/{{ productSlugPreview }}</p>
                        </div>
                        <v-btn icon="mdi-close" variant="text" aria-label="Закрыть" :disabled="savingProduct" @click="productDialog = false" />
                    </header>
                    <div class="admin-dialog-body">
                        <section class="admin-form-section">
                            <h3>Названия</h3>
                            <div class="admin-form-grid admin-form-grid--wide">
                                <v-text-field v-model="productForm.name_lv" label="Название LV *" required />
                                <v-text-field v-model="productForm.name_ru" label="Название RU" />
                                <v-text-field v-model="productForm.name_en" label="Название EN" />
                            </div>
                        </section>

                        <section class="admin-form-section">
                            <h3>Описание</h3>
                            <div class="admin-form-grid admin-form-grid--wide">
                                <v-textarea v-model="productForm.description_lv" label="Описание LV *" rows="3" required />
                                <v-textarea v-model="productForm.description_ru" label="Описание RU" rows="3" />
                                <v-textarea v-model="productForm.description_en" label="Описание EN" rows="3" />
                            </div>
                        </section>

                        <section class="admin-form-section">
                            <h3>Цена и фильтры</h3>
                            <div class="admin-form-grid admin-form-grid--wide">
                                <v-text-field v-model="productForm.price" type="number" step="0.01" min="0" label="Цена *" required />
                                <v-text-field v-model="productForm.sizesText" label="Размеры через запятую" />
                                <v-select v-model="productForm.category_ids" :items="productCategoryOptions" label="Категории *" multiple chips closable-chips required />
                            </div>
                        </section>

                        <section class="admin-form-section">
                            <h3>Изображения</h3>
                            <div class="admin-image-editor">
                                <div class="admin-image-strip">
                                    <figure v-for="image in productForm.existingImages" :key="image.id" :class="{ deleted: productForm.delete_image_ids.includes(image.id) }">
                                        <img :src="image.image_url" :alt="image.alt || productForm.name_lv">
                                        <v-btn size="x-small" color="error" variant="tonal" @click="markProductImageDeleted(image)">Удалить</v-btn>
                                    </figure>
                                    <figure v-for="preview in productForm.imagePreviews" :key="preview.url">
                                        <img :src="preview.url" :alt="preview.name">
                                        <figcaption>{{ preview.name }}</figcaption>
                                    </figure>
                                </div>
                                <label class="admin-upload-btn">
                                    <input type="file" accept="image/png,image/jpeg,image/webp" multiple @change="onProductImages">
                                    <v-icon icon="mdi-image-plus-outline" />
                                    <span>{{ productHasImages ? 'Добавить фото' : 'Загрузить главное фото *' }}</span>
                                </label>
                                <small>Первое изображение используется как главное фото товара.</small>
                            </div>
                        </section>

                        <section class="admin-form-section">
                            <h3>Настройки</h3>
                            <div class="admin-form-grid">
                                <v-switch v-model="productForm.is_active" label="Active" color="primary" hide-details />
                                <v-switch v-model="productForm.is_new" label="New" color="secondary" hide-details />
                            </div>
                        </section>
                    </div>
                    <footer class="admin-dialog-footer">
                        <v-btn variant="outlined" :disabled="savingProduct" @click="productDialog = false">Отмена</v-btn>
                        <v-btn type="submit" color="primary" :loading="savingProduct" :disabled="savingProduct">
                            {{ productForm.id ? 'Сохранить изменения' : 'Сохранить товар' }}
                        </v-btn>
                    </footer>
                </form>
            </v-dialog>
        </section>
    </main>
</template>
