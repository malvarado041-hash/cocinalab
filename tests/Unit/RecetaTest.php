<?php

namespace Tests\Unit;

use App\Models\Receta;
use PHPUnit\Framework\TestCase;

class RecetaTest extends TestCase
{
    public function test_conversion_exige_unidades_y_respeta_dimensiones(): void
    {
        $this->assertSame(0.001, Receta::factorConversion('g', 'kg'));
        $this->assertSame(1000.0, Receta::factorConversion('L', 'ml'));
        $this->assertNull(Receta::factorConversion('pieza', 'kg'));
        $this->assertNull(Receta::factorConversion(null, 'kg'));
    }

    public function test_normaliza_pasos_legacy(): void
    {
        $r = new Receta(['Procedimiento' => "1. 1. Cocinar\n2) Servir"]);
        $this->assertSame(['Cocinar', 'Servir'], $r->pasos());
    }
}
