<?php

namespace App\Http\Controllers\TaskFour;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UsersApi extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'DESC')->paginate(5);
        foreach ($users as $user) {
            $user->avatar = url(Storage::url('users/' . $user->avatar));
        }
        if ($users->count()) {
            return response()->json($users->toArray());
        }
        return response()->json(['message' => 'Users was not found'], 404);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|min:6',
            'avatar' => 'required|image|dimensions:min_width=50,min_height=50,ratio=1/1'
        ]);
        $imageName = str_random(32) . '.' . $validatedData['avatar']->extension();
        $imageSaved = Storage::disk('public')->put(
            'users/' . $imageName,
            file_get_contents($validatedData['avatar'])
        );

        if ($imageSaved) {
            $validatedData['avatar'] = $imageName;
            $user = User::create($validatedData);
            if ($user) {
                return response()->json([
                    'success' => true,
                    'message' => 'User successfully created'
                ]);
            } else {
                Storage::disk('public')->delete('users/' . $imageName);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Error creating user'
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users',
            'phone' => 'phone|min:6',
            'avatar' => 'image|dimensions:min_width=50,min_height=50,ratio=1/1'
        ]);
        $imageName = str_random(32) . '.' . $validatedData['avatar']->extension();
        $imageSaved = Storage::disk('public')->put(
            'users/' . $imageName,
            file_get_contents($validatedData['avatar'])
        );

        /** @var User $user */
        $user = User::find($id);
        if ($imageSaved && $user) {
            $validatedData['avatar'] = $imageName;
            $userUpdated = $user->update($validatedData);
            if ($userUpdated) {
                return response()->json([
                    'success' => true,
                    'message' => 'User successfully updated'
                ]);
            } else {
                Storage::disk('public')->delete('users/' . $imageName);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Error updating user'
        ], 400);
    }

    public function delete($id)
    {
        /** @var User $user */
        $user = User::find($id);
        if($user) {
            $userDeleted = $user->delete();
            if ($userDeleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'User successfully deleted'
                ]);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'User successfully deleted'
        ]);
    }
}
