<!-- Filters -->
<div class="px-4 pt-0 mb-0">
	<div class="form-row justify-content-between">

		<div class="col-md-auto mb-2">
			<label class="form-label"></label>
			<div class="col px-0 mb-1 text-md-left text-center">
				@if ($hr->can('add-team'))
					{{-- <button type="button" class="btn btn-success waves-effect" wire:click.prevent="add_team" wire:loading.attr="disabled" thotam-blockui><span class="fas fa-plus-circle mr-2"></span>Thêm</button> --}}
				@endif
			</div>
		</div>

		<div class="col-md-auto mb-2">
			<div class="form-row justify-content-between">

				<div class="col-12 col-md-auto px-0 text-md-right text-center" wire:ignore>
					<label class="form-label"></label>
					<div class="d-none" id="datatable-buttons">
					</div>
				</div>

			</div>
		</div>

	</div>
</div>
