<?php 
/*
 * API for GwentOneDeckbuilder
 * Right clicking on a card will bring up card information to the right of the list
 * Required: $id, $version, $lang
 * Response: HTML, JSON
 */

/*
 * Todo:
 * validation
 * css sass for this response 
 */

$id       = $_GET["id"] ?? 202308 ;
$response = $_GET["response"] ?? 'html';
$lang     = $_GET["lang"] ?? 'en';
$version  = '8.0.0';

$pdo = new PDO("pgsql:host=localhost;dbname=gwent", 'postgres', 'meowmeow');
$sql = "
        SELECT *     
        FROM        card.data
        INNER JOIN  card.locale_$lang ON card.data.i= card.locale_en.i
        WHERE (card.data.version = :version AND card.data.id->'card' = :id)
        ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':version', $version);
$stmt->bindParam(':id', $id);
$stmt->execute();

$card = $stmt->fetch(PDO::FETCH_OBJ);

$attr         = json_decode($card->attributes);
$ids          = json_decode($card->id);
$id           = $ids->card;
$art          = $ids->art;
$audio        = $ids->audio;
$name         = $card->name;
$category     = $card->category;
$ability      = str_replace('\n', '<br>', $card->ability_html);
$keywords     = str_replace('\n', '<br>', $card->keyword_html);
$flavor       = str_replace('\n', '<br>', $card->flavor);
$faction      = str_replace(' ', '_', strtolower($attr->faction));
$faction2     = str_replace(' ', '_', strtolower($attr->factionSecondary));
$set          = strtolower($attr->set);
$color        = strtolower($attr->color);
$type         = strtolower($attr->type);
$rarity       = strtolower($attr->rarity);
$power        = $attr->power;
$armor        = $attr->armor;
$provision    = $attr->provision;
$reach	      = $attr->reach;
$abilityIcon  = 2;
?>

<?php if($response === 'html'): ?>
<div class="cardInfo">

    <div class="api card-render" data-id="<?= $id; ?>" data-artid="<?= $art; ?>j" data-power="<?=$power;?>" data-armor="<?=$armor;?>" data-provision="<?=$provision;?>" data-faction="<?=$faction;?>" data-set="<?=$set;?>" data-color="<?=$color;?>" data-type="<?=$type;?>" data-rarity="<?=$rarity;?>" <?php if($faction2!=''){ echo ' data-faction-duo="' . $faction . '_' . $faction2 . '"';}?>>

        <div class="info">
            <div class="head">
                <div class="name"><?= $name; ?></div>
                <div class="category"><?= $category ?></div>
            </div>

            <div class="body">
                <div class="ability"><?= $ability; ?></div>  
                <?php if(!empty($keywords)): ?>                    
                <div class="seperator-short"></div>                    
                <div class="keywords"><?= $keywords; ?></div>                    
                <?php endif; ?>            
                <div class="seperator-long"></div>                    
                <div class="flavor"><?= $flavor; ?></div>      
            </div>
        </div>
                          
        <div class="image">
                
            <div class="art" onclick="premiumVideo(<?= $id;?>, <?= $art;?>)" data-res="medium">
<?php switch($type):
      case 'ability': ?>
<?php if ($abilityIcon == 0): ?>
                <div id="premium" class="img"><img src="https://gwent.one/img/assets/medium/art/<?= $art;?>.jpg"></div>
                <div class="asset border"></div>
                <div class="asset banner"></div>
                <div class="asset ability-icon"></div>
                <div class="asset provision-icon"><div class="asset provision"></div></div>
                <div class="asset rarity"></div>
<?php elseif ($abilityIcon == 1): ?>
                <img class="ability-icon-249" loading="lazy" src="https://gwent.one/img/icon/ability/pre720/<?= $id; ?>.png">
<?php elseif ($abilityIcon == 2): ?>
                <img class="ability-icon-249" loading="lazy" src="https://gwent.one/img/icon/ability/<?= $id; ?>.png">
<?php endif; ?>
<?php break;?>
                      
<?php case 'stratagem': ?>
                <div id="premium" class="img"><img src="https://gwent.one/img/assets/medium/art/<?= $art;?>.jpg"></div>
                <div class="asset border"></div>
                <div class="asset banner"></div>
                <div class="asset trinket"></div>
                <div class="asset rarity"></div>
<?php break;?>
                
<?php case 'unit': ?>
                <div id="premium" class="img"><img src="https://gwent.one/img/assets/medium/art/<?= $art;?>.jpg"></div>
                <div class="asset border"></div>
<?php if($armor!=0) : ?>                                        
                <div class="asset armor-icon"><div class="asset armor"></div></div>
<?php endif; ?>
<?php if($provision!=0) : ?>                                        
                <div class="asset provision-icon"></div>
                <div class="asset provision-bg"><div class="asset provision"></div></div>
<?php endif; ?>
                <div class="asset banner"><div class="asset power"></div></div>
                <div class="asset rarity"></div>
<?php break;?>
                  
<?php case 'artifact': ?>
                <div id="premium" class="img"><img src="https://gwent.one/img/assets/medium/art/<?= $art;?>.jpg"></div>
                <div class="asset border"></div>
<?php if($provision!=0) : ?>     
                <div class="asset provision-icon"></div>
                <div class="asset provision-bg"><div class="asset provision"></div></div>
<?php endif; ?>
                <div class="asset banner"></div>
                <div class="asset trinket"></div>
                <div class="asset rarity"></div>
<?php break;?>
                  
<?php case 'special': ?>
                <div id="premium" class="img"><img src="https://gwent.one/img/assets/medium/art/<?= $art;?>.jpg"></div>
                <div class="asset border"></div>
<?php if($provision!=0) : ?>     
                <div class="asset provision-icon"></div>
                <div class="asset provision-bg"><div class="asset provision"></div></div>
<?php endif; ?>
                <div class="asset banner"></div>
                <div class="asset trinket"></div>
                <div class="asset rarity"></div>
<?php break;?>
<?php endswitch;?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if($response === 'json') {
    header('Content-type: application/json; charset=UTF-8');
    $json = [
        'id' => $id,
        'art' => $art,
        'name' => $name,
        'flavor' => $flavor,
        'category' => $category,
        'ability' => $ability,
        'keyword' => $keywords,
        'provision' => $provision,
        'power' => $power,
        'armor' => $armor,
        'faction' => $faction,
        'set' => $set,
        'color' => $color,
        'type' => $type,
        'rarity' => $rarity,
        'reach' => $reach,
    ];
    echo json_encode($json, JSON_FORCE_OBJECT);
}
?>