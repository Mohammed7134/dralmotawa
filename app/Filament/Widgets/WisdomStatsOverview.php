<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Guest;
use App\Models\User;
use App\Models\Wisdom;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WisdomStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Wisdoms', Wisdom::count())
                ->icon('heroicon-o-light-bulb')
                ->color('primary'),
            Stat::make('Categories', Category::count())
                ->icon('heroicon-o-tag')
                ->color('success'),
            Stat::make('Push Subscribers', User::count() + Guest::count())
                ->icon('heroicon-o-bell')
                ->color('warning'),
            Stat::make('Total Likes', Wisdom::sum('likes'))
                ->icon('heroicon-o-heart')
                ->color('danger'),
        ];
    }
}
