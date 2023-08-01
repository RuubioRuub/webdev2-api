<?php
namespace Services;

use Repositories\GameRepository;

class GameService {

    private $repository;

    function __construct()
    {
        $this->repository = new GameRepository();
    }

    public function getAll($offset = NULL, $limit = NULL) {
        return $this->repository->getAll($offset, $limit);
    }

    public function getSelectedGame($id) {
        return $this->repository->getSelectedGame($id);
    }

    public function insert($item) {       
        return $this->repository->insert($item);        
    }

    public function update($item, $id) {       
        return $this->repository->update($item, $id);        
    }

    public function delete($item) {       
        return $this->repository->delete($item);        
    }
}

?>