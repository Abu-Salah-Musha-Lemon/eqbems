<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Question extends Model
{
    use HasFactory;

    protected $fillable = ['institution_id', 'subject_id', 'topic_id', 'author_id'];
    public function subject(): BelongsTo { return $this->belongsTo(Subject::class); }
    public function topic(): BelongsTo { return $this->belongsTo(Topic::class); }
    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }
    public function versions(): HasMany { return $this->hasMany(QuestionVersion::class); }
    public function latestVersion() { return $this->hasOne(QuestionVersion::class)->latestOfMany('version_number'); }
}
