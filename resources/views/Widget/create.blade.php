@extends('layouts.menu')
@section('title', __('ui.widgets.create_widget'))

@section('styles')
    <style>
        .widget-create-form {
            max-width: 900px;
        }

        .input-params-row .card-body {
            display: grid;
            grid-template-columns: 1fr 180px auto;
            gap: 8px;
            align-items: center;
        }

        @media (max-width: 760px) {
            .input-params-row .card-body {
                grid-template-columns: 1fr;
            }

            .input-params-row .card-body .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">{{ __('ui.widgets.create_title') }}</h2>
                <p class="page-subtitle">{{ __('ui.widgets.create_subtitle') }}</p>
            </div>
            <a href="{{ route('widget') }}" class="btn btn-outline-primary">{{ __('ui.widgets.back_to_list') }}</a>
        </section>

        <section class="page-card widget-create-form">
            <form action="{{ route('widget.store') }}" method="post">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('ui.widgets.name') }}</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('ui.widgets.name_placeholder') }}" required>
                </div>

                <div class="mb-3">
                    <label for="widget_name" class="form-label">{{ __('ui.widgets.component_name') }}</label>
                    <input type="text" class="form-control" id="widget_name" name="widget_name" placeholder="{{ __('ui.widgets.component_name_placeholder') }}" required>
                </div>

                <div class="mb-3">
                    <label for="accesses_key" class="form-label">{{ __('ui.widgets.security_key_label') }}</label>
                    <input type="text" class="form-control" id="accesses_key" name="accesses_key" placeholder="{{ __('ui.widgets.optional_placeholder') }}">
                </div>

                <div class="mb-2 d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <h5 class="mb-0">{{ __('ui.widgets.params_title') }}</h5>
                    <button type="button" class="btn btn-outline-primary" id="addField">{{ __('ui.widgets.add_field') }}</button>
                </div>

                <div id="inputParamsContainer" class="mb-3"></div>
                <input type="hidden" name="input_params" id="input_params">

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('ui.widgets.save_widget') }}</button>
                    <a href="{{ route('widget') }}" class="btn btn-outline-primary">{{ __('ui.common.cancel') }}</a>
                </div>
            </form>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputParamsContainer = document.getElementById('inputParamsContainer');
            const addFieldButton = document.getElementById('addField');
            const inputParamsField = document.getElementById('input_params');

            function updateInputParams() {
                const params = {};
                const rows = inputParamsContainer.querySelectorAll('.input-params-row');

                rows.forEach(function (row) {
                    const keyInput = row.querySelector('input[name="command_on"]');
                    const valueSelect = row.querySelector('select[name="command_value"]');

                    if (!keyInput || !valueSelect) {
                        return;
                    }

                    const key = keyInput.value.trim();
                    const value = valueSelect.value;

                    if (key) {
                        params[key] = value;
                    }
                });

                inputParamsField.value = JSON.stringify(params);
            }

            function addField() {
                const newCard = document.createElement('div');
                newCard.className = 'card mb-2 input-params-row';
                newCard.innerHTML = `
                    <div class="card-body">
                        <input type="text" class="form-control" name="command_on" placeholder="{{ __('ui.widgets.field_key_placeholder') }}" required>
                        <select class="form-select" name="command_value" required>
                            <option value="command">command</option>
                            <option value="text">text</option>
                        </select>
                        <button type="button" class="btn btn-outline-danger remove-field">{{ __('ui.widgets.remove_field') }}</button>
                    </div>
                `;

                inputParamsContainer.appendChild(newCard);
                updateInputParams();
            }

            addFieldButton.addEventListener('click', addField);

            inputParamsContainer.addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-field')) {
                    const row = event.target.closest('.input-params-row');
                    if (row) {
                        row.remove();
                        updateInputParams();
                    }
                }
            });

            inputParamsContainer.addEventListener('input', updateInputParams);
            inputParamsContainer.addEventListener('change', updateInputParams);

            addField();
        });
    </script>
@endsection
