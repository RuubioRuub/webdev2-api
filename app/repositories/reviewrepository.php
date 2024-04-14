<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;

class ReviewRepository extends Repository
{
    function getAll($offset = NULL, $limit = NULL)
    {
        try {
            $query = "SELECT * FROM review ";
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Review');
            $reviews = $stmt->fetchAll();

            return $reviews;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function getReviewsForSelectedGame($gameid)
    {
        $stmt = $this->connection->prepare("SELECT * FROM review WHERE gameID = :id ORDER BY reviewID DESC ");
        $stmt->bindParam(':id', $gameid);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Review');
        $reviews = $stmt->fetchAll();
        

        return $reviews;
    }

    function getOne($id)
    {
        try {
            $query = "SELECT * FROM review WHERE reviewID = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Review');
            $review = $stmt->fetch();

            return $review;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function insert($review)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into review (criticreview, gameID, writer, company, body, score, title) VALUES (?,?,?,?,?,?,?)");

            $stmt->execute([$review->criticreview, $review->gameID, $review->writer, $review->company, $review->body, $review->score, $review->title]);

            $review->reviewID = $this->connection->lastInsertId();

            return $this->getOne($review->reviewID);
        } catch (PDOException $e) {
            echo $e;
        }
    }


    function update($review, $id)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE review SET body = ?, score = ?, title = ? WHERE reviewID = ?");

            $stmt->execute([$review->body, $review->score, $review->title, $id]);

            return $this->getOne($review->reviewID);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM review WHERE reviewID = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return;
        } catch (PDOException $e) {
            echo $e;
        }
        return true;
    }

    public function getScore($gameID, $criticreview)
    {
        try {
        $stmt = $this->connection->prepare("SELECT AVG(score) FROM review WHERE criticreview = :critic AND gameID = :id");
        if ($criticreview)
            $stmt->bindValue(':critic', 1);
        else
            $stmt->bindValue(':critic', 0);

        $stmt->bindvalue(':id', $gameID);
        $stmt->execute();

        $score = $stmt->fetchAll();

        if (!$score || empty($score) || $score == null)
            return null;

        return $score[0][0];    
        } catch(PDOException $e) {
            echo $e;
        }
    }
}
