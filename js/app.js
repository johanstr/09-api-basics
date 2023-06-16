// Globale variabelen
let valuta_from_select = null;
let valuta_to_select = null;
let valuta_from_input = null;
let calc_output = null;
let calc_button = null;
let post_button = null;
let test_data = null;

/**
 * In de onderstaande variabele slaan we alle valuta gegevens op
 * die we binnen krijgen via een API call.
 */
let valuta = []; 

/**
 * Een functie die wordt opgestart door de browser
 * nadat de browser klaar is met het verwerken van alle
 * HTML en CSS
 */
window.onload = function () {
   // We halen de benodigde elementen uit de pagina binnen
   valuta_from_select = document.querySelector('#valutaSelectFrom');
   valuta_to_select = document.querySelector('#valutaSelectTo');
   valuta_from_input = document.querySelector('#valutaAmountFrom');
   calc_output = document.querySelector('#output');
   calc_button = document.querySelector('#calcButton');
   post_button = document.querySelector('#postButton');

   // We koppelen een click event handler aan de button
   calc_button.addEventListener('click', calculate);
   post_button.addEventListener('click', simulatePostRequest);

   
   // In deze functie worden alle valuta gegevens opgevraagd
   // via een API call
   getCurrencies();
};

/**
 * getCurrencies
 * -------------
 * Deze functie wordt asynchroon uitgevoerd door de aanduiding async
 * voorafgaand aan het function keyword
 * Dit is nodig omdat API calls op de achtergrond moeten worden uitgevoerd.
 */
async function getCurrencies()
{
   // Door await i.c.m. async wordt de call naar de API hieronder
   // asynchroon op de achtergrond uitgevoerd
   await fetch('http://api-basics-currency.local/api?cmd=all')
      .then(response => response.json())
      .then(data => {
         valuta = data;    // De ontvangen data opslaan in de lokale variabele
         fillSelect();     // Helper function om de select elementen te vullen
                           // met de valuta
      })
      .catch(error => console.error('API ERROR: ' + error));
}

/**
 * fillSelect
 * ----------
 * Vult de select elementen op de pagina met de valuta
 */
function fillSelect()
{
   valuta.forEach(currency => {
      let option = `
         <option value=${currency.id}>${currency.abbr} - ${currency.name}</option>
      `;
      valuta_from_select.innerHTML += option;
      valuta_to_select.innerHTML += option;
   });
}

/**
 * requestCalculation
 * ------------------
 * Roept de API aan met het verzoek een berekening uit te voeren met twee valuta
 * 
 * @param string value     // Het aantal munten in de eerste valuta
 * @param string from      // De afkorting van de eerste valuta
 * @param string to        // De afkorting van de tweede valuta
 */
async function requestCalculation(value, from, to)
{
   await fetch('http://api-basics-currency.local/api?cmd=calc&value=' + value + '&from=' + from + '&to=' + to)
      .then(response => response.json())
      .then(data => {
         console.log(data);
         let output = `
            <h1>Omgerekend</h1>
            <p>Van: ${data.from}&nbsp;${data.amount} (Koerswaarde: ${data.fromvalue})</p>
            <p>Naar: ${data.to}&nbsp;${data.calculated} (Koerswaarde: ${data.tovalue})</p>
         `;

         calc_output.innerHTML = output;
      })
      .catch(error => console.error('API ERROR: ' + error))
}

/**
 * calculate
 * ---------
 * Click event handler van de button
 * Deze roept de functie requestCalculatio weer aan om de
 * API aan te roepen.
 * 
 * @param EVENT event 
 */
function calculate(event)
{
   event.preventDefault();

   let value_from = valuta_from_input.value;
   let valuta_from = valuta[valuta_from_select.selectedIndex].abbr;
   let valuta_to = valuta[valuta_to_select.selectedIndex].abbr;

   requestCalculation(value_from, valuta_from, valuta_to);
}


function simulatePostRequest(event)
{
   event.preventDefault();

   createNewCurrency();
}

async function createNewCurrency()
{
   let formData = new FormData();
   
   formData.append('id', 1);
   formData.append('abbr', 'CRED');
   formData.append('description', 'POST Test JS');

   await fetch('http://api-basics-currency.local/api/', {
      method: 'POST',
      body: formData
   })
      .then(response => response.json())
      .then(data => {
         test_data = data;
         console.log(test_data);
      })
      .catch(error => console.error('API ERROR: ' + error));
   
}