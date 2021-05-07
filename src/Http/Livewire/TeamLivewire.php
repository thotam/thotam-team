<?php

namespace Thotam\ThotamTeam\Http\Livewire;

use Auth;
use Livewire\Component;
use Thotam\ThotamHr\Models\HR;
use Thotam\ThotamTeam\Models\Nhom;
use Thotam\ThotamPlus\Models\ChiNhanh;
use Thotam\ThotamPlus\Models\NhomSanPham;
use Thotam\ThotamTeam\Jobs\Nhom_Sync_Job;
use Thotam\ThotamTeam\Models\PhanLoaiNhom;
use Thotam\ThotamPlus\Models\KenhKinhDoanh;

class TeamLivewire extends Component
{
    /**
    * Các biến sử dụng trong Component
    *
    * @var mixed
    */
    public $name, $full_name, $chinhanh_id, $kenh_kinh_doanh_id, $nhom_san_pham_id, $phan_loai_id, $order, $active, $truc_thuoc_nhoms, $quanlys, $thanhviens;
    public $modal_title, $toastr_message;
    public $team, $team_id;
    public $hr;
    public $chinhanh_arrays, $phan_loai_arrays, $kenh_kinh_doanh_arrays, $nhom_san_pham_arrays = [], $nhom_arrays, $quanly_arrays;

    /**
     * @var bool
     */
    public $addStatus = false;
    public $viewStatus = false;
    public $editStatus = false;
    public $deleteStatus = false;
    public $setTeamMemberStatus = false;


    /**
     * Các biển sự kiện
     *
     * @var array
     */
    protected $listeners = ['dynamic_update_method', 'add_team', 'edit_team', 'set_member_team', ];

    /**
     * dynamic_update_method
     *
     * @return void
     */
    public function dynamic_update_method()
    {
        $this->dispatchBrowserEvent('dynamic_update');
    }

    /**
     * On updated action
     *
     * @param  mixed $propertyName
     * @return void
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Validation rules
     *
     * @var array
     */
    protected function rules() {
        return [
            'full_name' => 'required|string',
            'name' => 'required|string',
            'chinhanh_id' => 'required|exists:chinhanhs,id',
            'kenh_kinh_doanh_id' => $this->phan_loai_id == 3 ? 'required' :'nullable' . '|exists:kenh_kinh_doanhs,id',
            'nhom_san_pham_id' => 'nullable|exists:nhom_san_phams,id',
            'phan_loai_id' => 'required|exists:phan_loai_nhoms,id',
            'order' => 'nullable|numeric',
            'active' => 'nullable|boolean',
            'truc_thuoc_nhoms' => 'nullable|array',
            'truc_thuoc_nhoms.*' => 'nullable|exists:nhoms,id',
            'quanlys' => 'nullable|array',
            'quanlys.*' => 'nullable|exists:hrs,key',
        ];
    }

    /**
     * Custom attributes
     *
     * @var array
     */
    protected $validationAttributes = [
        'full_name' => 'tên nhóm đầy đủ',
        'name' => 'tên nhóm',
        'chinhanh_id' => 'chi nhánh',
        'kenh_kinh_doanh_id' => 'kênh kinh doanh',
        'nhom_san_pham_id' => 'nhóm sản phẩm',
        'phan_loai_id' => 'phân loại nhóm',
        'order' => 'thứ tự',
        'active' => 'kích hoạt',
        'truc_thuoc_nhoms' => 'trực thuộc nhóm',
    ];

    /**
     * cancel
     *
     * @return void
     */
    public function cancel()
    {
        $this->dispatchBrowserEvent('unblockUI');
        $this->dispatchBrowserEvent('hide_modals');
        $this->reset();
        $this->addStatus = false;
        $this->editStatus = false;
        $this->viewStatus = false;
        $this->deleteStatus = false;
        $this->setTeamMemberStatus = false;
        $this->resetValidation();
        $this->mount();
    }

    /**
     * mount data
     *
     * @return void
     */
    public function mount()
    {
        $this->hr = Auth::user()->hr;
    }

    /**
     * render
     *
     * @return void
     */
    public function render()
    {
        return view('thotam-team::livewire.team.team-livewire');
    }

    public function create_full_name()
    {
        $full_names = [];
        if (!!$this->chinhanh_id) {
            $full_names[] = ChiNhanh::find($this->chinhanh_id)->tag;
        }

        if ($this->phan_loai_id == 3) {
            if (!!$this->kenh_kinh_doanh_id) {
                $full_names[] = KenhKinhDoanh::find($this->kenh_kinh_doanh_id)->tag;
            }
            if (!!$this->nhom_san_pham_id) {
                $full_names[] = NhomSanPham::find($this->nhom_san_pham_id)->tag;
            }
        }
        if (!!$this->name) {
            $full_names[] = trim($this->name);
        }

        $this->full_name = implode("-",array_unique($full_names));

    }

