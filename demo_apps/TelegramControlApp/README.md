# TelegramControlApp

Продвинутое demo-приложение Telegram Bot для PhpAssistant.

## Возможности
- Настройка bot token и default chat id.
- Установка/удаление webhook в Telegram API.
- Тестовая отправка сообщения из UI.
- Обработка webhook-команд:
  - `/help`
  - `/status`
  - `/devices`
  - `/scenarios`
  - `/ping`
- Добавляет собственный пункт в боковое меню: `Telegram Bot`.

## Маршруты
- `GET /apps/telegramcontrol` - страница управления
- `POST /apps/telegramcontrol/settings`
- `POST /apps/telegramcontrol/webhook/set`
- `POST /apps/telegramcontrol/webhook/delete`
- `POST /apps/telegramcontrol/send-test`
- `POST /apps/telegramcontrol/webhook/{secret}` - endpoint для Telegram

## Требования
- Должна существовать таблица `app_settings`.
- Сервер должен быть доступен Telegram по HTTPS для webhook.

## Быстрый запуск
1. Создайте бота через BotFather.
2. Откройте пункт `Telegram Bot` в боковом меню.
3. Сохраните token и chat id.
4. Нажмите `Set webhook`.
5. Напишите боту `/help`.
