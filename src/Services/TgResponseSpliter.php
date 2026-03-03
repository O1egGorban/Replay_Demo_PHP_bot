<?php 
namespace Bot\Services;

class TgResponseSpliter {


    public static function split($tgResponse) {
        $text = trim($tgResponse);
        $parts = preg_split('/\s+/', $text, 2);
        return [
            'command' => $parts[0],
            'args' => $parts[1] ?? null
        ];
    }
}

?>