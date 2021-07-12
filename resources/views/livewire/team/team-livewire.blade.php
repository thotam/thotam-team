<div>
    <!-- Filters and Add Buttons -->
    @include('thotam-team::livewire.team.sub.filters')

    <!-- Incluce các modal -->
    @include('thotam-team::livewire.team.modal.add_edit_modal')
    @include('thotam-team::livewire.team.modal.set_team_member_modal')

    <!-- Scripts -->
    @push('livewires')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                window.thotam_livewire = @this;
                Livewire.emit("dynamic_update_method");
            });
        </script>
    @endpush

    <!-- Style -->
    @push('styles')
        @include('thotam-team::livewire.team.sub.style')
    @endpush
</div>
