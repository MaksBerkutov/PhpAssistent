<?php

namespace Apps;

abstract class BaseAppServiceProvider
{
    /**
     * Схема настроек аппса
     * @return array
     */
    abstract public static function getSchema(): array;

    /**
     * Зарегистрировать маршруты аппса
     */
    public function registerRoutes(): void
    {
        // По умолчанию ничего не делает, можно переопределить в конкретном App
    }

    /**
     * Зарегистрировать виджеты аппса
     */
    public function registerWidgets(): void
    {
        // По умолчанию ничего не делает
    }

    public function boot(): void
    {
        // По умолчанию ничего не делает
    }


    /**
     * Рендер интерфейса аппса (для админки)
     */
    abstract public function render(array $data = []);
}
