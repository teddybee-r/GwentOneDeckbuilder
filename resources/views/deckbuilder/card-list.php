<?php 

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
<div class="decklist card-render" id="<?= $id; ?>" data-id="<?= $id; ?>" data-name="<?= $name; ?>" data-art="<?= $art; ?>" data-artid="<?= $art; ?>j" data-power="<?=$power;?>" data-armor="<?=$armor;?>" data-provision="<?=$provision;?>" data-faction="<?=$faction;?>" data-set="<?=$set;?>" data-color="<?=$color;?>" data-type="<?=$type;?>" data-rarity="<?=$rarity;?>" <?php if($faction2!=''){ echo ' data-faction-duo="' . $faction . '_' . $faction2 . '"';}?>>

    <div class="image">
            
<?php switch($type):
  case 'ability': ?>
    <div class="art" onclick="Deck.setAbility(<?= $id; ?>)" oncontextmenu="cardInfo(<?= $id; ?>);return false;" data-res="rel">
<?php if ($abilityIcon == 0): ?>
            <div  class="img"><img src="https://gwent.one/img/assets/low/art/<?= $art;?>.jpg"></div>
            <div class="asset border"></div>
            <div class="asset banner"></div>
            <div class="asset ability-icon"></div>
            <div class="asset provision-icon"><div class="asset provision"></div></div>
            <div class="asset rarity"></div>
<?php elseif ($abilityIcon == 1): ?>
            <img class="ability-icon-150" loading="lazy" src="https://gwent.one/img/icon/ability/pre720/<?= $id; ?>.png">
<?php elseif ($abilityIcon == 2): ?>
            <img class="ability-icon-150" loading="lazy" src="https://gwent.one/img/icon/ability/<?= $id; ?>.png">
<?php endif; ?>
        </div>
<?php break;?>
                  
<?php case 'stratagem': ?>
        <div class="art" onclick="Deck.setStratagem(<?= $id; ?>)" oncontextmenu="cardInfo(<?= $id; ?>);return false;" data-res="rel">
            <div class="img"><img src="https://gwent.one/img/assets/low/art/<?= $art;?>.jpg"></div>
            <div class="asset border"></div>
            <div class="asset banner"></div>
            <div class="asset trinket"></div>
            <div class="asset rarity"></div>
        </div>
<?php break;?>
            
<?php case 'unit': ?>
        <div class="art" onclick="Deck.addCard(<?= $id; ?>)" oncontextmenu="cardInfo(<?= $id; ?>);return false;" data-res="rel">
            <div class="img"><img src="https://gwent.one/img/assets/low/art/<?= $art;?>.jpg"></div>
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
        </div>
<?php break;?>
              
<?php case 'artifact': ?>
        <div class="art" onclick="Deck.addCard(<?= $id; ?>)" oncontextmenu="cardInfo(<?= $id; ?>);return false;" data-res="rel">
            <div class="img"><img src="https://gwent.one/img/assets/low/art/<?= $art;?>.jpg"></div>
            <div class="asset border"></div>
<?php if($provision!=0) : ?>     
            <div class="asset provision-icon"></div>
            <div class="asset provision-bg"><div class="asset provision"></div></div>
<?php endif; ?>
            <div class="asset banner"></div>
            <div class="asset trinket"></div>
            <div class="asset rarity"></div>
        </div>
<?php break;?>
              
<?php case 'special': ?>
        <div class="art" onclick="Deck.addCard(<?= $id; ?>)" oncontextmenu="cardInfo(<?= $id; ?>);return false;" data-res="rel">
            <div class="img"><img src="https://gwent.one/img/assets/low/art/<?= $art;?>.jpg"></div>
            <div class="asset border"></div>
<?php if($provision!=0) : ?>     
            <div class="asset provision-icon"></div>
            <div class="asset provision-bg"><div class="asset provision"></div></div>
<?php endif; ?>
            <div class="asset banner"></div>
            <div class="asset trinket"></div>
            <div class="asset rarity"></div>
        </div>
<?php break;?>
<?php endswitch;?>
    </div>
</div>