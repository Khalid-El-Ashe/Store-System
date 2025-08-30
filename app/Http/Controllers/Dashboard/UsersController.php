<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('users.view'); // i need to check if the user has the ability to view users or not
        $users = User::paginate();
        return view('dashboard.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('users.create');
        $roles = Role::all();
        $user = new User();
        return view('dashboard.users.create', compact('roles', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'roles' => 'required|array',
        ]);
        $user = User::create($request->all());
        $user->roles()->attach($request->roles); // in here i need using the

        return redirect()->route('dashboard.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        Gate::authorize('users.update');
        $roles = Role::all();
        $user_roles = $user->roles->pluck('id')->toArray();

        return view('dashboard.users.edit', compact('roles', 'user', 'user_roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'roles' => 'required|array',
        ]);

        $user->update($request->all());
        $user->roles()->sync($request->roles); // in here i need using the relation to sync roles to the user (roles is fun in User model many to many)

        return redirect()->route('dashboard.users.index')->with('success', 'User updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('users.delete');
        User::destroy($id);
        return redirect()->route('dashboard.users.index')->with('success', 'User deleted successfully.');
    }
}
