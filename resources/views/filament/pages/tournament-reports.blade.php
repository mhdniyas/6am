<x-filament::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">Tournament Financial Report</x-slot>
            <x-slot name="description">View financial summaries and export data</x-slot>

            <!-- Report Filters -->
            <form wire:submit="applyFilters" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-filament::input.wrapper>
                            <x-filament::input.label>Date Range Start</x-filament::input.label>
                            <x-filament::input.date wire:model="dateStart" />
                        </x-filament::input.wrapper>
                    </div>
                    <div>
                        <x-filament::input.wrapper>
                            <x-filament::input.label>Date Range End</x-filament::input.label>
                            <x-filament::input.date wire:model="dateEnd" />
                        </x-filament::input.wrapper>
                    </div>
                    <div>
                        <x-filament::input.wrapper>
                            <x-filament::input.label>Expense Type</x-filament::input.label>
                            <x-filament::input.select wire:model="expenseType">
                                <option value="">All Types</option>
                                <option value="equipment">Equipment</option>
                                <option value="marketing">Marketing</option>
                                <option value="venue">Venue</option>
                                <option value="travel">Travel</option>
                                <option value="food">Food</option>
                                <option value="other">Other</option>
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>
                </div>

                <x-filament::button type="submit">
                    Apply Filters
                </x-filament::button>

                <x-filament::button wire:click="exportCsv" color="success">
                    Export CSV
                </x-filament::button>
            </form>
        </x-filament::section>

        <!-- Report Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-filament::card>
                <div class="text-xl font-bold mb-2">Total Expenses</div>
                <div class="text-3xl font-bold">${{ number_format(\App\Models\Expense::sum('amount'), 2) }}</div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-xl font-bold mb-2">Total Sponsor Contributions</div>
                <div class="text-3xl font-bold">${{ number_format(\App\Models\Sponsor::sum('paid_amount'), 2) }}</div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-xl font-bold mb-2">Balance</div>
                <div class="text-3xl font-bold">${{ number_format(\App\Models\Sponsor::sum('paid_amount') - \App\Models\Expense::sum('amount'), 2) }}</div>
            </x-filament::card>
        </div>

        <!-- Expense Breakdown Table -->
        <x-filament::section>
            <x-slot name="heading">Expense Breakdown</x-slot>

            <x-filament::table>
                <x-slot name="header">
                    <x-filament::table.heading>Type</x-filament::table.heading>
                    <x-filament::table.heading>Amount</x-filament::table.heading>
                    <x-filament::table.heading>Percentage</x-filament::table.heading>
                </x-slot>

                @php
                    $expenseTypes = \App\Models\Expense::selectRaw('expense_type, SUM(amount) as total')
                        ->groupBy('expense_type')
                        ->get();
                    $totalExpenses = \App\Models\Expense::sum('amount');
                @endphp

                @foreach($expenseTypes as $expense)
                    <x-filament::table.row>
                        <x-filament::table.cell>
                            <x-filament::badge>{{ ucfirst($expense->expense_type) }}</x-filament::badge>
                        </x-filament::table.cell>
                        <x-filament::table.cell>${{ number_format($expense->total, 2) }}</x-filament::table.cell>
                        <x-filament::table.cell>
                            {{ number_format(($expense->total / ($totalExpenses ?: 1)) * 100, 1) }}%
                        </x-filament::table.cell>
                    </x-filament::table.row>
                @endforeach
            </x-filament::table>
        </x-filament::section>

        <!-- Team Financial Summary -->
        <x-filament::section>
            <x-slot name="heading">Team Financial Summary</x-slot>

            <x-filament::table>
                <x-slot name="header">
                    <x-filament::table.heading>Team</x-filament::table.heading>
                    <x-filament::table.heading>Total Expenses</x-filament::table.heading>
                    <x-filament::table.heading>Cash Paid</x-filament::table.heading>
                    <x-filament::table.heading>Balance</x-filament::table.heading>
                </x-slot>

                @foreach(\App\Models\Team::all() as $team)
                    <x-filament::table.row>
                        <x-filament::table.cell>{{ $team->team_name }}</x-filament::table.cell>
                        <x-filament::table.cell>${{ number_format($team->getTotalDueAttribute(), 2) }}</x-filament::table.cell>
                        <x-filament::table.cell>${{ number_format($team->cash_paid, 2) }}</x-filament::table.cell>
                        <x-filament::table.cell>
                            <span class="{{ $team->getBalanceAttribute() > 0 ? 'text-danger-500' : 'text-success-500' }}">
                                ${{ number_format($team->getBalanceAttribute(), 2) }}
                            </span>
                        </x-filament::table.cell>
                    </x-filament::table.row>
                @endforeach
            </x-filament::table>
        </x-filament::section>
    </div>
</x-filament::page>
