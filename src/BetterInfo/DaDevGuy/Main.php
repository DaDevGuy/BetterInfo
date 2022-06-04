<?php
declare(strict_types=1);

namespace BetterInfo\DaDevGuy;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use BetterInfo\DaDevGuy\Listener\EventListener;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use Vecnavium\FormsUI\CustomForm;
use BetterInfo\DaDevGuy\Commands\ProfileCommand;

class Main extends PluginBase implements Listener{
   /**@var Main $main*/
    protected $info;
    
    public function onEnable(): void{
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->saveDefaultConfig();
        $this->info = new Config($this->getDataFolder() . "info.yml", Config::YAML);
        $this->getServer()->getCommandMap()->register("profile", new ProfileCommand($this));
    
        if($this->getConfig()->get("config-ver") !== 1){
            $this->getLogger()->info("BetterInfo Config Is Not Updated, Please Delete The BetterInfo Folder In plugin_data And Restart The Server!");
        }
    }

    public function onDisable(): void{
        $this->info->save();
    }

    public function Information($player){
        $form = new CustomForm(function (Player $player, array $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
        });
        $name = $player->getName();
        $health = $player->getHealth();
        $maxhealth = $player->getHealth()->getMaxHealth();
        $line = "\n";
        $kill = $this->info->getNested($player->getName() . ".kill");
        $join = $this->info->getNested($player->getName() . ".join");
        $death = $this->info->getNested($player->getName() . ".death");
        $info = str_replace(['{health}', '{name}', '{maxhealth}', '{line}', '{kills}', '{firstjoin}', '{death}'], [$health, $name, $maxhealth, $line, $kill, $join, $death], $this->getConfig()->get("ui.content"));
        $form->setTitle($this->getConfig()->get("ui.title"));
        $form->addLabel($info);
        $player->sendForm($form);
        return $form;
    }
}
