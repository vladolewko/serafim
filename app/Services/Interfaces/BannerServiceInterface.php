<?php
namespace App\Services\Interfaces;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Collection;

interface BannerServiceInterface
{
    public function getAll(): Collection;
    public function getById(int $id): Banner;
    public function create(array $data): Banner|null;
    public function update(int $id, array $data): Banner|null;
    public function destroy(int $id): bool;
}
