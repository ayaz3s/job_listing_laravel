<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'user_id','company', 'location', 'website', 'email', 'description', 'tags', 'logo'];

    public function scopeFilter($query, array $filters){

        // null coalescing operator (??)
        // The Null coalescing operator returns its first operand if it exists and is not NULL;
        // otherwise it returns its second operand.
        if($filters['tag'] ?? false){
            $query->where('tags', 'like', '%'.request('tag').'%');
        }

        if($filters['search'] ?? false){
            $query->where('title', 'like', '%'.request('search').'%')
                ->orWhere('description', 'like', '%'.request('search').'%')
                ->orWhere('tags', 'like', '%'.request('search').'%');
        }
    }

    // relationship to user
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
