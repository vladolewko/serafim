<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NovaPostService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{

    protected $novaPostService;

    public function __construct(NovaPostService $novaPostService) {
        $this->novaPostService = $novaPostService;
    }
       public function signIn(Request $request)
    {
        $login = $request->post('login');
        $password = $request->post('password');

        if (User::signIn($login, $password)) {
            return redirect()->route('admin.products')->with(['success' => 'Ви успішно увійшли']);

        }
        return redirect()->back()->with(['error' => 'Неправильний логін або пароль']);
    }

    public function logOut()
    {
        Auth::logout();

        return redirect('/');
    }

    public function novaPostSetup()
    {
        return view('admin.novaPostSetup');
    }

    /**
     * Налаштування відправника
     */
    public function setupSender(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:2', 'max:50'],
                'surname' => ['required', 'string', 'min:2', 'max:50'],
                'phone' => ['required', 'string'],
                'city' => ['required', 'string', 'min:2', 'max:100']
            ]);

            $result = $this->novaPostService->setupSender($validated);

            return response()->json([
                'success' => $result,
                'message' => $result ? 'Відправник налаштований успішно' : 'Помилка налаштування відправника'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка валідації: ' . $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка при налаштуванні відправника'
            ], 500);
        }
    }

    /**
     * Перевірка статусу сервісу
     */
    public function checkStatus()
    {
        try {
            $senderStatus = $this->novaPostService->checkSenderSetup();
            $apiTest = $this->novaPostService->testApiKey();

            return response()->json([
                'api_key' => $apiTest,
                'sender_setup' => $senderStatus,
                'status' => 'ok'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'api_key' => false,
                'sender_setup' => false,
                'status' => 'error',
                'message' => 'Помилка перевірки статусу сервісу'
            ], 500);
        }
    }
}
