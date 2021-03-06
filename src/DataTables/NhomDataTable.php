<?php

namespace Thotam\ThotamTeam\DataTables;

use Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Thotam\ThotamTeam\Models\Nhom;
use Thotam\ThotamPlus\Models\ChiNhanh;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder;
use Thotam\ThotamPlus\Models\NhomSanPham;
use Thotam\ThotamTeam\Models\PhanLoaiNhom;
use Thotam\ThotamPlus\Models\KenhKinhDoanh;

class NhomDataTable extends DataTable
{
    public $hr, $table_id;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->hr = Auth::user()->hr;
        $this->table_id = "nhom-table";
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
            ->filter(function ($query) {
                if (request('active_filter') !== NULL && request('active_filter') != -999) {
                    if (request('active_filter') == 1) {
                        $query->where('nhoms.active', true);
                    } elseif (request('active_filter') == -1) {
                        $query->where('nhoms.active', 0);
                    } else {
                        $query->where('nhoms.active', NULL);
                    }
                }

                if (!!request('full_name_filter')) {
                    $query->where('nhoms.full_name', 'like', '%' . request('full_name_filter') . '%');
                }

                if (!!request('chinhanh_name_filter')) {
                    if (is_array(request('chinhanh_name_filter'))) {
                        $query->whereIn('nhoms.chinhanh_id', request('chinhanh_name_filter'));
                    } elseif (request('chinhanh_name_filter') != -999) {
                        $query->where('nhoms.chinhanh_id', request('chinhanh_name_filter'));
                    }
                }

                if (!!request('phan_loai_name_filter')) {
                    if (is_array(request('phan_loai_name_filter'))) {
                        $query->whereIn('nhoms.phan_loai_id', request('phan_loai_name_filter'));
                    } elseif (request('phan_loai_name_filter') != -999) {
                        $query->where('nhoms.phan_loai_id', request('phan_loai_name_filter'));
                    }
                }

                if (!!request('kenh_kinh_doanh_name_filter')) {
                    $query->whereIn('nhoms.kenh_kinh_doanh_id', request('kenh_kinh_doanh_name_filter'));
                }

                if (!!request('nhom_san_pham_name_filter')) {
                    $query->whereIn('nhoms.nhom_san_pham_id', request('nhom_san_pham_name_filter'));
                }

                if (request('truc_thuoc_nhoms_full_name_filter') !== NULL && request('truc_thuoc_nhoms_full_name_filter') != -999) {
                    $query->whereHas('truc_thuoc_nhoms', function (Builder $query2) {
                        $query2->where('nhom_tructhuocs.nhom_quan_ly_id', request('truc_thuoc_nhoms_full_name_filter'));
                    });
                }

                if (request('nhom_has_quanlys_hoten_filter') !== NULL) {
                    $query->whereHas('nhom_has_quanlys', function (Builder $query2) {
                        $query2->where('hrs.hoten', 'like', '%' . request('nhom_has_quanlys_hoten_filter') . '%');
                    });
                }
            }, true)
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
                    return "???? v?? hi???u h??a";
                } elseif (!!!$query->active) {
                    return "Ch??a k??ch ho???t";
                } else {
                    return "??ang ho???t ?????ng";
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
                    ->setTableId($this->table_id)
                    ->columns($this->getColumns())
                    ->minifiedAjax("",NULL, [
                        "full_name_filter" => '$("#' . $this->table_id . '-full_name-filter").val()',
                        "active_filter" => '$("#' . $this->table_id . '-active-filter").val()',
                        "chinhanh_name_filter" => '$("#' . $this->table_id . '-chinhanh_name-filter").val()',
                        "phan_loai_name_filter" => '$("#' . $this->table_id . '-phan_loai_name-filter").val()',
                        "kenh_kinh_doanh_name_filter" => '$("#' . $this->table_id . '-kenh_kinh_doanh_name-filter").val()',
                        "nhom_san_pham_name_filter" => '$("#' . $this->table_id . '-nhom_san_pham_name-filter").val()',
                        "truc_thuoc_nhoms_full_name_filter" => '$("#' . $this->table_id . '-truc_thuoc_nhoms_full_name-filter").val()',
                        "nhom_has_quanlys_hoten_filter" => '$("#' . $this->table_id . '-nhom_has_quanlys_hoten-filter").val()',
                    ])
                    ->dom("<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'row'<'col-sm-12 table-responsive't>><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>><'d-none'B>")
                    ->buttons(
                        Button::make('excel')->addClass("btn btn-success waves-effect")->text('<span class="fas fa-file-excel mx-2"></span> Export'),
                        Button::make('reload')->addClass("btn btn-info waves-effect")->text('<span class="fas fa-filter mx-2"></span> Filter'),
                    )
                    ->parameters([
                        "autoWidth" => false,
                        "lengthMenu" => [
                            [10, 25, 50, -1],
                            [10, 25, 50, "T???t c???"]
                        ],
                        "order" => [],
                        'initComplete' => 'function(settings, json) {
                            var api = this.api();

                            $(document).on("click", "#filter_submit", function(e) {
                                api.draw(false);
                                e.preventDefault();
                            });

                            window.addEventListener("dt_draw", function(e) {
                                api.draw(false);
                                e.preventDefault();
                            })

                            $("thead#' . $this->table_id . '-thead").insertAfter(api.table().header());

                            api.buttons()
                                .container()
                                .removeClass("btn-group")
                                .appendTo($("#datatable-buttons"));

                            $("#datatable-buttons").removeClass("d-none")
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
                  ->title("T??n nh??m")
                  ->width(200)
                  ->searchable(true)
                  ->orderable(false)
                  ->footer("T??n nh??m")
                  ->filterView(view('thotam-laravel-datatables-filter::input', ['c_placeholder' => "H??? t??n"])->with("colum_filter_id")),
            Column::make("chinhanh.name")
                  ->title("Chi nh??nh")
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
                  ->footer("Chi nh??nh")
                  ->filterView(view('thotam-laravel-datatables-filter::select-multi', ['selects' => $this->getChiNhanhsProperty(), 'c_placeholder' => "Chi nh??nh"])->with("colum_filter_id")),
            Column::make("phan_loai.name")
                  ->title("Ph??n lo???i")
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
                  ->footer("Ph??n lo???i")
                  ->filterView(view('thotam-laravel-datatables-filter::select-multi', ['selects' => $this->getPhanLoaisProperty(), 'c_placeholder' => "Ph??n lo???i"])->with("colum_filter_id")),
            Column::make("kenh_kinh_doanh.name")
                  ->title("K??nh kinh doanh")
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
                  ->footer("K??nh kinh doanh")
                  ->filterView(view('thotam-laravel-datatables-filter::select-multi', ['selects' => $this->getKenhKinhDoanhsProperty(), 'c_placeholder' => "K??nh kinh doanh"])->with("colum_filter_id")),
            Column::make("nhom_san_pham.name")
                  ->title("Nh??m s???n ph???m")
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
                  ->footer("Nh??m s???n ph???m")
                  ->filterView(view('thotam-laravel-datatables-filter::select-multi', ['selects' => $this->getNhomSanPhamsProperty(), 'c_placeholder' => "Nh??m s???n ph???m"])->with("colum_filter_id")),
            Column::make("nhom_has_quanlys.hoten")
                  ->title("Qu???n l??")
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
                  ->footer("Qu???n l??")
                  ->filterView(view('thotam-laravel-datatables-filter::input', ['c_placeholder' => "Qu???n l??"])->with("colum_filter_id")),
            Column::make("truc_thuoc_nhoms.full_name")
                  ->title("Tr???c thu???c nh??m")
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
                  ->footer("Tr???c thu???c nh??m")
                  ->filterView(view('thotam-laravel-datatables-filter::select-single', ['selects' => $this->getNhomsProperty(), 'c_placeholder' => "Nh??m"])->with("colum_filter_id")),
            Column::make("active")
                  ->title("Tr???ng th??i")
                  ->searchable(false)
                  ->orderable(false)
                  ->footer("Tr???ng th??i")
                  ->filterView(view('thotam-laravel-datatables-filter::select-single', ['selects' => $this->getTrangThaisProperty(), 'c_placeholder' => "Tr???ng th??i"])->with("colum_filter_id"))
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

    public function getTrangThaisProperty()
    {
        return [
            "1" => "??ang ho???t ?????ng",
            "0" => "Ch??a k??ch ho???t",
            "-1" => "???? v?? hi???u h??a",
        ];
    }

    public function getChiNhanhsProperty()
    {
        return ChiNhanh::all()->pluck('name', 'id')->toArray();
    }

    public function getPhanLoaisProperty()
    {
        return PhanLoaiNhom::all()->pluck('name', 'id')->toArray();
    }

    public function getKenhKinhDoanhsProperty()
    {
        return KenhKinhDoanh::all()->pluck('name', 'id')->toArray();
    }

    public function getNhomSanPhamsProperty()
    {
        $nhomsanpham = [];
        foreach (NhomSanPham::with('kinhdoanh')->orderBy('nhom_san_phams.order')->get()->sortBy('kinhdoanh.order') as $key => $value) {
            $nhomsanpham[$value->id] = '[' . $value->kinhdoanh->name . '] ' . $value->name;
        }
        return $nhomsanpham;
    }

    public function getNhomsProperty()
    {
        return Nhom::orderBy('order', 'desc')->get()->pluck('full_name', 'id')->toArray();
    }
}
