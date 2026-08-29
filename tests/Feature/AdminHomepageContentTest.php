<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\HomeCategoryImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminHomepageContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_manage_homepage_content(): void
    {
        $this->getJson('/admin/api/homepage')->assertUnauthorized();
    }

    public function test_existing_hero_images_are_imported_and_visible_to_admin(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->assertDatabaseHas('hero_slides', [
            'image_path' => 'images/Hero/hero.png',
            'disk' => 'asset',
            'sort_order' => 0,
            'is_active' => true,
        ]);
        $this->assertDatabaseCount('hero_slides', 3);

        $this->getJson('/api/home-content')
            ->assertOk()
            ->assertJsonCount(3, 'hero_slides')
            ->assertJsonPath('hero_slides.0.image_url', '/images/Hero/hero.png');

        $this->actingAs($admin)
            ->getJson('/admin/api/homepage')
            ->assertOk()
            ->assertJsonCount(3, 'hero_slides')
            ->assertJsonPath('hero_slides.1.image_path', 'images/Hero/hero1.png');
    }

    public function test_admin_can_upload_order_replace_and_delete_hero_slides(): void
    {
        Storage::fake('public');
        HeroSlide::query()->delete();
        $admin = User::factory()->create(['is_admin' => true]);

        $upload = $this->actingAs($admin)->post('/admin/api/homepage/hero-slides', [
            'images' => [
                $this->fixtureImage('hero-one.png'),
                $this->fixtureImage('hero-two.png'),
            ],
            'alt' => 'Hero alt',
        ]);

        $upload->assertCreated()->assertJsonCount(2, 'hero_slides');
        $first = HeroSlide::query()->orderBy('sort_order')->firstOrFail();
        $second = HeroSlide::query()->orderByDesc('sort_order')->firstOrFail();

        Storage::disk('public')->assertExists($first->image_path);
        $upload->assertJsonPath('hero_slides.0.image_url', '/storage/'.$first->image_path);
        $this->assertValidPublicStorageUrl($upload->json('hero_slides.0.image_url'));

        $this->actingAs($admin)->patchJson('/admin/api/homepage/hero-slides/order', [
            'items' => [
                ['id' => $first->id, 'sort_order' => 1],
                ['id' => $second->id, 'sort_order' => 0],
            ],
        ])->assertOk();

        $oldPath = $first->image_path;
        $this->actingAs($admin)->post('/admin/api/homepage/hero-slides/'.$first->id, [
            'image' => $this->fixtureImage('replacement.png'),
            'alt' => 'Replacement',
        ])->assertOk()
            ->assertJsonPath('hero_slide.alt', 'Replacement')
            ->assertJsonPath('hero_slide.image_url', '/storage/'.$first->refresh()->image_path);

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($first->image_path);

        $path = $first->refresh()->image_path;
        $this->actingAs($admin)->deleteJson('/admin/api/homepage/hero-slides/'.$first->id)->assertOk();
        Storage::disk('public')->assertMissing($path);
        $this->assertDatabaseMissing('hero_slides', ['id' => $first->id]);
    }

    public function test_admin_can_save_hero_order_in_one_batch(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $slides = HeroSlide::query()->orderBy('sort_order')->get();

        $payload = [
            [
                'id' => $slides[0]->id,
                'alt' => $slides[0]->alt,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'id' => $slides[1]->id,
                'alt' => 'Updated alt',
                'is_active' => false,
                'sort_order' => 0,
            ],
            [
                'id' => $slides[2]->id,
                'alt' => $slides[2]->alt,
                'is_active' => true,
                'sort_order' => 1,
            ],
        ];

        $this->actingAs($admin)
            ->post('/admin/api/homepage/hero-slides/batch', [
                'slides' => json_encode($payload),
            ])
            ->assertOk()
            ->assertJsonPath('hero_slides.0.id', $slides[1]->id)
            ->assertJsonPath('hero_slides.0.alt', 'Updated alt')
            ->assertJsonPath('hero_slides.0.is_active', false);

        $this->assertDatabaseHas('hero_slides', [
            'id' => $slides[1]->id,
            'sort_order' => 0,
            'is_active' => false,
            'alt' => 'Updated alt',
        ]);
    }

    public function test_admin_can_replace_toggle_order_and_delete_category_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post('/admin/api/homepage/categories/birthday/image', [
            'image' => $this->fixtureImage('birthday.png'),
            'alt' => 'Birthday',
        ])->assertOk()->assertJsonPath('category_image.category_key', 'birthday');

        $category = HomeCategoryImage::query()->where('category_key', 'birthday')->firstOrFail();
        $oldPath = $category->image_path;

        Storage::disk('public')->assertExists($oldPath);
        $this->assertSame('/storage/'.$oldPath, $category->image_url);
        $this->assertValidPublicStorageUrl($category->image_url);

        $this->actingAs($admin)->post('/admin/api/homepage/categories/birthday/image', [
            'image' => $this->fixtureImage('birthday-new.png'),
            'alt' => 'Birthday new',
        ])->assertOk()
            ->assertJsonPath('category_image.alt', 'Birthday new')
            ->assertJsonPath('category_image.image_url', '/storage/'.$category->refresh()->image_path);

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($category->image_path);

        $this->actingAs($admin)->patchJson('/admin/api/homepage/categories', [
            'category_key' => 'birthday',
            'is_active' => false,
        ])->assertOk()->assertJsonPath('category_image.is_active', false);

        $this->actingAs($admin)->patchJson('/admin/api/homepage/categories/order', [
            'items' => [
                ['category_key' => 'birthday', 'sort_order' => 2],
                ['category_key' => 'mugs', 'sort_order' => 1],
            ],
        ])->assertOk();

        $path = $category->refresh()->image_path;
        $this->actingAs($admin)->deleteJson('/admin/api/homepage/categories/birthday/image')->assertOk();
        Storage::disk('public')->assertMissing($path);
        $this->assertDatabaseHas('home_category_images', [
            'category_key' => 'birthday',
            'image_path' => null,
        ]);
    }

    public function test_admin_can_save_category_changes_and_return_to_fallback(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post('/admin/api/homepage/categories/batch', [
                'categories' => json_encode([
                    [
                        'category_key' => 'birthday',
                        'alt' => 'Birthday custom',
                        'is_active' => true,
                        'sort_order' => 3,
                        'file_key' => 'birthday_file',
                    ],
                    [
                        'category_key' => 'mugs',
                        'alt' => 'Mugs',
                        'is_active' => true,
                        'sort_order' => 0,
                    ],
                ]),
                'files' => [
                    'birthday_file' => $this->fixtureImage('birthday.png'),
                ],
            ])
            ->assertOk()
            ->assertJsonPath('category_images.0.category_key', 'mugs');

        $category = HomeCategoryImage::query()->where('category_key', 'birthday')->firstOrFail();
        $uploadedPath = $category->image_path;

        Storage::disk('public')->assertExists($uploadedPath);
        $this->assertSame('/storage/'.$uploadedPath, $category->image_url);
        $this->assertValidPublicStorageUrl($category->image_url);
        $this->assertTrue($category->is_active);

        $this->getJson('/api/home-content')
            ->assertOk()
            ->assertJsonFragment([
                'category_key' => 'birthday',
                'image_url' => '/storage/'.$uploadedPath,
            ]);

        $this->actingAs($admin)
            ->post('/admin/api/homepage/categories/batch', [
                'categories' => json_encode([
                    [
                        'category_key' => 'birthday',
                        'alt' => 'Birthday custom',
                        'is_active' => true,
                        'sort_order' => 0,
                        'delete_image' => true,
                    ],
                ]),
            ])
            ->assertOk()
            ->assertJsonPath('category_images.0.category_key', 'birthday')
            ->assertJsonPath('category_images.0.image_path', null);

        Storage::disk('public')->assertMissing($uploadedPath);
        $this->assertDatabaseHas('home_category_images', [
            'category_key' => 'birthday',
            'image_path' => null,
            'disk' => null,
            'is_active' => true,
        ]);
    }

    private function assertValidPublicStorageUrl(?string $url): void
    {
        $this->assertNotNull($url);
        $this->assertStringStartsWith('/storage/homepage/', $url);
        $this->assertStringNotContainsString('/storage/storage/', $url);
        $this->assertStringNotContainsString('/var/www', $url);
        $this->assertStringNotContainsString('localhost', $url);
    }

    private function fixtureImage(string $name): UploadedFile
    {
        return new UploadedFile(public_path('images/Hero/hero.png'), $name, 'image/png', null, true);
    }
}
