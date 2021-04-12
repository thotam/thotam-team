<?php

namespace Thotam\ThotamTeam\DataTables;

use Auth;
use Thotam\ThotamTeam\Models\Nhom;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class NhomDataTable extends DataTable
{
    public $hr;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->hr = Auth::user()->hr;
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($query) {
                $Action_Icon="<div class='action-div icon-4 px-0 mx-1 d-flex justify-content-around text-center'>";

                if ($this->hr->can("edit-team")) {
                    $Action_Icon.="<div class='col action-icon-w-50 action-icon' thotam-livewire-method='edit_team' thotam-model-id='$query->id'><i class='text-indigo fas fa-edit'></i></div>";
                }

                if ($this->hr->can("set-member-team")) {
                    $Action_Icon.="<div class='col action-icon-w-50 action-icon' thotam-livewire-method='set_member_team' thotam-model-id='$query->id'><i class='text-success fas fa-users'></i></div>";
                }

                $Action_Icon.="</div>";

                return $Action_Icon;
            })
            ->editColumn('truc_thuoc_nhoms.full_name', function ($query) {
                return implode(", ", $query->truc_thuoc_nhoms->pluck("full_name")->toArray());
            })
            ->editColumn('nhom_has_quanlys.hoten', function ($query) {
                return implode(", ", $query->nhom_has_quanlys->pluck("hoten")->toArray());
            })
            ->editColumn('active', function ($query) {
                if ($query->active === 0) {
                    return "Đã vô hiệu hóa";
                } elseif (!!!$query->active) {
                    return "Chưa kích hoạt";
                } else {
                    return "Đang hoạt động";
                }
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Thotam\ThotamTeam\Models\Nhom $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Nhom $model)
    {
        $query = $model->newQuery();

        if (!request()->has('order')) {
            $query->orderBy('order', 'asc');
        };

        return $query->with(["phan_loai:id,name", "chinhanh:id,name", "kenh_kinh_doanh:id,name", "nhom_san_pham:id,name", "truc_thuoc_nhoms:id,full_name" ]);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('nhom-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom("<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'row'<'col-sm-12 table-responsive't>><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>")
                    ->parameters([
                        "autoWidth" => false,
                        "lengthMenu" => [
                            [10, 25, 50, -1],
                            [10, 25, 50, "Tất cả"]
                        ],
                        "order" => [],
                        'initComplete' => 'function(settings, json) {
                            var api = this.api();
                            window.addEventListener("dt_draw", function(e) {
                                api.draw(false);
                                e.preventDefault();
                            })
                            api.buttons()
                                .container()
                                .appendTo($("#datatable-buttons"));
                        }',
                    ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center')
                  ->title("")
                  ->footer(""),
            Column::make("full_name")
                  ->title("Tên nhóm")
                  ->width(200)
                  ->searchable(true)
                  ->orderable(true)
                  ->footer("Tên nhóm"),
            Column::make("chinhanh.name")
                  ->title("Chi nhánh")
                  ->width(20)
                  ->searchable(false)
                  ->orderable(false)
                  ->render("function() {
                        if (!!data) {
                            return data;
                        } else {
                            return null;
                        }
                    }")
                  ->footer("Chi nhánh"),
            Column::make("phan_loai.name")
                  ->title("Phân loại")
                  ->width(20)
                  ->searchable(false)
                  ->orderable(false)
                  ->render("function() {
                        if (!!data) {
                            return data;
                        } else {
                            return null;
                        }
                    }")
                  ->footer("Phân loại"),
            Column::make("kenh_kinh_doanh.name")
                  ->title("Kênh kinh doanh")
                  ->width(20)
                  ->searchable(false)
                  ->orderable(false)
                  ->render("function() {
                        if (!!data) {
                            return data;
                        } else {
                            return null;
                        }
                    }")
                  ->footer("Kênh kinh doanh"),
            Column::make("nhom_san_pham.name")
                  ->title("Nhóm sản phẩm")
                  ->width(20)
                  ->searchable(false)
                  ->orderable(false)
                  ->render("function() {
                        if (!!data) {
                            return data;
                        } else {
                            return null;
                        }
                    }")
                  ->footer("Nhóm sản phẩm"),
            Column::make("nhom_has_quanlys.hoten")
                  ->title("Quản lý")
                  ->width(20)
                  ->searchable(false)
                  ->orderable(false)
                  ->render("function() {
                        if (!!data) {
                            return data;
                        } else {
                            return null;
                        }
                    }")
                  ->footer("Quản lý"),
            Column::make("truc_thuoc_nhoms.full_name")
                  ->title("Trực thuộc nhóm")
                  ->width(20)
                  ->searchable(false)
                  ->orderable(false)
                  ->render("function() {
                        if (!!data) {
                            return data;
                        } else {
                            return null;
                        }
                    }")
                  ->footer("Trực thuộc nhóm"),
            Column::make("active")
                  ->title("Trạng thái")
                  ->searchable(false)
                  ->orderable(false)
                  ->footer("Trạng thái"),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Nhom_' . date('YmdHis');
    }
}
