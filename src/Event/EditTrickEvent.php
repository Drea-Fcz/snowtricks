<?php

namespace App\Event;

use App\Entity\Trick;
use Symfony\Contracts\EventDispatcher\Event;

class EditTrickEvent extends Event
{
    const EDIT_TRICK_EVENT = 'trick.edit';

    public function __construct(private Trick $trick)
    {
    }

    /**
     * @return Trick
     */
    public function getTrick(): Trick
    {
        return $this->trick;
    }

}
