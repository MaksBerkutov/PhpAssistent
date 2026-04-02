@extends('layouts.menu')
@section('title', __('ui.accounts.page_title'))

@section('styles')
    <style>
        .accounts-shell {
            display: grid;
            gap: 14px;
        }

        .accounts-head p {
            margin: 6px 0 0;
            color: var(--ink-body);
            max-width: 72ch;
        }

        .accounts-table-wrap {
            overflow-x: auto;
            max-width: 100%;
        }

        .accounts-role-form {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 250px;
        }

        .accounts-role-form .form-select {
            min-width: 140px;
        }

        .accounts-email {
            max-width: 320px;
            overflow-wrap: anywhere;
        }

        .accounts-danger-form {
            margin: 0;
        }

        @media (max-width: 720px) {
            .accounts-role-form {
                min-width: 0;
                flex-direction: column;
                align-items: stretch;
            }

            .accounts-role-form .btn,
            .accounts-danger-form .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <section class="accounts-shell">
        <header class="accounts-head">
            <h2 class="page-title mb-0">{{ __('ui.accounts.title') }}</h2>
            <p>{{ __('ui.accounts.subtitle') }}</p>
        </header>

        <article class="card">
            <div class="card-header d-flex justify-content-between align-items-center gap-2">
                <strong>{{ __('ui.accounts.users') }}</strong>
                <span class="chip">{{ __('ui.accounts.total', ['count' => $users->total()]) }}</span>
            </div>
            <div class="card-body p-0">
                <div class="accounts-table-wrap">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th>{{ __('ui.accounts.name') }}</th>
                                <th>{{ __('ui.accounts.email') }}</th>
                                <th style="width: 320px;">{{ __('ui.accounts.role') }}</th>
                                <th style="width: 150px;">{{ __('ui.accounts.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $item)
                                <tr>
                                    <td>#{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td class="accounts-email">{{ $item->email }}</td>
                                    <td>
                                        <form class="accounts-role-form" method="POST" action="{{ route('accounts.role', $item) }}">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" class="form-select form-select-sm">
                                                <option value="admin" @selected($item->role === 'admin')>{{ __('ui.accounts.role_admin') }}</option>
                                                <option value="user" @selected($item->role === 'user')>{{ __('ui.accounts.role_user') }}</option>
                                                <option value="blocked" @selected($item->role === 'blocked')>{{ __('ui.accounts.role_blocked') }}</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-primary">{{ __('ui.accounts.save') }}</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form class="accounts-danger-form" method="POST" action="{{ route('accounts.destroy', $item) }}"
                                            onsubmit="return confirm('{{ __('ui.accounts.delete_confirm', ['name' => addslashes($item->name)]) }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('ui.accounts.delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">{{ __('ui.accounts.empty') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        </article>
    </section>
@endsection
