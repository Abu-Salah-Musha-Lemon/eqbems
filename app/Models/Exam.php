<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Exam extends Model
{
    protected $fillable = ['institution_id','blueprint_id','subject_id','title','duration_minutes','total_marks','status','created_by','issued_by','issued_at','exam_label','class_label','paper_code','instructions','print_layout','online_enabled','online_start_at','online_end_at'];
    protected function casts(): array { return ['total_marks'=>'decimal:2', 'issued_at'=>'datetime', 'online_enabled'=>'boolean','online_start_at'=>'datetime','online_end_at'=>'datetime']; }
    public function questions(): HasMany { return $this->hasMany(ExamQuestion::class)->orderBy('position'); }
    public function institution(): BelongsTo { return $this->belongsTo(Institution::class); }
    public function onlineAttempts(): HasMany { return $this->hasMany(OnlineExamAttempt::class); }
    public function blueprint(): BelongsTo { return $this->belongsTo(Blueprint::class); }
    public function subject(): BelongsTo { return $this->belongsTo(Subject::class); }
}
