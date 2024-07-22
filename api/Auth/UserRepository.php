<?php
namespace uzziah;
// UserRepository.php
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
//use League\OAuth2\Server\Entities\UserEntity;

class UserRepository implements UserRepositoryInterface {
    public function getUserEntityByUserCredentials($username, $password, $grantType, $clientId, $scope=null) {
        // Validate user credentials and return a UserEntity
        // Assume $pdo is your PDO connection
        global $pdo;

        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $username);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            return;
        }

        $userEntity = new UserEntity();
        $userEntity->setIdentifier($user['id']);
        return $userEntity;
    }
}

// UserEntity.php


class UserEntity implements UserEntityInterface {
    private $identifier;

    public function getIdentifier() {
        return $this->identifier;
    }

    public function setIdentifier($identifier) {
        $this->identifier = $identifier;
    }
}
