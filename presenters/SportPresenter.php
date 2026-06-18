<?php
/**
 * SportPresenter - Assists the views with rich HTML formatting and presentation logic
 */
class SportPresenter {
    /**
     * Get HTML markup for a shiny medal badge
     */
    public function getMedalBadge($medal) {
        if (empty($medal)) {
            return '<span class="badge badge-none">ไม่มีเหรียญรางวัล</span>';
        }

        $medalClass = strtolower($medal);
        $icon = '';
        $medalThai = $medal;
        
        switch ($medal) {
            case 'Gold':
                $icon = '🥇';
                $medalThai = 'เหรียญทอง';
                break;
            case 'Silver':
                $icon = '🥈';
                $medalThai = 'เหรียญเงิน';
                break;
            case 'Bronze':
                $icon = '🥉';
                $medalThai = 'เหรียญทองแดง';
                break;
            default:
                return '<span class="badge badge-normal">' . htmlspecialchars($medal) . '</span>';
        }

        return '<span class="badge badge-medal medal-' . $medalClass . '" title="' . $medalThai . '">' . $icon . ' ' . htmlspecialchars($medalThai) . '</span>';
    }

    /**
     * Format database datetime string into a readable format
     */
    public function formatDate($dateTime) {
        if (empty($dateTime)) return 'ไม่มีข้อมูล';
        $timestamp = strtotime($dateTime);
        
        // Month array for clean formatting
        $months = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        
        $day = date('d', $timestamp);
        $month = $months[date('n', $timestamp) - 1];
        $year = date('Y', $timestamp) + 543; // Convert to Thai BE year
        $time = date('H:i', $timestamp);
        
        return "$day $month $year - $time น.";
    }

    /**
     * Returns inline style declaration for house colors
     */
    public function getHouseStyle($colorCode) {
        if (empty($colorCode)) {
            $colorCode = '#6b7280'; // Default gray
        }
        return 'style="--house-color: ' . htmlspecialchars($colorCode) . '; --house-color-rgb: ' . $this->hexToRgbString($colorCode) . ';"';
    }

    /**
     * Helper to convert hex code to comma-separated RGB values for rgba() styles
     */
    private function hexToRgbString($hex) {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return "$r, $g, $b";
    }

    /**
     * Translate house name to Thai
     */
    public function getHouseNameTh($houseName) {
        if (empty($houseName)) return '';
        
        if (stripos($houseName, 'purple') !== false || stripos($houseName, 'ม่วง') !== false) {
            return 'สีม่วง';
        }
        if (stripos($houseName, 'green') !== false || stripos($houseName, 'เขียว') !== false) {
            return 'สีเขียว';
        }
        if (stripos($houseName, 'orange') !== false || stripos($houseName, 'แสด') !== false || stripos($houseName, 'ส้ม') !== false) {
            return 'สีแสด';
        }
        // Light blue sky/cyan needs to be checked before dark blue
        if (stripos($houseName, 'light blue') !== false || stripos($houseName, 'sky') !== false || stripos($houseName, 'ฟ้า') !== false) {
            return 'สีฟ้า';
        }
        if (stripos($houseName, 'blue') !== false || stripos($houseName, 'น้ำเงิน') !== false) {
            return 'สีน้ำเงิน';
        }
        if (stripos($houseName, 'pink') !== false || stripos($houseName, 'ชมพู') !== false) {
            return 'สีชมพู';
        }
        if (stripos($houseName, 'red') !== false || stripos($houseName, 'แดง') !== false) {
            return 'สีแดง';
        }
        if (stripos($houseName, 'yellow') !== false || stripos($houseName, 'เหลือง') !== false) {
            return 'สีเหลือง';
        }
        return $houseName;
    }

    /**
     * Get Font Awesome icon class for a house name
     */
    public function getHouseIcon($houseName) {
        if (stripos($houseName, 'purple') !== false || stripos($houseName, 'ม่วง') !== false) {
            return 'fa-solid fa-gem';
        }
        if (stripos($houseName, 'green') !== false || stripos($houseName, 'เขียว') !== false) {
            return 'fa-solid fa-leaf';
        }
        if (stripos($houseName, 'orange') !== false || stripos($houseName, 'แสด') !== false || stripos($houseName, 'ส้ม') !== false) {
            return 'fa-solid fa-fire';
        }
        if (stripos($houseName, 'light blue') !== false || stripos($houseName, 'sky') !== false || stripos($houseName, 'ฟ้า') !== false) {
            return 'fa-solid fa-cloud';
        }
        if (stripos($houseName, 'blue') !== false || stripos($houseName, 'น้ำเงิน') !== false) {
            return 'fa-solid fa-shield-halved';
        }
        if (stripos($houseName, 'pink') !== false || stripos($houseName, 'ชมพู') !== false) {
            return 'fa-solid fa-heart';
        }
        if (stripos($houseName, 'red') !== false || stripos($houseName, 'แดง') !== false) {
            return 'fa-solid fa-fire';
        }
        if (stripos($houseName, 'yellow') !== false || stripos($houseName, 'เหลือง') !== false) {
            return 'fa-solid fa-sun';
        }
        return 'fa-solid fa-shield-halved';
    }
}

