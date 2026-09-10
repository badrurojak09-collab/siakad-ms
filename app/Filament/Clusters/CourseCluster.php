<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;


class CourseCluster extends Cluster
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBookmarkSquare;
    protected static ?string $navigationLabel = 'Matakuliah';
    protected static ?int $navigationSort = 2;
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationGroup(): ?string
    {
        return 'Perkuliahan';
    }
    public static function getClusterBreadcrumb(): string
    {
        return __('Perkuliahan');
    }
}
