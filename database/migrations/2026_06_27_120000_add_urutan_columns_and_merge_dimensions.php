<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Dimension;
use App\Models\Indicator;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('dimensions', 'urutan_romawi')) {
            Schema::table('dimensions', function (Blueprint $table) {
                $table->string('urutan_romawi', 10)->nullable()->after('urutan');
            });
        }

        if (!Schema::hasColumn('indicators', 'urutan_keseluruhan')) {
            Schema::table('indicators', function (Blueprint $table) {
                $table->integer('urutan_keseluruhan')->nullable()->after('urutan');
            });
        }

        // Update existing dimensions with Roman numerals and merge FAKTOR_INTERNAL and FAKTOR_EKSTERNAL
        $dim1 = Dimension::where('kode', 'MUTU_GURU')->first();
        if ($dim1) {
            $dim1->update(['urutan_romawi' => 'I']);
        }

        $dim2 = Dimension::where('kode', 'PROSES_PEMBELAJARAN')->first();
        if ($dim2) {
            $dim2->update(['urutan_romawi' => 'II']);
        }

        $dimInternal = Dimension::where('kode', 'FAKTOR_INTERNAL')->first();
        $dimEksternal = Dimension::where('kode', 'FAKTOR_EKSTERNAL')->first();
        $dimMerged = Dimension::where('kode', 'INFLUENCING_FACTORS')->first();

        if (!$dimMerged && ($dimInternal || $dimEksternal)) {
            $dimMerged = Dimension::create([
                'kode' => 'INFLUENCING_FACTORS',
                'nama' => 'Influencing Factors',
                'deskripsi' => 'Faktor internal dan eksternal yang mempengaruhi kinerja guru: komitmen, motivasi, efikasi diri, kesehatan psikologis, resiliensi, kepemimpinan, iklim sekolah, sarana prasarana, kolaborasi DUDI, dan sistem penghargaan.',
                'urutan' => 3,
                'urutan_romawi' => 'III',
            ]);
        } elseif ($dimMerged) {
            $dimMerged->update([
                'nama' => 'Influencing Factors',
                'urutan' => 3,
                'urutan_romawi' => 'III',
            ]);
        }

        if ($dimMerged) {
            if ($dimInternal) {
                Indicator::where('dimension_id', $dimInternal->id)->update(['dimension_id' => $dimMerged->id]);
                $dimInternal->delete();
            }

            if ($dimEksternal) {
                // Adjust urutan for eksternal indicators so they follow internal indicators
                $eksternalIndicators = Indicator::where('dimension_id', $dimEksternal->id)->orderBy('urutan')->get();
                $currentMax = Indicator::where('dimension_id', $dimMerged->id)->max('urutan') ?: 0;
                foreach ($eksternalIndicators as $ind) {
                    $currentMax++;
                    $ind->update([
                        'dimension_id' => $dimMerged->id,
                        'urutan' => $currentMax
                    ]);
                }
                $dimEksternal->delete();
            }
        }

        // Populate urutan_keseluruhan for all indicators
        $allIndicators = Indicator::join('dimensions', 'indicators.dimension_id', '=', 'dimensions.id')
            ->orderBy('dimensions.urutan')
            ->orderBy('indicators.urutan')
            ->select('indicators.*')
            ->get();

        foreach ($allIndicators as $index => $indicator) {
            Indicator::where('id', $indicator->id)->update(['urutan_keseluruhan' => $index + 1]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('indicators', 'urutan_keseluruhan')) {
            Schema::table('indicators', function (Blueprint $table) {
                $table->dropColumn('urutan_keseluruhan');
            });
        }

        if (Schema::hasColumn('dimensions', 'urutan_romawi')) {
            Schema::table('dimensions', function (Blueprint $table) {
                $table->dropColumn('urutan_romawi');
            });
        }
    }
};
