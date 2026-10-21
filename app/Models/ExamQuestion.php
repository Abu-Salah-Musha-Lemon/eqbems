<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ExamQuestion extends Model
{
    protected $fillable = ['exam_id','question_version_id','position','marks','content_snapshot','answer_snapshot'];
    protected function casts(): array { return ['content_snapshot'=>'array', 'marks'=>'decimal:2']; }
}
