<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Teams Metric Card -->
        <x-filament-panels::card>
            <div class="text-xl font-bold mb-2">
                Total Teams
            </div>
            <div class="text-3xl font-bold">
                {{ \App\Models\Team::count() }}
            </div>
        </x-filament-panels::card>

        <!-- Total Sponsors Metric Card -->
        <x-filament-panels::card>
            <div class="text-xl font-bold mb-2">
                Total Sponsors
            </div>
            <div class="text-3xl font-bold">
                {{ \App\Models\Sponsor::count() }}
            </div>
        </x-filament-panels::card>

        <!-- Outstanding Balances Card -->
        <x-filament-panels::card>
            <div class="text-xl font-bold mb-2">
                Outstanding Sponsor Balances
            </div>
            <div class="text-3xl font-bold text-danger-500">
                ${{ number_format(\App\Models\Sponsor::sum('committed_amount') - \App\Models\Sponsor::sum('paid_amount'), 2) }}
            </div>
        </x-filament-panels::card>

        <!-- Pending Expenses Card -->
        <x-filament-panels::card>
            <div class="text-xl font-bold mb-2">
                Pending Expenses
            </div>
            <div class="text-3xl font-bold text-warning-500">
                ${{ number_format(\App\Models\Expense::where('status', 'pending')->sum('amount'), 2) }}
            </div>
        </x-filament-panels::card>

        <!-- Incomplete Tasks Card -->
        <x-filament-panels::card>
            <div class="text-xl font-bold mb-2">
                Incomplete Tasks
            </div>
            <div class="text-3xl font-bold text-primary-500">
                {{ \App\Models\Task::where('done', false)->count() }}
            </div>
        </x-filament-panels::card>

        <!-- Overdue Tasks Card -->
        <x-filament-panels::card>
            <div class="text-xl font-bold mb-2">
                Overdue Tasks
            </div>
            <div class="text-3xl font-bold text-danger-500">
                {{ \App\Models\Task::where('due_date', '<', now())->where('done', false)->count() }}
            </div>
        </x-filament-panels::card>
    </div>

    <!-- Recent Teams Section -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">Recent Teams</h2>
        <x-filament-tables::table>
            <x-slot name="header">
                <x-filament-tables::header-cell>Team Name</x-filament-tables::header-cell>
                <x-filament-tables::header-cell>Contact Person</x-filament-tables::header-cell>
                <x-filament-tables::header-cell>Cash Paid</x-filament-tables::header-cell>
                <x-filament-tables::header-cell>Total Due</x-filament-tables::header-cell>
                <x-filament-tables::header-cell>Balance</x-filament-tables::header-cell>
            </x-slot>

            @foreach(\App\Models\Team::latest()->take(5)->get() as $team)
                <x-filament-tables::row>
                    <x-filament-tables::cell>{{ $team->team_name }}</x-filament-tables::cell>
                    <x-filament-tables::cell>{{ $team->contact_person }}</x-filament-tables::cell>
                    <x-filament-tables::cell>${{ number_format($team->cash_paid, 2) }}</x-filament-tables::cell>
                    <x-filament-tables::cell>${{ number_format($team->getTotalDueAttribute(), 2) }}</x-filament-tables::cell>
                    <x-filament-tables::cell>
                        <span class="{{ $team->getBalanceAttribute() > 0 ? 'text-danger-500' : 'text-success-500' }}">
                            ${{ number_format($team->getBalanceAttribute(), 2) }}
                        </span>
                    </x-filament-tables::cell>
                </x-filament-tables::row>
            @endforeach
        </x-filament-tables::table>
    </div>

    <!-- Recent Sponsors Section -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">Recent Sponsors</h2>
        <x-filament::table>
            <x-slot name="header">
                <x-filament::table.heading>Name</x-filament::table.heading>
                <x-filament::table.heading>Category</x-filament::table.heading>
                <x-filament::table.heading>Committed</x-filament::table.heading>
                <x-filament::table.heading>Paid</x-filament::table.heading>
                <x-filament::table.heading>Balance</x-filament::table.heading>
            </x-slot>

            @foreach(\App\Models\Sponsor::latest()->take(5)->get() as $sponsor)
                <x-filament::table.row>
                    <x-filament::table.cell>{{ $sponsor->name }}</x-filament::table.cell>
                    <x-filament::table.cell>
                        <x-filament::badge>{{ $sponsor->category }}</x-filament::badge>
                    </x-filament::table.cell>
                    <x-filament::table.cell>${{ number_format($sponsor->committed_amount, 2) }}</x-filament::table.cell>
                    <x-filament::table.cell>${{ number_format($sponsor->paid_amount, 2) }}</x-filament::table.cell>
                    <x-filament::table.cell>
                        <span class="{{ $sponsor->getBalanceAttribute() > 0 ? 'text-warning-500' : 'text-success-500' }}">
                            ${{ number_format($sponsor->getBalanceAttribute(), 2) }}
                        </span>
                    </x-filament::table.cell>
                </x-filament::table.row>
            @endforeach
        </x-filament::table>
    </div>
