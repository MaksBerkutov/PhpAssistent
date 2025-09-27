<div class="container mt-5">
    <form method="POST" action="{{ route('apps.telegram.save') }}">
        @csrf
        <div class="mb-3">
            <label>API Token</label>
            <input type="text" class="form-control" name="api_token" value="{{ $settings['api_token'] ?? '' }}">
        </div>
        <div class="mb-3">
            <label>Chat ID</label>
            <input type="text" class="form-control" name="chat_id" value="{{ $settings['chat_id'] ?? '' }}">
        </div>
        <div class="mb-3">
            <label>Whitelist сценариев (через запятую)</label>
            <input type="text" class="form-control" name="whitelist" value="{{ $settings['whitelist'] ?? '' }}">


            <button class="btn btn-success">Сохранить</button>
    </form>
</div>
