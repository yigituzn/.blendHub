<?php
header('Content-Type: application/json');

// Basit bir test cevabı
$request = json_decode(file_get_contents('php://input'), true);

if (isset($request['message'])) {
    $message = $request['message'];
    $response = "Mentör yanıtı: '" . htmlspecialchars($message) . "' sorusu üzerine düşünüyorum!";
    echo json_encode(['reply' => $response]);
} else {
    echo json_encode(['reply' => 'Anlamadım, tekrar eder misiniz?']);
}
?>
