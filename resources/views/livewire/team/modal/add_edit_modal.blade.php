<div wire:ignore.self class="modal fade" id="add_edit_modal" tabindex="-1" role="dialog" aria-labelledby="add_edit_modal" aria-hidden="true" data-toggle="modal" data-backdrop="static" data-full_nameboard="false">

    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content py-2">
            <div class="modal-header">
                <h4 class="modal-title text-purple"><span class="fas fa-users mr-3"></span>{{ $modal_title }}</h4>
                <button type="button" wire:click.prevent="cancel()" thotam-blockui class="close" data-dismiss="modal" wire:loading.attr="disabled" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @if ($addStatus || $editStatus)
                <div class="modal-body">
                    <div class="container-fluid mx-0 px-0">
                        <form>
                            <div class="row">

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label" for="full_name">Tên nhóm:</label>
                                        <div id="full_name_div">
                                            <input type="text" class="form-control px-2" wire:model.lazy="full_name" id="full_name" style="width: 100%" readonly placeholder="Tên nhóm ..." autocomplete="off">
                                        </div>
                                        @error('full_name')
                                            <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label text-indigo" for="name">Nhóm:</label>
                                        <div id="name_div">
                                            <input type="text" class="form-control px-2" wire:model.lazy="name" id="name" style="width: 100%" placeholder="Nhóm ..." autocomplete="off">
                                        </div>
                                        @error('name')
                                            <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label text-indigo" for="chinhanh_id">Chi nhánh:</label>
                                        <div class="select2-success" id="chinhanh_id_div">
                                            <select class="form-control px-2 thotam-select2" thotam-allow-clear="true" thotam-placeholder="Nhóm thuộc chi nhánh ..." thotam-search="10" wire:model="chinhanh_id" id="chinhanh_id" style="width: 100%">
                                                @if (!!count($chinhanh_arrays))
                                                    <option selected></option>
                                                    @foreach ($chinhanh_arrays as $chinhanh_array)
                                                        <option value="{{ $chinhanh_array["id"] }}">{{ $chinhanh_array["name"] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('chinhanh_id')
                                            <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label text-indigo" for="phan_loai_id">Phân loại:</label>
                                        <div class="select2-success" id="phan_loai_id_div">
                                            <select class="form-control px-2 thotam-select2" thotam-allow-clear="true" thotam-placeholder="Phân loại nhóm ..." thotam-search="10" wire:model="phan_loai_id" id="phan_loai_id" style="width: 100%">
                                                @if (!!count($phan_loai_arrays))
                                                    <option selected></option>
                                                    @foreach ($phan_loai_arrays as $phan_loai_array)
                                                        <option value="{{ $phan_loai_array["id"] }}">{{ $phan_loai_array["name"] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('phan_loai_id')
                                            <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                                @if ($phan_loai_id == 3)

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="col-form-label text-indigo" for="kenh_kinh_doanh_id">Kênh kinh doanh:</label>
                                            <div class="select2-success" id="kenh_kinh_doanh_id_div">
                                                <select class="form-control px-2 thotam-select2" thotam-allow-clear="true" thotam-placeholder="Kênh kinh doanh ..." thotam-search="10" wire:model="kenh_kinh_doanh_id" id="kenh_kinh_doanh_id" style="width: 100%">
                                                    @if (!!count($kenh_kinh_doanh_arrays))
                                                        <option selected></option>
                                                        @foreach ($kenh_kinh_doanh_arrays as $kenh_kinh_doanh_array)
                                                            <option value="{{ $kenh_kinh_doanh_array["id"] }}">{{ $kenh_kinh_doanh_array["name"] }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            @error('kenh_kinh_doanh_id')
                                                <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>

                                    @if (!!$this->kenh_kinh_doanh_id)
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="col-form-label text-indigo" for="nhom_san_pham_id">Nhóm sản phẩm:</label>
                                                <div class="select2-success" id="nhom_san_pham_id_div">
                                                    <select class="form-control px-2 thotam-select2" thotam-allow-clear="true" thotam-placeholder="Nhóm sản phẩm ..." thotam-search="10" wire:model="nhom_san_pham_id" id="nhom_san_pham_id" style="width: 100%">
                                                        @if (!!count($nhom_san_pham_arrays))
                                                            <option selected></option>
                                                            @foreach ($nhom_san_pham_arrays as $nhom_san_pham_array)
                                                                <option value="{{ $nhom_san_pham_array["id"] }}">{{ $nhom_san_pham_array["name"] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                @error('nhom_san_pham_id')
                                                    <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif

                                @endif

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label text-indigo" for="quanlys">Quản lý:</label>
                                        <div class="select2-success" id="quanlys_div">
                                            <select class="form-control px-2 thotam-select2-multi" multiple thotam-placeholder="Quản lý ..." thotam-search="10" wire:model="quanlys" id="quanlys" style="width: 100%">
                                                @if (!!count($nhansu_arrays))
                                                    @foreach ($nhansu_arrays as $nhansu_array)
                                                        <option value="{{ $nhansu_array["key"] }}">[{{ $nhansu_array["key"] }}] {{ $nhansu_array["hoten"] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('quanlys')
                                            <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label text-indigo" for="truc_thuoc_nhoms">Trực thuộc nhóm:</label>
                                        <div class="select2-success" id="truc_thuoc_nhoms_div">
                                            <select class="form-control px-2 thotam-select2-multi" multiple thotam-placeholder="Trực thuộc nhóm ..." thotam-search="10" wire:model="truc_thuoc_nhoms" id="truc_thuoc_nhoms" style="width: 100%">
                                                @if (!!count($nhom_arrays))
                                                    @foreach ($nhom_arrays as $nhom_array)
                                                        <option value="{{ $nhom_array["id"] }}">{{ $nhom_array["full_name"] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('truc_thuoc_nhoms')
                                            <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                                @if ($editStatus)
                                    <div class="col-12">
                                        <div class="input-group form-group border-bottom thotam-border py-2">
                                            <div class="input-group-prepend mr-4">
                                                <label class="col-form-label col-6 text-left pt-0 input-group-text border-0 text-indigo" for="active">Kích hoạt nhóm:</label>
                                            </div>
                                            <label class="switcher switcher-square">
                                                <input type="checkbox" class="switcher-input form-control" wire:model="active" id="active" style="width: 100%">
                                                <span class="switcher-indicator">
                                                    <span class="switcher-yes"></span>
                                                    <span class="switcher-no"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="modal-footer mx-auto">
                <button wire:click.prevent="cancel()" thotam-blockui class="btn btn-danger" wire:loading.attr="disabled" data-dismiss="modal">Đóng</button>
                <button wire:click.prevent="save_team()" thotam-blockui class="btn btn-success" wire:loading.attr="disabled">Xác nhận</button>
            </div>

        </div>
    </div>

</div>
