<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // tenant_id sudah memiliki FK dari migration 2026_08_25_190000_harden_phase_a_foundation.
        // Jangan membuat ulang academic_advisors_tenant_id_foreign karena nama constraint
        // MySQL bersifat unik dalam satu database.
        $foreignKeys = [
            [
                'column' => 'lecturer_id',
                'name' => 'academic_advisors_lecturer_id_fk',
                'reference_table' => 'lecturers',
            ],
            [
                'column' => 'student_id',
                'name' => 'academic_advisors_student_id_fk',
                'reference_table' => 'students',
            ],
            [
                'column' => 'semester_id',
                'name' => 'academic_advisors_semester_id_fk',
                'reference_table' => 'semesters',
            ],
        ];

        foreach ($foreignKeys as $foreignKey) {
            if ($this->foreignKeyExists('academic_advisors', $foreignKey['name'])) {
                continue;
            }

            Schema::table('academic_advisors', function (Blueprint $table) use ($foreignKey): void {
                $table
                    ->foreign($foreignKey['column'], $foreignKey['name'])
                    ->references('id')
                    ->on($foreignKey['reference_table'])
                    ->nullOnDelete();
            });
        }

        if (!$this->indexExists('academic_advisors', 'academic_advisors_active_lookup')) {
            Schema::table('academic_advisors', function (Blueprint $table): void {
                $table->index(
                    ['tenant_id', 'student_id', 'semester_id', 'is_active'],
                    'academic_advisors_active_lookup'
                );
            });
        }
    }

    public function down(): void
    {
        foreach ([
            'academic_advisors_lecturer_id_fk',
            'academic_advisors_student_id_fk',
            'academic_advisors_semester_id_fk',
        ] as $constraintName) {
            if (!$this->foreignKeyExists('academic_advisors', $constraintName)) {
                continue;
            }

            Schema::table('academic_advisors', function (Blueprint $table) use ($constraintName): void {
                $table->dropForeign($constraintName);
            });
        }

        if ($this->indexExists('academic_advisors', 'academic_advisors_active_lookup')) {
            Schema::table('academic_advisors', function (Blueprint $table): void {
                $table->dropIndex('academic_advisors_active_lookup');
            });
        }

        // FK tenant sengaja tidak dihapus karena dibuat oleh migration fase fondasi.
    }

    private function foreignKeyExists(string $table, string $constraintName): bool
    {
        return DB::table('information_schema.REFERENTIAL_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::connection()->getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $constraintName)
            ->exists();
    }

    private function indexExists(string $table, string $indexName): bool
    {
        return DB::selectOne(
            'SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ? LIMIT 1',
            [DB::connection()->getDatabaseName(), $table, $indexName]
        ) !== null;
    }
};
