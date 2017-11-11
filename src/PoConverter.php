<?php

namespace DLS;


use Sepia\FileHandler;
use DLS\MoConverter;
use Sepia\PoParser;

class PoConverter
{
	protected $replace = array(
		"А" => "A",
		"Б" => "B",
		"В" => "V",
		"Г" => "G",
		"Д" => "D",
		"Ђ" => "Đ",
		"Е" => "E",
		"Ж" => "Ž",
		"З" => "Z",
		"И" => "I",
		"Ј" => "J",
		"К" => "K",
		"Л" => "L",
		"Љ" => "LJ",
		"М" => "M",
		"Н" => "N",
		"Њ" => "NJ",
		"О" => "O",
		"П" => "P",
		"Р" => "R",
		"С" => "S",
		"Ш" => "Š",
		"Т" => "T",
		"Ћ" => "Ć",
		"У" => "U",
		"Ф" => "F",
		"Х" => "H",
		"Ц" => "C",
		"Ч" => "Č",
		"Џ" => "DŽ",
		"Ш" => "Š",
		"а" => "a",
		"б" => "b",
		"в" => "v",
		"г" => "g",
		"д" => "d",
		"ђ" => "đ",
		"е" => "e",
		"ж" => "ž",
		"з" => "z",
		"и" => "i",
		"ј" => "j",
		"к" => "k",
		"л" => "l",
		"љ" => "lj",
		"м" => "m",
		"н" => "n",
		"њ" => "nj",
		"о" => "o",
		"п" => "p",
		"р" => "r",
		"с" => "s",
		"ш" => "š",
		"т" => "t",
		"ћ" => "ć",
		"у" => "u",
		"ф" => "f",
		"х" => "h",
		"ц" => "c",
		"ч" => "č",
		"џ" => "dž",
		"ш" => "š",
		"Ња" => "Nja",
		"Ње" => "Nje",
		"Њи" => "Nji",
		"Њо" => "Njo",
		"Њу" => "Nju",
		"Ља" => "Lja",
		"Ље" => "Lje",
		"Љи" => "Lji",
		"Љо" => "Ljo",
		"Љу" => "Lju",
		"Џа" => "Dža",
		"Џе" => "Dže",
		"Џи" => "Dži",
		"Џо" => "Džo",
		"Џу" => "Džu",
	);
	private $file;
	private $save = true;

	/**
	 * PoConverter constructor.
	 *
	 * @param string $file
	 * @param bool $save
	 */
	public function __construct($file, $save = true)
	{
		$this->file = $file;
		$this->save = $save;
	}

	public function convert(){
		if(!file_exists($this->file)){
			throw new \Exception('File path is not correct');
		}
		if('po' !== pathinfo($this->file,PATHINFO_EXTENSION)){
			throw new \Exception('File is not .po extension');
		}
		$handler = new FileHandler($this->file);
		$parser = new PoParser($handler);
		$entries  = $parser->parse();
		foreach ($entries as $msgid=>$entry){
			if(empty($entry['msgid']) || !isset($entry['msgstr']) || empty($entry['msgstr'])){
				continue;
			}
			if(is_array($entry['msgstr'])){
				$i = 0;
				$to_save = array();
				foreach ($entry['msgstr'] as $str){
					if($this->save){
						$entry['msgstr'][$i] = $this->replaceChars($str);
						$to_save[] = $entry;
					}
					else{
						echo $str.' ===> '.$this->replaceChars($str).PHP_EOL;
					}
					$i++;
				}
				if($this->save && !empty($to_save)){
					$parser->setEntry($msgid,$entry,false);
				}
			}
			if(is_string($entry['msgstr'])){
				if($this->save){
					$entry['msgstr'] = $this->replaceChars($entry['msgstr']);
					$parser->setEntry($msgid,$entry,false);
				}
				else{
					echo $entry['msgstr'].' ===> '.$this->convert($entry['msgstr']).PHP_EOL;
				}
			}
		}
		if($this->save){
			$parser->writeFile($this->file);
			$writer = new MoConverter($this->file);
			$writer->convert();
		}
	}



	private function replaceChars($string){
		$chars = preg_split('//u', $string, -1);
		$return = '';
		foreach ($chars as $ch){
			if(isset($this->replace[$ch])){
				$return.=$this->replace[$ch];
			}
			else{
				$return.=$ch;
			}
		}
		return $return;
	}
}