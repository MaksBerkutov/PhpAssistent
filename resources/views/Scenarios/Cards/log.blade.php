<div id="log-card" class="card mb-3 d-none">
    <div class="card-body">
        <h5 class="card-title">{{ __('ui.scenarios.logging') }}</h5>
        <p class="text-muted small mb-3">{{ __('ui.scenarios.cards.log_desc') }}</p>
        <div class="mb-0">
            <label for="log_format" class="form-label">{{ __('ui.scenarios.cards.log_template') }}</label>
            <input
                type="text"
                id="log_format"
                name="log_format"
                class="form-control @error('log_format') is-invalid @enderror"
                placeholder="{{ __('ui.scenarios.cards.log_template_placeholder') }}"
                value="{{ old('log_format', isset($scenario) ? optional($scenario->scenarioLog)->format : '') }}"
            >
            @error('log_format')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
