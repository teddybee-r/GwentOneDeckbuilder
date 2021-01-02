/*
 * Gwent Deckbuilder Libary
 * 2021-01-02    teddybee_r 
 */

class Card {
  constructor(card) {
    var card = document.getElementById(card);

    this.provision = card.dataset.provision;
    this.power = card.dataset.power;
    this.armor = card.dataset.armor;
    this.name = card.dataset.name;
    this.art = card.dataset.art;
    this.id = card.dataset.id;
    this.type = card.dataset.type;

    console.log("new Card()");
    console.log("ID: " + this.id + ", Name: " + this.name + ", Provision: " + this.provision + ", Power: " + this.power  + ", Armor: " + this.armor  + ", Art: " + this.art  + ", Type: " + this.type )
  }
}
class Deckbuilder
{
  /*
   * Constructor
   */
    constructor() {
      this.deck = [];
      this.deckAttr = [];
    }

    
    removeArray(array, value) { 
      return array.filter(function(element){ 
          return element != value; 
      });
    }
    /*
     * Add card to deck
     * push to view
     */
    addCard(id) {
      console.log("Deckbuilder.addCard("+ id + ")");
      var card = new Card(id);
      this.deck.push(id);
      this.deckAttr.push({"cardid": id, "card": card});
      console.log(this.deck);
      console.log(this.deckAttr);
      this.printDeck();
    }

    delCard(id) {
      console.log("Deckbuilder.delCard("+ id + ")");
      const array = this.deck;
      
      console.log(array);
      this.deckAttr = this.deckAttr.filter(card => card.cardid != id);
      this.deck = this.removeArray(this.deck, id);
      this.printDeck();
    }

    printDeck() {
      console.log("Deckbuilder.printDeck()");

      /* reset the deck */
      cleanUp();
      this.deck.forEach(printJob);

      function cleanUp() {
        document.getElementById("DeckCards").innerHTML = "";
      }

      function printJob(item) {
        document.getElementById("DeckCards").innerHTML += "<img onclick=\"Deck.delCard("+item+")\" src=\"https://gwent.one/img/assets/deck/cards/" + item + ".png\"><br>";
      }
    }  
}