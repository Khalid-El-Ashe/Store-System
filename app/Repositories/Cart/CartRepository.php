<?php

namespace App\Repositories\Cart;

use App\Models\Product;
use Illuminate\Support\Collection;

// this interface Repository is the clean code way to build the repository
// this interface is the contract for the CartRepository
interface CartRepository
{
    public function get(): Collection;
    public function add(Product $product, $quantity = 1);
    public function delete($id);
    public function empty();
    public function total(): float;
    public function update($id, $quantity);
}
