<div style="display:grid;gap:12px;max-width:980px;">
    <section style="border:1px solid rgba(130,150,166,.25);border-radius:16px;padding:14px;background:rgba(23,33,43,.45);">
        <p style="margin:0 0 6px;font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:#8da3b6;">Demo app</p>
        <h3 style="margin:0 0 8px;color:#dbe7f1;">Status Beacon</h3>
        <p style="margin:0;color:#9fb4c4;">Привет, {{ $userName }}. Это демонстрационная app-панель, которая показывает как рендерится UI и как аппка может регистрировать свои маршруты.</p>
    </section>

    <section style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;">
        <article style="border:1px solid rgba(130,150,166,.25);border-radius:14px;padding:12px;background:rgba(23,33,43,.45);">
            <small style="display:block;color:#8da3b6;margin-bottom:6px;">Server time</small>
            <strong style="color:#dbe7f1;">{{ $serverTime }}</strong>
        </article>
        <article style="border:1px solid rgba(130,150,166,.25);border-radius:14px;padding:12px;background:rgba(23,33,43,.45);">
            <small style="display:block;color:#8da3b6;margin-bottom:6px;">Health check</small>
            <button id="statusBeaconPing" type="button" style="border:0;border-radius:10px;padding:8px 12px;background:#d8642c;color:#fff;font-weight:700;cursor:pointer;">Ping route</button>
            <div id="statusBeaconResult" style="margin-top:8px;color:#9fb4c4;font-size:13px;">Нажмите кнопку для проверки маршрута.</div>
        </article>
        <article style="border:1px solid rgba(130,150,166,.25);border-radius:14px;padding:12px;background:rgba(23,33,43,.45);">
            <small style="display:block;color:#8da3b6;margin-bottom:6px;">Smart tip</small>
            <strong style="color:#dbe7f1;">{{ $tip }}</strong>
        </article>
    </section>
</div>

<script>
    (function () {
        const pingBtn = document.getElementById('statusBeaconPing');
        const result = document.getElementById('statusBeaconResult');

        if (!pingBtn || !result) {
            return;
        }

        pingBtn.addEventListener('click', async function () {
            result.textContent = 'Проверяем...';

            try {
                const response = await fetch("{{ route('apps.statusbeacon.ping') }}", {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const json = await response.json();
                result.textContent = json.ok
                    ? `OK | ${json.server_time} | ${json.user}`
                    : 'Маршрут ответил с ошибкой.';
            } catch (error) {
                result.textContent = 'Ошибка ping запроса.';
            }
        });
    })();
</script>
