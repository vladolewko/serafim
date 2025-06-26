<?php

namespace App\Services;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class TelegramBotService
{
    private string $botToken;
    private string $chatId;
    private string $apiUrl;
    private NovaPostService $novaPostService;

    public function __construct(NovaPostService $novaPostService)
    {
        $this->botToken = env('TELEGRAM_BOT_TOKEN');
        $this->chatId = env('TELEGRAM_ADMIN_CHAT_ID');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}";
        $this->novaPostService = $novaPostService;
    }

    /**
     * Перевірка налаштувань Telegram
     */
    private function validateCredentials(): bool
    {
        if (empty($this->botToken) || empty($this->chatId)) {
            Log::error('Telegram credentials not configured');
            return false;
        }
        return true;
    }

    /**
     * Загальний метод для відправки повідомлення
     */
    public function sendMessage(string $message, string $parseMode = 'HTML', ?string $chatId = null): bool
    {
        if (!$this->validateCredentials()) {
            return false;
        }

        $targetChatId = $chatId ?? $this->chatId;

        try {
            $response = Http::post("{$this->apiUrl}/sendMessage", [
                'chat_id' => $targetChatId,
                'text' => $message,
                'parse_mode' => $parseMode,
            ]);

            return $this->handleResponse($response);
        } catch (Exception $e) {
            Log::error('Failed to send Telegram message: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Відправка замовлення в Telegram
     */
    public function sendOrderToTelegram($orderData): bool
    {
        $message = $this->formatOrderMessage($orderData);
        Log::info($message);
        return $this->sendMessage($message);
    }

    /**
     * Відправка простого повідомлення про помилку
     */
    public function sendErrorNotification(string $error, ?string $context = null): bool
    {
        $message = "⚠️ <b>Помилка в системі</b>\n\n";
        $message .= "📝 <b>Опис:</b> {$error}\n";

        if ($context) {
            $message .= "🔍 <b>Контекст:</b> {$context}\n";
        }

        $message .= "\n📅 <b>Час:</b> " . date('d.m.Y H:i:s');

        return $this->sendMessage($message);
    }

    /**
     * Відправка повідомлення про новий контакт/звернення
     */
    public function sendContactMessage(array $contactData): bool
    {
        $message = $this->formatContactMessage($contactData);
        return $this->sendMessage($message);
    }

    /**
     * Форматування повідомлення про замовлення
     */
    /**
     * Форматування повідомлення про замовлення
     */
    private function formatOrderMessage($orderData): string
    {
        $orderId = $orderData->id;
        $customerName = $orderData->customer_name;
        $customerSurname = $orderData->customer_surname;
        $customerPhone = $orderData->customer_phone;
        $customerEmail = $orderData->customer_email ?? 'Не вказано';
        $paymentType = $orderData->payment_type;
        $totalAmount = $orderData->total_amount;
        $productTotal = $orderData->product_total;
        $deliveryCost = $orderData->delivery_cost;
        $productName = $orderData->cart_data['product']['name'];
        $productQuantity = $orderData->cart_data['quantity'];
        $shippingAddress = $this->novaPostService->convertNovaPoshtaWarehouseRef($orderData->warehouse_ref);

        $message = "🔔 <b>Нове замовлення #{$orderId}</b>\n\n";
        $message .= "👤 <b>Клієнт:</b> " . $this->escapeHtml($customerName . ' ' . $customerSurname) . "\n";
        $message .= "📞 <b>Телефон:</b> " . $this->escapeHtml($customerPhone) . "\n";
        $message .= "📧 <b>Email:</b> " . $this->escapeHtml($customerEmail) . "\n";
        $message .= "📍 <b>Адреса доставки:</b> " . $this->escapeHtml($shippingAddress) . "\n";
        $message .= "💳 <b>Спосіб оплати:</b> " . $this->escapeHtml($paymentType) . "\n\n";

        // Інформація про товар
        $message .= "<b>📦 Товар:</b>\n";
        $message .= "• " . $this->escapeHtml($productName) . " x{$productQuantity}\n\n";

        // Розрахунок вартості
        $message .= "<b>💰 Розрахунок:</b>\n";
        $message .= "• Товар: " . number_format($productTotal, 2) . " грн\n";
        $message .= "• Доставка: " . number_format($deliveryCost, 2) . " грн\n";
        $message .= "• <b>Загальна сума: " . number_format($totalAmount, 2) . " грн</b>\n";

        $message .= "\n📅 <b>Дата:</b> " . date('d.m.Y H:i');

        return $message;
    }

    /**
     * Форматування повідомлення про контакт
     */
    private function formatContactMessage(array $contactData): string
    {
        $name = $contactData['name'] ?? 'Не вказано';
        $email = $contactData['email'] ?? 'Не вказано';
        $phone = $contactData['phone'] ?? 'Не вказано';
        $subject = $contactData['subject'] ?? 'Загальне звернення';
        $messageText = $contactData['message'] ?? 'Повідомлення відсутнє';

        $message = "📨 <b>Нове звернення</b>\n\n";
        $message .= "👤 <b>Ім'я:</b> " . $this->escapeHtml($name) . "\n";
        $message .= "📧 <b>Email:</b> " . $this->escapeHtml($email) . "\n";
        $message .= "📞 <b>Телефон:</b> " . $this->escapeHtml($phone) . "\n";
        $message .= "📋 <b>Тема:</b> " . $this->escapeHtml($subject) . "\n\n";
        $message .= "💬 <b>Повідомлення:</b>\n" . $this->escapeHtml($messageText) . "\n\n";
        $message .= "📅 <b>Дата:</b> " . date('d.m.Y H:i');

        return $message;
    }

    /**
     * Екранування HTML символів для безпеки
     */
    private function escapeHtml(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Обробка відповіді від Telegram API
     */
    private function handleResponse($response): bool
    {
        $result = $response->json();

        if (!isset($result['ok']) || $result['ok'] !== true) {
            Log::error('Telegram API error: ' . json_encode($result));
            return false;
        }

        return true;
    }

    /**
     * Перевірка підключення до бота
     */
    public function testConnection(): bool
    {
        if (!$this->validateCredentials()) {
            return false;
        }

        try {
            $response = Http::get("{$this->apiUrl}/getMe");
            return $this->handleResponse($response);
        } catch (Exception $e) {
            Log::error('Failed to test Telegram connection: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Отримання інформації про бота
     */
    public function getBotInfo(): ?array
    {
        if (!$this->validateCredentials()) {
            return null;
        }

        try {
            $response = Http::get("{$this->apiUrl}/getMe");
            $result = $response->json();

            if (isset($result['ok']) && $result['ok'] === true) {
                return $result['result'];
            }

            return null;
        } catch (Exception $e) {
            Log::error('Failed to get bot info: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Відправка повідомлення з можливістю додавання клавіатури
     */
    public function sendMessageWithKeyboard(string $message, array $keyboard, ?string $chatId = null): bool
    {
        if (!$this->validateCredentials()) {
            return false;
        }

        $targetChatId = $chatId ?? $this->chatId;

        try {
            $response = Http::post("{$this->apiUrl}/sendMessage", [
                'chat_id' => $targetChatId,
                'text' => $message,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $keyboard
                ])
            ]);

            return $this->handleResponse($response);
        } catch (Exception $e) {
            Log::error('Failed to send Telegram message with keyboard: ' . $e->getMessage());
            return false;
        }
    }
}
