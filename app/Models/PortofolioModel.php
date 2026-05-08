<?php

namespace App\Models;

use PDO;
use PDOException;

class PortofolioModel
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    /**
     * Get all portfolios with images
     */
    public function getAll()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT p.id, p.title, p.description, p.category, p.is_active, p.created_at,
                       COUNT(pi.id) as image_count,
                       GROUP_CONCAT(pi.image_path ORDER BY pi.sort_order SEPARATOR '|') as images,
                       (SELECT image_path FROM portfolio_images WHERE portfolio_id = p.id AND is_primary = 1 LIMIT 1) as image_path
                FROM portfolios p
                LEFT JOIN portfolio_images pi ON p.id = pi.portfolio_id
                GROUP BY p.id
                ORDER BY p.created_at DESC
            ");
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Parse images into array
            foreach ($results as &$portfolio) {
                $portfolio['images'] = !empty($portfolio['images']) ? explode('|', $portfolio['images']) : [];
            }

            return $results;
        } catch (PDOException $e) {
            error_log("Error getting all portfolios: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get portfolio by ID with all images
     */
    public function getById($id)
    {
        try {
            // Get portfolio details
            $stmt = $this->db->prepare("
                SELECT id, title, description, category, created_at
                FROM portfolios
                WHERE id = :id
                LIMIT 1
            ");
            $stmt->execute(['id' => $id]);
            $portfolio = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($portfolio) {
                // Get all images for this portfolio
                $portfolio['images'] = $this->getImages($id);
            }

            return $portfolio;
        } catch (PDOException $e) {
            error_log("Error getting portfolio by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create new portfolio
     */
    public function create($data)
    {
        try {
            $this->db->beginTransaction();

            // Insert portfolio without image_path
            $stmt = $this->db->prepare("
                INSERT INTO portfolios (title, description, category)
                VALUES (:title, :description, :category)
            ");

            $params = [
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'category' => $data['category'] ?? null
            ];

            $stmt->execute($params);
            $portfolioId = $this->db->lastInsertId();

            // Add images if provided
            if (!empty($data['images'])) {
                foreach ($data['images'] as $index => $imagePath) {
                    $isPrimary = ($index === 0) ? 1 : 0; // First image is primary
                    $this->addImage($portfolioId, $imagePath, $isPrimary, $index);
                }
            }

            $this->db->commit();
            return $portfolioId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error creating portfolio: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update portfolio
     */
    public function update($id, $data)
    {
        try {
            $sql = "
                UPDATE portfolios
                SET title = :title,
                    description = :description,
                    category = :category
            ";

            $params = [
                'id' => $id,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'category' => $data['category'] ?? null
            ];

            $sql .= " WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);

            // Add new images if provided
            if (!empty($data['new_images'])) {
                $currentImageCount = $this->getImageCount($id);
                foreach ($data['new_images'] as $index => $imagePath) {
                    $sortOrder = $currentImageCount + $index;
                    $isPrimary = ($currentImageCount === 0 && $index === 0) ? 1 : 0;
                    $this->addImage($id, $imagePath, $isPrimary, $sortOrder);
                }
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Error updating portfolio: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete portfolio (cascade delete images)
     */
    public function delete($id)
    {
        try {
            // Get all images before deleting
            $images = $this->getImages($id);

            // Delete all image files
            foreach ($images as $image) {
                if (!empty($image['image_path']) && file_exists($image['image_path'])) {
                    unlink($image['image_path']);
                }
            }

            // Delete portfolio (images will be cascade deleted)
            $stmt = $this->db->prepare("
                DELETE FROM portfolios
                WHERE id = :id
            ");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting portfolio: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get portfolios by category with images
     */
    public function getByCategory($category)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT p.id, p.title, p.description, p.category, p.created_at,
                       (SELECT image_path FROM portfolio_images WHERE portfolio_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
                FROM portfolios p
                WHERE p.category = :category
                ORDER BY p.created_at DESC
            ");
            $stmt->execute(['category' => $category]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Add images array to each portfolio
            foreach ($results as &$portfolio) {
                $portfolio['images'] = $this->getImages($portfolio['id']);
            }

            return $results;
        } catch (PDOException $e) {
            error_log("Error getting portfolios by category: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all unique categories
     */
    public function getCategories()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT DISTINCT category
                FROM portfolios
                WHERE category IS NOT NULL
                ORDER BY category ASC
            ");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $results ?: [];
        } catch (PDOException $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get recent portfolios (limit 5)
     */
    public function getRecent($limit = 5)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT p.id, p.title, p.description, p.category, p.created_at,
                       (SELECT image_path FROM portfolio_images WHERE portfolio_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
                FROM portfolios p
                ORDER BY p.created_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Add images array
            foreach ($results as &$portfolio) {
                $portfolio['images'] = $this->getImages($portfolio['id']);
            }

            return $results;
        } catch (PDOException $e) {
            error_log("Error getting recent portfolios: " . $e->getMessage());
            return false;
        }
    }

    // ============================================
    // MULTIPLE IMAGES METHODS
    // ============================================

    /**
     * Get all images for a portfolio
     */
    public function getImages($portfolioId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, image_path, is_primary, sort_order
                FROM portfolio_images
                WHERE portfolio_id = :portfolio_id
                ORDER BY sort_order ASC
            ");
            $stmt->execute(['portfolio_id' => $portfolioId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting images: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get primary image for a portfolio
     */
    public function getPrimaryImage($portfolioId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT image_path
                FROM portfolio_images
                WHERE portfolio_id = :portfolio_id AND is_primary = 1
                LIMIT 1
            ");
            $stmt->execute(['portfolio_id' => $portfolioId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['image_path'] : null;
        } catch (PDOException $e) {
            error_log("Error getting primary image: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Add image to portfolio
     */
    public function addImage($portfolioId, $imagePath, $isPrimary = 0, $sortOrder = 0)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO portfolio_images (portfolio_id, image_path, is_primary, sort_order)
                VALUES (:portfolio_id, :image_path, :is_primary, :sort_order)
            ");

            return $stmt->execute([
                'portfolio_id' => $portfolioId,
                'image_path' => $imagePath,
                'is_primary' => $isPrimary,
                'sort_order' => $sortOrder
            ]);
        } catch (PDOException $e) {
            error_log("Error adding image: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete image from portfolio
     */
    public function deleteImage($imageId)
    {
        try {
            // Get image path before deleting
            $stmt = $this->db->prepare("
                SELECT image_path, portfolio_id
                FROM portfolio_images
                WHERE id = :id
                LIMIT 1
            ");
            $stmt->execute(['id' => $imageId]);
            $image = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($image) {
                // Delete file if exists
                if (!empty($image['image_path']) && file_exists($image['image_path'])) {
                    unlink($image['image_path']);
                }

                // Delete from database
                $stmt = $this->db->prepare("
                    DELETE FROM portfolio_images
                    WHERE id = :id
                ");
                $result = $stmt->execute(['id' => $imageId]);

                // If deleted image was primary, set a new primary
                if ($result && $image['portfolio_id']) {
                    $this->setNewPrimaryIfNeeded($image['portfolio_id']);
                }

                return $result;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Error deleting image: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Set image as primary
     */
    public function setPrimaryImage($imageId, $portfolioId)
    {
        try {
            $this->db->beginTransaction();

            // Remove primary flag from all images in this portfolio
            $stmt = $this->db->prepare("
                UPDATE portfolio_images
                SET is_primary = 0
                WHERE portfolio_id = :portfolio_id
            ");
            $stmt->execute(['portfolio_id' => $portfolioId]);

            // Set new primary
            $stmt = $this->db->prepare("
                UPDATE portfolio_images
                SET is_primary = 1
                WHERE id = :id
            ");
            $result = $stmt->execute(['id' => $imageId]);

            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error setting primary image: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update image sort order
     */
    public function updateImageOrder($imageId, $newOrder)
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE portfolio_images
                SET sort_order = :sort_order
                WHERE id = :id
            ");
            return $stmt->execute([
                'sort_order' => $newOrder,
                'id' => $imageId
            ]);
        } catch (PDOException $e) {
            error_log("Error updating image order: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get image count for portfolio
     */
    public function getImageCount($portfolioId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count
                FROM portfolio_images
                WHERE portfolio_id = :portfolio_id
            ");
            $stmt->execute(['portfolio_id' => $portfolioId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'];
        } catch (PDOException $e) {
            error_log("Error getting image count: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Set new primary image if no primary exists
     */
    private function setNewPrimaryIfNeeded($portfolioId)
    {
        try {
            // Check if portfolio has any primary image
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count
                FROM portfolio_images
                WHERE portfolio_id = :portfolio_id AND is_primary = 1
            ");
            $stmt->execute(['portfolio_id' => $portfolioId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // If no primary, set the first image as primary
            if ($result['count'] == 0) {
                $stmt = $this->db->prepare("
                    UPDATE portfolio_images
                    SET is_primary = 1
                    WHERE portfolio_id = :portfolio_id
                    ORDER BY sort_order ASC
                    LIMIT 1
                ");
                $stmt->execute(['portfolio_id' => $portfolioId]);
            }
        } catch (PDOException $e) {
            error_log("Error setting new primary: " . $e->getMessage());
        }
    }
}
