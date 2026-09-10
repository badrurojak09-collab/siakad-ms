<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class AssessmentCluster extends Cluster
{


    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;
    protected static ?string $navigationLabel = 'Penilaian';
    protected static ?int $navigationSort = 1;
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationGroup(): ?string
    {
        return 'Penilaian';
    }
    public static function getClusterBreadcrumb(): string
    {
        return __('Penilaian');
    }
}
