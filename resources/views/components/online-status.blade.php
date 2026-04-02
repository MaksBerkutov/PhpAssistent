@if ($status)
    <span class="badge bg-success" style="font-size: 15px"><ion-icon name="eye-outline"></ion-icon> {{ __('ui.common.online') }}</span>
@else
    <span class="badge bg-danger" style="font-size: 15px"><ion-icon name="eye-off-outline"></ion-icon> {{ __('ui.common.offline') }}</span>
@endif
