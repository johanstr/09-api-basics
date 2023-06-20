<?php

@include_once('Database.php');

/**
 * getAllCurrencies
 * ----------------
 * Wordt aangeroepen op basis van de API call met querystring cmd=all
 * Geeft de gegevens van alle valuta terug aan de client
 *
 * @return void
 */
function getAllCurrencies()
{
   $sql = "SELECT * FROM `currency`";
   Database::query($sql);
   $rows = Database::getAll();

   header('Content-Type: application/json');
   echo json_encode($rows);
}

/**
 * getCurrency
 * -----------
 * Wordt aangeroepen op basis van de API call met querystring cmd=one
 * Geeft de gegevens van een valuta terug aan de client
 *
 * @param string $currency
 * 
 * @return void
 */
function getCurrency($currency)
{
   $sql = "SELECT * FROM `currency` WHERE `abbr`=:abbr";
   Database::query($sql, [':abbr' => strtoupper($currency)]);
   
   $row = Database::get();

   header('Content-Type: application/json');
   echo json_encode($row);
}

/**
 * calculateValue
 * --------------
 * Wordt aangeroepen op basis van de API call met querystring cmd=calc
 * Ontvangt 3 gegevens en rekent dan de value in de ene valuta om
 * naar de waarde in de andere valuta en stuurt deze gegevens terug naar
 * de client
 *
 * @param string $value
 * @param string $from_currency
 * @param string $to_currency
 * 
 * @return void
 */
function calculateValue($value, $from_currency, $to_currency)
{
   // Eerst zetten we alle valuta codes om naar hoofdletters
   $from_currency = strtoupper($from_currency);
   $to_currency = strtoupper($to_currency);

   // We stellen nu de SQL-statement samen om de gegevens van de twee
   // betrokken valuta binnen te halen uit de database
   $sql = "SELECT * FROM `currency` WHERE `abbr`=:from";
   Database::query($sql, [':from' => $from_currency]);

   $row_from = Database::get();

   $sql = "SELECT * FROM `currency` WHERE `abbr`=:to";
   Database::query($sql, [':to' => $to_currency]);

   $row_to = Database::get();

   // We gebruiken nu hulpvariabelen om tussen berekeningen te maken
   // En zetten daarbij het type van de waarde om van string naar float
   $valueInEuros = floatval($value) / floatval($row_from['value']);
   $valueInTo = floatval($valueInEuros) * floatval($row_to['value']);  // Het resultaat

   // Nu is het tijd om de berekende informatie in een array te verzamelen en deze
   // om te vormen naar JSON om terug te kunnen sturen naar de client
   header('Content-Type: application/json');
   echo json_encode([
      'calculated' => $valueInTo,
      'from' => $from_currency,
      "amount" => $value,
      'fromvalue' => $row_to['value'],
      "to" => $to_currency,
      'tovalue' => $row_from['value']
   ]);
}