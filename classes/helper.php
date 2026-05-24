<?php

class Helper
{
    public static function formatPrice($price)
    {
        return '$' . number_format($price, 2);
    }

    public static function getCourseLevelBadge($level)
    {
        $badges = [
            'beginner'     => 'success',
            'intermediate' => 'warning',
            'advanced'     => 'danger',
        ];
        return $badges[$level] ?? 'secondary';
    }

    public static function sanitizeInput($data)
    {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    // ... other helper functions
}