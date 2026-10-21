<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Result extends Model
{
    protected $fillable = ['exam_id','student_id','score','grade','status','marked_by','published_by','published_at'];
    protected function casts(): array { return ['score'=>'decimal:2', 'published_at'=>'datetime']; }
    public function exam(): BelongsTo { return $this->belongsTo(Exam::class); }
    public function student(): BelongsTo { return $this->belongsTo(User::class, 'student_id'); }
}
