<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $status = $request->enum('status', UserStatus::class);
        $searchColumn = $request->query('search_column');
        $searchValue = $request->query('search_value');
        $sort = $request->query('sort');

        // Get the query builder
        $query = User::query();

        // Status
        $query->status($status);

        // Search
        $validSearchColumns = ['name', 'email'];

        $query->searchWildcard($searchValue, in_array($searchColumn, $validSearchColumns) ? $searchColumn : null);

        // Sort
        $validSortColumns = ['id', 'name', 'email', 'last_online_at'];

        $query->resolveSortString($sort, 'id', 'desc', $validSortColumns);

        // Get the result
        $columns = ['id', 'name', 'email', 'email_verified_at', 'last_online_at', 'banned_at'];

        $users = $query->select($columns)
            ->paginate(25)
            ->withIndexPathAndQueryString()
            ->appendAttributes('status')
            ->hideAttributes('email_verified_at', 'banned_at');

        return $request->wantsJson()
            ? $users
            : view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return $this->edit(new User());
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());

        return redirect(route('admin.users.index'))
            ->with('item_id', $user->id)
            ->withSuccessNotification(__('User :name has been added successfully.', ['name' => e($user->name)]));
    }

    /**
     * Display the specified user.
     */
    public function show(Request $request, User $user)
    {
        return $request->wantsJson()
            ? $user
            : view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());

        return redirect()->backOrIndex()
            ->with('item_id', $user->id)
            ->withSuccessNotification(__('User :name has been updated successfully.', ['name' => e($user->name)]));
    }

    /**
     * Activate the specified user.
     */
    public function activate(User $user)
    {
        abort_if($user->email_verified_at, 422, __('The user is already activated.'));

        $user->email_verified_at = now();
        $user->save();

        event(new Verified($user));
    }

    /**
     * Ban the specified user.
     */
    public function ban(User $user)
    {
        abort_if($user->isBanned(), 422, __('The user is already banned.'));

        $user->ban();
    }

    /**
     * Unban the specified user.
     */
    public function unban(User $user)
    {
        abort_if($user->isNotBanned(), 422, __('The user is already unbanned.'));

        $user->unban();
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $user->delete();

        return $this->index($request);
    }
}
