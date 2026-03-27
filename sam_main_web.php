<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>삼시세끼 팀 | 인프라 관제 대시보드</title>
    <style>
        /* 전체 다크 모드 & 하이테크 감성 배경 */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #0f172a; color: #f8fafc; margin: 0; padding: 0; line-height: 1.6; }
        
        /* 상단 헤더 영역 */
        header { text-align: center; padding: 4rem 1rem 3rem; background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); border-bottom: 1px solid #334155; }
        h1 { margin: 0; font-size: 2.8rem; letter-spacing: 2px; text-shadow: 0 0 10px rgba(255,255,255,0.2); }
        .subtitle { color: #94a3b8; font-size: 1.2rem; margin-top: 10px; }
        
        /* 터미널 느낌의 서버 정보 창 (로드밸런싱 증명용) */
        .server-status { background-color: #000; color: #10b981; font-family: 'Courier New', Courier, monospace; display: inline-block; padding: 20px 40px; border-radius: 8px; border: 1px solid #10b981; box-shadow: 0 0 20px rgba(16, 185, 129, 0.2); margin-top: 30px; font-size: 1.3rem; text-align: left; }
        .blink { animation: blinker 1s linear infinite; }
        @keyframes blinker { 50% { opacity: 0; } }

        /* 컨텐츠 그리드 레이아웃 */
        .container { max-width: 1100px; margin: 40px auto; padding: 0 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; }
        
        /* 공통 카드 스타일 */
        .card { background: #1e293b; padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); transition: transform 0.3s ease; border-top: 4px solid #3b82f6; }
        .card:hover { transform: translateY(-5px); }
        .card h3 { margin-top: 0; font-size: 1.4rem; color: #e2e8f0; display: flex; align-items: center; gap: 10px; }
        .card p { color: #cbd5e1; font-size: 1.05rem; }

        /* ★ 핵심 포인트: DR 강조 애니메이션 카드 ★ */
        .dr-highlight { 
            grid-column: 1 / -1; /* 첫 줄 전체를 차지하게 만듦 */
            border-top: none;
            border: 2px solid #ef4444; 
            background: linear-gradient(145deg, #1e293b 0%, #450a0a 100%);
            animation: pulse-red 2s infinite; 
            text-align: center;
        }
        .dr-highlight h3 { color: #fca5a5; justify-content: center; font-size: 1.8rem; }
        .dr-highlight p { font-size: 1.2rem; max-width: 800px; margin: 0 auto; }
        
        /* 붉은색 심장 박동(경고/복구) 애니메이션 */
        @keyframes pulse-red { 
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); } 
            70% { box-shadow: 0 0 0 20px rgba(239, 68, 68, 0); } 
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); } 
        }

        footer { text-align: center; margin-top: 60px; padding: 30px; color: #64748b; font-size: 0.95rem; border-top: 1px solid #1e293b; }
    </style>
</head>
<body>

    <header>
        <h1>🍱 삼시세끼 Team Infrastructure</h1>
        <div class="subtitle">하이브리드 클라우드 DR 아키텍처</div>
        
        <div class="server-status">
            <span style="color: #64748b;">root@sam-sisekki:~#</span> ./check_status.sh<br><br>
            [<span class="blink">●</span>] 응답 서버 IP &nbsp;&nbsp;: <strong style="color: #fff;"><?php echo $_SERVER['SERVER_ADDR']; ?></strong> <br>
            [<span class="blink">●</span>] 호스트(Pod) 이름 : <strong style="color: #fff;"><?php echo gethostname(); ?></strong>
        </div>
    </header>

    <div class="container">
        
        <div class="card dr-highlight">
            <h3>🚨 1. 무중단 재해 복구 (Disaster Recovery) 🚨</h3>
            <p>On-Premise(Vagrant) 메인 센터에 장애가 발생하더라도, AWS Route 53 헬스체크를 통해 트래픽을 즉각 EKS(AWS)로 우회시킵니다. 사용자는 단 1초의 데이터 유실 없이 완벽한 <b>Warm Standby</b> 환경에서 서비스를 계속 이용할 수 있습니다.</p>
        </div>

        <div class="card" style="border-top-color: #10b981;">
            <h3>🔄 2. GitOps 자동화 배포</h3>
            <p>GitHub을 단일 배포 환경으로 삼아 <b>ArgoCD</b>가 24시간 감시합니다. 코드가 수정되면 On-Premise와 AWS 양쪽 클러스터에 동일한 애플리케이션 파드가 100% 자동으로 동기화 배포됩니다.</p>
        </div>

        <div class="card" style="border-top-color: #f59e0b;">
            <h3>💾 3. 볼륨 & DB 실시간 동기화</h3>
            <p>독립된 NFS(On-Prem)와 EFS(AWS) 환경을 구축하고, <b>AWS DMS</b>와 <b>DataSync</b>를 활용해 MySQL DB와 파일 스토리지를 실시간으로 1:1 복제하여 완벽한 Stateful 동기화를 달성했습니다.</p>
        </div>

        <div class="card" style="border-top-color: #8b5cf6;">
            <h3>📊 4. 통합 모니터링 관제</h3>
            <p><b>Tailscale VPN</b> 보안망을 통해 On-Premise와 AWS 두 환경의 하드웨어(Node), K8s 상태(KSM), DB(MySQL) 지표를 AWS <b>Prometheus & Grafana</b> 대시보드 한 곳으로 안전하게 모아 실시간으로 관제합니다.</p>
        </div>

    </div>

    <footer>
        &copy; 2026 삼시세끼 Team. Designed & Architected for High Availability.
    </footer>

</body>
</html>