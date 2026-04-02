# Apps Documentation

## 1. Что такое App в проекте

App — это расширение PhpAssistent с собственным `ServiceProvider`, UI, роутами и настройками.

В проекте есть два режима существования app:

1. **Installed app** (через UI):
- есть запись в таблице `apps`
- видна в разделе `/apps`
- открывается через `apps.open`

2. **Local app** (папка в `app/Apps/...`):
- может быть подхвачена `AppManager` без установки
- может добавить пункты в боковое меню через `menuItems()`

## 2. Ключевые компоненты

### 2.1 База

Таблица `apps` (migration: `database/migrations/2025_09_26_222806_create_apps_table.php`):
- `name`
- `slug`
- `version`
- `entrypoint`
- `description`
- `schema`

Таблица `app_settings` (migration: `database/migrations/2025_09_27_000000_create_app_settings_table.php`):
- `app` (slug app)
- `key`
- `value`

Используется для хранения настроек app (например Telegram token/chat_id/mode).

### 2.2 Базовый контракт

`app/Apps/BaseAppServiceProvider.php`

Обязательные/основные методы:
- `getSchema(): array`
- `render(array $data = [])`

Опциональные:
- `registerRoutes()`
- `registerWidgets()`
- `menuItems()`
- `boot()`

### 2.3 Менеджер app

`app/Services/AppManager.php`

Отвечает за:
- автозагрузку классов namespace `Apps\\*`
- discovery провайдеров из БД и `app/Apps/*/ServiceProvider.php`
- интеграцию app (`registerRoutes`, `boot`, и т.д.)
- сбор меню app (`menuItems`)
- рендер app через `renderApp()`

## 3. Структура архива app (для установки)

Минимум:
- `manifest.json`
- `src/ServiceProvider.php`

Опционально:
- `Views/*` (копируются в `resources/views/Apps/{SafeSlug}` и `resources/views/apps/{SafeSlug}`)
- `Controllers/*` (копируются в `app/Apps/{SafeSlug}/Controllers`)

### 3.1 Пример `manifest.json`

```json
{
  "name": "My App",
  "slug": "myapp",
  "version": "1.0.0",
  "description": "My extension",
  "entrypoint": "Apps\\Myapp\\ServiceProvider"
}
```

> Важно: в текущей логике `entrypoint` формируется автоматически как `Apps\\{SafeSlug}\\ServiceProvider`, поэтому `slug` влияет на namespace.

## 4. Жизненный цикл install/update/delete

Реализован в `app/Http/Controllers/AppInstallerController.php`.

### 4.1 Install

- `POST /apps/install`
- ищет `manifest.json` в архиве (не только в корне)
- удаляет BOM перед `json_decode`
- проверяет наличие `src`
- копирует файлы
- создаёт запись в `apps`

### 4.2 Update

- `POST /apps/{app}/update`
- тот же pipeline, но с проверкой `slug`:
  - `manifest.slug` должен совпадать с обновляемой app
- обновляет запись в `apps`

### 4.3 Delete

- `DELETE /apps/{app}`
- удаляет:
  - `app/Apps/{SafeSlug}`
  - `resources/views/Apps/{SafeSlug}`
  - `resources/views/apps/{SafeSlug}`
- удаляет запись из `apps`

## 5. Роуты управления app-ками

Группа: `Route::prefix('apps')` в `routes/web.php`.

Сейчас доступны:
- `GET /apps` -> index
- `GET /apps/upload` -> upload form
- `POST /apps/install` -> install
- `GET /apps/{app}/update` -> update form
- `POST /apps/{app}/update` -> update
- `DELETE /apps/{app}` -> delete
- `GET /apps/open/{app}` -> open app UI

## 6. Меню app

Боковое меню собирается в `resources/views/layouts/menu.blade.php`.

Чтобы app добавила свои пункты, верните массив из `menuItems()`:

```php
public function menuItems(): array
{
    return [
        [
            'label' => 'My App',
            'route' => 'apps.myapp.dashboard', // или 'url' => '/apps/myapp'
            'route_params' => [],
            'image' => 'flash-outline',
            'guard' => '', // или 'admin|user'
        ],
    ];
}
```

Поддерживается:
- `route` + `route_params`
- или прямой `url`

## 7. TelegramControl app (сложный пример)

Путь:
- `app/Apps/Telegramcontrol`
- `resources/views/Apps/Telegramcontrol`

### 7.1 Что умеет

- настройки `bot_token`, `chat_id`, `webhook_secret`
- режимы доставки:
  - `webhook`
  - `polling`
- `setWebhook` / `deleteWebhook`
- `run polling once`
- `send test message`
- обработка команд:
  - `/help`
  - `/status`
  - `/devices`
  - `/scenarios`
  - `/ping`

### 7.2 Когда что использовать

Webhook:
- нужен публичный HTTPS-домен
- Telegram должен видеть ваш URL

Polling:
- подходит для локалки
- публичный домен не нужен
- вручную запускайте `Run polling once` (или повесьте cron/command)

## 8. Типовые ошибки и решения

### `manifest.json is missing`
Архив не содержит `manifest.json` или вложен некорректно.

### `manifest.json is invalid`
Невалидный JSON или проблемная кодировка (BOM). Исправьте JSON.

### `slug mismatch`
При update загружен архив другой app. `manifest.slug` должен совпадать.

### `mkdir(): File exists`
Исправлено в контроллере (`ensureDirectory`). Если повторится — проверьте, не файл ли лежит по пути директории.

### `Class Apps\... not found`
Проверьте:
- namespace/путь файла
- наличие `ServiceProvider.php`
- очистите кэш: `php artisan optimize:clear`

### `Telegram setWebhook failed: Failed to resolve host`
Ваш `APP_URL` локальный и недоступен Telegram.

Решение:
- используйте публичный HTTPS-домен
- или переключайтесь на polling

## 9. Рекомендации по разработке новых app

1. Сначала делайте локально в `app/Apps/{AppName}`.
2. Держите один `ServiceProvider` как точку входа.
3. Все внешние интеграции оборачивайте в отдельный controller/service app.
4. Для настроек используйте `app_settings` с ключом `app=<slug>`.
5. После изменений маршрутов/классов выполняйте:

```bash
php artisan optimize:clear
```

## 10. Быстрый чек-лист перед релизом app

- [ ] `manifest.json` валиден
- [ ] `slug` стабилен
- [ ] `ServiceProvider` загружается
- [ ] `menuItems()` возвращает корректные route/url
- [ ] UI открывается из `/apps/open/{app}`
- [ ] update с тем же slug проходит
- [ ] delete чистит файлы и БД
