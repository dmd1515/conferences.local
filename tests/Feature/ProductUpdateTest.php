<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;

class ProductUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_product_without_image()
    {
        $product = Product::factory()->create();

        $response = $this->put(route('products.update', $product->id), [
            'product_number' => 'PN123',
            'name' => 'New Name',
            'price' => 10,
            'sale_price' => 8,
            'sizes' => ['s' => 1, 'm' => 2, 'l' => 3]
        ]);

        $response->assertRedirect(route('shop.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'product_number' => 'PN123',
            'name' => 'New Name',
        ]);
    }

    /** @test */
    public function it_updates_product_and_replaces_image()
    {
        Storage::fake('public');

        $product = Product::factory()->create([
            'image' => 'products/old.jpg'
        ]);

        Storage::disk('public')->put('products/old.jpg', 'dummy');

        $response = $this->put(route('products.update', $product->id), [
            'product_number' => 'PN999',
            'name' => 'Updated',
            'price' => 10,
            'sale_price' => 9,
            'sizes' => ['s' => 1, 'm' => 2, 'l' => 3],
            'image' => UploadedFile::fake()->image('new.jpg')
        ]);

        $response->assertRedirect(route('shop.index'));

        Storage::disk('public')->assertMissing('products/old.jpg');
        Storage::disk('public')->assertExists('products/new.jpg');
    }

    /** @test */
    public function it_fails_when_product_number_is_not_unique()
    {
        Product::factory()->create(['product_number' => 'PN001']);
        $product = Product::factory()->create();

        $response = $this->from('/form')->put(route('products.update', $product->id), [
            'product_number' => 'PN001', // jau egzistuoja
            'name' => 'Test',
            'price' => 10,
            'sizes' => ['s' => 1, 'm' => 2, 'l' => 3]
        ]);

        $response->assertRedirect('/form');
        $response->assertSessionHasErrors('product_number');
    }

    /** @test */
    public function it_fails_when_sizes_are_invalid()
    {
        $product = Product::factory()->create();

        $response = $this->put(route('products.update', $product->id), [
            'product_number' => 'X',
            'name' => 'X',
            'price' => 10,
            'sizes' => ['s' => -1, 'm' => 0, 'l' => 1]
        ]);

        $response->assertSessionHasErrors('sizes.s');
    }

    /** @test */
    public function it_fails_if_image_is_invalid()
    {
        $product = Product::factory()->create();

        $response = $this->put(route('products.update', $product->id), [
            'product_number' => 'A',
            'name' => 'A',
            'price' => 10,
            'sizes' => ['s' => 1, 'm' => 1, 'l' => 1],
            'image' => UploadedFile::fake()->create('file.pdf', 10) // ne vaizdas
        ]);

        $response->assertSessionHasErrors('image');
    }
}
