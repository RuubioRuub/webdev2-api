<?php
namespace Services;

use Repositories\ReviewRepository;

class ReviewService {

    private $repository;

    function __construct()
    {
        $this->repository = new ReviewRepository();
    }

    public function getAll($offset = NULL, $limit = NULL) {
        return $this->repository->getAll($offset, $limit);
    }

    function getReviewsForSelectedGame($gameid) {
        return $this->repository->getReviewsForSelectedGame($gameid);
    }

    public function getOne($id) {
        return $this->repository->getOne($id);
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

    public function getScore($gameID, $criticreview) {
        return $this->repository->getScore($gameID, $criticreview);
    }
}

?>