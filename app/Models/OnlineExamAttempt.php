<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class OnlineExamAttempt extends Model
{
    protected $fillable = ['exam_id','student_id','status','started_at','submitted_at','graded_at','awarded_marks'];
    protected function casts(): array { return ['started_at'=>'datetime','submitted_at'=>'datetime','graded_at'=>'datetime','awarded_marks'=>'decimal:2']; }
    public function exam(): BelongsTo { return $this->belongsTo(Exam::class); }
    public function student(): BelongsTo { return $this->belongsTo(User::class,'student_id'); }
    public function answers(): HasMany { return $this->hasMany(OnlineExamAnswer::class); }
}
