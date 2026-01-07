<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'model_type',
        'model_id',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIconAttribute()
    {
        $icons = [
            'created' => 'fa-plus-circle',
            'updated' => 'fa-edit',
            'deleted' => 'fa-trash',
            'status_changed' => 'fa-exchange-alt',
            'featured' => 'fa-star',
            'login' => 'fa-sign-in-alt',
        ];

        return $icons[$this->action] ?? 'fa-history';
    }

    public function getColorAttribute()
    {
        $colors = [
            'created' => '#10B981',
            'updated' => '#4A90D9',
            'deleted' => '#EF4444',
            'status_changed' => '#F59E0B',
            'featured' => '#FFD700',
            'login' => '#A855F7',
        ];

        return $colors[$this->action] ?? '#6B7280';
    }

    public static function log($action, $description, $model = null, $properties = [])
    {
        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'properties' => $properties,
        ]);
    }
}
