<?php

namespace Thotam\ThotamTeam\Models;

use Thotam\ThotamHr\Models\HR;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Thotam\ThotamTeam\Models\PhanLoaiNhom;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thotam\ThotamPlus\Traits\ThoTamPlusTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Nhom extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Userstamps;
    use ThoTamPlusTrait;

    /**
     * Disable Laravel's mass assignment protection
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nhoms';

    /**
     * Get the phan_loai that owns the Nhom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function phan_loai(): BelongsTo
    {
        return $this->belongsTo(PhanLoaiNhom::class, 'phan_loai_id', 'id');
    }

    /**
     * The nhom_has_quanlys that belong to the Nhom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function nhom_has_quanlys(): BelongsToMany
    {
        return $this->belongsToMany(HR::class, 'nhom_quanlys', 'nhom_id', 'hr_key');
    }

    /**
     * The nhom_has_thanhviens that belong to the Nhom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function nhom_has_thanhviens(): BelongsToMany
    {
        return $this->belongsToMany(HR::class, 'nhom_thanhviens', 'nhom_id', 'hr_key');
    }

    /**
     * The truc_thuoc_nhoms that belong to the Nhom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function truc_thuoc_nhoms(): BelongsToMany
    {
        return $this->belongsToMany(Nhom::class, 'nhom_tructhuocs', 'nhom_truc_thuoc_id', 'nhom_quan_ly_id');
    }

    /**
     * The nhom_tructhuocs that belong to the Nhom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function nhom_tructhuocs(): BelongsToMany
    {
        return $this->belongsToMany(Nhom::class, 'nhom_tructhuocs', 'nhom_quan_ly_id', 'nhom_truc_thuoc_id');
    }

    /**
     * getIsNotKDAttribute
     *
     * @return void
     */
    public function getIsNotKDAttribute()
    {
        return $this->phan_loai_id != 3;
    }
}
