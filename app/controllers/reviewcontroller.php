<?php

namespace Controllers;

use Exception;
use PDOException;
use Services\ReviewService;

class ReviewController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new ReviewService();
    }

    public function getAll()
    {
        $offset = NULL;
        $limit = NULL;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        $reviews = $this->service->getAll($offset, $limit);

        $this->respond($reviews);
    }

    public function getReviewsForSelectedGame($gameid) {
        $reviews = $this->service->getReviewsForSelectedGame($gameid);

        $this->respond($reviews);
    }

    public function getOne($id)
    {
        $product = $this->service->getOne($id);

        // we might need some kind of error checking that returns a 404 if the product is not found in the DB
        if (!$product) {
            $this->respondWithError(404, "Product not found");
            return;
        }

        $this->respond($product);
    }

    public function create()
    {
        try {
            $review = $this->createObjectFromPostedJson("Models\\Review");
            $review = $this->service->insert($review);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($review);
    }

    public function update($id)
    {
        try {
            $review = $this->createObjectFromPostedJson("Models\\Review");
            $review = $this->service->update($review, $id);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($review);
    }

    public function delete($id)
    {
        try {
            $this->service->delete($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond(true);
    }

    public function getScore($gameID) {
        try {
            $averageScores = array();

            $userscore = $this->service->getScore($gameID, false);
            $criticscore = $this->service->getScore($gameID, true);
            
            array_push($averageScores, $userscore);
            array_push($averageScores, $criticscore);

            $this->respond($averageScores);
        } catch(PDOException $e) {
            $this->respondWithError(500, $e);
        }
    }
}
