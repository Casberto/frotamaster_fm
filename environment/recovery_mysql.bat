@echo off
setlocal enabledelayedexpansion

:: ============================================================================
::       SCRIPT DE RECUPERACAO DO MYSQL PARA XAMPP (VERSAO APRIMORADA)
:: ============================================================================
:: OBJETIVO:
:: Este script automatiza a restauracao da pasta de dados do MySQL.
:: Ele agora pergunta o caminho da instalacao do XAMPP para evitar erros.
:: ============================================================================

title Recuperacao do MySQL - XAMPP

cls
echo =================================================
echo  Recuperacao Automatica do MySQL para XAMPP
echo =================================================
echo.

:: --- PASSO 1: Obter o caminho do XAMPP ---
set "XAMPP_BASE_PATH=C:\xampp"
echo Por favor, informe o caminho de instalacao do XAMPP.
set /p "XAMPP_BASE_PATH=Pressione ENTER se for [%XAMPP_BASE_PATH%]: "

set "XAMPP_MYSQL_PATH=%XAMPP_BASE_PATH%\mysql"
set "DATA_PATH=%XAMPP_MYSQL_PATH%\data"
set "BACKUP_STRUCTURE_PATH=%XAMPP_MYSQL_PATH%\backup"

echo.
echo Verificando os caminhos...
echo Pasta de dados: %DATA_PATH%
echo Pasta de backup: %BACKUP_STRUCTURE_PATH%
echo.

:: --- Verificacoes Iniciais ---
if not exist "%DATA_PATH%" (
    echo [ERRO FATAL] O diretorio de dados nao foi encontrado:
    echo "%DATA_PATH%"
    echo Verifique se o caminho do XAMPP que voce informou esta correto.
    goto end
)

if not exist "%BACKUP_STRUCTURE_PATH%" (
    echo [ERRO FATAL] O diretorio de backup da estrutura nao foi encontrado:
    echo "%BACKUP_STRUCTURE_PATH%"
    echo A pasta de backup e essencial para a recuperacao.
    goto end
)

echo Caminhos verificados com sucesso!
echo.
echo O processo de recuperacao ira comecar.
pause
cls

:: --- PASSO 2: Fazer uma copia da pasta "data" com a data atual ---
echo [ETAPA 1 de 4] Criando backup de seguranca da pasta 'data' atual...
for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /format:list') do set datetime=%%I
set "DATE_SUFFIX=%datetime:~6,2%-%datetime:~4,2%-%datetime:~0,4%"
set "BACKUP_COPY_NAME=data_backup_%DATE_SUFFIX%"
set "BACKUP_COPY_PATH=%XAMPP_MYSQL_PATH%\%BACKUP_COPY_NAME%"

if exist "%BACKUP_COPY_PATH%" (
    echo [AVISO] Ja existe um backup para hoje. O processo continuara sem criar um novo.
) else (
    echo.
    echo --- Executando o comando de backup (robocopy)...
    robocopy "%DATA_PATH%" "%BACKUP_COPY_PATH%" /E /R:2 /W:5
    if %errorlevel% ge 8 (
        echo [ERRO FATAL] Falha ao criar a copia de seguranca. O processo foi abortado.
        goto end
    )
    echo --- Backup de seguranca criado com sucesso.
    echo.
)
echo.
echo === Fim da ETAPA 1. Pressione uma tecla para continuar... ===
pause
cls

:: --- PASSO 3: Deletar pastas e arquivos ---
echo [ETAPA 2 de 4] Removendo a estrutura corrompida do sistema MySQL...
echo.
echo Removendo "%DATA_PATH%\mysql"...
rmdir /s /q "%DATA_PATH%\mysql"
echo Removendo "%DATA_PATH%\performance_schema"...
rmdir /s /q "%DATA_PATH%\performance_schema"
echo Removendo "%DATA_PATH%\phpmyadmin"...
rmdir /s /q "%DATA_PATH%\phpmyadmin"
echo Removendo "%DATA_PATH%\test"...
rmdir /s /q "%DATA_PATH%\test"
echo.
echo Pastas do sistema removidas.
echo Agora, removendo arquivos soltos (exceto ibdata1)...
echo.

pushd "%DATA_PATH%"
for /f "delims=" %%F in ('dir /b /a-d') do (
    if /i not "%%~nxF" == "ibdata1" (
        echo Deletando "%%F"...
        del "%%F" /f /q
    )
)
popd
echo Arquivos soltos removidos.
echo.
echo === Fim da ETAPA 2. Pressione uma tecla para continuar... ===
pause
cls

:: --- PASSO 4: Copiar arquivos da pasta "backup" ---
echo [ETAPA 3 de 4] Restaurando a estrutura padrao do MySQL...
echo --- Executando o comando de restauracao (robocopy)...
robocopy "%BACKUP_STRUCTURE_PATH%" "%DATA_PATH%" /E /R:2 /W:5
if %errorlevel% ge 8 (
    echo [ERRO FATAL] Falha ao restaurar a estrutura do MySQL.
    echo Seus dados estao seguros em "%BACKUP_COPY_PATH%".
    goto end
)
echo Estrutura restaurada com sucesso.
echo.
echo === Fim da ETAPA 3. Pressione uma tecla para continuar... ===
pause
cls

:: --- Finalizacao ---
echo [ETAPA 4 de 4] Processo concluido!
echo.
echo =================================================================
echo  O MySQL foi restaurado com sucesso!
echo.
echo  Tente iniciar o servico MySQL novamente pelo painel do XAMPP.
echo =================================================================
echo.

:end
echo Pressione qualquer tecla para sair.
pause
endlocal

