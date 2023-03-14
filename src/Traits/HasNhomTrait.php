<?php

namespace Thotam\ThotamTeam\Traits;

use Thotam\ThotamTeam\Models\Nhom;
use Illuminate\Database\Eloquent\Builder;
use Thotam\ThotamPlus\Models\NhomSanPham;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasNhomTrait
{

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
     * The nhom_san_phams that belong to the HasNhomTrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function nhom_san_phams(): BelongsToMany
    {
        return $this->belongsToMany(NhomSanPham::class, 'hr_nhom_san_phams', 'hr_key', 'nhom_san_pham_id');
    }


    /**
     * getMyAllTeamAttribute
     *
     * @return void
     */
    public function getMyAllTeamAttribute()
    {
        $nhom_arrays = $this->quanly_of_nhoms;
        $nhom_arrays = $nhom_arrays->merge($this->thanhvien_of_nhoms);
        return array_filter($nhom_arrays->pluck("full_name", "id")->toArray());
    }

    /**
     * getMyAllOtcTeamAttribute
     *
     * @return void
     */
    public function getMyAllOtcTeamAttribute()
    {
        $nhom_arrays = $this->quanly_of_nhoms;
        $nhom_arrays = $nhom_arrays->merge($this->thanhvien_of_nhoms);
        return array_filter($nhom_arrays->where('kenh_kinh_doanh_id', 2)->pluck("full_name", "id")->toArray());
    }

    /**
     * getMyAllGtTeamAttribute
     *
     * @return void
     */
    public function getMyAllGtTeamAttribute()
    {
        $nhom_arrays = $this->quanly_of_nhoms;
        $nhom_arrays = $nhom_arrays->merge($this->thanhvien_of_nhoms);
        return array_filter($nhom_arrays->where('kenh_kinh_doanh_id', 5)->pluck("full_name", "id")->toArray());
    }

    /**
     * getMyAllOtcGtTeamAttribute
     *
     * @return void
     */
    public function getMyAllOtcGtTeamAttribute()
    {
        $nhom_arrays = $this->quanly_of_nhoms;
        $nhom_arrays = $nhom_arrays->merge($this->thanhvien_of_nhoms);
        return array_filter($nhom_arrays->whereIn('kenh_kinh_doanh_id', [2, 5])->pluck("full_name", "id")->toArray());
    }

    /**
     * getMyAllEtcsTeamAttribute
     *
     * @return void
     */
    public function getMyAllEtcsTeamAttribute()
    {
        $nhom_arrays = $this->quanly_of_nhoms;
        $nhom_arrays = $nhom_arrays->merge($this->thanhvien_of_nhoms);
        return array_filter($nhom_arrays->whereIn('kenh_kinh_doanh_id', [1, 3, 4])->pluck("full_name", "id")->toArray());
    }

    /**
     * getQuanlyOfMultiLevelNhomsAttribute
     *
     * @return void
     */
    public function getQuanlyOfMultiLevelNhomsAttribute()
    {
        $nhoms = $this->quanly_of_nhoms->pluck('id')->toArray();
        $loop = 0;
        $sub_nhom[0] = $nhoms;

        do {
            $loop++;
            $sub_nhom[$loop] = Nhom::whereHas('truc_thuoc_nhoms', function (Builder $query) use ($sub_nhom, $loop) {
                $query->whereIn('nhom_tructhuocs.nhom_quan_ly_id', $sub_nhom[$loop - 1]);
            })->pluck('id')->toArray();
            $nhoms = array_merge($nhoms, $sub_nhom[$loop]);
        } while ($loop <= 20 && !!count($sub_nhom[$loop]));

        return Nhom::whereIn('id', $nhoms)->orderBy('order', 'desc')->orderBy('id')->get();
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
     * getIsKdQuanlyAttribute
     *
     * @return void
     */
    public function getIsKdQuanlyAttribute()
    {
        foreach ($this->quanly_of_nhoms as $nhom) {
            if ($nhom->phan_loai_id == 3) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * getIsKdThanhvienAttribute
     *
     * @return void
     */
    public function getIsKdThanhvienAttribute()
    {
        foreach ($this->thanhvien_of_nhoms as $nhom) {
            if ($nhom->phan_loai_id == 3) {
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

        foreach ($this->quanly_of_multi_level_nhoms as $nhom) {
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

    /**
     * getIsEtcsQuanlyAttribute
     *
     * @return void
     */
    public function getIsEtcsQuanlyAttribute()
    {
        $etcs_teams = Nhom::where("active", true)->whereIn('kenh_kinh_doanh_id', [1, 3, 4])->get();

        foreach ($this->quanly_of_multi_level_nhoms as $nhom) {
            if ($etcs_teams->contains($nhom)) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * getIsEtcsThanhvienAttribute
     *
     * @return void
     */
    public function getIsEtcsThanhvienAttribute()
    {
        $etcs_teams = Nhom::where("active", true)->whereIn('kenh_kinh_doanh_id', [1, 3, 4])->get();

        foreach ($this->thanhvien_of_nhoms as $nhom) {
            if ($etcs_teams->contains($nhom)) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * getIsTtsQuanlyAttribute
     *
     * @return void
     */
    public function getIsTtsQuanlyAttribute()
    {
        foreach ($this->quanly_of_nhoms as $nhom) {
            if ($nhom->phan_loai_id == 5) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * getIsTtsThanhvienAttribute
     *
     * @return void
     */
    public function getIsTtsThanhvienAttribute()
    {
        foreach ($this->thanhvien_of_nhoms as $nhom) {
            if ($nhom->phan_loai_id == 5) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * getIsGtQuanlyAttribute
     *
     * @return void
     */
    public function getIsGtQuanlyAttribute()
    {
        $gt_teams = Nhom::where("active", true)->where("kenh_kinh_doanh_id", 5)->get();

        foreach ($this->quanly_of_multi_level_nhoms as $nhom) {
            if ($gt_teams->contains($nhom)) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * getIsGtThanhvienAttribute
     *
     * @return void
     */
    public function getIsGtThanhvienAttribute()
    {
        $gt_teams = Nhom::where("active", true)->where("kenh_kinh_doanh_id", 5)->get();

        foreach ($this->thanhvien_of_nhoms as $nhom) {
            if ($gt_teams->contains($nhom)) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * getIsOtcGtQuanlyAttribute
     *
     * @return void
     */
    public function getIsOtcGtQuanlyAttribute()
    {
        $Teams = Nhom::where("active", true)->whereIn("kenh_kinh_doanh_id", [2, 5])->get();

        foreach ($this->quanly_of_multi_level_nhoms as $nhom) {
            if ($Teams->contains($nhom)) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * getIsOtcGtThanhvienAttribute
     *
     * @return void
     */
    public function getIsOtcGtThanhvienAttribute()
    {
        $Teams = Nhom::where("active", true)->whereIn("kenh_kinh_doanh_id", [2, 5])->get();

        foreach ($this->thanhvien_of_nhoms as $nhom) {
            if ($Teams->contains($nhom)) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * getIsXsxQuanlyAttribute
     *
     * @return void
     */
    public function getIsXsxQuanlyAttribute()
    {
        $xsx_teams = Nhom::where("active", true)->where("phan_loai_id", 1)->get();

        foreach ($this->quanly_of_nhoms as $nhom) {
            if ($xsx_teams->contains($nhom)) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * getIsXsxThanhvienAttribute
     *
     * @return void
     */
    public function getIsXsxThanhvienAttribute()
    {
        $xsx_teams = Nhom::where("active", true)->where("phan_loai_id", 1)->get();

        foreach ($this->thanhvien_of_nhoms as $nhom) {
            if ($xsx_teams->contains($nhom)) {
                return true;
                break;
            }
        }

        return false;
    }
}
