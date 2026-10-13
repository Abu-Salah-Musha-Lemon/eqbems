<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::query()->count();
        $newUsersThisMonth = User::query()->where('created_at', '>=', now()->startOfMonth())->count();
        $institutionCount = Institution::query()->count();
        $activeInstitutions = Institution::query()->where('status', true)->count();
        $inactiveInstitutions = max(0, $institutionCount - $activeInstitutions);
        $recentUsers = User::query()->latest()->limit(6)->get(['id', 'name', 'email', 'created_at']);
        $recentInstitutions = Institution::query()->latest()->limit(5)->get(['id', 'uuid', 'code', 'name', 'email', 'status', 'created_at']);

        // Academic/examination modules are not yet present in the current migrations.
        $moduleCounts = [
            'questions' => Schema::hasTable('questions') ? \DB::table('questions')->count() : null,
            'exams' => Schema::hasTable('exams') ? \DB::table('exams')->count() : null,
            'results' => Schema::hasTable('results') ? \DB::table('results')->count() : null,
        ];

        $monthlyUsers = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->startOfMonth()->subMonths($monthsAgo);
            return [
                'label' => $date->format('M'),
                'count' => User::query()->whereBetween('created_at', [$date, $date->copy()->endOfMonth()])->count(),
            ];
        });

        return view('dashboard.index', compact(
            'userCount', 'newUsersThisMonth', 'institutionCount', 'activeInstitutions',
            'inactiveInstitutions', 'recentUsers', 'recentInstitutions', 'moduleCounts', 'monthlyUsers'
        ));
    }
}
