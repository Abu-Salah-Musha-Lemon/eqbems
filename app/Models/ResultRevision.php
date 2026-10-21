<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ResultRevision extends Model
{
    protected $fillable = ['result_id','previous_score','new_score','previous_grade','new_grade','reason','changed_by'];
}
