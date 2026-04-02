<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountManagerController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->orderByRaw("CASE role WHEN 'admin' THEN 0 WHEN 'user' THEN 1 WHEN 'blocked' THEN 2 ELSE 3 END")
            ->orderBy('name')
            ->paginate(15);

        return view('accounts.index', compact('users'));
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', 'in:admin,user,blocked'],
        ]);

        if ((int) $request->user()->id === (int) $user->id && $data['role'] !== 'admin') {
            return back()->with('error', __('ui.accounts.errors.self_downgrade'));
        }

        if ($user->role === 'admin' && $data['role'] !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', __('ui.accounts.errors.last_admin_required'));
        }

        $user->role = $data['role'];
        $user->save();

        return back()->with('success', __('ui.accounts.success.role_updated'));
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ((int) $request->user()->id === (int) $user->id) {
            return back()->with('error', __('ui.accounts.errors.self_delete'));
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', __('ui.accounts.errors.last_admin_delete'));
        }

        $user->delete();

        return back()->with('success', __('ui.accounts.success.deleted'));
    }
}
