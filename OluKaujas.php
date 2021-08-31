<?php

class Egg
{
    public int $powerNumber;
    function __construct(int $powerNumber = 50)
    {
        $this->powerNumber = $powerNumber;
    }
}
class BattleSimulator
{
    private array $playerEggs;
    private array $computerEggs;
    private int $playerScore;
    private int $computerScore ;
    private array $fromFilePlayerEggPowerNumbers;

    function __construct(array $computerEggs)
    {
        $this->playerEggs = $this->generatePlayerEggsFromFile(); //Makes PlayerEggs From file were written Egg powerNumbers with space
        $this->computerEggs = $computerEggs;
    }
    private function displayEggs()
    {
        echo "Player has ".count($this->playerEggs).' Eggs'.PHP_EOL;
        echo "Computer has ".count($this->computerEggs).' Eggs'.PHP_EOL;
    }
    private function makeTextFileForSaving():string
    {
        $text ='Player left battle with '.count($this->playerEggs).' eggs'.PHP_EOL;
        $text .= 'Computer left battle with '.count($this->computerEggs).' eggs';
        return $text;
    }
    private function saveResultsInFile()
    {
        $myfile = fopen('results', "w",FILE_USE_INCLUDE_PATH) or die("Unable to open file!");
        $txt = $this->makeTextFileForSaving();
        fwrite($myfile, $txt);
        fclose($myfile);
    }
    private function getPlayerPowerNumbersFromFile()
    {
        $file = file_get_contents('player',FILE_USE_INCLUDE_PATH);
        $this->fromFilePlayerEggPowerNumbers = explode(' ',$file);
    }
    public function generatePlayerEggsFromFile():array
    {
        $this->getPlayerPowerNumbersFromFile();
        foreach($this->fromFilePlayerEggPowerNumbers as $newEggPower)
        {
            $this->playerEggs[] = new Egg((int)$newEggPower);
        }
        return $this->playerEggs;
    }
    private function singleBattle(Egg $playerEgg,Egg $computerEgg)
    {
        $battleScore = rand(1,($playerEgg->powerNumber+$computerEgg->powerNumber));
        if($battleScore <= $playerEgg->powerNumber)
        {
            $this->playerScore++;
        }else{
            $this->computerScore++;
        }
    }
    private function roundWinnerGetsEgg(Egg $playerBattleEgg,Egg $computerBattleEgg)
    {
        If ($this->playerScore > $this->computerScore)
        {
            array_push($this->playerEggs,$playerBattleEgg);
        }elseif ($this->playerScore < $this->computerScore)
        {
            array_push($this->computerEggs,$computerBattleEgg);
        }
    }
    public function battleTillEnd()
    {
        while(true)
        {
            $this->computerScore = 0;
            $this->playerScore = 0;
            $playerBattleEgg = array_shift($this->playerEggs);
            $computerBattleEgg = array_shift($this->computerEggs);
            $this->singleBattle($playerBattleEgg,$computerBattleEgg);
            $this->singleBattle($playerBattleEgg,$computerBattleEgg);
            $this->roundWinnerGetsEgg($playerBattleEgg,$computerBattleEgg);
            if(count($this->playerEggs) == 0 || count($this->computerEggs) == 0)
            {
                $this->displayEggs();
                $this->saveResultsInFile();
                break;
            }
        }
    }
}
function generateEggBasket(int $amount): array
{
    return array_fill(0,$amount,new Egg(rand(20,80)));
}
//$playerEggs = [new Egg(50),new Egg(20),new Egg(70),new Egg(40),new Egg(40)];
$EasterBattle = new BattleSimulator(generateEggBasket(5));
$EasterBattle->battleTillEnd();


