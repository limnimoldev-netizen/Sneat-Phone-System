<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_the_login_page(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get('/login')->assertOk();
    }

    public function test_direct_login_redirects_to_a_working_localized_dashboard(): void
    {
        $this->seed();
        $user = User::where('name', 'administrator')->firstOrFail();

        $this->post('/login', ['name' => $user->name, 'password' => config('app.seed_admin_password')])
            ->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
        $this->get('/')->assertRedirect(route('dashboard', 'en'));
        $this->get('/en/dashboard')->assertOk();
        $this->get('/kh/dashboard')->assertOk();
    }

    public function test_checkout_records_the_inventory_price_and_prevents_a_duplicate_sale(): void
    {
        $this->seed();
        $user = User::where('name', 'administrator')->firstOrFail();
        $product = Product::create([
            'product_name' => 'Test phone',
            'product_imei' => '123456789012345',
            'brand_id' => 1,
            'series_id' => 1,
            'color_id' => 1,
            'model_type_id' => 1,
            'storage_id' => 1,
            'network_id' => 1,
            'purchase_price' => '80.00',
            'selling_price' => '120.50',
            'employee_id' => $user->employee->id,
        ]);
        $checkout = route('sales.store', 'en');
        $payload = [
            'order_date' => '2026-02-01',
            'products' => json_encode([['id' => $product->id, 'price' => 1]]),
        ];

        $this->actingAs($user)->get(route('sales.create', 'en'))->assertOk();
        $this->get(route('orders.create', 'en'))->assertOk();
        $this->postJson($checkout, array_replace($payload, ['products' => '[]']))
            ->assertUnprocessable()->assertJsonValidationErrors('products');
        $this->assertDatabaseCount('orders', 0);
        $this->post($checkout, $payload)->assertRedirect(route('sales.index', 'en'));

        $this->get(route('sales.index', 'en'))->assertOk()->assertSee('120.50');
        $this->assertDatabaseHas('orders', ['id' => 1, 'employee_id' => $user->employee->id, 'total_amount' => '120.50']);
        $this->assertDatabaseHas('order_details', ['product_id' => $product->id, 'unit_price' => '120.50']);
        $this->assertEquals(Product::STATUS_ID_SOLD, $product->refresh()->status);
        $this->get(route('sales.show', ['lang' => 'en', 'id' => 1]))
            ->assertOk()->assertSee('Test phone')->assertSee('120.50');

        $this->postJson($checkout, $payload)->assertUnprocessable()->assertJsonValidationErrors('products');
        $this->assertDatabaseCount('orders', 1);
        $this->delete(route('sales.destroy', ['lang' => 'en', 'id' => 1]))->assertRedirect(route('sales.index', 'en'));
        $this->assertSoftDeleted('orders', ['id' => 1]);
        $this->assertEquals(Product::STATUS_ID_AVAILABLE, $product->refresh()->status);
    }
}
