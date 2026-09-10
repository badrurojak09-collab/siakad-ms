<?php

namespace App\Filament\Clusters;

use Filament\Support\Icons\Heroicon;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;

class AttendanceCluster extends Cluster
{
    protected static ?string $slug = 'attendance';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static ?string $navigationLabel = 'Presensi';
    protected static ?string $modelLabel = 'Presensi';
    protected static ?string $pluralModelLabel = 'Presensi';
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?int $navigationSort = 3;
    public static function getNavigationGroup(): ?string
    {
        return 'Perkuliahan';
    }
    public static function getClusterBreadcrumb(): string
    {
        return __('Presensi');
    }
}
