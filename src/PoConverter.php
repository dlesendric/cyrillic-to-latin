<?php

namespace DLS;


use Sepia\PoParser\SourceHandler\FileSystem;
use DLS\MoConverter;
use Sepia\PoParser\PoCompiler;
use Sepia\PoParser\Parser;

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
		$handler = new FileSystem($this->file);
		$parser  = new Parser($handler);
		$catalog = $parser->parse();

		foreach ($catalog->getEntries() as $entry){
			$msgstr = $entry->getMsgStr();
			if ($msgstr) {
				$entry->setMsgStr($this->replaceChars($msgstr));
			} else {
				$entry->setMsgStrPlurals(array_map(array('DLS\PoConverter', 'replaceChars'), $entry->getMsgStrPlurals()));
			}
		}

		if($this->save){
			$compiler = new PoCompiler();
			$podata = $compiler->compile($catalog);
			$handler->save($podata);
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
