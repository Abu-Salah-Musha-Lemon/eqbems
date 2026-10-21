<?php
namespace App\Services;
use InvalidArgumentException;
final class GradeService
{
    // Fixed two-decimal units: no binary floating point in threshold comparisons.
    public static function cents(string $amount): int
    {
        if (! preg_match('/^\d+(?:\.\d{1,2})?$/', $amount)) throw new InvalidArgumentException('Invalid decimal marks.');
        [$whole, $fraction] = array_pad(explode('.',$amount,2),2,'');
        return (int)$whole * 100 + (int)str_pad($fraction,2,'0');
    }
    public static function grade(string $score, string $possible): string
    {
        $earned = self::cents($score);
        $total = self::cents($possible);
        if ($total <= 0 || $earned > $total) throw new InvalidArgumentException('Invalid score or total marks.');
        foreach ([80=>'A',70=>'B',60=>'C',50=>'D'] as $threshold=>$grade) {
            if ($earned * 100 >= $total * $threshold) return $grade;
        }
        return 'F';
    }
}