</x-filament::page>

        <!-- Overdue Tasks Card -->
        <x-filament-panels::card>
            <div class="text-xl font-bold mb-2">
                Overdue Tasks
            </div>
            <div class="text-3xl font-bold text-danger-500">
                {{ \App\Models\Task::where('due_date', '<', now())->where('done', false)->count() }}
            </div>
        </x-filament-panels::card>
    </div>

    <!-- Recent Teams Section -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">Recent Teams</h2>
        <x-filament-tables::table>
            <x-slot name="header">
                <x-filament-tables::header-cell>Team Name</x-filament-tables::header-cell>
                <x-filament-tables::header-cell>Contact Person</x-filament-tables::header-cell>
                <x-filament-tables::header-cell>Cash Paid</x-filament-tables::header-cell>
                <x-filament-tables::header-cell>Total Due</x-filament-tables::header-cell>
                <x-filament-tables::header-cell>Balance</x-filament-tables::header-cell>
            </x-slot>

            @foreach(\App\Models\Team::latest()->take(5)->get() as $team)
                <x-filament-tables::row>
                    <x-filament-tables::cell>{{ $team->team_name }}</x-filament-tables::cell>
                    <x-filament-tables::cell>{{ $team->contact_person }}</x-filament-tables::cell>
                    <x-filament-tables::cell>${{ number_format($team->cash_paid, 2) }}</x-filament-tables::cell>
                    <x-filament-tables::cell>${{ number_format($team->getTotalDueAttribute(), 2) }}</x-filament-tables::cell>
                    <x-filament-tables::cell>
                        <span class="{{ $team->getBalanceAttribute() > 0 ? 'text-danger-500' : 'text-success-500' }}">
                            ${{ number_format($team->getBalanceAttribute(), 2) }}
                        </span>
                    </x-filament-tables::cell>
                </x-filament-tables::row>
            @endforeach
        </x-filament-tables::table>
    </div>

    <!-- Recent Sponsors Section -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">Recent Sponsors</h2>
        <x-filament-tables::table>
            <x-slot name="header">
                <x-filament-tables::header-cell>Name</x-filament-tables::header-cell>
                <x-filament-tables::header-cell>Category</x-filament-tables::header-cell>
                <x-filament-tables::header-cell>Committed</x-filament-tables::header-cell>
                <x-filament-tables::header-cell>Paid</x-filament-tables::header-cell>
                <x-filament-tables::header-cell>Balance</x-filament-tables::header-cell>
            </x-slot>

            @foreach(\App\Models\Sponsor::latest()->take(5)->get() as $sponsor)
                <x-filament-tables::row>
                    <x-filament-tables::cell>{{ $sponsor->name }}</x-filament-tables::cell>
                    <x-filament-tables::cell>
                        <x-filament-support::badge>{{ $sponsor->category }}</x-filament-support::badge>
                    </x-filament-tables::cell>
                    <x-filament-tables::cell>${{ number_format($sponsor->committed_amount, 2) }}</x-filament-tables::cell>
                    <x-filament-tables::cell>${{ number_format($sponsor->paid_amount, 2) }}</x-filament-tables::cell>
                    <x-filament-tables::cell>
                        <span class="{{ $sponsor->getBalanceAttribute() > 0 ? 'text-warning-500' : 'text-success-500' }}">
                            ${{ number_format($sponsor->getBalanceAttribute(), 2) }}
                        </span>
                    </x-filament-tables::cell>
                </x-filament-tables::row>
            @endforeach
        </x-filament-tables::table>
    </div>
</x-filament-panels::page>
