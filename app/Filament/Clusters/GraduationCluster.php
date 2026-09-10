<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class GraduationCluster extends Cluster
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;
    protected static ?string $navigationLabel = 'Wisuda';
    protected static ?int $navigationSort = 2;
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationGroup(): ?string
    {
        return 'Tugas Akhir & Kelulusan';
    }
    public static function getClusterBreadcrumb(): string
    {
        return __('Wisuda');
    }
}
