<?php 

$pdo = new PDO("pgsql:host=localhost;dbname=gwent", 'postgres', 'meowmeow');
$id = $_GET["id"] ?? 202308 ;
$lang = $_GET["lang"] ?? 'en';
$version = '8.0.0';
$sql = "
        SELECT *     
        FROM        card.data
        INNER JOIN  card.locale_$lang ON card.data.i= card.locale_en.i
        WHERE (card.data.version = '8.0.0' AND card.data.id->'card' = '$id')
        ";
              
$card = $pdo->query($sql)->fetch(PDO::FETCH_OBJ);

?>
<div class="cardInfo">
    <div class="content-wrap">
<?php 
$attr         = json_decode($card->attributes);
$ids          = json_decode($card->id);
$id           = $ids->card;
$artid        = $ids->art;
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
$abilityIcon  = 2;?>

        <div class="gwent-card" data-res="medium" data-id="<?= $id; ?>" data-artid="<?= $artid; ?>j" data-power="<?=$power;?>" data-armor="<?=$armor;?>" data-provision="<?=$provision;?>" data-faction="<?=$faction;?>" data-set="<?=$set;?>" data-color="<?=$color;?>" data-type="<?=$type;?>" data-rarity="<?=$rarity;?>" <?php if($faction2!=''){ echo ' data-faction-duo="' . $faction . '_' . $faction2 . '"';}?>>

            <div class="info">
                <div class="head">
                    <div class="name"><?= $name; ?></div>
                    <div class="category"><?= $category ?></div>
                </div>

                <div class="body">
                    <div class="ability"><?= $ability; ?></div>  

                    <?php if(!empty($keywordsHTML)): ?>                    
                    <div class="seperator-short"></div>                    
                    <div class="keyword"><?= $keywordsHTML; ?>
                    </div>                    
                    <?php endif; ?>
                </div>
            </div>
                          
            <div class="image">
                          
                <div class="art">
                          
              <?php switch($type):
                case 'ability': ?>
<?php if ($abilityIcon == 0): ?>
                        <div id="premium" class="card_asset-img"><img src="https://gwent.one/img/assets/medium/art/<?= $artid;?>.jpg"></div>
                        <div class="card_asset-border"></div>
                        <div class="card_asset-banner"></div>
                        <div class="card_asset-ability-icon"></div>
                        <div class="card_asset-ability-provision-icon"><div class="card_asset-ability-provision"></div></div>
                        <div class="card_asset-rarity"></div>
<?php elseif ($abilityIcon == 1): ?>
                        <img class="ability-icon-249"  loading="lazy" src="https://gwent.one/img/icon/ability/pre720/<?= $id; ?>.png">
<?php elseif ($abilityIcon == 2): ?>
                        <img class="ability-icon-249"  loading="lazy" src="https://gwent.one/img/icon/ability/<?= $id; ?>.png">
<?php endif; ?>
<?php break;?>
                      
<?php case 'stratagem': ?>
                    <div id="premium" class="card_asset-img"><img src="https://gwent.one/img/assets/medium/art/<?= $artid;?>.jpg"></div>
                    <div class="card_asset-border"></div>
                    <div class="card_asset-banner"></div>
                    <div class="card_asset-trinket"></div>
                    <div class="card_asset-rarity"></div>
<?php break;?>
                
<?php case 'unit': ?>
                    <div id="premium" class="card_asset-img"><img src="https://gwent.one/img/assets/medium/art/<?= $artid;?>.jpg"></div>
                    <div class="card_asset-border"></div>
<?php if($armor!=0) : ?>                                        
                    <div class="card_asset-armor-icon"><div class="card_asset-armor"></div></div>
<?php endif; ?>
<?php if($provision!=0) : ?>                                        
                    <div class="card_asset-provision-icon"></div>
                    <div class="card_asset-provision-bg"><div class="card_asset-provision"></div></div>
<?php endif; ?>
                    <div class="card_asset-banner"><div class="card_asset-power"></div></div>
                    <div class="card_asset-rarity"></div>
<?php break;?>
                  
                <?php case 'artifact': ?>
                    <div id="premium" class="card_asset-img"><img src="https://gwent.one/img/assets/medium/art/<?= $artid;?>.jpg"></div>
                    <div class="card_asset-border"></div>
                  <?php if($provision!=0) : ?>     
                    <div class="card_asset-provision-icon"></div>
                    <div class="card_asset-provision-bg"><div class="card_asset-provision"></div></div>
                  <?php endif; ?>
                    <div class="card_asset-banner"></div>
                    <div class="card_asset-trinket"></div>
                    <div class="card_asset-rarity"></div>
                <?php break;?>
                  
                <?php case 'special': ?>
                    <div id="premium" class="card_asset-img"><img src="https://gwent.one/img/assets/medium/art/<?= $artid;?>.jpg"></div>
                    <div class="card_asset-border"></div>
                  <?php if($provision!=0) : ?>     
                    <div class="card_asset-provision-icon"></div>
                    <div class="card_asset-provision-bg"><div class="card_asset-provision"></div></div>
                  <?php endif; ?>
                    <div class="card_asset-banner"></div>
                    <div class="card_asset-trinket"></div>
                    <div class="card_asset-rarity"></div>
                <?php break;?>
              <?php endswitch;?>
            </div>
        </div>
    </div>
</div>