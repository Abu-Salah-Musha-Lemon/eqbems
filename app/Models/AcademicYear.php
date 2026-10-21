<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AcademicYear extends Model
{
    protected $fillable = ['institution_id', 'label', 'starts_on', 'ends_on'];
    protected function casts(): array { return ['starts_on' => 'date', 'ends_on' => 'date']; }
}
