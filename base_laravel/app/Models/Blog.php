<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Policies\PostPolicy;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'image',
        'status',
        'category_id',
        'views'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tag', 'blog_id', 'tag_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'blog_user', 'blog_id', 'user_id');
    }

    public function taggedUsers()
    {
        return $this->belongsToMany(User::class, 'blog_user');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}