    /**
     * updatedPhanLoaiId
     *
     * @return void
     */
    public function updatedPhanLoaiId()
    {
        if ($this->phan_loai_id != 3) {
            $this->kenh_kinh_doanh_id = null;
            $this->nhom_san_pham_id = null;
        }

        $this->dispatchBrowserEvent('dynamic_update');
        $this->create_full_name();
    }

    /**
     * updatedKenhKinhDoanhId
     *
     * @return void
     */
    public function updatedKenhKinhDoanhId()
    {
        if (!!$this->kenh_kinh_doanh_id) {
            $this->nhom_san_pham_arrays = KenhKinhDoanh::find($this->kenh_kinh_doanh_id)->nhom_san_phams()->orderBy("order")->select("id", "name")->get()->toArray();
        } else {
            $this->nhom_san_pham_arrays = null;
        }

        $this->nhom_san_pham_id = null;

        $this->dispatchBrowserEvent('dynamic_update');
        $this->create_full_name();
    }

    /**
     * updatedName
     *
     * @return void
     */
    public function updatedName()
    {
        $this->create_full_name();
    }

    /**
     * updatedNhomSanPhamId
     *
     * @return void
     */
    public function updatedNhomSanPhamId()
    {
        $this->create_full_name();
    }

    /**
     * updatedChiNhanhId
     *
     * @return void
     */
    public function updatedChiNhanhId()
    {
        $this->create_full_name();
    }

    /**
     * add_team
     *
     * @return void
     */
    public function add_team()
    {
        if ($this->hr->cannot("add-team")) {
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            $this->cancel();
            return null;
        }

        $this->chinhanh_arrays = ChiNhanh::orderBy("order")->select("id", "name")->get()->toArray();
        $this->phan_loai_arrays = PhanLoaiNhom::orderBy("order")->select("id", "name")->get()->toArray();
        $this->kenh_kinh_doanh_arrays = KenhKinhDoanh::orderBy("order")->select("id", "name")->get()->toArray();
        $this->nhom_arrays = Nhom::orderBy("order")->select("id", "full_name")->get()->toArray();

        $this->addStatus = true;
        $this->modal_title = "Thêm nhóm mới";
        $this->toastr_message = "Thêm nhóm mới thành công";

        $this->active = true;

        $this->dispatchBrowserEvent('unblockUI');
        $this->dispatchBrowserEvent('dynamic_update');
        $this->dispatchBrowserEvent('show_modal', "#add_edit_modal");
    }

    /**
     * edit_team
     *
     * @param  mixed $team
     * @return void
     */
    public function edit_team(Nhom $team)
    {
        if ($this->hr->cannot("add-team")) {
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            $this->cancel();
            return null;
        }

        $this->team = $team;
        $this->team_id = $this->team->id;
        $this->full_name = $this->team->full_name;
        $this->name = $this->team->name;
        $this->chinhanh_id = $this->team->chinhanh_id;
        $this->kenh_kinh_doanh_id = $this->team->kenh_kinh_doanh_id;
        $this->nhom_san_pham_id = $this->team->nhom_san_pham_id;
        $this->phan_loai_id = $this->team->phan_loai_id;
        $this->order = $this->team->order;
        $this->active = !!$this->team->active;
        $this->truc_thuoc_nhoms = $this->team->truc_thuoc_nhoms->pluck("id")->toArray();
        $this->quanlys = $this->team->nhom_has_quanlys->pluck("key")->toArray();

        if (!!$this->kenh_kinh_doanh_id) {
            $this->nhom_san_pham_arrays = KenhKinhDoanh::find($this->kenh_kinh_doanh_id)->nhom_san_phams()->orderBy("order")->select("id", "name")->get()->toArray();
        } else {
            $this->nhom_san_pham_arrays = null;
        }

        $this->chinhanh_arrays = ChiNhanh::orderBy("order")->select("id", "name")->get()->toArray();
        $this->phan_loai_arrays = PhanLoaiNhom::orderBy("order")->select("id", "name")->get()->toArray();
        $this->kenh_kinh_doanh_arrays = KenhKinhDoanh::orderBy("order")->select("id", "name")->get()->toArray();
        $this->nhom_arrays = Nhom::orderBy("order")->select("id", "full_name")->get()->toArray();

        $this->editStatus = true;
        $this->modal_title = "Chỉnh sửa nhóm";
        $this->toastr_message = "Chỉnh sửa nhóm thành công";

        $this->dispatchBrowserEvent('unblockUI');
        $this->dispatchBrowserEvent('dynamic_update');
        $this->dispatchBrowserEvent('show_modal', "#add_edit_modal");


    }

