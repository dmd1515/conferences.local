<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_deletes_product_and_its_image()
    {
        Storage::fake('public');

        $product = Product::factory()->create([
            'image' => 'products/test.jpg',
        ]);

        // Sukuriame failą
        Storage::disk('public')->put('products/test.jpg', 'fake content');

        $this->delete(route('products.destroy', $product->id))
            ->assertRedirect(route('shop.index'));

        // Failas turi būti ištrintas
        Storage::disk('public')->assertMissing('products/test.jpg');

        // Produktas turi būti ištrintas
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /** @test */
    public function it_deletes_product_even_if_it_has_no_image()
    {
        Storage::fake('public');

        $product = Product::factory()->create([
            'image' => null,
        ]);

        $this->delete(route('products.destroy', $product->id))
            ->assertRedirect(route('shop.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
