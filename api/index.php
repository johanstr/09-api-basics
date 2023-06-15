<?php
@include_once('app/HttpStatus.php');
@include_once('app/apifunctions.php');

/*
 * Aanroepen van onze API
 * ----------------------
 * 
 * currency-api/index.php     of         currency-api/index.php?cmd=all     
 *      Geef alle currencies terug
 * 
 * currency-api/index.php?cmd=one&currency=usd
 *      Geef de waarde van de USD terug
 * 
 * currency-api/index.php?cmd=calc&value=120&from=usd&to=eur
 *      Bereken de waarde van 120 USD in Euro's en geef de uitkomst terug
 */

$cmd = '';

// POST SIMULATIE
if($_SERVER['REQUEST_METHOD'] == 'POST') {
   // We simuleren hier het afhandelen van een POST-request
   header('Content-Type: application/json');
   header('HTTP/1.1 200 Ok');
   echo json_encode($_POST);
   die();
}

/**
 * We kijken eerst of er wel een querystring is met als eerste parameter cmd
 * Zo niet, dan bepalen we dat cmd=all is bedoeld
 */
if( ! isset($_GET['cmd']) ) {
   $cmd = 'all';
} else {
   $cmd = strtolower($_GET['cmd']); // Voor de zekerheid zetten we waarde in cmd om naar kleine letters
}

/**
 * Nu bepalen we op basis van de waarde in $cmd welke actie moet worden ondernomen
 */
switch($cmd) {
   case 'all':                   // Geef alle valuta terug
      getAllCurrencies();
      break;

   case 'one':                   // Geef gegevens van één bepaalde valuta terug
      /**
       * We verwachten dan wel een tweede parameter in de querystring, namelijk currency
       */
      if( ! isset($_GET['currency']) ) {
         /**
          * Er is geen tweede parameter in de querystring meegegeven
          * Dus geven we een foutcode terug en sturen daarbij een json structuur terug met
          * de foutmelding.
          */
         HttpStatus::http_return(400, 'Gegeven ontbreekt in de request');
      }
      // Het is goedgegaan dus roepen we nu de juiste functie aan
      getCurrency($_GET['currency']);
      break;

   case 'calc':               // Voer een berekening uit
      /**
       * Nu hebben we 3 extra parameters in de querystring nodig,
       * dus controleren we of deze wel gegeven zijn
       */
      if( ! isset($_GET['value']) || ! isset($_GET['from']) || ! isset($_GET['to']) ) {
         // Nee dus, zeker 1 van de verplichte parameters mist hier
         HttpStatus::http_return(400, 'Tenminste 1 van de 3 benodigde gegevens ontbreekt in de request');
      }
      // Ze zijn er, dus roepen we nu de juiste functie aan
      calculateValue($_GET['value'], $_GET['from'], $_GET['to']);
      break;

   default:
      // Onbekende command
      // Sturen een HTTP status code terug
      // En beeindingen het programma.
      HttpStatus::http_return(404, 'Onbekende request');
}