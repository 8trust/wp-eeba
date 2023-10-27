<?php

namespace Inavii\Instagram\Wp;

class AdminNoticeLog
{
    public static function addOption(string $key, int $userId, string $userName, string $message): void
    {
        $existingOptions = self::getOption($key, array());
        $keyToCheck = self::generateKey($userId, $message);

        if (!isset($existingOptions[$keyToCheck])) {
            $existingOptions[$keyToCheck] = [
                'userId' => $userId,
                'userName' => $userName,
                'message' => $message,
            ];

            self::updateOption($key, $existingOptions);
        }
    }

    public static function getOption(string $key, $default = null)
    {
        return get_option($key, $default);
    }

    public static function getErrors(string $key, $default = null): array
    {
        return array_values((array)get_option($key, $default));
    }

    public static function removeOption(string $key): void
    {
        delete_option($key);
    }

    public static function removeAccountOption(string $key, int $userId): void
    {
        self::updateOption($key, []);
    }

    private static function updateOption(string $key, $value): void
    {
        update_option($key, $value);
    }

    private static function generateKey(int $userId, string $message): string
    {
        return $userId . '_' . md5($message);
    }
}
