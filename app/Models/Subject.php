<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['institution_id', 'code', 'name', 'description'];
    public function institution(): BelongsTo { return $this->belongsTo(Institution::class)->withTrashed(); }
    public function questions(): HasMany { return $this->hasMany(Question::class); }
    public function topics(): HasMany { return $this->hasMany(Topic::class); }
}
