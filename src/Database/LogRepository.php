<?php 
namespace Bot\Database;

// Логика следующая. Мы получаем базу данных и сообщение из телеграмма. (Массив который разрезан Сплитером). 
// На основе этого массива мы готовим Insert в базу данных в определенные таблицы
class LogRepository {

    private \PDO $pdo;
    
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function saveReplayLog ($userID, string $userRequest, string $command, bool $isWhiteListed, ?string $provider, ?string $link) {
        $TABLE_NAME = "botrequestlogging";
        $sql = "INSERT INTO ".$TABLE_NAME."(userID, request, command, isWhiteListed, provider, link, created)
                VALUES (:userID, :userRequest, :command, :isWhiteListed, :provider, :link, NOW())";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(
            [
                'userID' => $userID,
                'userRequest' => $userRequest,
                'command' => $command,
                'isWhiteListed' => $isWhiteListed,
                'provider' => $provider,
                'link' => $link
            ]
        );
        
    }


    //public function 
    


}



?>