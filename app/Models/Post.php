<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
class Post extends Model
{
    use HasFactory;
    use HasTranslations;
    protected $fillable = [
        'title', 'body', 'user_id',
    ];

    public $translatable = ['title','body'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
