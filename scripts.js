/*
 * Gwent Deckbuilder Libary
 * 2021-01-02    teddybee_r 
 * 
{
  "version": "8.0.0" ,
  "cards": {
    "leader": {"id": 100000, "provision": 15},
    "stratagem": "",
    "deck": {
      "id": 100000,
      "amount": 1,
      "data": {} 
    } 
  } 
}

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

    console.log("new Card() ID: " + this.id + ", Name: " + this.name + ", Provision: " + this.provision + ", Power: " + this.power  + ", Armor: " + this.armor  + ", Art: " + this.art  + ", Type: " + this.type )
  }
}


class Decklist
{

  /*
   * Constructor
   */
    constructor() {
      /* @var string */
      this.version = document.getElementById("Deck").dataset.version;
      /* @var int */
      this.provisionTotal = 0;
      /* @var int */
      this.provisionLimit = 150;
      /* @var int */
      this.deckSize = 0;
      /* @var obj */
      this.leader = [];
      /* @var obj */
      this.stratagem = [];
      /* @var obj */
      this.cards = [];
    }

    removeArray(array, value) { 
      return array.filter(function(element){ 
          return element != value; 
      });
    }


    setLeader(id) {
      console.log("Decklist.setLeader("+ id + ")");
      var card = new Card(id);

      this.leader[0] = card;
      this.provisionLimit = 150 + Number(card.provision)

      this.printDeck();
    }

    setStratagem(id) {
      console.log("Decklist.setStratagem("+ id + ")");

      var card = new Card(id);

      this.stratagem[0] = card;
      
      this.printDeck();
    }

    addCard(id) {
      console.log("Decklist.addCard("+ id + ")");

      var card = new Card(id);
      var cardSearch = this.cards.find(x => x.id === id);
      console.log(cardSearch);

      /*
       * If cardSearch is not defined push it to the deck
       */
      if(cardSearch === undefined) {
        this.cards.push(card);
      }
      /*
       * If it is we increase the amount by 1
       * Only bronze are allowed to have two copies
       */
      else if(cardSearch.amount === 1 && cardSearch.color === 'Bronze') {
        cardSearch.amount = 2;
      }
      this.printDeck();
    }

    delCard(id) {
      console.log("Deckbuilder.delCard("+ id + ")");
      var card = this.cards.find(x => x.id === id);

      if(card.amount === 1) {
        this.cards = this.cards.filter(card => card.id !== id);
      }
      else if(card.amount === 2) {
        card.amount = 1;
      }

      this.printDeck();
    }

    setProvision() {
      var limit = this.provisionLimit;

      var total = this.cards.reduce((total, obj) => Number(obj.provision*obj.amount) + total,0)
      document.getElementById("DeckProvision").innerHTML = total + "/" + limit;
      
      var deckSize = this.cards.reduce((total, obj) => Number(obj.amount) + total,0)
      document.getElementById("DeckSize").innerHTML = deckSize + "/25";
      
    }

    printDeck() {
      console.log("Deckbuilder.printDeck()");

      /* reset the deck */
      cleanUp();
      /* sort the deck */
      this.cards.sort(dynamicSortMultiple("-provision", "name"));

      /* loop the deck object and write to document */
      this.cards.forEach(printCard);
      this.leader.forEach(printLeader);
      this.stratagem.forEach(printStratagem);

      /* Set the Provisions */
      this.setProvision();

      function cleanUp() {
        document.getElementById("DeckCards").innerHTML = "";
      }
      function printLeader(card) {
        document.getElementById("DeckLeader").innerHTML = "";
        document.getElementById("DeckLeader").innerHTML += "<img style=\"height:125px;\" src=\"https://gwent.one/img/icon/ability/" + card.id + ".png\"><br>";
      }
      function printStratagem(card) {
        document.getElementById("DeckStratagem").innerHTML = "";
        document.getElementById("DeckStratagem").innerHTML += "<img class=\"DeckCard\" src=\"https://gwent.one/img/assets/deck/cards/" + card.art + ".png\"><br>";
      }
      function printCard(card) {
        document.getElementById("DeckCards").innerHTML += "<img class=\"DeckCard\" onclick=\"Deck.delCard("+card.id+")\" src=\"https://gwent.one/img/assets/deck/cards/" + card.art + ".png\"><br>";

      }
    }  
}


function dynamicSort(property) {
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

function dynamicSortMultiple() {
  var props = arguments;
  return function (obj1, obj2) {
      var i = 0, result = 0, numberOfProperties = props.length;
      while(result === 0 && i < numberOfProperties) {
          result = dynamicSort(props[i])(obj1, obj2);
          i++;
      }
      return result;
  }
}











function lzw_encode(s) {
  var dict = {};
  var data = (s + "").split("");
  var out = [];
  var currChar;
  var phrase = data[0];
  var code = 256;
  for (var i=1; i<data.length; i++) {
      currChar=data[i];
      if (dict[phrase + currChar] != null) {
          phrase += currChar;
      }
      else {
          out.push(phrase.length > 1 ? dict[phrase] : phrase.charCodeAt(0));
          dict[phrase + currChar] = code;
          code++;
          phrase=currChar;
      }
  }
  out.push(phrase.length > 1 ? dict[phrase] : phrase.charCodeAt(0));
  for (var i=0; i<out.length; i++) {
      out[i] = String.fromCharCode(out[i]);
  }
  return out.join("");
}

// Decompress an LZW-encoded string
function lzw_decode(s) {
  var dict = {};
  var data = (s + "").split("");
  var currChar = data[0];
  var oldPhrase = currChar;
  var out = [currChar];
  var code = 256;
  var phrase;
  for (var i=1; i<data.length; i++) {
      var currCode = data[i].charCodeAt(0);
      if (currCode < 256) {
          phrase = data[i];
      }
      else {
         phrase = dict[currCode] ? dict[currCode] : (oldPhrase + currChar);
      }
      out.push(phrase);
      currChar = phrase.charAt(0);
      dict[code] = oldPhrase + currChar;
      code++;
      oldPhrase = phrase;
  }
  return out.join("");
}