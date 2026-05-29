<?php

// app/Repositories/PriceRepository.php

namespace App\Repositories;

use App\Models\Price;
use Illuminate\Database\Eloquent\Collection;

class PriceRepository
{
    public function all(): Collection
    {
        return Price::all();
    }

    public function findById(int $id): ?Price
    {
        return Price::find($id);
    }

    public function findTrashedById(int $id): ?Price
    {
        return Price::withTrashed()->find($id);
    }

    public function create(array $data): Price
    {
        return Price::create($data);
    }

    public function update(Price $price, array $data): Price
    {
        $price->update($data);

        return $price;
    }

    public function delete(Price $price): void
    {
        $price->delete();
    }

    public function restore(Price $price): void
    {
        $price->restore();
    }
}
