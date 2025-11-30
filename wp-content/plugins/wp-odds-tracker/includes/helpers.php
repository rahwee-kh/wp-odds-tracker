<?php


if (!defined('ABSPATH')) exit;

function wpot_get_cached_data($date)
{
    $cache_key = "wpot_matches_".$date;
    $data = get_transient($cache_key);

    return $data ?: [];
}


function wpot_normalize_date($date) {

    // If empty, default to today
    if (!$date) $date = 'today';

    // today → Y-m-d
    if ($date === 'today') {
        return date('Y-m-d');
    }

    // tomorrow → Y-m-d
    if ($date === 'tomorrow') {
        return date('Y-m-d', strtotime('+1 day'));
    }

    // If already YYYY-MM-DD → return as is
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return $date;
    }

    // Fallback: try to parse string into timestamp
    $timestamp = strtotime($date);
    if ($timestamp) {
        return date('Y-m-d', $timestamp);
    }

    // Final fallback = today
    return date('Y-m-d');
}

