<?php

namespace App\Filament\Clusters;

use Filament\Support\Icons\Heroicon;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;

class StudentCluster extends Cluster
{
    protected static ?string $slug = 'students';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?string $navigationLabel = 'Mahasiswa';
    protected static ?string $modelLabel = 'Mahasiswa';
    protected static ?string $pluralModelLabel = 'Mahasiswa';
    protected static ?int $navigationSort = 1;
    public static function getNavigationGroup(): ?string
    {
        return 'Akademik';
    }
    public static function getClusterBreadcrumb(): string
    {
        return __('Mahasiswa');
    }
}
