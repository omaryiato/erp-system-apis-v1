<?php

// namespace App\Http\Resources\Inventory\Reports;

// use Illuminate\Http\Request;
// use Illuminate\Http\Resources\Json\JsonResource;

// class ProjectReportResource extends JsonResource
// {
//     public function toArray(Request $request): array
//     {
//         $project = $this->resource['project'];

//         return [
//             'project' => [
//                 'id' => $project->id,
//                 'name' => $project->project_name,
//             ],

//             'total_revenue' =>
//                 $this->resource['total_revenue'],

//             'received' =>
//                 $this->resource['received'],


//             'receivable' =>
//                 $this->resource['receivable'],

//         ];
//     }
// }


namespace App\Http\Resources\Inventory\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_revenue' => (float) $this->resource['total_revenue'],

            'received' => (float) $this->resource['received'],

            'receivable' => (float) $this->resource['receivable'],
        ];
    }
}
