<?php

namespace Tests\Feature;

use App\Models\CatalogCategory;
use App\Models\Product;
use App\Models\User;
use App\Services\CatalogImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_static_catalog_is_imported_to_database_and_public_api(): void
    {
        app(CatalogImportService::class)->import();

        $this->assertDatabaseHas('catalog_categories', [
            'type' => 'occasion',
            'slug' => 'ziemassvetki',
            'image_path' => 'images/Products/день матери 4.png',
            'disk' => 'asset',
        ]);
        $this->assertDatabaseHas('products', ['slug' => 'postcard-alive']);

        $this->getJson('/api/catalog')
            ->assertOk()
            ->assertJsonPath('categories.0.items.0.image_url', '/images/Products/день матери 4.png')
            ->assertJsonFragment(['slug' => 'postcard-alive']);
    }

    public function test_admin_can_create_category_and_replace_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post('/admin/api/catalog-categories', [
            'type' => 'occasion',
            'name_lv' => 'Tests',
            'name_ru' => 'Тест',
            'name_en' => 'Test',
            'slug' => 'tests',
            'is_active' => true,
            'sort_order' => 20,
            'image' => $this->fixtureImage('category.png'),
        ]);

        $response->assertCreated()->assertJsonPath('category.slug', 'tests');
        $category = CatalogCategory::query()->where('slug', 'tests')->firstOrFail();
        Storage::disk('public')->assertExists($category->image_path);
        $this->assertSame('/storage/'.$category->image_path, $category->image_url);

        $oldPath = $category->image_path;
        $this->actingAs($admin)->post('/admin/api/catalog-categories/'.$category->id, [
            '_method' => 'PUT',
            'type' => 'occasion',
            'name_lv' => 'Tests updated',
            'name_ru' => 'Тест обновлён',
            'name_en' => 'Test updated',
            'slug' => 'tests',
            'is_active' => false,
            'sort_order' => 21,
            'image' => $this->fixtureImage('category-new.png'),
        ])->assertOk()
            ->assertJsonPath('category.name_lv', 'Tests updated')
            ->assertJsonPath('category.is_active', false);

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($category->refresh()->image_path);
    }

    public function test_admin_can_create_edit_and_delete_product_with_images_and_categories(): void
    {
        Storage::fake('public');
        app(CatalogImportService::class)->import();
        $admin = User::factory()->create(['is_admin' => true]);
        $category = CatalogCategory::query()->where('slug', 'ziemassvetki')->firstOrFail();

        $create = $this->actingAs($admin)->post('/admin/api/products', [
            'name_lv' => 'Personalizēta šokolāde mammai',
            'name_ru' => 'Подарок',
            'name_en' => 'Gift',
            'description_lv' => 'LV description',
            'description_ru' => 'RU description',
            'description_en' => 'EN description',
            'price' => 19,
            'is_active' => true,
            'is_new' => true,
            'category_ids' => [$category->id],
            'images' => [$this->fixtureImage('product.png')],
        ]);

        $create->assertCreated()
            ->assertJsonPath('product.slug', 'personalizeta-sokolade-mammai')
            ->assertJsonPath('product.categories.0.id', $category->id)
            ->assertJsonPath('product.name_ru', 'Подарок');

        $product = Product::query()->where('slug', 'personalizeta-sokolade-mammai')->firstOrFail();
        $image = $product->productImages()->firstOrFail();
        Storage::disk('public')->assertExists($image->image_path);
        $this->assertStringStartsWith('/storage/catalog/products/', $image->image_url);

        $this->getJson('/api/catalog?category_slug=ziemassvetki')
            ->assertOk()
            ->assertJsonFragment(['slug' => 'personalizeta-sokolade-mammai'])
            ->assertJsonFragment(['name_ru' => 'Подарок']);

        $this->getJson('/api/catalog?category_slug=ziemassvetki&locale=ru')
            ->assertOk()
            ->assertJsonFragment(['name' => 'Подарок']);

        $this->actingAs($admin)->post('/admin/api/products/'.$product->id, [
            '_method' => 'PUT',
            'name_lv' => 'Custom gift updated',
            'name_ru' => 'Подарок обновлён',
            'name_en' => 'Gift updated',
            'description_lv' => 'Updated LV description',
            'price' => 25,
            'is_active' => true,
            'category_ids' => [$category->id],
            'delete_image_ids' => [$image->id],
            'images' => [$this->fixtureImage('product-new.png')],
        ])->assertOk()
            ->assertJsonPath('product.name_lv', 'Custom gift updated');

        Storage::disk('public')->assertMissing($image->image_path);
        $newImage = $product->refresh()->productImages()->firstOrFail();
        Storage::disk('public')->assertExists($newImage->image_path);

        $this->actingAs($admin)->deleteJson('/admin/api/products/'.$product->id)->assertOk();
        Storage::disk('public')->assertMissing($newImage->image_path);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_product_slugs_are_generated_uniquely_from_lv_name(): void
    {
        Storage::fake('public');
        app(CatalogImportService::class)->import();
        $admin = User::factory()->create(['is_admin' => true]);
        $category = CatalogCategory::query()->where('slug', '8-marts')->firstOrFail();
        $payload = [
            'name_lv' => 'Personalizēta šokolāde mammai',
            'description_lv' => 'Apraksts',
            'price' => 29,
            'is_active' => true,
            'category_ids' => [$category->id],
            'images' => [$this->fixtureImage('first.png')],
        ];

        $this->actingAs($admin)
            ->post('/admin/api/products', $payload)
            ->assertCreated()
            ->assertJsonPath('product.slug', 'personalizeta-sokolade-mammai');

        $payload['images'] = [$this->fixtureImage('second.png')];

        $this->actingAs($admin)
            ->post('/admin/api/products', $payload)
            ->assertCreated()
            ->assertJsonPath('product.slug', 'personalizeta-sokolade-mammai-2');

        $this->assertDatabaseHas('products', ['slug' => 'personalizeta-sokolade-mammai']);
        $this->assertDatabaseHas('products', ['slug' => 'personalizeta-sokolade-mammai-2']);
    }

    private function fixtureImage(string $name): UploadedFile
    {
        return new UploadedFile(public_path('images/Hero/hero.png'), $name, 'image/png', null, true);
    }
}
