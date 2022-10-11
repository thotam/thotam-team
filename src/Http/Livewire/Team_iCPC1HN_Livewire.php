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
use Thotam\ThotamAuth\Models\iCPC1HN_Group;
use Thotam\ThotamPlus\Models\KenhKinhDoanh;

class Team_iCPC1HN_Livewire extends Component
{
    /**
     * Các biến sử dụng trong Component
     *
     * @var mixed
     */
    public $nhom, $nhom_id;
    public $modal_title, $toastr_message;
    public $hr;
    public $nhom_arrays = [];

    /**
     * @var bool
     */
    public $syncStatus = false;


    /**
     * Các biển sự kiện
     *
     * @var array
     */
    protected $listeners = ['dynamic_update_method', 'sync_team'];

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
    protected function rules()
    {
        return [
            'nhom_id' => 'required|exists:nhoms,id',
        ];
    }

    /**
     * Custom attributes
     *
     * @var array
     */
    protected $validationAttributes = [
        'nhom_id' => 'nhóm',
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
        return view('thotam-team::livewire.team-icpc1hn.team-livewire');
    }


    /**
     * sync_team
     *
     * @return void
     */
    public function sync_team(iCPC1HN_Group $nhom)
    {
        $this->nhom = $nhom;

        if ($this->hr->cannot("edit-team")) {
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            $this->cancel();
            return null;
        }

        $this->nhom_arrays = Nhom::orderBy("order")->select("id", "full_name")->get()->toArray();

        $this->nhom_id = $this->nhom->nhom_id;

        $this->syncStatus = true;
        $this->modal_title = "Đồng bộ nhóm";
        $this->toastr_message = "Đồng bộ nhóm thành công";

        $this->dispatchBrowserEvent('unblockUI');
        $this->dispatchBrowserEvent('dynamic_update');
        $this->dispatchBrowserEvent('show_modal', "#sync_modal");
    }


    /**
     * sync_action
     *
     * @return void
     */
    public function sync_action()
    {
        if (!$this->hr->canAny(["edit-team"])) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            return null;
        }

        //Xác thực dữ liệu
        $this->dispatchBrowserEvent('unblockUI');
        $this->validate([
            'nhom_id' => 'required|exists:nhoms,id',
        ]);
        $this->dispatchBrowserEvent('blockUI');

        try {
            $this->nhom->update([
                'nhom_id' => $this->nhom_id
            ]);
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
