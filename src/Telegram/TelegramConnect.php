<?php 
namespace Bot\Telegram;


class TelegramConnect
{
    private $token;
    private $apiUrl;
    private array $update;
    public function __construct($token, array $update)
    {
        $this->token = $token;
        $this->apiUrl = 'https://api.telegram.org/bot' . $token;
        $this->update = $update;

    }
    public function getUserId()
    {
        return $this->update['message']['from']['id'] ?? null;
    }

    public function getText()
    {
        return $this->update['message']['text'] ?? null;
    }

    public function sendMessage($chatId, $text)
    {
        $url = $this->apiUrl . '/sendMessage';
        $data = [
            'chat_id' => $chatId,
            'text' => $text
        ];
        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ],
        ];
        $context  = stream_context_create($options);
        file_get_contents($url, false, $context);
    }
}
?>