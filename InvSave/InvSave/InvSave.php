<?php

 namespace InvSave;

 use pocketmine\plugin\PluginBase;

 use pocketmine\event\Listener;

 use pocketmine\Player;

 use pocketmine\event\player\PlayerJoinEvent;

 use pocketmine\event\entity\EntityLevelChangeEvent;

 use pocketmine\inventory\PlayerInventory;

 use pocketmine\utils\Config;

 class InvSave extends PluginBase implements Listener {

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if(!is_dir($this->getDataFolder() . "players/")) {
			@mkdir($this->getDataFolder() . "players/");
	}
	}
	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
 		if(!file_exists($this->getDataFolder() ."players/" . $player->getName() . ".yml")) {
			$this->inv = new Config($this->getDataFolder()."MS/" . $player->getName() . ".yml", CONFIG::YAML);
			$this->inv->set("Inventory");
			$this->inv->save();
		}
 	}
	
	public function onLevelChange(EntityLevelChangeEvent $event) {
 		$entity = $event->getEntity();
		if($entity instanceof Player){
			$last = $event->getOrigin()->getName();
			$new = $event->getTarget()->getName();
			if($last == "MS") {
				$this->save($entity);
			}
			if($new == "MS") {
				$this->give($entity);
			}
	}
 	}
	
	public function save(Player $player) {
 		$items = $player->getInventory()->getContents(true);
 		$this->inv->set("Inventory", $items);
		$this->inv->save();
	}
	
	public function give(Player $player) {
		$items = $this->inv->get("Inventory");
		$contents = [];
		foreach($items as $i) {
 			$contents[] = Item::get($i[0], $i[1], $i[2]);
		}
		$player->getInventory()->setContents($contents);
 	}
 }
