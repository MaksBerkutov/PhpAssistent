# Feature Plan: Improve & Simplify Usage

## Goal
Сделать PhpAssistent проще для первого старта, понятнее в ежедневной работе и надежнее в эксплуатации.

---

## Phase 1: Quick Wins (1-2 weeks)

### 1. Onboarding Wizard (First Run)
Что добавить:
- Пошаговый мастер после логина:
  1) профиль
  2) первое устройство
  3) первый виджет
  4) первый сценарий
  5) проверка Telegram

Польза:
- новый пользователь не теряется,
- быстрее доходит до “первой ценности”.

### 2. Smart Empty States
Что добавить:
- Вместо “пусто” показывать CTA-кнопки и короткие подсказки:
  - “Добавить устройство”
  - “Создать сценарий из шаблона”

Польза:
- меньше тупиков в UI,
- понятный следующий шаг.

### 3. Presets for Scenarios
Что добавить:
- Готовые шаблоны сценариев:
  - Ночной режим
  - Уход из дома
  - Энергосбережение

Польза:
- быстрое создание сценариев без сложной настройки.

### 4. Unified Notifications Center
Что добавить:
- Центр уведомлений в topbar:
  - успешные/ошибочные действия
  - интеграционные ошибки
  - статус app

Польза:
- пользователь видит, что происходит в системе, без просмотра логов.

---

## Phase 2: Usability Upgrade (2-4 weeks)

### 5. Device Health & Diagnostics
Что добавить:
- Статус устройства: online/offline/latency/last seen.
- Кнопка “Диагностика” (пинг, последний ответ, конфиг-валидность).

Польза:
- легче понимать, почему команда не сработала.

### 6. Scenario Debug Mode
Что добавить:
- Просмотр трассировки сценария:
  - триггер
  - шаги
  - результат каждого шага

Польза:
- быстрая отладка автоматизаций.

### 7. Command Palette / Quick Actions
Что добавить:
- Быстрый поиск действий (по `Ctrl+K`):
  - открыть устройства
  - создать сценарий
  - отправить команду

Польза:
- ускоряет работу power users.

### 8. Personalizable Dashboard Layout
Что добавить:
- Drag-and-drop виджетов,
- сохранение пользовательских layout presets.

Польза:
- интерфейс подстраивается под реальный сценарий использования.

---

## Phase 3: Integration & Automation (3-5 weeks)

### 9. Telegram Assistant 2.0
Что добавить:
- Режимы webhook/polling (уже есть) +
- подписки на события (alerts/devices/scenarios),
- команды управления устройствами с ролями.

Польза:
- удаленное управление и мониторинг без входа в веб.

### 10. Rules Builder (No-Code)
Что добавить:
- Визуальный конструктор правил:
  - IF (условие)
  - THEN (действие)
  - ELSE (опционально)

Польза:
- меньше технического порога для конечного пользователя.

### 11. App Marketplace (Local Registry)
Что добавить:
- Каталог app с карточками, версией, changelog,
- установка/обновление в 1 клик.

Польза:
- расширяемость системы без ручной возни с архивами.

### 12. Event Bus for Apps
Что добавить:
- События ядра (`device.updated`, `scenario.triggered`, etc.)
- app могут подписываться на события через контракт.

Польза:
- сильная экосистема расширений.

---

## Phase 4: Reliability & Admin Experience (ongoing)

### 13. Audit Log + Activity Feed
Что добавить:
- Журнал действий:
  - кто что изменил
  - когда
  - результат

Польза:
- прозрачность и контроль.

### 14. Backup/Restore
Что добавить:
- Экспорт/импорт:
  - устройств
  - сценариев
  - dashboard
  - app settings

Польза:
- безопасные миграции и восстановление.

### 15. Health Dashboard
Что добавить:
- Статус DB, queue, cron, Telegram webhook/polling, apps.

Польза:
- быстрее находить инфраструктурные проблемы.

### 16. Guided Troubleshooting
Что добавить:
- Мастер диагностики “почему не работает”:
  - устройство offline?
  - токен невалиден?
  - webhook недоступен?

Польза:
- меньше поддержки вручную, больше self-service.

---

## Prioritized Top 10 (Recommended Start)
1. Onboarding Wizard
2. Smart Empty States
3. Preset Scenarios
4. Device Diagnostics
5. Scenario Debug Mode
6. Unified Notifications Center
7. Personalizable Dashboard Layout
8. Telegram subscriptions/events
9. Audit Log
10. Backup/Restore

---

## KPI to Track
- Time to first successful automation
- % users who connect first device on day 1
- Scenario creation success rate
- Support tickets per active user
- Integration error rate (Telegram/IoT)
- Weekly active users in dashboard

---

## Implementation Note
Лучше внедрять блоками: сначала onboarding + diagnostics + notifications, затем integrations + marketplace. Это даст быстрый эффект по usability и снизит количество ошибок пользователей.
