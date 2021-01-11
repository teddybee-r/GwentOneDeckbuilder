# Gwent Deck Builder
Requires [GwentOneDB](https://github.com/teddybee-r/GwentOneDB "github.com/teddybee-r/GwentOneDB")  

# Work in Progress
Just wait we're slowly getting there..  
Most of the stuff here right now is get things on the screen not get them nicely there.

# Decklist images
Using [html2canvas](https://html2canvas.hertzen.com/ "html2canvas.hertzen.com") (.js) for deck images.


# ideas
deck codes: generated from a sorted array of ID only? (duplicates appear twice) [2000001, 2000002, 200003, 200004, 200005, 200005, ...]
since we are supporting 12 languages we can't really store things like name here and need to rebuild it later

# todos
- rewrite for multi lang
- rewrite html/css/js .DeckCard (deckbuilder.js)
- add js filtering for cards (similar to gwent.one/en/cards/)

# bugs
- js: unit counter currently ignores amount

# notice
I am no developer all of my knowledge is build up with gwent.one.  
This includes the backend since everything is hosted on baremetal in docker containers.  
I'll gladly take advices but please note that I want to understand things.  
I want to go without libaries and scripts (html2canvas) as much as possible.  
gwent.one is also being slowly redone without bootstrap.  

This is why you'll might find some stuff in here that does nothing :)