<?php

namespace Apps;

abstract class BaseAppServiceProvider
{
    abstract public static function getSchema(): array;

    public function registerRoutes(): void
    {
        // Optional app route registration.
    }

    public function registerWidgets(): void
    {
        // Optional widget registration.
    }

    /**
     * Sidebar items provided by app.
     *
     * Item format:
     * - label: string (required)
     * - url: string (required if route is not set)
     * - route: string (optional route name)
     * - route_params: array (optional route params)
     * - image: string (optional ionicon name)
     * - guard: string (optional role filter, e.g. "admin|user")
     *
     * @return array<int, array<string, mixed>>
     */
    public function menuItems(): array
    {
        return [];
    }

    public function boot(): void
    {
        // Optional app boot logic.
    }

    abstract public function render(array $data = []);
}
