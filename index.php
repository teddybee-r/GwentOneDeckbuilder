<?php 

$pdo = new PDO("pgsql:host=localhost;dbname=gwent", 'postgres', 'meowmeow');
$faction = $_GET["faction"] ?? 'Skellige';
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
        (card.data.attributes->>'faction' IN ('Neutral', '$faction')
        OR  card.data.attributes->>'factionSecondary' IN ('Neutral', '$faction'))
        AND card.data.attributes->>'set'     != 'NonOwnable'
        )
        OR (VERSION = '8.0.0' AND card.data.id->>'card' = '202140')
        ORDER BY 
        (CASE
            WHEN card.data.attributes->>'type' = 'Ability' THEN 1
            WHEN card.data.attributes->>'type' = 'Stratagem' THEN 2
        END),
        card.data.attributes->'provision' DESC,
        card.locale_en.name
        ";
$version = '8.0.0';

              
$result = $pdo->query($sql)->fetchAll(PDO::FETCH_OBJ);
#var_dump($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta name="viewport"           content="width=device-width, initial-scale=1">
    <meta name="theme-color"        content="#334455">
    <meta name="description"        content="Build your own Gwent decks online.">
    <meta property="og:title"       content="Gwent Deckbuilder"/>
    <meta property="og:type"        content="website"/>
    <meta property="og:image"       content="https://gwent.one/img/icon/favicon/192x192.png" />
    <meta property="og:url"         content="https://gwent.one/"/>
    <meta property="og:site_name"   content="The Voice of Gwent - gwent.one"/>
    <meta property="og:description" content="Build your own Gwent decks online.">

    <script type="text/javascript" src="js/deckbuilder.js"></script>
    <script type="text/javascript" src="js/html2canvas.js"></script>

    <link rel="stylesheet" type="text/css" href="css/style.css">

    <title>Deckbuilder</title>
    

</head>
<body>
    <div id="canvas"></div>
    <div id="app-nav">
        <h1>app-nav</h1>
        <div class="filters">
            <div class="faction"></div>
            <div class="type"></div>
            <div class="rarity"></div>
            <div class="set"></div>
            <div class="provision"></div>
            <div class="power"></div>
        </div>
        <button onclick="downloadDeck()">Download</button>
    </div>
    <div id="app">
        <div id="Deck" data-version="<?= $version; ?>">
        <div id="h2c">
            <div class="head">
                <div id="DeckName"></div>
                <div id="DeckAbility"></div>
                <div id="DeckSize"></div>
                <div id="DeckUnits"></div>
                <div id="DeckProvision"></div>
            </div>
            <div id="DeckStratagem"></div>
            <div id="DeckCards"></div>
        </div>
        </div>


        <div id="DeckBuilder">
        <?php 
            $abilities = [];
            $stratagems = [];
            $cards = []; 
        ?>
<?php foreach($result as $key => $card): ?>
    <?php switch($card->type): 
        case("Ability"): ?>
        <?php array_push($abilities, $card); ?>
            <div class="DeckBuilderLeader" id="<?= $card->id; ?>" onclick="Deck.setAbility(<?= $card->id; ?>)" data-name="<?= $card->name; ?>" data-provision="<?= $card->provision; ?>" data-power="<?= $card->power; ?>" data-armor="<?= $card->armor; ?>" data-art="<?= $card->art; ?>" data-id="<?= $card->id; ?>" data-color="<?= $card->color; ?>" data-type="<?= $card->type; ?>">
                <img src="img/assets/ability/<?= $card->id; ?>.png">
            </div>
        <?php break; ?>
        <?php case("Stratagem"): ?>
        <?php array_push($stratagems, $card); ?>
            <div class="DeckBuilderStratagem" id="<?= $card->id; ?>" onclick="Deck.setStratagem(<?= $card->id; ?>)" data-name="<?= $card->name; ?>" data-provision="<?= $card->provision; ?>" data-power="<?= $card->power; ?>" data-armor="<?= $card->armor; ?>" data-art="<?= $card->art; ?>" data-id="<?= $card->id; ?>" data-color="<?= $card->color; ?>" data-type="<?= $card->type; ?>">
                <img src="img/assets/low/art/<?= $card->art; ?>.png">
            </div>
        <?php break; ?>
        <?php default: ?>
        <?php array_push($cards, $card); ?>
            <div class="DeckBuilderCard" id="<?= $card->id; ?>" onclick="Deck.addCard(<?= $card->id; ?>)" oncontextmenu="cardInfo(<?= $card->id; ?>);return false;" data-name="<?= $card->name; ?>" data-provision="<?= $card->provision; ?>" data-power="<?= $card->power; ?>" data-armor="<?= $card->armor; ?>" data-art="<?= $card->art; ?>" data-id="<?= $card->id; ?>" data-color="<?= $card->color; ?>" data-type="<?= $card->type; ?>">
                <img src="img/assets/low/art/<?= $card->art; ?>.png">
            </div>
        <?php break; ?>
    <?php endswitch; ?>
<?php endforeach; ?>
    </div>

    <div id="CardInformation">
    </div>
    <script>
        var Deck = new Decklist();
        Deck.setStratagem(202140);
    </script>
</body>
</html>
