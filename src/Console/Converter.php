<?php

namespace DLS\Console;


use DLS\PoConverter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Converter extends Command
{


	protected function configure()
	{
		$this
			->setName('app:convert')
			->setDescription('Converts Cyrillic .po files int Latin .po files')
			->setHelp('This command allows you to convert cyrillic to latin .po files')
			->addArgument('file', InputArgument::REQUIRED, 'The .po file')
			->addOption(
				'save',
				's',
				InputOption::VALUE_OPTIONAL,
				'Save file?',
				1
			);
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$file = $input->getArgument('file');
		$converter = new PoConverter($file,$input->getOption('save'));
		$converter->convert();
	}


}