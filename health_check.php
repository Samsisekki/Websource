<?php
// 에러 메시지 노출 방지 (보안)
error_reporting(0);

// 1. K8s 환경변수에서 DB 접속 정보 가져오기 (환경에 맞게 수정 가능)
$host = getenv('DB_HOST') ?: 'mysql-service'; // 온프레미스 MySQL K8s 서비스명
$db   = getenv('DB_NAME') ?: 'dr_test_db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'password123!';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// 2. DB 연결 옵션 (여기가 핵심입니다!)
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    // 🚨 헬스체크 타임아웃 설정 (아주 중요)
    // DB가 죽었을 때 무한정 기다리지 않고 3초 만에 연결을 포기하여 빠른 Failover 유도
    PDO::ATTR_TIMEOUT => 3, 
];

try {
    // 3. DB 연결 시도
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // 연결 성공 시: HTTP 200 정상 반환
    http_response_code(200);
    echo "OK - App and DB are Healthy";

} catch (\PDOException $e) {
    // 4. DB 연결 실패 시 (DB 파드 장애 등): HTTP 500 에러 반환
    http_response_code(500);
    
    // (선택) 로그를 남기거나 화면에 출력하지만, 실제 서비스에서는 에러 상세내역은 숨기는 것이 좋습니다.
    echo "ERROR - DB Connection Failed";
}
?>
