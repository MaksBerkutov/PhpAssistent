<div id="db-card" class="card mb-3 d-none">
    <div class="card-body">
        <h5 class="card-title">Налаштування БД</h5>
        <div class="mb-3">
            <label for="db_login" class="form-label">Логін</label>
            <input type="text" id="db_login" name="db_login" class="form-control @error('db_login') is-invalid @enderror" value="{{ old('db_login', isset($scenario)?optional($scenario->ScenarioDb)->login:'') }}">
            @error('db_login')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="db_password" class="form-label">Пароль</label>
            <input type="password" id="db_password" name="db_password" class="form-control @error('db_password') is-invalid @enderror" value="{{ old('db_password', isset($scenario)?optional($scenario->ScenarioDb)->password:'') }}">
            @error('db_password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="db_name" class="form-label">Ім'я бази даних</label>
            <input type="text" id="db_name" name="db_name" class="form-control @error('db_name') is-invalid @enderror" value="{{ old('db_name', isset($scenario)?optional($scenario->ScenarioDb)->db_name:'') }}">
            @error('db_name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="db_table" class="form-label">Ім'я таблиці</label>
            <input type="text" id="db_table" name="db_table" class="form-control @error('db_table') is-invalid @enderror" value="{{ old('db_table', isset($scenario)?optional($scenario->ScenarioDb)->table_name:'') }}">
            @error('db_table')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="db_key_field" class="form-label">Поле для ключа</label>
            <input type="text" id="db_key_field" name="db_key_field" class="form-control @error('db_key_field') is-invalid @enderror" value="{{ old('db_key_field', isset($scenario)?optional($scenario->ScenarioDb)->name_key:'') }}">
            @error('db_key_field')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="db_value_field" class="form-label">Поле для значення</label>
            <input type="text" id="db_value_field" name="db_value_field" class="form-control @error('db_value_field') is-invalid @enderror" value="{{ old('db_value_field', isset($scenario)?optional($scenario->ScenarioDb)->name_value:'') }}">
            @error('db_value_field')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
