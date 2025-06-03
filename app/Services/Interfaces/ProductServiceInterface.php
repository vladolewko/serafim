<?php
namespace App\Services\Interfaces;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

interface ProductServiceInterface
{
    public function getAll(): Collection;
    public function getById(int $id): Product;
    public function create(array $data, $profileImage = null): Product;
    public function update(int $id, array $data, $profileImage = null): Product;
    public function destroy(int $id): bool;
}