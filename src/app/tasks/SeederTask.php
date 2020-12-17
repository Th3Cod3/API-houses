<?php

declare(strict_types=1);

namespace App\Tasks;

use App\Seeders\Houses;
use Phalcon\Cli\Task;

class SeederTask extends Task
{
    /**
    * Generate fake houses
    *
    * @param int $amount
    * @return bool
    **/
    public function housesAction(int $amount = 1)
    {
        for ($i = 0; $i < $amount; $i++) {
            $house = Houses::createHouse();
            if(!$house->save()){
                foreach ($house->getMessages() as $message) {
                    echo "$message \n";
                }
                return false;
            } else {
                echo "Create:({$house->id}) {$house->street} {$house->number}, {$house->city} [{$house->zip_code}]\n";
            }
        }

        return true;
    }
}