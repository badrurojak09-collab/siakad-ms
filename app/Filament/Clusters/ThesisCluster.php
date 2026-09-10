<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class ThesisCluster extends Cluster
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static ?string $navigationLabel = 'Tugas Akhir';
    protected static ?int $navigationSort = 1;
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationGroup(): ?string
    {
        return 'Tugas Akhir & Kelulusan';
    }
    public static function getClusterBreadcrumb(): string
    {
        return __('Tugas Akhir');
    }
}
