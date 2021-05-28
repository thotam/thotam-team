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

    /**
     * getIsThanhvienAttribute
     *
     * @return void
     */
    public function getIsThanhvienAttribute()
    {
        return !!count($this->thanhvien_of_nhoms);
    }

    /**
     * getIsQuanlyAttribute
     *
     * @return void
     */
    public function getIsQuanlyAttribute()
    {
        return !!count($this->quanly_of_nhoms);
    }

    /**
     * getIsMktQuanlyAttribute
     *
     * @return void
     */
    public function getIsMktQuanlyAttribute()
    {
        $mkt_teams = Nhom::where("active", true)->where("phan_loai_id", 4)->get();

        foreach ($this->quanly_of_nhoms as $nhom) {
            if ($mkt_teams->contains($nhom)) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * getIsMktThanhvienAttribute
     *
     * @return void
     */
    public function getIsMktThanhvienAttribute()
    {
        $mkt_teams = Nhom::where("active", true)->where("phan_loai_id", 4)->get();

        foreach ($this->thanhvien_of_nhoms as $nhom) {
            if ($mkt_teams->contains($nhom)) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * getIsOtcQuanlyAttribute
     *
     * @return void
     */
    public function getIsOtcQuanlyAttribute()
    {
        $otc_teams = Nhom::where("active", true)->where("kenh_kinh_doanh_id", 2)->get();

        foreach ($this->quanly_of_nhoms as $nhom) {
            if ($otc_teams->contains($nhom)) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * getIsOtcThanhvienAttribute
     *
     * @return void
     */
    public function getIsOtcThanhvienAttribute()
    {
        $otc_teams = Nhom::where("active", true)->where("kenh_kinh_doanh_id", 2)->get();

        foreach ($this->thanhvien_of_nhoms as $nhom) {
            if ($otc_teams->contains($nhom)) {
                return true;
                break;
            }
        }

        return false;
    }

}
