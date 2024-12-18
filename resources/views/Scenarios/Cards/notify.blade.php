<div id="notify-card" class="card mb-3 d-none">
    <div class="card-body">
        <h5 class="card-title">Настройки уведомления</h5>
        <div class="mb-3">
            <label for="notification_message" class="form-label">Сообщение для уведомления</label>
            <input type="text" id="notification_message" name="notification_message" class="form-control @error('notification_message') is-invalid @enderror" placeholder="Пример: Ключ: {key}, Значение: {value}" value="{{ old('notification_message', isset($scenario)?optional($scenario->scenarioNotify)->format:'') }}">
            @error('notification_message')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="notification_type" class="form-label">Сообщение для уведомления</label>
            <input type="text" id="notification_type" name="notification_type" class="form-control @error('notification_type') is-invalid @enderror" placeholder="Пример: Ошибка или Уведомление" value="{{ old('notification_type', isset($scenario)?optional($scenario->scenarioNotify)->type:'') }}">
            @error('notification_type')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
