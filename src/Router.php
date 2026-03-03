<?php 
namespace Bot;

use Bot\Services\JsonValidator;
use Bot\Services\TgResponseSpliter;
use Bot\Telegram\Commands\RoundReplay;


class Router
{
    private $telegram;
    private $logRepository;
    private $userRepository;

    public function __construct($telegram, $logRepository, $userRepository)
    {
        $this->telegram = $telegram;
        $this->logRepository = $logRepository;
        $this->userRepository = $userRepository;
    }

    public function route()
    {
        $userId = $this->telegram->getUserId();
        $TgMessage = $this->telegram->getText();
        if (!$TgMessage) {
            $this ->telegram->sendMessage($userId, 'Sorry, we could not process your request. Please send a valid command.');
            return;
        }
        $splitMessage = TgResponseSpliter::split($TgMessage);
        $comand = $splitMessage['command'];
        $args = $splitMessage['args'];
        if ($args !== null) {
            $args = str_replace("\xC2\xA0", ' ', $args);
        }
        if(!$this->userRepository->WhiteListCheck($userId)) {
            $this->telegram->sendMessage($userId, 'Sorry, you are not authorized to use this bot.');
            $this->logRepository->saveReplayLog($userId, $comand, $args, false, null, null);
            return;
        };
        if ($comand === '/round_check') {
            $validationResult = JsonValidator::validate($args);
            if (!$validationResult) {
                $this->telegram->sendMessage($userId, "Sorry, the provided data is not valid JSON. Please check your input and try again.");
                $this->logRepository->saveReplayLog($userId, $TgMessage, $comand, true, 'Invalid JSON', null);
                return;
            }
            $jsonData = json_decode($args, true);
            $providerLink = RoundReplay::validate($jsonData);
            if ($providerLink) {
                $this->telegram->sendMessage($userId, "Here is your replay link: " . $providerLink);
                $this->logRepository->saveReplayLog($userId, $TgMessage, $comand, true, 'Provider Replay', $providerLink);
            } else {
                $this->telegram->sendMessage($userId, "Sorry, we couldn't find a replay link for the provided data.");
                $this->logRepository->saveReplayLog($userId, $TgMessage, $comand, true, 'False Log', null);
            }
        } else {
            $this->telegram->sendMessage($userId, "Sorry, we couldn't recognize the command.");
            $this->logRepository->saveReplayLog($userId, $TgMessage, $comand, true, 'False Command', null);
        }
    }
}

?>
