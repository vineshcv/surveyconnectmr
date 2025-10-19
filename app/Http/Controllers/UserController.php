<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-user|edit-user|delete-user', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-user', ['only' => ['store']]);
        $this->middleware('permission:edit-user', ['only' => ['update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    }

    public function index(): View
    {
        return view('users.index', [
            'roles' => Role::pluck('name', 'id')->all(),
            'users' => User::latest('id')->paginate(20),
        ]);
    }

    public function show(User $user): JsonResponse
    {
        $user->load('roles');
        return response()->json($user);
    }

    public function store(StoreUserRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);

        $user = User::create($input);
        $user->assignRole($request->roles);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'New user added successfully.', 'user' => $user]);
        }

        return redirect()->route('users.index')->withSuccess('New user added successfully.');
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $input = $request->all();
        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password);
        } else {
            $input = $request->except('password');
        }
        $user->update($input);
        $user->syncRoles($request->roles);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'User updated successfully.', 'user' => $user]);
        }

        return redirect()->route('users.index')->withSuccess('User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('Super Admin') || $user->id == auth()->user()->id) {
            abort(403, 'You do not have permission to delete this user.');
        }

        $user->syncRoles([]);
        $user->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        }

        return redirect()->route('users.index')->withSuccess('User deleted successfully.');
    }
}
