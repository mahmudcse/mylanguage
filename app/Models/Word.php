<?php

namespace App\Models;

use App\Models\User;
use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Word extends Model
{
    use HasFactory;

    protected $table = 'words';

    protected $fillable = [
        'user_id',
        'article_id',
        'word',
        'definition',
        'no_of_read',
        'learned',
        'deleted'
    ];

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function articles(){
        return $this->belongsTo(Article::class, 'user_id', 'id');
    }

}
