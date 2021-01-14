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
<div id="app-nav">
    <div class="filters">

        <div class="filter name">
            <div class="filter-head">Name</div>
            <div class="filter-option">
                <input type="text" id="searchName" value="" placeholder="Search">
                <div onclick="filterDeckbuilderTextbox('name', 'searchName')">Name</div>
            </div>
        </div>
        <div class="filter type">
            <div class="filter-head">Type</div>
            <div class="filter-option">
                <div onclick="filterDeckbuilder('type', 'ability')"     ><img src="img\icon\filter\type\black\leader.png"></div>
                <div onclick="filterDeckbuilder('type', 'stratagem')"   ><img src="img\icon\filter\type\black\stratagem.png"></div>
                <div onclick="filterDeckbuilder('type', 'unit')"        ><img src="img\icon\filter\type\black\unit.png"></div>
                <div onclick="filterDeckbuilder('type', 'special')"     ><img src="img\icon\filter\type\black\special.png"></div>
                <div onclick="filterDeckbuilder('type', 'artifact')"    ><img src="img\icon\filter\type\black\artifact.png"></div>
                <div onclick="filterDeckbuilder('type', '*')"           ><img src="img\icon\filter\other\black\all.png"></div>
            </div>
        </div>
        <div class="filter rarity">
            <div class="filter-head">Rarity</div>
            <div class="filter-option">
                <div onclick="filterDeckbuilder('rarity', 'common')"    ><img src="img\icon\filter\rarity\common.png"></div>
                <div onclick="filterDeckbuilder('rarity', 'rare')"      ><img src="img\icon\filter\rarity\rare.png"></div>
                <div onclick="filterDeckbuilder('rarity', 'epic')"      ><img src="img\icon\filter\rarity\epic.png"></div>
                <div onclick="filterDeckbuilder('rarity', 'legendary')" ><img src="img\icon\filter\rarity\legendary.png"></div>
                <div onclick="filterDeckbuilder('rarity', '*')"         ><img src="img\icon\filter\other\black\all.png"></div>
            </div>
        </div>
        <div class="filter set"> 
            <div class="filter-head">Set</div>
            <div class="filter-option">
                <div onclick="filterDeckbuilder('set', 'unmillable')"           ><img src="img\icon\filter\set\black\starter.png"></div>
                <div onclick="filterDeckbuilder('set', 'baseset')"              ><img src="img\icon\filter\set\black\base.png"></div>
                <div onclick="filterDeckbuilder('set', 'thronebreaker')"        ><img src="img\icon\filter\set\black\thronebreaker.png"></div>
                <div onclick="filterDeckbuilder('set', 'novigrad')"             ><img src="img\icon\filter\set\black\novigrad.png"></div>
                <div onclick="filterDeckbuilder('set', 'iron judgment')"        ><img src="img\icon\filter\set\black\iron_judgment.png"></div>
                <div onclick="filterDeckbuilder('set', 'merchants of ofir')"    ><img src="img\icon\filter\set\black\merchants_of_ofir.png"></div>
                <div onclick="filterDeckbuilder('set', 'master mirror')"        ><img src="img\icon\filter\set\black\master_mirror.png"></div>
                <div onclick="filterDeckbuilder('set', 'way of the witcher')"   ><img src="img\icon\filter\set\black\way_of_the_witcher.png"></div>
                <div onclick="filterDeckbuilder('set', '*')"                    ><img src="img\icon\filter\other\black\all.png"></div>
            </div>
        </div>
        <div class="filter provision">
            <div class="filter-head">Provision</div>
            <div class="filter-option">
                <div onclick="filterDeckbuilder('provision', '4')"  >4</div>
                <div onclick="filterDeckbuilder('provision', '5')"  >5</div>
                <div onclick="filterDeckbuilder('provision', '6')"  >6</div>
                <div onclick="filterDeckbuilder('provision', '7')"  >7</div>
                <div onclick="filterDeckbuilder('provision', '8')"  >8</div>
                <div onclick="filterDeckbuilder('provision', '9')"  >9</div>
                <div onclick="filterDeckbuilder('provision', '10')" >10</div>
                <div onclick="filterDeckbuilder('provision', '11')" >11</div>
                <div onclick="filterDeckbuilder('provision', '12')" >12</div>
                <div onclick="filterDeckbuilder('provision', '13')" >13</div>
                <div onclick="filterDeckbuilder('provision', '14')" >14</div>
                <div onclick="filterDeckbuilder('provision', '*')"  ><img src="img\icon\filter\other\black\all.png"></div>
            </div>
        </div>
        <div class="filter power">
            <div class="filter-head">Power</div>
            <div class="filter-option">
                <div onclick="filterDeckbuilder('power', '1')"  >1</div>
                <div onclick="filterDeckbuilder('power', '2')"  >2</div>
                <div onclick="filterDeckbuilder('power', '3')"  >3</div>
                <div onclick="filterDeckbuilder('power', '4')"  >4</div>
                <div onclick="filterDeckbuilder('power', '5')"  >5</div>
                <div onclick="filterDeckbuilder('power', '6')"  >6</div>
                <div onclick="filterDeckbuilder('power', '7')"  >7</div>
                <div onclick="filterDeckbuilder('power', '8')"  >8</div>
                <div onclick="filterDeckbuilder('power', '9')"  >9</div>
                <div onclick="filterDeckbuilder('power', '10')" >10</div>
                <div onclick="filterDeckbuilder('power', '11')" >11</div>
                <div onclick="filterDeckbuilder('power', '12')" >12</div>
                <div onclick="filterDeckbuilder('power', '13')" >13</div>
                <div onclick="filterDeckbuilder('power', '*')"  ><img src="img\icon\filter\other\black\all.png"></div>
            </div>
        </div>
    </div>
    <div onclick="downloadDeck()">Download</div>
</div>

<div id="app">
    <div id="Deck" data-version="<?= $version; ?>">
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

<?php $abilities = $stratagems = $cards = []; ?>

    <div id="DeckBuilder">
<?php foreach($result as $key => $card): ?>
    <?php include("resources/views/deckbuilder/card-list.php"); ?>
<?php endforeach; ?>
    </div>

    <div id="CardInformation"></div>
    
<script>
    var Deck = new Decklist();
    Deck.setStratagem(202140);
</script>

</div>
</body>
</html>