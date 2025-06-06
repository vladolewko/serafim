<?php

namespace App\Http\Controllers;

use App\Services\NovaPostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SenderSetupController extends Controller
{
    protected $novaPostService;

    public function __construct(NovaPostService $novaPostService)
    {
        $this->novaPostService = $novaPostService;
    }

    /**
     * Показати форму створення відправника
     */
    public function showSetupForm()
    {
        // Спробуємо отримати існуючих відправників
        $senders = $this->novaPostService->getSenders();
        
        return view('admin.sender-setup', compact('senders'));
    }

    /**
     * Створити нового відправника
     */
    public function createSender(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'owner_first_name' => 'required|string|max:255',
            'owner_last_name' => 'required|string|max:255',
            'owner_middle_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:25',
            'email' => 'required|email|max:255',
            'edrpou' => 'nullable|string|max:10',
        ]);

        try {
            // Створюємо відправника
            $sender = $this->novaPostService->createSender(
                $data['company_name'],
                $data['owner_first_name'],
                $data['owner_last_name'],
                $data['owner_middle_name'],
                $data['phone'],
                $data['email'],
                $data['edrpou']
            );

            if (empty($sender)) {
                return back()->withErrors(['error' => 'Помилка створення відправника']);
            }

            $senderRef = $sender[0]['Ref'];

            // Створюємо контактну особу
            $contactPerson = $this->novaPostService->createSenderContactPerson(
                $senderRef,
                $data['owner_first_name'],
                $data['owner_last_name'],
                $data['owner_middle_name'],
                $data['phone']
            );

            // Зберігаємо дані відправника в .env або базі даних
            $this->saveSenderToEnv($senderRef, $contactPerson[0]['Ref']);

            return redirect()->back()->with('success', 'Відправника успішно створено! Ref: ' . $senderRef);

        } catch (\Exception $e) {
            Log::error('Помилка створення відправника: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Показати існуючих відправників та їх дані
     */
    public function showSenderDetails($senderRef)
    {
        try {
            $contactPersons = $this->novaPostService->getSenderContactPersons($senderRef);
            $addresses = $this->novaPostService->getSenderAddresses($senderRef);

            return view('admin.sender-details', [
                'senderRef' => $senderRef,
                'contactPersons' => $contactPersons,
                'addresses' => $addresses
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Зберегти дані відправника (простий варіант - в .env)
     */
    private function saveSenderToEnv($senderRef, $contactPersonRef)
    {
        // Простий спосіб - вивести дані для ручного додавання в .env
        Log::info('Дані відправника для .env:', [
            'NOVA_POST_SENDER_REF' => $senderRef,
            'NOVA_POST_CONTACT_SENDER_REF' => $contactPersonRef
        ]);

        // Або можете використати пакет для автоматичного оновлення .env
        // Але краще зберігати в базі даних для production
    }

    /**
     * Налаштувати адресу відправника (відділення)
     */
    public function setupSenderWarehouse(Request $request)
    {
        $data = $request->validate([
            'sender_ref' => 'required|string',
            'settlement' => 'required|string',
            'warehouse' => 'required|string',
        ]);

        try {
            // Тут можете зберегти адресу відправника
            // Наразі просто логуємо для ручного додавання в код
            Log::info('Дані відділення відправника:', [
                'NOVA_POST_SENDER_CITY_REF' => $data['settlement'],
                'NOVA_POST_SENDER_WAREHOUSE_REF' => $data['warehouse']
            ]);

            return back()->with('success', 'Адресу відправника налаштовано! Перевірте логи для отримання Ref-ів.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}