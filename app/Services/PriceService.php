<?php

// app/Services/PriceService.php

namespace App\Services;

use App\Models\Discount;
use App\Models\Price;
use App\Repositories\PriceRepository;
use Illuminate\Database\Eloquent\Collection;

class PriceService
{
    public function __construct(
        private readonly PriceRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?Price
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?Price
    {
        return $this->repository->findTrashedById($id);
    }

    public function create(array $data): Price
    {
        return $this->repository->create($data);
    }

    public function update(Price $price, array $data): Price
    {
        return $this->repository->update($price, $data);
    }

    public function delete(Price $price): void
    {
        $this->repository->delete($price);
    }

    public function restore(Price $price): void
    {
        $this->repository->restore($price);
    }

    /**
     * Applies a discount to a price.
     * Returns the original amount if the discount is soft-deleted.
     */
    public function applyDiscount(Price $price, Discount $discount): float
    {
        if ($discount->deleted_at !== null) {
            return (float) $price->amount;
        }

        return (float) $price->amount * (1 - $discount->percentage / 100);
    }
}
