<?php

/**
 * Database
 * --------
 * Een STATIC class.
 * Dit doen we zodat voor gebruik van de class een programmeur de class
 * niet steeds hoeft te instantiëren als een object.
 */
class Database
{
   // Database credentials
   private static $dbHost = '127.0.0.1';        // Voorkeur voor IP-adres, hoeft dan niet langs lokale DNS
   private static $dbName = 'currency';
   private static $dbUser = 'root';
   private static $dbPass = 'root';

   // Properties to register connection and statements - PDO
   private static $dbConnection = null;
   private static $dbStatement = null;


   /**
    * connect
    * -------
    * Maakt een verbinding met de database server.
    * Dit is echter een private method. We laten de andere methods zelf controleren
    * of er een verbinding is en zo niet deze als nog gemaakt wordt.
    * Hierdoor hoeft de programmeur die deze class gebruikt dit niet zelf te doen.
    *
    * @return boolean      True als de connectie gemaakt is, anders False
    */
   private static function connect(): bool
   {
      try {
         self::$dbConnection = new PDO(
            'mysql:host=' . self::$dbHost . ';dbname=' . self::$dbName,
            self::$dbUser,
            self::$dbPass
         );
      } catch (PDOException $error) {
         return false;
      }

      return true;
   }

   /**
    * query
    * -----
    * Met deze method is de gebruikende developer in staat een SQL-statement te sturen
    * naar de database server. 
    *
    * @param string $sql      De SQL-statement, eventueel voorzien van placeholders
    * @param array $args      OPTIONEEL. De placeholders met hun werkelijke waarden.
    *
    * @return boolean         True wanneer het zonder fouten verlopen is, anders False
    */
   public static function query(string $sql, array $args = []): bool
   {
      // If there is no connection yet, we will connect right now
      if (is_null(self::$dbConnection))
         if (!self::connect())
            return false;

      // If argument $sql is empty then there is no need to proceed
      if (!empty($sql)) {
         try {
            self::$dbStatement = self::$dbConnection->prepare($sql);
            self::$dbStatement->execute($args);
         } catch (PDOException $error) {
            return false;
         }
      } else
         return false;

      return true;
   }

   /**
    * get
    * ---
    * Met deze method kunnen een enkele record binnenhalen
    * Uiteraard is dit een record op basis van de result set die
    * op de database server klaar staat na een succesvolle run
    * van een SQL-statement.
    *
    * @return array        De record
    */
   public static function get(): array
   {
      $result = [];

      // Als er een statement is dan heeft het pas zin om de data te halen uit de DB
      if (!is_null(self::$dbStatement)) {
         if (self::$dbStatement->rowCount() > 1)
            $result = self::getAll();
         else
            $result =  self::$dbStatement->fetch(PDO::FETCH_ASSOC);

         if (!$result)
            $result = [];     // Er is blijkbaar geen data beschikbaar
      }

      return $result;
   }


   /**
    * getAll
    * ------
    * Met deze method halen we 1 of meerdere records binnen vanuit de result set
    *
    * @return array     Een multi-dimensionale array met records
    */
   public static function getAll(): array
   {
      $result = [];

      // Als er een statement is dan heeft het pas zin om de data te halen uit de DB
      if (!is_null(self::$dbStatement)) {
         $result = self::$dbStatement->fetchAll(PDO::FETCH_ASSOC);

         if (!$result)
            $result = [];     // Er is blijkbaar geen data beschikbaar
      }

      return $result;
   }
}
