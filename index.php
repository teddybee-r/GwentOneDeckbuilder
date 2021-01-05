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
        card.data.attributes->>'color' as color,
        card.data.attributes->>'type' as type
        
        FROM        card.data
        INNER JOIN  card.locale_en ON card.data.i= card.locale_en.i

        WHERE 
        (VERSION = '8.0.0'
        AND 
        (card.data.attributes->>'faction' IN ('Neutral', 'Skellige')
        OR  card.data.attributes->>'factionSecondary' IN ('Neutral', 'Skellige'))
        AND card.data.attributes->>'set'     != 'NonOwnable'
        )
        OR (VERSION = '8.0.0' AND card.data.id->>'card' = '202140')
        ORDER BY 
        (CASE
            WHEN card.data.attributes->>'type' = 'Ability' THEN 1
            WHEN card.data.attributes->>'type' = 'Stratagem' THEN 2
        END),
        card.data.attributes->'provision' DESC,
        card.data.attributes->'provision' DESC,
        card.locale_en.name
        ";
$version = '8.0.0';

              
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
        <div id="Deck" data-version="<?= $version; ?>">
            <div id="DeckProvision"></div>
            <div id="DeckLeader"></div>
            <div id="DeckStratagem"></div>
            <div id="DeckCards"></div>
        </div>

        <script>var Deck = new Decklist();</script>

        <div id="DeckBuilder">
<?php foreach($result as $key => $card): ?>
    <?php switch($card->type): 
        case("Ability"): ?>
            <div class="DeckBuilderLeader" id="<?= $card->id; ?>" onclick="Deck.setLeader(<?= $card->id; ?>)" data-name="<?= $card->name; ?>" data-provision="<?= $card->provision; ?>" data-power="<?= $card->power; ?>" data-armor="<?= $card->armor; ?>" data-art="<?= $card->art; ?>" data-id="<?= $card->id; ?>" data-color="<?= $card->color; ?>" data-type="<?= $card->type; ?>">
                <img loading="lazy" src="https://gwent.one/img/icon/ability/<?= $card->id; ?>.png">
            </div>
        <?php break; ?>
        <?php case("Stratagem"): ?>
            <div class="DeckBuilderStratagem" id="<?= $card->id; ?>" onclick="Deck.setStratagem(<?= $card->id; ?>)" data-name="<?= $card->name; ?>" data-provision="<?= $card->provision; ?>" data-power="<?= $card->power; ?>" data-armor="<?= $card->armor; ?>" data-art="<?= $card->art; ?>" data-id="<?= $card->id; ?>" data-color="<?= $card->color; ?>" data-type="<?= $card->type; ?>">
                <img loading="lazy" src="https://gwent.one/img/assets/low/art/<?= $card->art; ?>.png">
            </div>
        <?php break; ?>
        <?php default: ?>
            <div class="DeckBuilderCard" id="<?= $card->id; ?>" onclick="Deck.addCard(<?= $card->id; ?>)" data-name="<?= $card->name; ?>" data-provision="<?= $card->provision; ?>" data-power="<?= $card->power; ?>" data-armor="<?= $card->armor; ?>" data-art="<?= $card->art; ?>" data-id="<?= $card->id; ?>" data-color="<?= $card->color; ?>" data-type="<?= $card->type; ?>">
                <img loading="lazy" src="https://gwent.one/img/assets/low/art/<?= $card->art; ?>.png">
            </div>
        <?php break; ?>
    <?php endswitch; ?>
<?php endforeach; ?>
    </div>

</body>
</html>
