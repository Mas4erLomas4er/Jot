<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * @method static first()
 */
class Contact extends Model
{
    use Searchable, HasFactory;

    protected $guarded = [];

    protected $dates = ['birthday'];

    public function path ()
    {
        return '/contacts/' .  $this->id;
    }

    public function scopeBirthdays ($query)
    {
        $currentMonth = '"%-' . now()->format('m') . "-%\"";
        return $query->whereRaw("birthday like $currentMonth");
    }

    public function setBirthdayAttribute ($birthday)
    {
        $this->attributes['birthday'] = Carbon::parse($birthday);
    }

    public function setNameAttribute ($name)
    {
        $this->attributes['name'] = ucwords($name);
    }

    public function user () : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
