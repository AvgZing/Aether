<?php

declare(strict_types=1);

namespace Plexus;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;

use Plexus\Main;

class Events implements Listener {

  private $plugin;

  public function __construct(Main $plugin){
    $this->plugin = $plugin;
  }

  public function getPlugin() : Main {
    return $this->plugin;
  }

  public function join(\pocketmine\event\player\PlayerJoinEvent $e) : void {
    $e->setJoinMessage('');
    $player = $e->getPlayer();
  if($player instanceof \pocketmine\Player){
    $this->getPlugin()->join($player);
   }
  }

  public function message(\pocketmine\event\player\PlayerChatEvent $e) : void {
    $msg = $e->getMessage();
    $player = $e->getPlayer();
  if($player instanceof \pocketmine\Player){
    $this->getPlugin()->discord("```\n". $player->getName() .' said '. $msg ."\n```", 'chat');
   }
  }

  public function onHungerEvent(\pocketmine\event\player\PlayerExhaustEvent $e){
    $player = $e->getPlayer();
  if($player instanceof \pocketmine\Player){
  if($this->getPlugin()->atSpawn($player) === true){
    $e->setCancelled(true);
    }
   }
  }

  public function onEntityDamage(\pocketmine\event\entity\EntityDamageEvent $e){
    $entity = $e->getEntity();
  if($entity instanceof \Plexus\entity\FloatingNameTag){
    $e->setCancelled(true);
  }
  if($entity instanceof \pocketmine\Player){
  if($this->getPlugin()->atSpawn($entity) === true){
    $e->setCancelled(true);
   }
  }
  if(!$e instanceof \pocketmine\event\entity\EntityDamageByEntityEvent){
    return;
  }
    $damager = $e->getDamager();
  if(!$damager instanceof \pocketmine\Player) {
    return;
  }
  if($entity instanceof \pocketmine\Player && $damager instanceof \pocketmine\Player){
  if($this->getPlugin()->atSpawn($entity) === false && $this->getPlugin()->atSpawn($damager) === true){
    $e->setCancelled(true);
   }
  }
  if($entity instanceof \Plexus\entity\Npc && $damager instanceof \pocketmine\Player){
    $e->setCancelled(true);
  if($entity->getNamedTag()->Name == C::AQUA .'Welcome'){
    $ui = $this->plugin->ui['welcome'];
    $ui->send($damager);
  }
  if($entity->getNamedTag()->Name == C::YELLOW .'Shop'){
    $ui = $this->plugin->ui['shop_main'];
    $ui->send($damager);
  }
  if($entity->getNamedTag()->Name == C::AQUA .'Look at This Spawn'){
    $damager->teleport(new \pocketmine\math\Vector3(870.50, 106, 1080.50));
  }
  if($entity->getNamedTag()->Name == C::YELLOW .'Return to Spawn'){
    $this->getPlugin()->spawn($damager);
    }
   }
  }

  public function move(\pocketmine\event\player\PlayerMoveEvent $e) : void {
    $player = $e->getPlayer();
  if($e->getTo()->distance($e->getFrom()) > 0.2){
  if($player instanceof \pocketmine\Player){
  foreach($this->getPlugin()->npc as $npc){
    $npc->look($player);
     }
    } 
   }
  }

  public function quit(\pocketmine\event\player\PlayerQuitEvent $e) : void {
    $player = $e->getPlayer();
    $e->setQuitMessage('');
  if($player instanceof \pocketmine\Player){
  if(isset($this->getPlugin()->player[$player->getName()])){
    $this->getPlugin()->player[$player->getName()]->save();
    unset($this->getPlugin()->player[$player->getName()]);
    }
   }
  }
}