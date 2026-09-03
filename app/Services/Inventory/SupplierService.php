<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Supplier;
use App\Repositories\Inventory\SupplierRepository;
use Illuminate\Support\Facades\DB;

class SupplierService
{
    public function __construct(
        private SupplierRepository $repository
    ) {}

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(Supplier $supplier): ?Supplier
    {
        return $this->repository->getDetails($supplier);
    }

    public function create(array $supplier_request): Supplier
    {
        return $this->repository->create($this->prepareSupplierInfo($supplier_request));
    }

    public function update(
        Supplier $supplier,
        array $supplier_request
    ): Supplier {
        return $this->repository->update(
            $supplier,
            $this->prepareSupplierInfo($supplier_request)
        );
    }

    public function delete(Supplier $supplier): bool
    {
        return  $this->repository->delete($supplier);
    }

    public function prepareSupplierInfo(array $supplier_request)
    {

        $supplier_data =  [
            'supplier_code' => $supplier_request['supplier_code'] ?? null,
            'name' => $supplier_request['name'] ?? null,
            'phone' => $supplier_request['phone'] ?? null,
            'email' => $supplier_request['email'] ?? null,
            'address' => $supplier_request['address'] ?? null,
            'tax_number' => $supplier_request['tax_number'] ?? null,
            'notes' => $supplier_request['notes'] ?? null,
            'status' => $supplier_request['status'] ?? null,
        ];

        return $supplier_data;
    }
}
