console.log("┬┴┬┴┬┴┤                      ʕ·ᴥ·├┬┴┬┴┬┴");
console.log("┬┴┬┴┬┴┤ Gwent Deckbuilder Libary ├┬┴┬┴┬┴");
console.log("┬┴┬┴┬┴┤ 2021-01-06    teddybee_r ├┬┴┬┴┬┴");
console.log("┬┴┬┴┬┴┤   thanks                 ├┬┴┬┴┬┴");
console.log("┬┴┬┴┬┴┤•ᴥ•ʔ  for poking around   ├┬┴┬┴┬┴");

/*
 * This deckbuilder works by making all the data available on the website
 * using data-attribute- tags.
*/
class Card {
  constructor(card) {
    var card = document.getElementById(card);

    this.id = Number(card.dataset.id);
    this.amount = 1;
    this.provision = Number(card.dataset.provision);
    this.power = Number(card.dataset.power);
    this.armor = Number(card.dataset.armor);
    this.name = card.dataset.name;
    this.art = Number(card.dataset.art);
    this.color = card.dataset.color;
    this.type = card.dataset.type;

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

    setAbility(id) {
        var card = new Card(id);

        this.ability[0] = card;
        this.provision.limit = 150 + Number(card.provision)

        this.printDeck();
        console.log("Decklist.setAbility("+ id + ") > Ability set to '" + card.name +"'");
    }

    setStratagem(id) {
        var card = new Card(id);

        this.stratagem[0] = card;
      
        this.printDeck();
        console.log("Decklist.setStratagem("+ id + ") > Stratagem set to '" + card.name +"'");
    }

    addCard(id) {
        var card = new Card(id);
        var cardSearch = this.findCard(id);

        if(cardSearch === null) {
            this.cards.push(card);
            console.log("Decklist.addCard("+ id + ") > '" + card.name + "' added to Decklist.cards");
        } else if(cardSearch.amount === 1 && cardSearch.color === 'Bronze') {
            cardSearch.amount = 2;
            console.log("Decklist.addCard("+ id + ") > card.amount increased by 1");
        } else {
            console.log("Decklist.addCard("+ id + ") > card.amount limit reached");
        }
        this.printDeck();
    }

    delCard(id) {
        var card = this.findCard(id);

        if(card.amount === 1) {
            this.cards = this.cards.filter(x => x.id !== id);
            console.log("Decklist.delCard("+ id + ") > '" + card.name + "' removed from Decklist.cards");
        }
        else if(card.amount === 2) {
            card.amount = 1;
            console.log("Decklist.delCard("+ id + ") > card.amount decreased by 1");
        }
        this.printDeck();
    }

    findCard(id) {
        var card = this.cards.find(x => x.id === id);
        if(!card) {
          return null;
        } else {
          return card;
        }
    }

    printDeck() {

      /* reset, sort, render, provision */
      cleanUp();

      this.cards.sort(cardSortMultiple("-provision", "name"));

      this.cards.forEach(printCard);
      this.ability.forEach(printAbility);
      this.stratagem.forEach(printStratagem);

      var total = this.cards.reduce((total, obj) => Number(obj.provision*obj.amount) + total,0)
      document.getElementById("DeckProvision").innerHTML = "Provision: " + total + " / " + this.provision.limit;
      var deckSize = this.cards.reduce((total, obj) => Number(obj.amount) + total,0)
      document.getElementById("DeckSize").innerHTML = "Cards: " + deckSize + " / 25";


      function cleanUp() {
        document.getElementById("DeckCards").innerHTML = "";
      }
      function printAbility(card) {
        document.getElementById("DeckAbility").innerHTML = "";
        document.getElementById("DeckAbility").innerHTML += "<img style=\"height:100px;\" src=\"https://gwent.one/img/icon/ability/" + card.id + ".png\"><br>";
      }
      function printStratagem(card) {
        document.getElementById("DeckStratagem").innerHTML = "";
        document.getElementById("DeckStratagem").innerHTML += "<img class=\"DeckCard\" src=\"https://gwent.one/img/assets/deck/cards/" + card.art + ".png\"><br>";
      }
      function printCard(card) {
        document.getElementById("DeckCards").innerHTML += `
        <div class="DeckCard" onclick="Deck.delCard(${card.id})" data-name="${card.name}" data-amount="${card.amount}" data-provision="${card.provision}" data-power="${card.power}" data-armor="${card.armor}" data-art="${card.art}" data-id="${card.id}" data-color="${card.color}" data-type="${card.type}">
          <div class="art"><img data-amount="${card.amount}" src="https://gwent.one/img/assets/deck/cards/${card.art}.png"></div>
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
  /* Reverse sort oder if the first part is a - */
  if(property[0] === "-") {
      sortOrder = -1;
      property = property.substr(1);
  }
  return function (a,b) {
      /* Number check if not included it will sort numbers like: 9, 8, 7, 4, 22, 14, 11 */
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