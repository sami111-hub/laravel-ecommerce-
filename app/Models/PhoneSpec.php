<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhoneSpec extends Model
{
    protected $fillable = [
        'phone_id',
        'group',
        'key',
        'value',
        'order'
    ];

    protected $casts = [
        'order' => 'integer'
    ];

    // Relations
    public function phone(): BelongsTo
    {
        return $this->belongsTo(Phone::class);
    }

    // Helpers
    public static function getGroups(): array
    {
        return [
            'الشاشة' => 'display',
            'المعالج' => 'processor',
            'الذاكرة' => 'memory',
            'الكاميرات' => 'cameras',
            'البطارية' => 'battery',
            'الاتصال' => 'connectivity',
            'النظام' => 'system',
            'الأبعاد' => 'dimensions',
            'مميزات أخرى' => 'features'
        ];
    }
}
