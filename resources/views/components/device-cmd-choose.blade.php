@php
    $selectedCommand = old($name, $old ?? null);
@endphp

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <select id="{{ $name }}" name="{{ $name }}" class="form-select @error($name) is-invalid @enderror">
        <option value="">{{ __('ui.common.select_command') }}</option>
    </select>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<script>
    (function () {
        const deviceSelect = document.getElementById('{{ $deviceChoseName }}');
        const commandSelect = document.getElementById('{{ $name }}');
        const selectedCommand = @json($selectedCommand);

        if (!deviceSelect || !commandSelect) {
            return;
        }

        function parseCommands(rawCommands) {
            if (!rawCommands) {
                return [];
            }

            try {
                const firstParse = JSON.parse(rawCommands);
                return Array.isArray(firstParse) ? firstParse : JSON.parse(firstParse);
            } catch (e) {
                return [];
            }
        }

        function refreshCommands() {
            commandSelect.innerHTML = '<option value="">{{ __('ui.common.select_command') }}</option>';

            const selectedModule = deviceSelect.options[deviceSelect.selectedIndex];
            const commandArray = parseCommands(selectedModule ? selectedModule.getAttribute('data-commands') : null);

            commandArray.forEach(function (command) {
                const option = document.createElement('option');
                option.value = command;
                option.textContent = command;

                if (String(command) === String(selectedCommand)) {
                    option.selected = true;
                }

                commandSelect.appendChild(option);
            });
        }

        deviceSelect.addEventListener('change', refreshCommands);
        refreshCommands();
    })();
</script>
