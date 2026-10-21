<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class OnlineExamAnswer extends Model
{
    protected $fillable = ['online_exam_attempt_id','exam_question_id','response','awarded_marks','awarded_parts'];
    protected function casts(): array { return ['response'=>'array','awarded_parts'=>'array','awarded_marks'=>'decimal:2']; }
    public function question(): BelongsTo { return $this->belongsTo(ExamQuestion::class, 'exam_question_id'); }
    public function attempt(): BelongsTo { return $this->belongsTo(OnlineExamAttempt::class, 'online_exam_attempt_id'); }
}
