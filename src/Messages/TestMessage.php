<?php


namespace App\Messages;


class TestMessage
{
    private $word;

    public function __construct(string $word)
    {
        $this->word = $word;
    }

    /**
     * @return string
     */
    public function getWord(): string
    {
        return $this->word;
    }

    /**
     * @param string $word
     */
    public function setWord(string $word): void
    {
        $this->word = $word;
    }

}
