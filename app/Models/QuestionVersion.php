<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class QuestionVersion extends Model
{
    use HasFactory;

    protected $fillable = ['question_id','version_number','type','difficulty','body','options','answer_text','explanation','marks','status','created_by','reviewed_by','reviewed_at','review_note','format','stimulus','creative_parts'];
    protected function casts(): array { return ['creative_parts' => 'array', 'options' => 'array', 'marks' => 'decimal:2', 'reviewed_at' => 'datetime']; }
    public function question(): BelongsTo { return $this->belongsTo(Question::class); }
}
