<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

class PedidoTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $cliente;
    protected $producto;
    protected $empleado;

    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations
        $this->artisan('migrate');
        
        // Create test categoria
        $categoria = Categoria::create([
            'nombre' => 'Test Category',
            'status' => 'activo',
            'tipo' => 'test',
            'orden' => 1
        ]);
        
        // Create a test product with stock
        $this->producto = Producto::create([
            'nombre' => 'Test Product',
            'precio' => 10.00,
            'descripcion' => 'Test Description',
            'status' => 'activo',
            'stock' => 5,
            'categoria_id' => $categoria->id
        ]);

        // Create a test employee user with proper role
        $this->empleado = User::create([
            'name' => 'Test Employee',
            'email' => 'employee@example.com',
            'password' => bcrypt('password'),
            'role' => 'employee'
        ]);

        // Create a test client (already is a user)
        $this->cliente = Cliente::create([
            'nombre' => 'Test Client',
            'telefono' => '1234567890',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    #[Test]
    public function can_create_pedido()
    {
        $items = [
            [
                'producto_id' => $this->producto->id,
                'cantidad' => 2,
                'precio' => $this->producto->precio,
                'nombre' => $this->producto->nombre
            ]
        ];

        $pedidoData = [
            'cliente_id' => $this->cliente->id,
            'cliente_nombre' => $this->cliente->nombre,
            'cliente_telefono' => $this->cliente->telefono,
            'direccion' => 'Test Address',
            'items' => $items,
            'total' => $this->producto->precio * 2
        ];

        $pedido = Pedido::create($pedidoData);

        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id,
            'status' => 'pendiente',
            'total' => $this->producto->precio * 2
        ]);
    }

    #[Test]
    public function stock_decreases_when_pedido_marked_as_listo()
    {
        // Ensure we start with known stock
        $this->producto->update(['stock' => 5]);
        
        // Create order
        $pedido = $this->createTestPedido();
        $initialStock = $this->producto->fresh()->stock;

        // Mark as ready through empleado endpoint
        $response = $this->actingAs($this->empleado)
            ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->patch(route('empleado.pedidos.updateStatus', $pedido), [
                'status' => 'listo'
            ]);

        // Should succeed
        $response->assertStatus(200);
        $response->assertSessionHas('success');

        // Check stock decreased
        $this->producto->refresh();
        $actualStock = $this->producto->stock;
        $this->assertEquals($initialStock - 2, $actualStock, 
            "Stock should have decreased from $initialStock to " . ($initialStock - 2) . " but is $actualStock");
    }

    #[Test]
    public function cannot_cancel_pedido_if_not_pendiente()
    {
        $pedido = $this->createTestPedido();
        
        // Mark as preparing
        $pedido->update(['status' => 'preparando']);
        
        // Try to cancel
        $response = $this->actingAs($this->cliente)
            ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post(route('cliente.pedidos.cancelar', $pedido));
            
        $response->assertStatus(422);
        $this->assertEquals('preparando', $pedido->fresh()->status);
    }

    #[Test]
    public function cannot_mark_as_listo_if_insufficient_stock()
    {
        // Set product stock to 1
        $this->producto->update(['stock' => 1]);
        
        // Create order for 2 units
        $pedido = $this->createTestPedido();
        
        // Try to mark as ready
        $response = $this->actingAs($this->empleado)
            ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->patch(route('empleado.pedidos.updateStatus', $pedido), [
                'status' => 'listo'
            ]);
            
        $response->assertStatus(422);
        $response->assertSessionHas('error');
        
        // Verify status didn't change
        $pedido->refresh();
        $this->assertEquals('pendiente', $pedido->status);
    }

    #[Test]
    public function stock_returns_when_pedido_cancelled_from_listo()
    {
        // Start fresh with known stock
        $this->producto->update(['stock' => 5]);
        $initialStock = $this->producto->fresh()->stock;
        $this->assertEquals(5, $initialStock, 'Initial stock should be 5');
        
        // Create order and mark as ready
        $pedido = $this->createTestPedido();
        
        // Mark as ready through empleado endpoint
        $response = $this->actingAs($this->empleado)
            ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->patch(route('empleado.pedidos.updateStatus', $pedido), [
                'status' => 'listo'
            ]);
            
        $response->assertStatus(200);
        $response->assertSessionHas('success');
        
        // Verify stock decreased
        $this->producto->refresh();
        $afterReadyStock = $this->producto->stock;
        $this->assertEquals($initialStock - 2, $afterReadyStock, 
            "Stock should have decreased from $initialStock to " . ($initialStock - 2) . " but is $afterReadyStock");
        
        // Cancel order and verify stock returns
        $response = $this->actingAs($this->cliente)
            ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post(route('cliente.pedidos.cancelar', $pedido));
        
        // Check stock returned
        $this->producto->refresh();
        $finalStock = $this->producto->stock;
        $this->assertEquals($initialStock, $finalStock, "Stock should have returned to $initialStock but is $finalStock");
    }

    protected function createTestPedido()
    {
        return Pedido::create([
            'cliente_id' => $this->cliente->id,
            'cliente_nombre' => $this->cliente->nombre,
            'cliente_telefono' => $this->cliente->telefono,
            'direccion' => 'Test Address',
            'items' => [[
                'producto_id' => $this->producto->id,
                'cantidad' => 2,
                'precio' => $this->producto->precio,
                'nombre' => $this->producto->nombre
            ]],
            'total' => $this->producto->precio * 2,
            'status' => 'pendiente'
        ]);
    }
}