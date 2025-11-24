<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class PaymentSuccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_processes_payment_and_updates_stock_cart_and_session()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Session::put('promo_discount', 20);

        $product = Product::factory()->create([
            'sizes' => ['M' => 5, 'L' => 3]
        ]);

        Cart::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'size' => 'M',
            'quantity' => 2,
        ]);

        $response = $this->get(route('payment.success'));

        $response->assertRedirect(route('shop.index'));

        // Patikriname, ar sumažėjo dydžio kiekis
        $this->assertEquals(
            ['M' => 3, 'L' => 3],
            $product->fresh()->sizes
        );

        // Patikriname, ar išvalytas krepšelis
        $this->assertDatabaseMissing('carts', [
            'user_id' => $user->id
        ]);

        // Sesijoje neturi būti promo kodo
        $this->assertFalse(session()->has('promo_discount'));
    }

    /** @test */
    public function it_keeps_stock_at_zero_when_purchase_exceeds_available()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create([
            'sizes' => ['M' => 1]
        ]);

        Cart::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'size' => 'M',
            'quantity' => 5,
        ]);

        $this->get(route('payment.success'));

        $this->assertEquals(
            ['M' => 0],
            $product->fresh()->sizes
        );
    }
}
