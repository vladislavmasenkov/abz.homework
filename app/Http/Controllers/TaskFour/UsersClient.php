<?php

namespace App\Http\Controllers\TaskFour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class UsersClient extends Controller
{
    public function index()
    {
        return view('taskfour.index');
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

        $client = new Client(['base_uri' => 'http://192.168.10.10/api/v1/']);
        try {
            $result = $client->request('POST', 'users/create/', [
                'form_params' => $validatedData,
            ]);
            dd(json_decode($result->getBody()));
            return response()->json(json_decode($result->getBody()), $result->getStatusCode());
        } catch (GuzzleException $e) {
            return response()->json(['success' => 'false', 'message' => $e->getMessage()], 400);
        }
    }

    public function getUsers()
    {
        $client = new Client(['base_uri' => 'http://192.168.10.10/api/v1/']);

        try {
            $result = $client->request('GET', 'users', [
                'verify' => false,
                'query' => ['page' => \request()->get('page')],
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic Rlkts87G85OhVsyRvZhn6glatIMRnBdmKrjxBvkMrqQ0aIDy2RzlaFSgTwDp',
                    'X-CSRF-TOKEN' => csrf_token()
                ],
            ]);
            return response()->json(json_decode($result->getBody()), $result->getStatusCode());
        } catch (GuzzleException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
