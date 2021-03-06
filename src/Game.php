<?php


namespace Rpt;


use Rpt\Klass\Fighter;
use Rpt\Klass\Wizard;
use Rpt\Race\Human;

class Game
{
  private $active = TRUE;

  /**
   * @var IProcess
   */
  private $process = NULL;

  private $characters;

  public function __construct(array $characters)
  {
    $this->characters = $characters;
  }

  public function isActive() {
    return $this->active;
  }

  public function prompt() {
    if ($this->process) {
      if ($this->process->isActive()) {
        return $this->process->prompt();
      }
      else {
        $this->process = NULL;
      }
    }

    return ['message' => ["Menu"], 'options' => [1 => "Combat", 2 => "Exit"]];
  }

  public function input($input) {
    if ($this->process) {
      if ($this->process->isActive()) {
        return $this->process->input($input);
      }
      else {
        $this->process = NULL;
      }
    }

    // Combat
    if ($input == 1) {
      $this->process = new Combat($this->characters);
    }
    // Exit
    elseif ($input == 2) {
      $this->active = FALSE;
    }
  }

  public static function get() {
    $characters = [];

    $abilities = new Abilities([15, 8, 14, 10, 12, 13]);
    $lacross = new Character("Lacross", new Human("Draconic"), new Fighter(), $abilities);
    $lacross->equip(new \Rpt\Armor\ChainMail());

    $abilities = new Abilities([10, 13, 14, 16, 12, 8]);
    $jadis = new Character("Jadis", new \Rpt\Race\HighElf("Dwarvish"), new Wizard(), $abilities);
    $jadis->equip(new \Rpt\Weapon\ShortSword());
    $jadis->equip(new \Rpt\Weapon\GreatAxe());

    $characters[] = $lacross;
    $characters[] = $jadis;

    return new self($characters);
  }

}