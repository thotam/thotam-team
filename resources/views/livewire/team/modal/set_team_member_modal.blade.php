<div wire:ignore.self class="modal fade" id="set_team_member_modal" tabindex="-1" role="dialog" aria-labelledby="set_team_member_modal" aria-hidden="true" data-toggle="modal" data-backdrop="static" data-full_nameboard="false">

    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content py-2">
            <div class="modal-header">
                <h4 class="modal-title text-purple"><span class="fas fa-users mr-3"></span>{{ $modal_title }}</h4>
                <button type="button" wire:click.prevent="cancel()" thotam-blockui class="close" data-dismiss="modal" wire:loading.attr="disabled" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @if ($setTeamMemberStatus)
                <div class="modal-body">
                    <div class="container-fluid mx-0 px-0">
                        <form>
                            <div class="row">

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label" for="full_name">Tên nhóm:</label>
                                        <div>
                                            <span type="text" class="form-control px-2 h-auto">{{ $full_name }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label text-indigo" for="thanhviens">Thành viên:</label>
                                        <div class="select2-success" id="thanhviens_div" wire:ignore>
                                            <select class="form-control px-2" multiple thotam-placeholder="Thành viên ..." thotam-search="10" wire:model="thanhviens" id="thanhviens" style="width: 100%" x-init="thotam_ajax_select2($el, @this, '{{ route('admin.member.select_hr') }}', 50, '{{ csrf_token() }}')">
                                                @if (!!count($nhansu_arrays))
                                                    @foreach ($nhansu_arrays as $key => $hoten)
                                                        <option value="{{ $key }}">[{{ $key }}] {{ $hoten }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('thanhviens')
                                            <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="modal-footer mx-auto">
                <button wire:click.prevent="cancel()" thotam-blockui class="btn btn-danger" wire:loading.attr="disabled" data-dismiss="modal">Đóng</button>
                <button wire:click.prevent="set_member_team_save()" thotam-blockui class="btn btn-success" wire:loading.attr="disabled">Xác nhận</button>
            </div>

        </div>
    </div>

</div>
