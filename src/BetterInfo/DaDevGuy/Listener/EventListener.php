<?php
declare(strict_types=1);

namespace BetterInfo\DaDevGuy\Listener;

use BetterInfo\DaDevGuy\Main;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;

class EventListener implements Listener{

    protected $main;

    public function __construct(Main $main)
    {
        $this->main = $main;    
    }

    //Nest OnJoin

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();

        if(!$this->main->info->exists($player->getName())){
            $this->main->info->setNested($player->getName() . ".join", 1);
            $this->main->info->setNested($player->getName() . ".kill", 0);
            $this->main->info->setNested($player->getName() . ".death", 0);
            $this->main->info->save();
        } else {
            $this->main->info->setNested($player->getName() . ".join", ($this->main->info->getNested($player->getName() . ".join") + 1));
            $this->main->info->save();
        }
    }

    //Death Count

    public function onDeath(PlayerDeathEvent $event){
        $player = $event->getPlayer();
        $this->main->info->setNested($player->getName() . ".death", ($this->main->info->getNested($player->getName() . ".death") + 1));
        $this->main->info->save();
    }

    //Blocks Count

    public function onBreak(BlockBreakEvent $event){
        $player = $event->getPlayer();
        if(!$event->isCancelled()){
        $this->main->info->setNested($player->getName() . ".break", ($this->main->info->getNested($player->getName(). ".break") + 1));
        $this->main->info->save();
    }
    }

    //Kills Count

    public function onKill(EntityDamageEvent $event){
        $player = $event->getEntity();
        if(!$event->isCancelled()){
        if($player instanceof Player){
        if($event->getFinalDamage() >= $player->getHealth()){
        if($event instanceof EntityDamageByEntityEvent){
        $damager = $event->getDamager();
        if($damager instanceof Player){
        $this->main->info->setNested($damager->getName() . ".kill", ($this->main->info->getNested($damager->getName()) + 1));
        $this->main->info->save();
                          }
                    }
                }
            }
        }
    }
}
