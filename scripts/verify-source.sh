#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
echo 'Checking PHP syntax...'
count=0
while IFS= read -r -d '' file; do
  php -l "$file" >/dev/null
  count=$((count + 1))
done < <(find app bootstrap config database routes tests -type f -name '*.php' -size +0c -print0)
echo "PASS: ${count} PHP source/test files have valid syntax."
echo 'Checking local application JavaScript syntax...'
count=0
while IFS= read -r -d '' file; do
  node --check "$file"
  count=$((count + 1))
done < <(find resources/js -type f -name '*.js' -print0)
echo "PASS: ${count} first-party JavaScript files have valid syntax."
echo 'Checking GradeService without Laravel dependencies...'
php -r '
require "app/Services/GradeService.php";
use App\Services\GradeService;
$cases = [["0", "100", "F"], ["49.99", "100", "F"], ["50", "100", "D"], ["60", "100", "C"], ["70", "100", "B"], ["80", "100", "A"], ["100", "100", "A"], ["7.99", "10", "B"]];
foreach ($cases as [$score, $total, $expected]) {
    if (GradeService::grade($score, $total) !== $expected) {
        fwrite(STDERR, "FAIL: {$score}/{$total}\n"); exit(1);
    }
}
echo "PASS: ".count($cases)." grade threshold tests.\n";
'
echo 'Source checks complete. For full integration tests run composer install, php artisan test, npm ci and npm run build.'
