<?php
namespace App\Actions\Reporting;

use App\Models\ReportDefinition;
use Illuminate\Validation\ValidationException;

class GenerateReportAction
{
    public function execute(ReportDefinition $definition, array $parameters = []): array
    {
        if (blank($definition->query_template))
            throw ValidationException::withMessages(['query_template' => 'Report belum memiliki query terdaftar.']);
        throw new \RuntimeException('Report engine belum diaktifkan: gunakan query registry terparameterisasi, bukan SQL bebas.');
    }
}
