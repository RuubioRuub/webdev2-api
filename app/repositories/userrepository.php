<?php

namespace Repositories;

use Exception;
use PDO;
use PDOException;
use Repositories\Repository;

class UserRepository extends Repository
{
    function checkUsernamePassword($username, $password)
    {
        try {
            // retrieve the user with the given username
            $stmt = $this->connection->prepare("SELECT id, username, password, email, role, company FROM user WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $user = $stmt->fetch();

            // verify if the password matches the hash in the database
            $result = $this->verifyPassword($password, $user->password);

            if (!$result)
                return false;

            // do not pass the password hash to the caller
            $user->password = "";

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getAll($offset, $limit)
    {
        try {
            $query = "SELECT * FROM user ";
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');

            $users = $stmt->fetchAll();

            return $users;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function checkEmailAndUsername($email, $username)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM user WHERE email = :email OR username = :username");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $user = $stmt->fetch();

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function register($user)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into user (username, password, email, role, company) VALUES (:username, :password, :email, :role, :company)");
            $stmt->bindParam(":username", $user->username);
            $stmt->bindParam(":password", $user->password);
            $stmt->bindParam(":email", $user->email);
            $stmt->bindParam(":role", $user->role);
            $stmt->bindParam(":company", $user->company);

            $stmt->execute();

            $user->id = $this->connection->lastInsertId();
            $user->password = "";

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM user WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    // hash the password (currently uses bcrypt)
    function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // verify the password hash
    function verifyPassword($input, $hash)
    {
        return password_verify($input, $hash);
    }
}
