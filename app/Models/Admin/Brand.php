<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed description
 * @property mixed name
 * @property mixed status
 */
class Brand extends Model
{
    use SoftDeletes;

    protected $guarded = ['deleted_at', 'created_at', 'updated_at'];

    public function getStatusTextAttribute()
    {
        if ($this->status == false) {
            return __('Inactive');
        }
        return __('Active');
    }

    public function setThumbnailAttribute($value)
    {
        $this->attributes['thumbnail'] = 'uploads/images/product-brands/' . $value;
    }

    public function getDefaultThumbnailAttribute()
    {
        if ($this->thumbnail == null){
            return 'assets/150.jpg';
        }

        return $this->thumbnail;
    }
}
