<?php

namespace Thotam\ThotamTeam\Traits;

use Thotam\ThotamTeam\Models\Nhom;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasNhomTrait {

    /**
     * The quanly_of_nhoms that belong to the HasNhomTrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function quanly_of_nhoms(): BelongsToMany
    {
        return $this->belongsToMany(Nhom::class, 'nhom_quanlys', 'hr_key', 'nhom_id');
    }

    /**
     * The thanhvien_of_nhoms that belong to the HasNhomTrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function thanhvien_of_nhoms(): BelongsToMany
    {
        return $this->belongsToMany(Nhom::class, 'nhom_thanhviens', 'hr_key', 'nhom_id');
    }

}
