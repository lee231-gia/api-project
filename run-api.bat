@echo off
if exist C:\Apps\php\php.exe (
    C:\Apps\php\php.exe -S localhost:8000 index.php
) else (
    php -S localhost:8000 index.php
)
