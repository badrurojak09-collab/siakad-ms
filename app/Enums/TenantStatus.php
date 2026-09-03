<?php
namespace App\Enums;

enum TenantStatus: string
{
    case Trial = 'trial';
    case Active = 'active';
    case Suspended = 'suspended';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Trial => 'Trial',
            self::Active => 'Aktif',
            self::Suspended => 'Suspended',
            self::Inactive => 'Tidak Aktif'
        };
    }

    public function operational(): bool
    {
        return in_array($this, [self::Trial, self::Active], true);
    }
}
