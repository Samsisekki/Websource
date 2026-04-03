FROM php:8.0-apache

# 작업 디렉토리 설정
WORKDIR /var/www/html

# 👉 핵심 추가: PHP 용 MySQL (PDO) 확장 모듈 설치!
RUN docker-php-ext-install pdo pdo_mysql mysqli

# 소스 파일 복사 (sam_main_web.php를 index.php로 서비스)
COPY sam_main_web.php ./index.php

# 헬스체크 파일도 도커 이미지 안에 복사해 넣기
COPY health_check.php ./health_check.php

# Apache 권한 설정
RUN chown -R www-data:www-data /var/www/html

# 80 포트 개방
EXPOSE 80
