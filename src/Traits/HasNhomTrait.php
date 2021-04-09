<?php

namespace Thotam\ThotamPlus\Traits;

use Thotam\ThotamTeam\Models\Nhom;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasNhomTrait {

    /**
     * The quanly_of_nhom that belong to the HasNhomTrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function quanly_of_nhom(): BelongsToMany
    {
        return $this->belongsToMany(Nhom::class, 'nhom_quanly_table', 'hr_key', 'nhom_id');
    }

    /**
     * The thanhvien_of_nhom that belong to the HasNhomTrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function thanhvien_of_nhom(): BelongsToMany
    {
        return $this->belongsToMany(Nhom::class, 'nhom_thanhvien_table', 'hr_key', 'nhom_id');
    }

}
