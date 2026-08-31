<?php

namespace App\Http\Resources\Inventory\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectFinancialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $project = $this->resource['project'];

        return [
            'project' => [
                'id' => $project->id,
                'name' => $project->project_name,
            ],

            'total_revenue' =>
                $this->resource['total_revenue'],

            'total_expenses' =>
                $this->resource['total_expenses'],

            'received' =>
                $this->resource['received'],

            'paid' =>
                $this->resource['paid'],

            'profit' =>
                $this->resource['profit'],

            'receivable' =>
                $this->resource['receivable'],

            'payable' =>
                $this->resource['payable'],
        ];
    }
}
