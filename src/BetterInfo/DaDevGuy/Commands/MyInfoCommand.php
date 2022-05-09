<?php

namespace BetterInfo\DaDevGuy\Commands;

use BetterInfo\DaDevGuy\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class MyInfoCommand extends Command implements PluginOwned{

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(count($args) == 0){
            if($sender instanceof Player){
                $this->plugin->infoform($sender);
            } else {
                $sender->sendMessage("Please Use This Command In-Game!");
            }
        } else {
            $sender->sendMessage("Please Dont Give arguments");
        }
        return true;
    }

    public function getPlugin(): Plugin{
        return $this->plugin;
    }

    public function getOwningPlugin(): Main{
        return $this->plugin;
    }
}