// Globale variabele
let form = null;

// Setup methode
window.onload = function () {
   form = document.querySelector('myform');
   
   form.addEventListener('submit', handleFormSubmit);
}

// Formulier Submit Eventhandler
function handleFormSubmit(event)
{
   // Eerste wat we moeten doen is voorkomen dat
   // de browser de standaard actie gaat uitvoeren
   event.preventDefault();

   // Helper function om de API call uit te voeren
   createNewCurrency();
}

// Helper function die de API call uitvoert
async function createNewCurrency()
{
   // Variabele die we vullen met een object gekoppeld aan het formulier
   let formData = new FormData(form);

   // De API Call
   await fetch('http://api-basics-currency.local/api/',
      // We moeten nu een header meesturen met de volgende properties
      {
         method: 'POST',         // Geeft aan dat het een POST-request is
         body: formData          // Hiermee sturen we de formuliergegevens mee
      }
   )
      .then(response => response.json())  // Antwoord van API omzetten naar JSON-structuur
      .then(data => {                     // Vervolgens weer opvangen, maar nu geschikte format
         antwoord_van_server = data;      // Bewaar het antwoord in een globale variabele
         console.log(antwoord_van_server);
      })
      .catch(error => console.error('API ERROR: ' + error));   // Fouten opvangen
}


async function createNewCurrency2()
{
   // Variabele die we vullen met een FormData object, maar nu niet gekoppeld aan een formulier 
   let formData = new FormData();
   
   // We kunnen ook handmatig waarden toevoegen aan het object FormData
   formData.append('id', input_id.value);
   formData.append('abbr', input_abbr.value);
   formData.append('description', input_description.value);

   // De API Call
   await fetch('http://api-basics-currency.local/api/',
      // We moeten nu een header meesturen met de volgende properties
      {
         method: 'POST',         // Geeft aan dat het een POST-request is
         body: formData          // Hiermee sturen we de formuliergegevens mee
      }
   )
      .then(response => response.json())  // Antwoord van API omzetten naar JSON-structuur
      .then(data => {                     // Vervolgens weer opvangen, maar nu geschikte format
         antwoord_van_server = data;      // Bewaar het antwoord in een globale variabele
         console.log(antwoord_van_server);
      })
      .catch(error => console.error('API ERROR: ' + error));   // Fouten opvangen
}