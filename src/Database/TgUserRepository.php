<?php
namespace Bot\Database;
use PDO;
class TgUserRepository
{
    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function WhiteListCheck($user) : bool{
        $sql = "SELECT 1 FROM user_list WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $user]);
        return (bool) $stmt->fetchColumn();
    }
}

?>