/*
 * Gwent Deckbuilder Libary
 * 2021-01-02    teddybee_r 
 */

class Card {
  constructor(card) {
    var card = document.getElementById(card);
    console.log(card);
    this.provision = card.querySelector('data-provision');
    console.log(this.provision);
    this.power = card.querySelector('data-power');
    this.armor = card.querySelector('data-armor');
    this.name = card.querySelector('data-name');
    this.art = card.querySelector('data-art');
    this.id = card.querySelector('data-id');
    this.type = card.querySelector('data-type');
  }
}
class Deckbuilder
{
  /*
   * Constructor
   */
    constructor() {
      this.deck = [];
    }
    /*
     * Add card to deck
     * push to view
     */
    addCard(id) {
      console.log("Deckbuilder.addCard("+ id + ")");
      var card = new Card(id);
      this.deck.push(card);
      console.log(this.deck);
      this.printDeck();
    }
    delCard(id) {
      console.log("Deckbuilder.delCard("+ id + ")");
      const array = this.deck;
      
      console.log(array);

      const index = array.indexOf(id);
      if (index > -1) {
       array.splice(index, 1);
      }
      this.deck = array;
      this.printDeck();
    }
    printDeck() {
      console.log("Deckbuilder.printDeck()");

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