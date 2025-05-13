<?php

namespace App\Filament\Pages;

use App\Models\Expense;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class TournamentReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    
    protected static ?string $navigationLabel = 'Financial Reports';
    
    protected static ?string $navigationGroup = 'Tournament Admin';

    protected static string $view = 'filament.pages.tournament-reports';
    
    public $dateStart;
    public $dateEnd;
    public $expenseType;
    
    public function mount(): void
    {
        $this->dateStart = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateEnd = Carbon::now()->format('Y-m-d');
        $this->expenseType = '';
    }
    
    public function applyFilters(): void
    {
        // This would normally apply filters, but we're using direct model calls in the view
        $this->dispatch('notify', [
            'title' => 'Filters Applied',
            'message' => 'The report has been updated with your filters.',
            'status' => 'success',
        ]);
    }
    
    public function exportCsv(): Response
    {
        // Generate CSV data
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="tournament-expenses-' . date('Y-m-d') . '.csv"',
        ];
        
        $expenses = Expense::query()
            ->when($this->dateStart, fn($query) => $query->where('date', '>=', $this->dateStart))
            ->when($this->dateEnd, fn($query) => $query->where('date', '<=', $this->dateEnd))
            ->when($this->expenseType, fn($query) => $query->where('expense_type', $this->expenseType))
            ->with('team')
            ->get();
            
        $callback = function() use ($expenses) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['Date', 'Type', 'Description', 'Team', 'Amount', 'Status']);
            
            // Add expense data
            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->date->format('Y-m-d'),
                    $expense->expense_type,
                    $expense->description,
                    $expense->team ? $expense->team->team_name : 'N/A',
                    number_format($expense->amount, 2),
                    $expense->status,
                ]);
            }
            
            fclose($file);
        };
        
        $this->dispatch('notify', [
            'title' => 'Download Started',
            'message' => 'Your CSV file is being downloaded.',
            'status' => 'success',
        ]);
        
        return response()->stream($callback, 200, $headers);
    }
}
