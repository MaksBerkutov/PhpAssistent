# StatusBeaconApp (demo)

Это учебный пример App-пакета для вашей системы установки приложений.

## Что делает
- Рендерит простую панель в разделе Apps -> Open.
- Регистрирует собственный маршрут `GET /apps/statusbeacon/ping`.
- Показывает пример `getSchema()` для настроек app.

## Структура
- `manifest.json` — мета-данные приложения.
- `src/ServiceProvider.php` — основной класс app.
- `Views/dashboard.blade.php` — интерфейс app.

## Как это работает внутри
1. Установщик читает `manifest.json`.
2. Копирует `src/*` в `app/Apps/Statusbeacon`.
3. Копирует `Views/*` в `resources/views/apps/Statusbeacon`.
4. Сохраняет запись в таблицу `apps` с entrypoint:
   - `Apps\\Statusbeacon\\ServiceProvider`
5. При открытии app вызывается:
   - `AppManager::integrate()` -> `registerRoutes()` / `boot()`
   - `AppManager::renderApp()` -> `render()`

## Быстрая установка
1. Запакуйте содержимое папки `StatusBeaconApp` в zip.
2. В интерфейсе откройте `Приложения -> Установить приложение`.
3. Загрузите zip.
4. Откройте установленное приложение и нажмите `Ping route`.
