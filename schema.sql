CREATE DATABASE IF NOT EXISTS `school_sports_day` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `school_sports_day`;

-- Table 1: Houses
CREATE TABLE IF NOT EXISTS `houses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `house_name` VARCHAR(50) NOT NULL,
    `color_code` VARCHAR(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 2: Sports
CREATE TABLE IF NOT EXISTS `sports` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sport_name` VARCHAR(50) NOT NULL,
    `category` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 3: Sports Day Athletes (linking student profiles from phichaia_student.student)
CREATE TABLE IF NOT EXISTS `sports_day_athletes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` VARCHAR(10) NOT NULL,
    `house_id` INT NOT NULL,
    INDEX `idx_student_id` (`student_id`),
    CONSTRAINT `fk_athlete_house` FOREIGN KEY (`house_id`) REFERENCES `houses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 4: Event Registrations (linking athletes to specific sports events)
CREATE TABLE IF NOT EXISTS `event_registrations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sports_day_athlete_id` INT NOT NULL,
    `sport_id` INT NOT NULL,
    CONSTRAINT `fk_reg_athlete` FOREIGN KEY (`sports_day_athlete_id`) REFERENCES `sports_day_athletes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_reg_sport` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 5: Matches & Events
CREATE TABLE IF NOT EXISTS `matches_events` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sport_id` INT NOT NULL,
    `event_date` DATETIME NOT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'Scheduled',
    CONSTRAINT `fk_match_sport` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 6: Results
CREATE TABLE IF NOT EXISTS `results` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `match_id` INT NOT NULL,
    `house_id` INT NOT NULL,
    `points` INT NOT NULL DEFAULT 0,
    `medal` VARCHAR(10) DEFAULT NULL,
    CONSTRAINT `fk_result_match` FOREIGN KEY (`match_id`) REFERENCES `matches_events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_result_house` FOREIGN KEY (`house_id`) REFERENCES `houses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
