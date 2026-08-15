<?php

declare(strict_types=1);

namespace Houpq\FlySystem;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

class Main extends PluginBase implements Listener{

    protected function onEnable() : void{
        $this->saveDefaultConfig();

        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $this->getScheduler()->scheduleRepeatingTask(
            new ClosureTask(function() : void{
                foreach($this->getServer()->getOnlinePlayers() as $player){
                    $this->checkFlight($player);
                }
            }),
            20
        );

        $this->getLogger()->info("FlySystem habilitado.");
    }

    public function onCommand(
        CommandSender $sender,
        Command $command,
        string $label,
        array $args
    ) : bool{

        if(strtolower($command->getName()) !== "fly"){
            return false;
        }

        if(!$sender instanceof Player){
            $sender->sendMessage("§cEste comando solo puede usarse dentro del juego.");
            return true;
        }

        if(!$sender->hasPermission("fly.command")){
            $sender->sendMessage((string)$this->getConfig()->getNested("messages.no-permission"));
            return true;
        }

        $allowedWorlds = $this->getConfig()->get("allowed-worlds", []);
        $worldName = $sender->getWorld()->getFolderName();

        // BYPASS DE MUNDOS
        if(
            !$sender->hasPermission("fly.bypass") &&
            !in_array($worldName, $allowedWorlds, true)
        ){
            $sender->sendMessage(
                (string)$this->getConfig()->getNested("messages.world-not-allowed")
            );
            return true;
        }

        $enabled = !$sender->getAllowFlight();

        $sender->setAllowFlight($enabled);

        if($enabled){
            $sender->sendMessage(
                (string)$this->getConfig()->getNested("messages.enabled")
            );
        }else{
            $sender->setFlying(false);
            $sender->sendMessage(
                (string)$this->getConfig()->getNested("messages.disabled")
            );
        }

        return true;
    }

    public function onJoin(PlayerJoinEvent $event) : void{
        $this->checkFlight($event->getPlayer());
    }

    private function checkFlight(Player $player) : void{
        $allowedWorlds = $this->getConfig()->get("allowed-worlds", []);
        $worldName = $player->getWorld()->getFolderName();

        // SI TIENE BYPASS NO SE DESACTIVA EL FLY
        if(
            !$player->hasPermission("fly.bypass") &&
            !in_array($worldName, $allowedWorlds, true)
        ){
            if($player->getAllowFlight()){
                $player->setFlying(false);
                $player->setAllowFlight(false);

                $player->sendMessage(
                    (string)$this->getConfig()->getNested("messages.auto-disabled")
                );
            }
        }
    }
}