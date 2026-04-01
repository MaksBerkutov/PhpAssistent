<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ManageUsersCommand extends Command
{
    /**
     * Название команды для вызова
     *
     * @var string
     */
    protected $signature = 'users:manage
                            {--make-admin= : Сделать пользователя админом (ID)}
                            {--remove-admin= : Убрать права админа (ID)}
                            {--delete= : Удалить пользователя (ID)}';

    /**
     * Описание команды
     *
     * @var string
     */
    protected $description = 'Управление пользователями: просмотр, выдача и снятие прав администратора, удаление.';

    /**
     * Выполнение команды
     */
    public function handle()
    {
        $activeOptions = collect($this->options())
            ->filter(fn($value) => !is_null($value) && $value !== false);

        if ($activeOptions->count() > 1) {
            $this->error('⚠️ Можно указать только одну опцию за раз.');
            return Command::INVALID;
        }

        if ($activeOptions->isEmpty()) {
            return $this->listUsers();
        }

        [$option, $value] = $activeOptions->mapWithKeys(fn($v, $k) => [$k => $v])->first(function ($v, $k) {
            return true;
        }) ? [$activeOptions->keys()->first(), $activeOptions->first()] : [null, null];

        $method = Str::camel($option);

        if (!method_exists($this, $method)) {
            $this->error("Метод для опции '--{$option}' не найден ({$method}()).");
            return Command::FAILURE;
        }

        return $this->{$method}($value);
    }

    /**
     * Список пользователей
     */
    protected function listUsers()
    {
        $users = User::select('id', 'name', 'role')->get();

        if ($users->isEmpty()) {
            $this->warn('Пользователей не найдено.');
            return Command::SUCCESS;
        }

        $this->table(['ID', 'Имя', 'Роль'], $users->map(function ($u) {
            return [$u->id, $u->name, $u->role ?? '—'];
        })->toArray());

        return Command::SUCCESS;
    }

    protected function makeAdmin($id)
    {
        return $this->updateRole($id, 'admin', 'Пользователь теперь администратор.');
    }

    protected function removeAdmin($id)
    {
        return $this->updateRole($id, 'user', 'Пользователь больше не администратор.');
    }

    protected function delete($id)
    {
        $user = User::find($id);

        if (!$user) {
            $this->error("Пользователь с ID {$id} не найден.");
            return Command::FAILURE;
        }

        if (!$this->confirm("Удалить пользователя {$user->name}?")) {
            $this->info("Операция отменена.");
            return Command::SUCCESS;
        }

        $user->delete();
        $this->info("🗑 Пользователь {$user->name} удалён.");
        return Command::SUCCESS;
    }
    /**
     * Вспомогательный метод для изменения роли
     */
    protected function updateRole($id, $role, $message)
    {
        $user = User::find($id);

        if (!$user) {
            $this->error("Пользователь с ID {$id} не найден.");
            return Command::FAILURE;
        }

        $user->role = $role;
        $user->save();

        $this->info("✅ {$user->name}: {$message}");
        return Command::SUCCESS;
    }

}
