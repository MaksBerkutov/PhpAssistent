<div id="notify-card" class="card mb-3 d-none">
    <div class="card-body">
        <h5 class="card-title">{{ __('ui.scenarios.notification') }}</h5>
        <p class="text-muted small mb-3">{{ __('ui.scenarios.cards.notify_desc') }}</p>

        <div class="mb-3">
            <label for="notification_message" class="form-label">{{ __('ui.scenarios.cards.notify_message') }}</label>
            <input type="text" id="notification_message" name="notification_message" class="form-control @error('notification_message') is-invalid @enderror" placeholder="{{ __('ui.scenarios.cards.notify_message_placeholder') }}" value="{{ old('notification_message', isset($scenario) ? optional($scenario->scenarioNotify)->format : '') }}">
            @error('notification_message')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-0">
            <label for="notification_type" class="form-label">{{ __('ui.scenarios.cards.notify_type') }}</label>
            <input type="text" id="notification_type" name="notification_type" class="form-control @error('notification_type') is-invalid @enderror" placeholder="{{ __('ui.scenarios.cards.notify_type_placeholder') }}" value="{{ old('notification_type', isset($scenario) ? optional($scenario->scenarioNotify)->type : '') }}">
            @error('notification_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
