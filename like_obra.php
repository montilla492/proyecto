<?php
require_once "conexion.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $obra_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    if ($obra_id > 0) {
        try {
            //likes 
            $stmt = $pdo->prepare("UPDATE obras SET likes = likes + 1 WHERE id = :id");
            $stmt->execute([':id' => $obra_id]);
            
            
            $stmt_likes = $pdo->prepare("SELECT likes FROM obras WHERE id = :id");
            $stmt_likes->execute([':id' => $obra_id]);
            $res = $stmt_likes->fetch(PDO::FETCH_ASSOC);
            
            if ($res) {
                echo json_encode([
                    'success' => true,
                    'likes' => $res['likes']
                ]);
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Error en la base de datos'
            ]);
            exit;
        }
    }
}

echo json_encode([
    'success' => false,
    'error' => 'Solicitud inválida'
]);
exit;
