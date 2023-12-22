<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Events', auth()->user()->events()->count()),
            Stat::make('Tasks', auth()->user()->tasks()->count())
        ];
    }
}
