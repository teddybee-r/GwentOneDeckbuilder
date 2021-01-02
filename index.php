<?php 

$pdo = new PDO("pgsql:host=localhost;dbname=gwent", 'postgres', 'meowmeow');
$sql = "
        SELECT
        card.data.id->>'card' as id,
        card.data.id->>'art' as art,
        card.locale_en.name as name,
        card.data.attributes->>'provision' as provision,
        card.data.attributes->>'power' as power,
        card.data.attributes->>'armor' as armor,
        card.data.attributes->>'type' as type
        
        FROM card.data
        INNER JOIN card.locale_en ON card.locale_en.i = card.data.i
        WHERE (
        	VERSION = '8.0.0'
        AND card.data.attributes->>'faction' IN ('Neutral', 'Scoiatael')
        AND card.data.attributes->>'set' != 'NonOwnable'
        AND card.data.attributes->>'type' != 'Ability'
              )
        ORDER BY card.data.attributes->'provision' DESC";

              
$result = $pdo->query($sql)->fetchAll(PDO::FETCH_OBJ);
#var_dump($result);

?>
<!DOCTYPE html>
<html lang="en" >
<head>
    <title>Deckbuilder</title>
    
    <script type="text/javascript" src="scripts.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
    <div id="app">
        <div id="Deck">
            <div id="DeckLeader"></div>
            <div id="DeckStratagem"></div>
            <div id="DeckCards"></div>
        </div>

        <script>var Deck = new Deckbuilder();</script>

        <div id="DeckBuilder">
<?php foreach($result as $key => $card): ?>
            <div class="DeckBuilderCard" id="<?= $card->id; ?>" onclick="Deck.addCard(<?= $card->art; ?>)" data-name="<?= $card->name; ?>" data-provision="<?= $card->provision; ?>" data-power="<?= $card->power; ?>" data-armor="<?= $card->armor; ?>" data-art="<?= $card->art; ?>" data-id="<?= $card->id; ?>" data-type="<?= $card->type; ?>">
                <img src="https://gwent.one/img/assets/low/art/<?= $card->art; ?>.png">
            </div>
<?php endforeach; ?>
        </div>
    </div>

</body>
</html>
