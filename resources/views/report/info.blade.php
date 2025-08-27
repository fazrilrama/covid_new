
@if(!empty($additionalMessage))
<div class="alert alert-{{ $additionalError ? 'danger' : 'info' }}">
    {{ $additionalMessage  }}
</div>
@endif