<?php
require_once __DIR__ . '/../config/db.php';

class Modification {
    
    /**
     * Get all modifications for a specific car
     * Compatible with both old and new database structure
     */
    public static function getByCar($carId) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM modifications WHERE car_id = ? ORDER BY installation_date DESC, mod_date DESC, created_at DESC");
        $stmt->execute([$carId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Alias for getByCar (for consistency with other code)
     */
    public static function getByCarId($carId) {
        return self::getByCar($carId);
    }
    
    /**
     * Get a single modification by ID
     */
    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT m.*, c.make, c.model, c.brand, c.year, c.user_id
                               FROM modifications m
                               LEFT JOIN cars c ON m.car_id = c.id
                               WHERE m.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * OLD METHOD - Add modification (simple version - kept for backward compatibility)
     * @deprecated Use create() instead
     */
    public static function add($carId, $date, $description, $cost) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO modifications (car_id, mod_date, description, cost) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$carId, $date, $description, $cost]);
    }
    
    /**
     * NEW METHOD - Create modification (full version with all fields)
     * This is the main method to use going forward
     */
    public static function create($data) {
        global $pdo;
        
        // Check which columns exist in the database
        $stmt = $pdo->query("SHOW COLUMNS FROM modifications");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Determine which fields to insert based on available columns
        $hasNewStructure = in_array('mod_type', $columns);
        
        if ($hasNewStructure) {
            // New structure with all fields
            $sql = "INSERT INTO modifications (
                        car_id, 
                        mod_type, 
                        category, 
                        description, 
                        installation_date, 
                        installation_cost, 
                        parts_cost, 
                        total_cost, 
                        status, 
                        warranty, 
                        notes,
                        created_at
                    ) VALUES (
                        :car_id, 
                        :mod_type, 
                        :category, 
                        :description, 
                        :installation_date, 
                        :installation_cost, 
                        :parts_cost, 
                        :total_cost, 
                        :status, 
                        :warranty, 
                        :notes,
                        NOW()
                    )";
            
            try {
                $stmt = $pdo->prepare($sql);
                
                $stmt->bindParam(':car_id', $data['car_id'], PDO::PARAM_INT);
                $stmt->bindParam(':mod_type', $data['mod_type'], PDO::PARAM_STR);
                $stmt->bindParam(':category', $data['category'], PDO::PARAM_STR);
                $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
                $stmt->bindParam(':installation_date', $data['installation_date'], PDO::PARAM_STR);
                $stmt->bindParam(':installation_cost', $data['installation_cost']);
                $stmt->bindParam(':parts_cost', $data['parts_cost']);
                $stmt->bindParam(':total_cost', $data['total_cost']);
                $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
                
                // Warranty can be null
                if ($data['warranty'] === null || $data['warranty'] === '') {
                    $stmt->bindValue(':warranty', null, PDO::PARAM_NULL);
                } else {
                    $stmt->bindParam(':warranty', $data['warranty'], PDO::PARAM_INT);
                }
                
                $stmt->bindParam(':notes', $data['notes'], PDO::PARAM_STR);
                
                $stmt->execute();
                
                return $pdo->lastInsertId();
            } catch (PDOException $e) {
                error_log('Database error in Modification::create: ' . $e->getMessage());
                return false;
            }
        } else {
            // Old structure - fallback to simple insert
            return self::add(
                $data['car_id'],
                $data['installation_date'] ?? date('Y-m-d'),
                $data['description'] ?? $data['mod_type'],
                $data['total_cost'] ?? 0
            );
        }
    }
    
    /**
     * Update a modification
     */
    public static function update($id, $data) {
        global $pdo;
        
        // Check which columns exist
        $stmt = $pdo->query("SHOW COLUMNS FROM modifications");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $hasNewStructure = in_array('mod_type', $columns);
        
        if ($hasNewStructure) {
            $sql = "UPDATE modifications SET
                        mod_type = :mod_type,
                        category = :category,
                        description = :description,
                        installation_date = :installation_date,
                        installation_cost = :installation_cost,
                        parts_cost = :parts_cost,
                        total_cost = :total_cost,
                        status = :status,
                        warranty = :warranty,
                        notes = :notes,
                        updated_at = NOW()
                    WHERE id = :id";
            
            try {
                $stmt = $pdo->prepare($sql);
                
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':mod_type', $data['mod_type'], PDO::PARAM_STR);
                $stmt->bindParam(':category', $data['category'], PDO::PARAM_STR);
                $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
                $stmt->bindParam(':installation_date', $data['installation_date'], PDO::PARAM_STR);
                $stmt->bindParam(':installation_cost', $data['installation_cost']);
                $stmt->bindParam(':parts_cost', $data['parts_cost']);
                $stmt->bindParam(':total_cost', $data['total_cost']);
                $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
                
                if ($data['warranty'] === null || $data['warranty'] === '') {
                    $stmt->bindValue(':warranty', null, PDO::PARAM_NULL);
                } else {
                    $stmt->bindParam(':warranty', $data['warranty'], PDO::PARAM_INT);
                }
                
                $stmt->bindParam(':notes', $data['notes'], PDO::PARAM_STR);
                
                return $stmt->execute();
            } catch (PDOException $e) {
                error_log('Database error in Modification::update: ' . $e->getMessage());
                return false;
            }
        } else {
            // Old structure update
            $sql = "UPDATE modifications SET 
                        mod_date = ?,
                        description = ?,
                        cost = ?
                    WHERE id = ?";
            
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['installation_date'] ?? date('Y-m-d'),
                $data['description'],
                $data['total_cost'] ?? 0,
                $id
            ]);
        }
    }
    
    /**
     * Delete a modification
     */
    public static function delete($id) {
        global $pdo;
        
        $sql = "DELETE FROM modifications WHERE id = ?";
        
        try {
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log('Database error in Modification::delete: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get total cost of all modifications for a car
     */
    public static function getTotalCostByCarId($carId) {
        global $pdo;
        
        // Check which cost column exists
        $stmt = $pdo->query("SHOW COLUMNS FROM modifications");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $costColumn = in_array('total_cost', $columns) ? 'total_cost' : 'cost';
        
        $sql = "SELECT SUM($costColumn) as total 
                FROM modifications 
                WHERE car_id = ?";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$carId]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log('Database error in Modification::getTotalCostByCarId: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get modifications count by status for a car
     */
    public static function getStatusCountByCarId($carId) {
        global $pdo;
        
        // Check if status column exists
        $stmt = $pdo->query("SHOW COLUMNS FROM modifications");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (!in_array('status', $columns)) {
            return [];
        }
        
        $sql = "SELECT status, COUNT(*) as count 
                FROM modifications 
                WHERE car_id = ?
                GROUP BY status";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$carId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Database error in Modification::getStatusCountByCarId: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get modifications by category for a car
     */
    public static function getByCategoryAndCarId($carId, $category) {
        global $pdo;
        
        $sql = "SELECT * FROM modifications 
                WHERE car_id = ? AND category = ?
                ORDER BY installation_date DESC, mod_date DESC";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$carId, $category]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Database error in Modification::getByCategoryAndCarId: ' . $e->getMessage());
            return [];
        }
    }
}
?>