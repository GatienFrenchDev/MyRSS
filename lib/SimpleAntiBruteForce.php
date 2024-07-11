<?php

require_once __DIR__ . "/../src/classes/Database.php";

/**
 *
 * SimpleAntiBruteForce - https://github.com/gatienfrenchdev/SimpleAntiBruteForce
 * 
 * Small library to manage brute force attempts on a PHP form.
 * Didn't find a library that offered a correct and simple system.
 *
 *
 * To use it, you only need to create a MySQL table on your project following this data model:
 * user_failed_logins(id: int, email: varchar, attempted_at:int, ip_adress:varchar)
 *
 * Then call the `::isAuthorized()` method before verifying the entered password.
 *
 * And call the `::addFailedAttempt()` method when a connection fails to remember it.
 *
 * For any questions, contact me by email.
 *
 * @author GatienFrenchDev <contact@gatiendev.fr>
 * @license MIT
 * @version 1.0
 * @copyright  Copyright (c) 2024 GatienDev
 * 
 */

class SimpleAntiBruteForce
{

	private static int $MAX_FAILED_ATTEMPT = 10;
	private static int $INTERVAL_IN_S = 300;

	/**
	 * Returns `true` if the IP is allowed. Returns `false` if the IP is blocked.
	 */
	static function isAuthorized(string $ip_adress, string $email): bool
	{
		$mysqli = Database::connexion();

		$timestamp_max = time() - self::$INTERVAL_IN_S;

        $stmt = $mysqli->prepare("SELECT COUNT(id) as nb FROM user_failed_logins WHERE email = ? AND ip_adress = ? AND attempted_at > ?");
        $stmt->bind_param("ssi", $email, $ip_adress, $timestamp_max);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $mysqli->close();
        return $res[0]["nb"] < self::$MAX_FAILED_ATTEMPT;
	}

	/**
	 * To add a new failed connection attempt.
	 */
	static function addFailedAttempt(string $ip_adress, string $email): void
	{
		$mysqli = Database::connexion();

		$current_time = time();

		// add a new record in the db
        $stmt = $mysqli->prepare("INSERT INTO user_failed_logins (email, attempted_at, ip_adress) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $email, $current_time, $ip_adress);
        $stmt->execute();

		// clean the db by removing expired attempt
		$min_expired_timestamp = time() - 2*self::$INTERVAL_IN_S;
        $stmt = $mysqli->prepare("DELETE FROM user_failed_logins WHERE attempted_at < ?");
        $stmt->bind_param("i", $min_expired_timestamp);
		$stmt->execute();

        $stmt->close();
        $mysqli->close();
	}
}