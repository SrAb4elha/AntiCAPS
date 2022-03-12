<?php

/*
 * AntiCAPS
 * A plugin by Sr4abelha
 */
namespace beemc\caps;

use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\Player;

class Main extends PluginBase implements Listener{
	public $command = false;

	public function onLoad(){
		$this->getLogger()->info(TextFormat::BLUE . "Loading " . $this->getDescription()->getFullName());
	}

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onCaps(PlayerCommandPreprocessEvent $event){
		$message = $event->getMessage();
		if(strtoupper($message) == $message){
			$isnum = false;
			foreach(explode(" ", $message) as $num){
				$isnum = is_numeric($num);
			}
			$ischaronly = false;
			foreach(str_split($message) as $char){
				$ischaronly = ($this->getSpecialChar($char) !== false?false:true);
			}
			if(!$isnum) $event->getPlayer()->sendMessage(TextFormat::RED . "Don't use Caps.");
			$newmsg = $this->invertCase($event->getPlayer(), $message);
			if($this->command){
				$this->getServer()->dispatchCommand($event->getPlayer(), $newmsg);
				$event->setCancelled();
			}
			else{
				$event->setMessage($newmsg);
			}
		}
	}

	public function invertCase(Player $sender, $str){
		$newStr = '';
		$this->command = false;
		if($str[0] == "7"){
			$this->command = true;
			$str = substr($str, 1);
		}
		for($i = 0; $i < strlen($str); $i++){
			if($this->getSpecialChar($str[$i]) === false){
				if(strtoupper($str[$i]) == $str[$i]){
					$newStr .= strtolower($str[$i]);
				}
				else{
					$newStr .= strtoupper($str[$i]);
				}
			}
			else{
				$newStr .= $this->getSpecialChar($str[$i]);
			}
		}
		return $newStr;
	}

	public function getSpecialChar($char){
		$specials = ['!' => '1','"' => '2','&sect;' => '3','$' => '4','%' => '5','&' => '6','/' => '7','(' => '8',')' => '9','=' => '0','?' => 'ß','*' => '+',"'" => '#','>' => '<',';' => ',',':' => '.','_' => '-'];
		$flip = $specials;
		array_flip($flip);
		if(in_array($char, array_keys($specials))) return $specials[$char];
		elseif(in_array($char, array_keys($flip))) return $flip[$char];
		else return false;
	}
}