<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;

class GameRepository extends Repository
{
    function getAll($offset = NULL, $limit = NULL)
    {
        try {
            $query = "SELECT * FROM game";
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Game');
            $articles = $stmt->fetchAll();

            return $articles;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function getSelectedGame($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM game WHERE gameID = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Game');
            $game = $stmt->fetch();

            return $game;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function insert($game)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into game (title, publisher, genre, description, image) VALUES (:title, :publisher, :genre, :description, :image)");
            $stmt->bindParam(":title", $game->title);
            $stmt->bindParam(":publisher", $game->publisher);
            $stmt->bindParam(":genre", $game->genre);
            $stmt->bindParam(":description", $game->description);
            $stmt->bindParam(":image", $game->image);

            $stmt->execute();

            $game->id = $this->connection->lastInsertId();

            return $game;
        } catch (PDOException $e) {
            echo $e;
        }
    }


    function update($category, $id)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE category SET name = ? WHERE id = ?");

            $stmt->execute([$category->name, $id]);

            return $category;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM game WHERE gameID = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return;
        } catch (PDOException $e) {
            echo $e;
        }
    }
}
