<div>
	<!-- Filters and Add Buttons -->
	@include('thotam-team::livewire.team-icpc1hn.sub.filters')

	<!-- Incluce các modal -->
	@include('thotam-team::livewire.team-icpc1hn.modal.sync_modal')

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
		@include('thotam-team::livewire.team-icpc1hn.sub.style')
	@endpush
</div>
