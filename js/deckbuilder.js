console.log("┬┴┬┴┬┴┤                      ʕ·ᴥ·├┬┴┬┴┬┴");
console.log("┬┴┬┴┬┴┤ Gwent Deckbuilder Libary ├┬┴┬┴┬┴");
console.log("┬┴┬┴┬┴┤ 2021-01-06    teddybee_r ├┬┴┬┴┬┴");
console.log("┬┴┬┴┬┴┤   thanks                 ├┬┴┬┴┬┴");
console.log("┬┴┬┴┬┴┤•ᴥ•ʔ  for poking around   ├┬┴┬┴┬┴");

/*
 * This deckbuilder works by making all the data available on the website
 * using data-attribute- tags.
*/

// every deck card is made here
class Card {
  constructor(card) {
    // fetch the element storing all data-attributes
    var card = document.getElementById(card);
    // write to object
    this.id = Number(card.dataset.id);
    this.art = Number(card.dataset.art);
    this.name = card.dataset.name;
    this.provision = Number(card.dataset.provision);
    this.power = Number(card.dataset.power);
    this.armor = Number(card.dataset.armor);
    this.color = card.dataset.color;
    this.type = card.dataset.type;
    this.amount = 1;

    console.log("new Card("+this.id+") {\"id\":  " + this.id + ", \"name\": \"" + this.name + "\", \"provision\": " + this.provision + ", \"power\": " + this.power  + ", \"armor\": " + this.armor  + ", \"art\": " + this.art  + ", \"type\": \"" + this.type + "\"}")
  }
}


class Decklist
{

    constructor() {
        this.version = document.getElementById("Deck").dataset.version;
        this.provision = [];
        this.provision.total = 0;
        this.provision.limit = 150;
        this.deckSize = 0;
        this.ability = [];
        this.stratagem = [];
        this.cards = [];
    }

    /*
     * There can be only one Ability and Stratagem so we'll set and override them everytime.
     * The Abilities increase the total provision available for use.
     */
    setAbility(id) {
        var card = new Card(id);
        // set or override
        this.ability[0] = card;
        // Increase the deck provision
        this.provision.limit = 150 + Number(card.provision)

        this.renderDeck();
        console.log("Decklist.setAbility("+ id + ") > Ability set to '" + card.name +"'");
    }
    setStratagem(id) {
        var card = new Card(id);
        // set or override
        this.stratagem[0] = card;
      
        this.renderDeck();
        console.log("Decklist.setStratagem("+ id + ") > Stratagem set to '" + card.name +"'");
    }

    /*
     * Cards can be added and removed. There can also be two copies of a bronze card.
     * 1. Search if the card id is already present
     * 2. Check card.amount 
     * 3. Add/remove based on card found, card.amount = 1/2
     */
    addCard(id) {
        var card = new Card(id);
        var cardSearch = this.findCard(id);

        if(cardSearch === null) {
            // card does not exist, add card to deck
            this.cards.push(card);
            console.log("Decklist.addCard("+ id + ") > '" + card.name + "' added to Decklist.cards");
        } else if(cardSearch.amount === 1 && cardSearch.color === 'Bronze') {
            // card does exist and is a bronze card increase amount to 2 (maximum)
            cardSearch.amount = 2;
            console.log("Decklist.addCard("+ id + ") > card.amount increased by 1");
        } else {
            // card does exist but is a gold
            console.log("Decklist.addCard("+ id + ") > card.amount limit reached");
        }
        this.renderDeck();
    }
    delCard(id) {
        var card = this.findCard(id);

        if(card.amount === 1) {
            // one copy in the deck = delete.
            this.cards = this.cards.filter(x => x.id !== id);
            console.log("Decklist.delCard("+ id + ") > '" + card.name + "' removed from Decklist.cards");
        }
        else if(card.amount === 2) {
            // two copies in the deck = reduce amount
            card.amount = 1;
            console.log("Decklist.delCard("+ id + ") > card.amount decreased by 1");
        }
        this.renderDeck();
    }

    // helper function
    findCard(id) {
        var card = this.cards.find(x => x.id === id);
        if(!card) {
          return null;
        } else {
          return card;
        }
    }

