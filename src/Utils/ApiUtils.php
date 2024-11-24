<?php
namespace Utils;

class ApiUtils {
    public static function sanitizeString(string $input, bool $required = false): string {
        $sanitized = trim(filter_var($input, FILTER_SANITIZE_STRING));
        if ($required && empty($sanitized)) {
            throw new \Exception("O campo é obrigatório.");
        }
        return $sanitized;
    }

    public static function sanitizeDateTime(string $date, string $time): string {
        $dateTime = $date . ' ' . $time;
        if (!strtotime($dateTime)) {
            throw new \Exception("Data e hora inválidas.");
        }
        return date('Y-m-d H:i:s', strtotime($dateTime));
    }

    public static function redirect(string $url): void {
        header("Location: $url");
        exit;
    }

    public static function formatErrorMessage(string $message): string {
        return htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    }
}
