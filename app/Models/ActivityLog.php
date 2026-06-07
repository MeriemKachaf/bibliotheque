<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'description', 'ip', 'niveau'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, string $description, string $niveau = 'info'): void
    {
        static::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'description' => $description,
            'ip'          => request()->ip(),
            'niveau'      => $niveau,
        ]);
    }
}