    /**
     * save_team
     *
     * @return void
     */
    public function save_team()
    {
        if (!$this->hr->canAny(["add-team", "edit-team"])) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            return null;
        }

        $this->create_full_name();

        //Xác thực dữ liệu
        $this->dispatchBrowserEvent('unblockUI');
        $validate = $this->validate([
            'full_name' => 'required|string',
            'name' => 'required|string',
            'chinhanh_id' => 'required|exists:chinhanhs,id',
            'kenh_kinh_doanh_id' => $this->phan_loai_id == 3 ? 'required' :'nullable' . '|exists:kenh_kinh_doanhs,id',
            'nhom_san_pham_id' => 'nullable|exists:nhom_san_phams,id',
            'phan_loai_id' => 'required|exists:phan_loai_nhoms,id',
            'order' => 'nullable|numeric',
            'active' => 'nullable|boolean',
            'truc_thuoc_nhoms' => 'nullable|array',
            'truc_thuoc_nhoms.*' => 'nullable|exists:nhoms,id',
            'quanlys' => 'nullable|array',
            'quanlys.*' => 'nullable|exists:hrs,key',
        ]);
        $this->dispatchBrowserEvent('blockUI');

        try {
            $this->team = Nhom::updateOrCreate([
                'id' => $this->team_id,
            ],[
                "full_name" => trim($this->full_name),
                "name" => trim($this->name),
                "chinhanh_id" => $this->chinhanh_id,
                "kenh_kinh_doanh_id" => $this->kenh_kinh_doanh_id,
                "nhom_san_pham_id" => $this->nhom_san_pham_id,
                "phan_loai_id" => $this->phan_loai_id,
                "order" => $this->order,
                "active" => $this->active,
            ]);

            $this->team->nhom_has_quanlys()->sync($this->quanlys);
            $this->team->truc_thuoc_nhoms()->sync($this->truc_thuoc_nhoms);

            Nhom_Sync_Job::dispatch($this->team);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => implode(" - ", $e->errorInfo)]);
            return null;
        } catch (\Exception $e2) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => $e2->getMessage()]);
            return null;
        }

        //Đẩy thông tin về trình duyệt
        $this->dispatchBrowserEvent('dt_draw');
        $toastr_message = $this->toastr_message;
        $this->cancel();
        $this->dispatchBrowserEvent('toastr', ['type' => 'success', 'title' => "Thành công", 'message' => $toastr_message]);
    }

    /**
     * set_member_team
     *
     * @param  mixed $team
     * @return void
     */
    public function set_member_team(Nhom $team)
    {
        if ($this->hr->cannot("set-member-team")) {
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            $this->cancel();
            return null;
        }

        $this->team = $team;
        $this->full_name = $this->team->full_name;
        $this->thanhviens = $this->team->nhom_has_thanhviens->pluck("key")->toArray();

        $this->setTeamMemberStatus = true;
        $this->modal_title = "Cập nhật nhân sự nhóm";
        $this->toastr_message = "Cập nhật nhân sự nhóm thành công";

        $this->dispatchBrowserEvent('unblockUI');
        $this->dispatchBrowserEvent('dynamic_update');
        $this->dispatchBrowserEvent('show_modal', "#set_team_member_modal");
    }

    public function set_member_team_save()
    {
        if ($this->hr->cannot("set-member-team")) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            return null;
        }

        //Xác thực dữ liệu
        $this->dispatchBrowserEvent('unblockUI');
        $validate = $this->validate([
            'thanhviens' => 'nullable|array',
            'thanhviens.*' => 'nullable|exists:hrs,key',
        ]);
        $this->dispatchBrowserEvent('blockUI');

        try {
            $this->team->nhom_has_thanhviens()->sync($this->thanhviens);

            Nhom_Sync_Job::dispatch($this->team);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => implode(" - ", $e->errorInfo)]);
            return null;
        } catch (\Exception $e2) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => $e2->getMessage()]);
            return null;
        }

        //Đẩy thông tin về trình duyệt
        $this->dispatchBrowserEvent('dt_draw');
        $toastr_message = $this->toastr_message;
        $this->cancel();
        $this->dispatchBrowserEvent('toastr', ['type' => 'success', 'title' => "Thành công", 'message' => $toastr_message]);
    }
}
