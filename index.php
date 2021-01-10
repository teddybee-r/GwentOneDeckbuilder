<?php 

$pdo = new PDO("pgsql:host=localhost;dbname=gwent", 'postgres', 'meowmeow');
$faction = $_GET["faction"] ?? 'Skellige';
$version = '8.0.0';
$sql = "
        SELECT *        
        FROM        card.data
        INNER JOIN  card.locale_en ON card.data.i= card.locale_en.i

        WHERE 
        (VERSION = :version
        AND 
        (card.data.attributes->>'faction' IN ('Neutral', :faction)
        OR  card.data.attributes->>'factionSecondary' IN ('Neutral', :faction))
        AND card.data.attributes->>'set'     != 'NonOwnable'
        )
        OR (VERSION = :version AND card.data.id->>'card' = '202140')
        ORDER BY 
        (CASE
            WHEN card.data.attributes->>'type' = 'Ability' THEN 1
            WHEN card.data.attributes->>'type' = 'Stratagem' THEN 2
        END),
        card.data.attributes->'provision' DESC,
        card.locale_en.name
        ";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':version', $version);
$stmt->bindParam(':faction', $faction);
$stmt->execute();
              
$result = $stmt->fetchAll(PDO::FETCH_OBJ);
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
        <h1>Filters go here</h1>
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
                <div id="DeckName">gwent.one</div>
                <div id="DeckAbility"><img src="img/assets/ability/000000.png"></div>
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
    <?php include("resources/views/deckbuilder/card-list.php"); ?>
<?php endforeach; ?>
    </div>

    <div id="CardInformation">
    </div>
    <script>
        var Deck = new Decklist();
        Deck.setStratagem(202140);

        function premiumVideo(card, art) {
            var image = document.getElementById("premium");
            var video = `
            <video id="premium"  onclick="premiumVideo(`+card+`, `+art+`)" poster="https://gwent.one/img/assets/medium/art/`+art+`.jpg" class="premium__video" autoplay="yes" width="249" height="357">
                <source src="https://gwent.one/video/card/premium/`+card+`.webm" type="video/webm">
                <source src="https://gwent.one/video/card/premium/`+card+`.mp4" type="video/mp4">
            </video>`;
            image.outerHTML = video;
        }
    </script>

<script>
</script>
</body>
</html>