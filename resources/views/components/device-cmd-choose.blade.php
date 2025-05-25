<div class="mb-3">
    <label for="{{$name}}" class="form-label">{{$label}}</label>
    <select id="{{$name}}" name="{{$name}}" class="form-select @error($name) is-invalid @enderror">
        <option value="">Виберіть команду</option>
    </select>
    @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<script>
    document.getElementById('{{$deviceChoseName}}').addEventListener('change', ChangeCommand);
    function ChangeCommand() {
        const module = document.getElementById('{{$deviceChoseName}}');

        const commandSelect = document.getElementById('{{$name}}');
        commandSelect.innerHTML = '<option value="">Виберіть команду</option>';

        const selectedModule = module.options[module.selectedIndex];
        const commands = selectedModule.getAttribute('data-commands');
        if (commands) {
            const commandArray = JSON.parse(JSON.parse(commands));
            //console.log(typeof commandArray)

            commandArray.forEach(command => {
                const option = document.createElement('option');
                option.value = command;
                option.textContent = command;
                if(command == '{{ old($name,$old)}}')
                    option.selected = true;

                commandSelect.appendChild(option);
            });
        }
    }
</script>
