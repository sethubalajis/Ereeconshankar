<?php

namespace App\Services;

use App\Models\CompanySetting;
use App\Models\BranchSetting;
use Carbon\Carbon;

class DateTimeFormatter
{
    /**
     * Get the resolved settings for the current context.
     * Fallback: User -> Branch -> Company -> Default
     */
    public static function getSettings()
    {
        // Default settings
        $settings = [
            'date_format' => 'd/m/Y',
            'time_format' => 'h:i A',
            'datetime_format' => 'd/m/Y h:i A',
            'timezone' => 'UTC',
            'first_day_of_week' => '1', // Monday
        ];

        // Ensure we are in an active context
        $companyId = session('company_id') ?? (auth()->check() ? auth()->user()->company_id : null);
        $branchId = session('branch_id');

        // 1. Company Settings
        if ($companyId) {
            $companySettings = CompanySetting::where('company_id', $companyId)->pluck('setting_value', 'setting_key')->toArray();
            $settings = array_merge($settings, self::mapKeys($companySettings));
        }

        // 2. Branch Settings (Overrides Company)
        if ($branchId) {
            $branchSettings = BranchSetting::where('branch_id', $branchId)->pluck('setting_value', 'setting_key')->toArray();
            $settings = array_merge($settings, self::mapKeys($branchSettings));
        }

        // If time format is 24 Hour, adjust datetime format and time format if they are defaults or simple
        if (isset($settings['time_format'])) {
            if ($settings['time_format'] === 'H:i') {
                $settings['datetime_format'] = str_replace(['h:i A', 'h:i a'], 'H:i', $settings['datetime_format']);
            }
        }

        return $settings;
    }

    private static function mapKeys($dbSettings)
    {
        $mapped = [];
        if (isset($dbSettings['date_format'])) $mapped['date_format'] = $dbSettings['date_format'];
        if (isset($dbSettings['time_format'])) $mapped['time_format'] = $dbSettings['time_format'];
        if (isset($dbSettings['datetime_format'])) $mapped['datetime_format'] = $dbSettings['datetime_format'];
        if (isset($dbSettings['timezone'])) $mapped['timezone'] = $dbSettings['timezone'];
        if (isset($dbSettings['first_day_of_week'])) $mapped['first_day_of_week'] = $dbSettings['first_day_of_week'];
        return $mapped;
    }

    public static function formatDate($date)
    {
        if (!$date) return null;
        $settings = self::getSettings();
        return Carbon::parse($date)->timezone($settings['timezone'])->format($settings['date_format']);
    }

    public static function formatTime($time)
    {
        if (!$time) return null;
        $settings = self::getSettings();
        // Time strings might not have a date, parse them carefully
        return Carbon::parse($time)->format($settings['time_format']);
    }

    public static function formatDateTime($datetime)
    {
        if (!$datetime) return null;
        $settings = self::getSettings();
        return Carbon::parse($datetime)->timezone($settings['timezone'])->format($settings['datetime_format']);
    }

    /**
     * Converts a localized date string (e.g. 24/08/2026) back to Y-m-d.
     */
    public static function parseDate($formattedString)
    {
        if (!$formattedString) return null;
        $settings = self::getSettings();
        try {
            return Carbon::createFromFormat($settings['date_format'], $formattedString)->format('Y-m-d');
        } catch (\Exception $e) {
            // Fallback in case native date picker returns Y-m-d already
            return Carbon::parse($formattedString)->format('Y-m-d');
        }
    }

    /**
     * Converts a localized time string back to H:i:s.
     */
    public static function parseTime($formattedString)
    {
        if (!$formattedString) return null;
        $settings = self::getSettings();
        try {
            return Carbon::createFromFormat($settings['time_format'], $formattedString)->format('H:i:s');
        } catch (\Exception $e) {
            return Carbon::parse($formattedString)->format('H:i:s');
        }
    }

    /**
     * Converts a localized datetime string back to UTC Y-m-d H:i:s.
     */
    public static function parseDateTime($formattedString)
    {
        if (!$formattedString) return null;
        $settings = self::getSettings();
        try {
            return Carbon::createFromFormat($settings['datetime_format'], $formattedString, $settings['timezone'])
                ->setTimezone('UTC')
                ->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return Carbon::parse($formattedString, $settings['timezone'])->setTimezone('UTC')->format('Y-m-d H:i:s');
        }
    }

    /**
     * Get Filament compatible Date format (JS/PHP hybrid might be needed, but Filament uses PHP formats)
     */
    public static function getFilamentDateFormat()
    {
        return self::getSettings()['date_format'];
    }

    public static function getFilamentTimeFormat()
    {
        return self::getSettings()['time_format'];
    }

    public static function getFilamentDateTimeFormat()
    {
        return self::getSettings()['datetime_format'];
    }
}