    /*
     * Writing to DOM
     * this is to get things on the screen and will be less ugly in the future
     * printFUNCTION will probably moved into a renderCard function
     */
    renderDeck() {

        // reset, sort, render
        cleanUp();
    
        this.cards.sort(cardSortMultiple("-provision", "name", "power"));

        this.cards.forEach(printCard);
        this.ability.forEach(printAbility);
        this.stratagem.forEach(printStratagem);

        // provision available, deck size, unit amount
        var total = this.cards.reduce((total, obj) => obj.provision*obj.amount + total,0)
        document.getElementById("DeckProvision").innerHTML = "<img src=\"img/icon/deckbuilder/provision.png\"> " + "<span>" + (this.provision.limit - total) + "</span>";
        
        var deckSize = this.cards.reduce((total, obj) => obj.amount + total,0)
        document.getElementById("DeckSize").innerHTML = "<img src=\"img/icon/deckbuilder/cards.png\">  " + "<span>" +deckSize + "</span>";
        
        var units = this.cards.reduce((total, obj) => (obj.type ==='Unit') + total,0)
        document.getElementById("DeckUnits").innerHTML = "<img src=\"img/icon/deckbuilder/units.png\"> " + "<span>" + units + "</span>";


        function cleanUp() {
            document.getElementById("DeckCards").innerHTML = "";
        }
        function printAbility(card) {
            document.getElementById("DeckAbility").innerHTML = "";
            document.getElementById("DeckAbility").innerHTML += "<img oncontextmenu=\"cardInfo("+card.id+");return false;\" src=\"img/assets/ability/" + card.id + ".png\">";
            document.getElementById("DeckName").innerHTML = "";
            document.getElementById("DeckName").innerHTML += card.name;
        }

        function printStratagem(card) {
            document.getElementById("DeckStratagem").innerHTML = "";
            document.getElementById("DeckStratagem").innerHTML += `
                <div class="DeckCard" oncontextmenu="cardInfo('${card.id}');return false;" data-name="${card.name}" data-provision="${card.provision}" data-power="${card.power}" data-armor="${card.armor}" data-art="${card.art}" data-id="${card.id}" data-color="${card.color}" data-type="${card.type}">
                <div class="art"><img src="img/assets/deck/cards/${card.art}.png"></div>
                <div class="gradient"></div>
                <div class="border"></div>
                <div class="stratagem" style="background-image: url('img/assets/deck/other/stratagem.png');"></div>
                <div class="amount"></div>
                <div class="name">${card.name}</div>
            `;
        }
        function printCard(card) {
            document.getElementById("DeckCards").innerHTML += `
            <div class="DeckCard" onclick="Deck.delCard(${card.id})" oncontextmenu="cardInfo(${card.id});return false;" data-name="${card.name}" data-amount="${card.amount}" data-provision="${card.provision}" data-power="${card.power}" data-armor="${card.armor}" data-art="${card.art}" data-id="${card.id}" data-color="${card.color}" data-type="${card.type}">
            <div class="art"><img src="img/assets/deck/cards/${card.art}.png"></div>
            <div class="gradient"></div>
            <div class="border"></div>
            <div class="provision" style="background-image: url('img/assets/deck/provision/${card.provision}.png');"></div>
            <div class="power" style="background-image: url('img/assets/deck/power/${card.power}.png');">></div>
            <div class="amount"></div>
            <div class="name">${card.name}</div>
        `;
        }
    }  
}


function cardSort(property) {
  var sortOrder = 1;
  // Reverse sort order if the first part is a -
  if(property[0] === "-") {
      sortOrder = -1;
      property = property.substr(1);
  }
  return function (a,b) {
      // Number check if not included it will sort numbers like: 9, 8, 7, 4, 22, 14, 11
      if( !isNaN(a[property]) ) a[property] = Number(a[property]);
      if( !isNaN(b[property]) ) b[property] = Number(b[property]);

      var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
      return result * sortOrder;
  }
}

function cardSortMultiple() {
  var props = arguments;
  return function (obj1, obj2) {
      var i = 0, result = 0, numberOfProperties = props.length;
      while(result === 0 && i < numberOfProperties) {
          result = cardSort(props[i])(obj1, obj2);
          i++;
      }
      return result;
  }
}

// html2canvas pushing to element #canvas
function h2c() {
    html2canvas(document.getElementById("h2c"), { allowTaint: true, backgroundColor: "rgba(0,0,0,0)"}).then(canvas => {
        document.getElementById("canvas").append(canvas)
    });
}
// html2canvas pushing to download

function download(url){
    // create a new anchor tag
    var a = document.createElement('a');
    a.style.display = "none";
    a.setAttribute("href", url);
    a.setAttribute("download", "deck.png");
    // add anchor tag, click and remove it
    document.body.appendChild(a);
    a.click();
    a.remove();
}

function downloadDeck() {
  var element = document.getElementById("h2c");

  html2canvas(element, { allowTaint: true, backgroundColor: "rgba(0,0,0,0)"}).then(function(canvas) {
    download(canvas.toDataURL("image/png"));
  })
}

/*
 * Rightclicking a card will display the card information
 * This is done in PHP so we're doing it with AJAX here
 * Requires the postgres database to function
 * The API response can be the full html data or just a json (?response=html/json)
 */
function cardInfo(id) {
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function()
    {
        if ( this.readyState == 4 && this.status == 200 )
        {
            document.getElementById("CardInformation").innerHTML = this.responseText;
        }
    };

    ajax.open("GET", "api.php?id=" + id, true);
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.send();   
}