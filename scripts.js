/*
 * Gwent Deckbuilder Libary
 * 2021-01-02    teddybee_r 
 */
class Deckbuilder
{
  /*
   * Constructor
   */
    constructor() {
      this.deck = [];
    }
    Card(id) {
      search = document.getElementById(id);
      this.provision = search.querySelectorAll('data-provision');
      this.power = search.querySelectorAll('data-power');
      this.armor = search.querySelectorAll('data-armor');
      this.name = search.querySelectorAll('data-name');
      this.id = search.querySelectorAll('data-id');
      this.art = search.querySelectorAll('data-art');
    }
    /*
     * Add card to deck
     * push to view
     */
    addCard(id) {
      console.log("Deckbuilder.addCard("+ id + ")");
      this.deck.push(id);
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