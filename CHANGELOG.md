# Changelog

## 2026-04-02

### Added
- Admin-only account manager section.
- New `AccountManagerController` with user listing, role updates, and account deletion.
- Admin routes for account management:
  - `GET /accounts`
  - `PATCH /accounts/{user}/role`
  - `DELETE /accounts/{user}`
- New view: `resources/views/accounts/index.blade.php`.
- New i18n keys in `lang/en/ui.php` for accounts module and navigation item.

### Changed
- Sidebar navigation now includes an admin-only Accounts entry via translation key (`ui.nav.accounts`).
- Accounts page switched fully to translation keys (`ui.accounts.*`).
- Account manager controller messages switched to translation keys.
- Russian locale file updated by user.

### Notes
- Preserved existing role safety checks:
  - cannot downgrade own account from admin;
  - cannot delete own account;
  - cannot remove/downgrade the last admin.
