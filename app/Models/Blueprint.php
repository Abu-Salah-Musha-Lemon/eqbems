<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Blueprint extends Model
{
    protected $fillable = ['institution_id','subject_id','title','question_count','duration_minutes','type','difficulty','format','created_by'];
    public function subject(): BelongsTo { return $this->belongsTo(Subject::class); }

    /** Explicitly chosen questions; their latest approved versions are used during generation. */
    public function selectedQuestions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'blueprint_questions')->withTimestamps();
    }
}
