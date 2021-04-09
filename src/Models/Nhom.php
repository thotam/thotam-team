<?php

namespace Thotam\ThotamTeam\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Thotam\ThotamTeam\Models\PhanLoaiNhom;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thotam\ThotamPlus\Traits\ThoTamPlusTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
