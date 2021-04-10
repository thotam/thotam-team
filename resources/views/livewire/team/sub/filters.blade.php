<!-- Filters -->
<div class="px-4 pt-4 mb-0" wire:ignore>
    <div class="form-row">

        @if ($hr->can("add-team"))
            <div class="col-md-auto mb-4 pr-md-3">
                <label class="form-label d-none d-md-block">&nbsp;</label>
                <div class="col px-0 mb-1 text-md-left text-center">
                    <button type="button" class="btn btn-success waves-effect" wire:click.prevent="add_team" wire:loading.attr="disabled" thotam-blockui><span class="fas fa-plus-circle mr-2"></span>Thêm</button>
                </div>
            </div>
        @endif

    </div>
</div>
<!-- / Filters -->
