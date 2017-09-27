<?php

namespace erkam2002;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as C;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\command\{Command, CommandSender};

class Vanish extends PluginBase implements Listener {

    public $prefix = C::BLUE."Vanish".C::DARK_GRAY." >".C::WHITE." ";

    public $config;

    public $vanish = array();

    public function onEnable(){
        $this->getLogger()->info("Activated!");
        $this->saveResource("config.yml");
        @mkdir($this->getDataFolder());
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, [
            "Creative_Vanish" => false
        ]);
        $this->config->set("Creative_Vanish", false);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
        $name = $sender->getName();
        if($cmd->getName() == "vanish") {
            if ($sender->hasPermission("vanish.use")) {
                if (!in_array($name, $this->vanish)) {
                    $this->vanish[] = $name;
                    $sender->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, true);
                    $sender->setNameTagVisible(false);
                    if($this->config->get("Creative_Vanish") == false){
                        $sender->setGamemode(2);
                    }
                    $sender->sendMessage($this->prefix . C::GREEN . "You are now super vanished. No one can see you.");
                } elseif (in_array($name, $this->vanish)) {
                    unset($this->vanish[array_search($name, $this->vanish)]);
                    $sender->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
                    $sender->setNameTagVisible(true);
                    if($this->config->get("Creative_Vanish") == false)
                        $sender->setGamemode(0);
                    }
                    $sender->setHealth(20);
                    $sender->setFood(20);
                    $sender->sendMessage($this->prefix . C::RED . "You are no longer super vanished! everyone should be able to see you.");
                }
            }
        }
    }
}
