<?php

namespace App\Filament\Clusters;

use Filament\Support\Icons\Heroicon;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;

class KrsCluster extends Cluster
{
    protected static ?string $slug = 'krs';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static ?string $navigationLabel = 'Kartu Rencana Studi';
    protected static ?string $modelLabel = 'Kartu Rencana Studi';
    protected static ?string $pluralModelLabel = 'Kartu Rencana Studi';
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    public static function getNavigationGroup(): ?string
    {
        return 'Mahasiswa';
    }
    public static function getClusterBreadcrumb(): string
    {
        return __('Kartu Rencana Studi');
    }
}
