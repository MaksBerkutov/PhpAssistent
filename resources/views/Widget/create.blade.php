@extends('layouts.menu')
@section('title','Create Widget')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2>Форма для заполнения</h2>
            </div>
            <div class="card-body">
                <form action="{{route('widget.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name">Имя</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Пример: Мой виджет" required>
                    </div>
                    <div class="form-group">
                        <label for="widget_name">Название виджета</label>
                        <input type="text" class="form-control" id="widget_name" name="widget_name" placeholder="Пример: my-widget" required>
                    </div>
                    <div class="form-group">
                        <label for="accesses_key">Ключ доступа</label>
                        <input type="text" class="form-control" id="accesses_key" name="accesses_key" >
                    </div>
                    <h4>Параметры виджета</h4>
                    <div id="inputParamsContainer"></div>
                    <input type="hidden" name="input_params" id="input_params">
                    <button type="button" class="btn btn-primary mb-3" id="addField">Добавить поле</button>
                    <button type="submit" class="btn btn-success">Отправить</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputParamsContainer = document.getElementById('inputParamsContainer');
            const addFieldButton = document.getElementById('addField');
            const inputParamsField = document.getElementById('input_params');

            function updateInputParams() {
                const params = {};
                const rows = inputParamsContainer.querySelectorAll('.input-params-row');

                rows.forEach(row => {
                    const keyInput = row.querySelector('input[name="command_on"]');
                    const valueSelect = row.querySelector('select[name="command_value"]');

                    const key = keyInput.value.trim();
                    const value = valueSelect.value;

                    if (key) {
                        params[key]=value;
                    }
                });

                inputParamsField.value = JSON.stringify(params);
            }

            addFieldButton.addEventListener('click', function () {
                const newCard = document.createElement('div');
                newCard.className = 'card mb-2 input-params-row';
                newCard.innerHTML = `
                <div class="card-body d-flex align-items-center">
                    <input type="text" class="form-control me-2" name="command_on" placeholder="Пример: custom_field" required>
                    <select class="form-control me-2" name="command_value" required>
                        <option value="command">command</option>
                        <option value="text">text</option>
                    </select>
                    <button type="button" class="btn btn-danger remove-field">Удалить</button>
                </div>
            `;
                inputParamsContainer.appendChild(newCard);
                updateInputParams();
            });

            inputParamsContainer.addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-field')) {
                    const cardToRemove = event.target.closest('.card');
                    inputParamsContainer.removeChild(cardToRemove);
                    updateInputParams();
                }
            });

            inputParamsContainer.addEventListener('input', function (event) {
                if (event.target.name === 'command_on') {
                    updateInputParams();
                }
            });

            inputParamsContainer.addEventListener('change', function (event) {
                if (event.target.name === 'command_value') {
                    updateInputParams();
                }
            });

        });
    </script>
@endsection

