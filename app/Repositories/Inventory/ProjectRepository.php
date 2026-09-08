<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\Project;
use Carbon\Carbon;

class ProjectRepository
{
    public function getAll()
    {
        return Project::with([
            'revenues',
            'cashTransactions'
            ])->get();
    }

    public function getDetails(Project $project): ?Project
    {
        return $project->load([
            'revenues',
            'cashTransactions'
            ]);
    }

    public function create(array $project_request): Project
    {
        return Project::create($project_request);
    }

    public function update(
        Project $project,
        array $project_request
    ): Project {
        $project->update($project_request);

        return $project->refresh();
    }

    public function delete(Project $project): bool
    {
        return (bool) $project->delete();
    }

    public function projectsReport(
        ?string $from = null,
        ?string $to = null ): array
    {
        $project = Project::query();

        $revenueQuery = $project
            ->revenues();

        $cashQuery = $project
            ->cashTransactions();

        // if ($from) {
        //     $fromDate = Carbon::createFromFormat('m/Y', $from)->startOfMonth();

        //     $project->whereDate('revenue_date', '>=', $fromDate);
        // }

        // if ($to) {
        //     $toDate = Carbon::createFromFormat('m/Y', $to)->endOfMonth();

        //     $project->whereDate('revenue_date', '<=', $toDate);
        // }


        $totalRevenues = (float)
            $revenueQuery->sum('amount');

        // $paid = (float) $cashQuery
        //     ->clone()
        //     ->sum('amount');

        $received = (float) $cashQuery
            ->clone()
            ->whereIn('transaction_type', [
                'income',
                'other_income',
                'revenue_payment',
            ])
            ->sum('amount');

        return [
            'project' => $project,

            'total_revenue' =>
                $totalRevenues,

            'received' =>
                $received,


            'receivable' =>
                max($totalRevenues - $received, 0),

        ];
    }
}
