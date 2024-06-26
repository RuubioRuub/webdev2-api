<?php
namespace Services;

use Repositories\UserRepository;

class UserService {

    private $repository;

    function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function checkUsernamePassword($username, $password) {
        return $this->repository->checkUsernamePassword($username, $password);
    }

    public function getAll($offset, $limit) {
        return $this->repository->getAll($offset, $limit);
    }

    public function register($user) {
        return $this->repository->register($user);
    }

    public function delete($id) {
        $this->repository->delete($id);
    }

    public function checkEmailAndUsername($email, $username) {
        return $this->repository->checkEmailAndUsername($email, $username);
    }
}

?>