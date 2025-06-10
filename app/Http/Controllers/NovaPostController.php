<?php

namespace App\Http\Controllers;

use App\Services\NovaPostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class NovaPostController extends Controller
{
    protected $novaPostService;

    public function __construct(NovaPostService $novaPostService)
    {
        $this->novaPostService = $novaPostService;
    }

    public function searchSettlement(Request $request)
    {
        $search = $request->input('search');
        $settlements = $this->novaPostService->searchSettlement($search);

        if (empty($settlements)) {
            return view('orders.create', [
                'error' => 'Немає населених пунктів, що відповідають запиту',
            ]);
        }

        Session::put('nova_post_data', ['search' => $search]);

        return view('orders.create', [
            'addressData' => ['search' => $search],
            'settlements' => $settlements,
        ]);
    }

    public function chooseSettlement(Request $request)
    {
        $settlementRef = $request->input('settlement');
        $data = Session::get('nova_post_data', []);
        $data['settlement'] = $settlementRef;
        Session::put('nova_post_data', $data);

        $warehouses = $this->novaPostService->getWarehouses($settlementRef);
        $settlements = $this->novaPostService->searchSettlement($data['search']);

        if (empty($warehouses)) {
            return view('orders.create', [
                'error' => 'Немає відділень, що відповідають запиту',
            ]);
        }

        return view('orders.create', [
            'addressData' => $data,
            'settlements' => $settlements,
            'warehouses' => $warehouses,
        ]);
    }

    public function setWarehouse(Request $request)
    {
//        dd($this->novaPostService->setupSender(['city' => 'Хмельницький', 'name' => 'Владислав', 'surname' => 'Олешко', 'phone' => 380960613008]));
        $warehouseRef = $request->input('warehouse');
        $data = Session::get('nova_post_data', []);
        $data['warehouse'] = $warehouseRef;
        Session::put('nova_post_data', $data);

        $settlements = $this->novaPostService->searchSettlement($data['search']);
        $warehouses = $this->novaPostService->getWarehouses($data['settlement']);

        return view('orders.create', [
            'addressData' => $data,
            'settlements' => $settlements,
            'warehouses' => $warehouses,
        ]);
    }

    public function createCounterparty(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'phone' => 'required|string|max:25',
            ]);

            $data = Session::get('nova_post_data', []);

            if (empty($data['settlement']) || empty($data['warehouse'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Не обрано населений пункт або відділення'
                ]);
            }

            // Створюємо контрагента
            $counterparty = $this->novaPostService->createCounterparty($validated);

            // Створюємо ТТН
            $ttn = $this->novaPostService->createTTN([
                'settlement' => $data['settlement'],
                'warehouse' => $data['warehouse'],
                'counterparty_ref' => $counterparty['Ref'],
                'phone' => $validated['phone'],
                'name' => $validated['name'],
                'surname' => $validated['surname'],
            ]);

            Session::forget('nova_post_data');

            return response()->json([
                'success' => true,
                'ttn_number' => $ttn['IntDocNumber'] ?? $ttn['Number'] ?? 'Невідомий номер',
                'message' => 'ТТН успішно створено'
            ], 200, [], JSON_UNESCAPED_UNICODE);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка валідації: ' . implode(', ', $e->validator->errors()->all())
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating TTN in controller', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function checkStatus()
    {
        $senderStatus = $this->novaPostService->checkSenderSetup();
        $apiTest = $this->novaPostService->testApiKey();

        return response()->json([
            'api_key' => $apiTest,
            'sender_setup' => $senderStatus
        ]);
    }

    public function testApi()
    {
        $result = $this->novaPostService->testApiKey();

        return response()->json($result);
    }

    public function setupSender(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'phone' => 'required|string|max:25',
            'city' => 'required|string|max:255',
        ]);

        $result = $this->novaPostService->setupSender($validated);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Відправник налаштований успішно' : 'Помилка налаштування відправника'
        ]);
    }
}
