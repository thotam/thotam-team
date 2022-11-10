<div wire:ignore.self class="modal fade" id="sync_modal" tabindex="-1" role="dialog" aria-labelledby="sync_modal" aria-hidden="true" data-toggle="modal" data-backdrop="static" data-full_nameboard="false">

	<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
		<div class="modal-content py-2">
			<div class="modal-header">
				<h4 class="modal-title text-purple"><span class="fas fa-users mr-3"></span>{{ $modal_title }}</h4>
				<button type="button" wire:click.prevent="cancel()" thotam-blockui class="close" data-dismiss="modal" wire:loading.attr="disabled" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			@if ($syncStatus)
				<div class="modal-body">
					<div class="container-fluid mx-0 px-0">
						<form>
							<div class="row">

								<div class="col-12">
									<div class="form-group">
										<label class="col-form-label">iCPC1HN nhóm:</label>
										<div>
											<span type="text" class="form-control px-2 h-auto">{{ $nhom->icpc1hn_group_id }}</span>
										</div>
									</div>
								</div>

								<div class="col-12">
									<div class="form-group">
										<label class="col-form-label text-indigo" for="nhom_id">Đồng bộ nhóm:</label>
										<div class="select2-success" id="nhom_id_div" wire:ignore>
											<select class="form-control px-2" thotam-placeholder="Đồng bộ nhóm ..." thotam-search="10" wire:model="nhom_id" id="nhom_id" style="width: 100%" x-init="thotam_select2($el, @this)">
												@if (!!count($nhom_arrays))
													<option selected></option>
													@foreach ($nhom_arrays as $nhom_array)
														<option value="{{ $nhom_array['id'] }}">{{ $nhom_array['full_name'] }}</option>
													@endforeach
												@endif
											</select>
										</div>
										@error('nhom_id')
											<label class="pl-1 small invalid-feedback d-inline-block"><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
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
				<button wire:click.prevent="sync_action()" thotam-blockui class="btn btn-success" wire:loading.attr="disabled">Xác nhận</button>
			</div>

		</div>
	</div>

</div>
