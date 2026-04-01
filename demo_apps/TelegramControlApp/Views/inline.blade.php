<div style="display:grid;gap:10px;">
    <section style="border:1px solid rgba(130,150,166,.25);border-radius:14px;padding:12px;background:rgba(23,33,43,.45);">
        <h3 style="margin:0 0 8px;color:#dbe7f1;">Telegram Bot Control</h3>
        <p style="margin:6px 0;color:#9fb4c4;">Status: <strong style="color:#dbe7f1;">{{ $configured ? 'configured' : 'not configured' }}</strong></p>
        <p style="margin:6px 0;color:#9fb4c4;">Webhook: {{ $webhookUrl ?: 'not set' }}</p>
        <p style="margin:10px 0 0;color:#97acbc;">Open sidebar item "Telegram Bot" to configure token and webhook.</p>
    </section>
</div>
