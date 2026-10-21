<?php
use App\Services\GradeService;

test('grade thresholds use exact decimal cent arithmetic', function () {
    expect(GradeService::grade('80.00','100.00'))->toBe('A')
        ->and(GradeService::grade('79.99','100.00'))->toBe('B')
        ->and(GradeService::grade('70.00','100.00'))->toBe('B')
        ->and(GradeService::grade('60.00','100.00'))->toBe('C')
        ->and(GradeService::grade('50.00','100.00'))->toBe('D')
        ->and(GradeService::grade('49.99','100.00'))->toBe('F')
        ->and(GradeService::grade('0','1'))->toBe('F');
});
