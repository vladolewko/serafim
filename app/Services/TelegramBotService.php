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
    public function sendMessage(string $message, string $parseMode = 'HTML', int $maxRetries = 3): bool
    {
        if (!$this->validateCredentials()) {
            return false;
        }

        $targetChatId = $this->chatId;
        $attempt = 0;

        while ($attempt < $maxRetries) {
            try {
                $response = Http::timeout(30)->post("{$this->apiUrl}/sendMessage", [
                    'chat_id' => $targetChatId,
                    'text' => $message,
                    'parse_mode' => $parseMode,
                ]);

                if ($response->successful()) {
                    return $this->handleResponse($response);
                }

                // Перевіряємо чи це помилка rate limiting
                $responseData = $response->json();
                if (isset($responseData['error_code']) && $responseData['error_code'] == 429) {
                    $retryAfter = $responseData['parameters']['retry_after'] ?? pow(2, $attempt + 1);

                    Log::warning("Telegram rate limit hit. Retrying after {$retryAfter} seconds");
                    sleep($retryAfter);
                    $attempt++;
                    continue;
                }

                throw new Exception("Telegram API error: " . $response->body());

            } catch (Exception $e) {
                $attempt++;

                if (str_contains($e->getMessage(), 'Too many requests') || str_contains($e->getMessage(), '429')) {
                    if ($attempt < $maxRetries) {
                        $delay = pow(2, $attempt);
                        Log::warning("Too many requests to Telegram. Retrying in {$delay} seconds (attempt {$attempt}/{$maxRetries})");
                        sleep($delay);
                        continue;
                    }
                }

                Log::error('Failed to send Telegram message: ' . $e->getMessage());
                return false;
            }
        }

        Log::error("Failed to send Telegram message after {$maxRetries} attempts");
        return false;
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
     * Форматування повідомлення про замовлення
     */
    private function formatOrderMessage($orderData): string
    {
        $orderId = $orderData->id;
        $orderTTN = $orderData->ttn_number;
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
        $message .= "🔔 <b>ТТН №</b>" . $this->escapeHtml($orderTTN) . "\n";
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

}
