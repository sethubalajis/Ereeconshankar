<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\PromotionEngine;

class PromotionEngineTest extends TestCase
{
    protected $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = new PromotionEngine();
    }

    public function test_buy_one_get_one_makes_lowest_price_free()
    {
        $cart = [
            ['product_id' => 1, 'quantity' => 1, 'base_price' => 5000], // Saree A
            ['product_id' => 1, 'quantity' => 1, 'base_price' => 2000], // Saree B
        ];

        $offers = [
            [
                'id' => 1,
                'type' => 'B1G1',
                'name' => 'Buy 1 Get 1 Saree',
                'priority' => 100,
                'is_stackable' => false,
                'buy_product_id' => 1,
                'buy_quantity' => 1,
                'get_quantity' => 1,
            ]
        ];

        $result = $this->engine->calculateCart($cart, null, null, $offers);
        
        $items = collect($result['items']);
        
        // Saree A should be 5000
        $this->assertEquals(5000, $items[0]['net_price']);
        $this->assertFalse($items[0]['is_free_item']);
        
        // Saree B should be free
        $this->assertEquals(0, $items[1]['net_price']);
        $this->assertTrue($items[1]['is_free_item']);
        $this->assertEquals('B1G1: Buy 1 Get 1 Saree', $items[1]['free_item_reason']);
    }

    public function test_exclusive_offer_prevents_stacking()
    {
        $cart = [
            ['product_id' => 2, 'quantity' => 1, 'base_price' => 1000], // Shirt
        ];

        $offers = [
            [
                'id' => 2,
                'type' => 'FESTIVAL',
                'name' => 'Diwali 20%',
                'priority' => 80,
                'is_stackable' => false, // Exclusive!
                'target_product_id' => 2,
                'percentage' => 20,
            ],
            [
                'id' => 3,
                'type' => 'CUSTOMER_GROUP',
                'name' => 'Gold Member 10%',
                'priority' => 50,
                'is_stackable' => true,
                'target_product_id' => 2,
                'percentage' => 10,
            ]
        ];

        $result = $this->engine->calculateCart($cart, null, null, $offers);
        
        $item = $result['items'][0];
        
        // Original: 1000. 
        // Diwali 20% applies -> Discount 200. Net 800.
        // It's exclusive, so Gold Member 10% is REJECTED.
        $this->assertEquals(800, $item['net_price']);
        
        // Verify audit log shows REJECTED for Gold Member
        $goldLog = collect($result['audit_log'])->firstWhere('promotion', 'Gold Member 10%');
        $this->assertEquals('REJECTED', $goldLog['status']);
    }

    public function test_stackable_offers_apply_sequentially()
    {
        $cart = [
            ['product_id' => 3, 'quantity' => 1, 'base_price' => 1000], 
        ];

        $offers = [
            [
                'id' => 4,
                'type' => 'FESTIVAL',
                'name' => 'Festival 10%',
                'priority' => 80,
                'is_stackable' => true, 
                'target_product_id' => 3,
                'percentage' => 10,
            ],
            [
                'id' => 5,
                'type' => 'CUSTOMER_GROUP',
                'name' => 'Gold Member 5%',
                'priority' => 50,
                'is_stackable' => true,
                'target_product_id' => 3,
                'percentage' => 5,
            ]
        ];

        $result = $this->engine->calculateCart($cart, null, null, $offers);
        $item = $result['items'][0];
        
        // Original: 1000. 
        // Festival 10% -> Net 900.
        // Gold 5% of 900 -> Discount 45. Net 855.
        $this->assertEquals(855, $item['net_price']);
    }
}
