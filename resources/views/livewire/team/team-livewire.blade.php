@php
    $quanly_arrays = Thotam\ThotamHr\Models\HR::orderBy("key")->select("key", "hoten")->get()->toArray();
@endphp

<div>
    <!-- Filters and Add Buttons -->
    @include('thotam-team::livewire.team.sub.filters')

    <!-- Incluce các modal -->
    @include('thotam-team::livewire.team.modal.add_edit_modal')

    <!-- Scripts -->
    @push('livewires')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                window.thotam_livewire = @this;
                Livewire.emit("dynamic_update_method");
            });
        </script>
    @endpush
</div>
