<?php

namespace Controllers;

use Exception;
use Services\GameService;

class GameController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new GameService();
    }

    public function getAll()
    {
        // Checks for a valid jwt, returns 401 if none is found
        // $token = $this->checkForJwt();
        // if (!$token)
        //     return;

        

        $games = $this->service->getAll();

        $this->respond($games);
    }

    public function getSelectedGame($id)
    {
        $category = $this->service->getSelectedGame($id);

        // we might need some kind of error checking that returns a 404 if the product is not found in the DB
        if (!$category) {
            $this->respondWithError(404, "Category not found");
            return;
        }

        $this->respond($category);
    }

    public function create()
    {
        try {
            $category = $this->createObjectFromPostedJson("Models\\Category");
            $this->service->insert($category);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($category);
    }

    public function update($id)
    {
        try {
            $category = $this->createObjectFromPostedJson("Models\\Category");
            $this->service->update($category, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($category);
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
}
