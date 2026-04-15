<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Level;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $title = 'Master Data User';

        $users = User::with(['level'])->latest()->get();

        return view('users.index', compact('users', 'title'));
    }


    public function create(Request $request)
    {
        $title = 'Tambah User';
        $levels = Level::all();

        return view('users.create', compact('levels', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'id_level' => 'required|exists:levels,id'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'id_level' => $request->id_level
        ]);

        return redirect()->route('users.index')->with('success', 'Data berhasil ditambah!');
    }

    public function edit(string $id)
    {
        $title = "Edit User";
        $user = User::find($id);
        $levels = Level::all();
        return view('users.edit', compact('title', 'user', 'levels'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8',
            'id_level' => 'required|exists:levels,id'
        ]);
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->id_level = $request->id_level;
        if ($request->password) {
            $user->password = $request->password;
        }
        $user->save();
        return redirect()->route('users.index')->with('success', 'Data berhasil ditambah!');
    }

    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return view('users.index');
    }
}
