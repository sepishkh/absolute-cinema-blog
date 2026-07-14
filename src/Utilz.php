<?php

namespace AbsCin;

class Utilz {
    public static function Escape($text) {
        return htmlspecialchars($text, ENT_HTML5, "UTF-8");
    }

    public static function FormatDate($date) {
        $d = strtotime($date);
        return date("M d, Y", $d);
    }

    public static function FullName($fname, $lname) {
        return ($fname . " " . $lname);
    }

    public static function GetCategory($category) {
        switch ($category) {
            case 0:
                return "Movie";
            case 1:
                return "TV Show";
            case 2:
                return "Theatre";
            default:
                return "ERROR";
        }
    }

    public static function GetRole($role) {
        switch ($role) {
            case 2:
                return "god";
            case 1:
                return "admin";
            case 0:
                return "user";
            default:
                return "ERROR";
        }
    }

    public static function GetApproval($status) {
        switch ($status) {
            case -1:
                return "Disapproved";
            case 0:
                return "Waiting for approval";
            case 1:
                return "Approved";
            default:
                return "ERROR";
        }
    }

    public static function GetThumbnail($category) {
        switch ($category) {
            case 0:
                return "🍿";
            case 1:
                return "📺";
            case 2:
                return "🎭";
            default:
                return "ERROR";
        }
    }

    public static function UpdateIfExists(array $get, string $key, $val) {
        if(array_key_exists($key, $get)) {
            $get[$key] = $val;
        }
        return $get;
    }
}
