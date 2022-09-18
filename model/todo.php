<?php

class todo
{
    private int $userId;
    private int $id;
    private string $title;
    private bool $completed;

    public function __construct(object $todo)
    {
        $this->userId = $todo->userId;
        $this->id = $todo->id;
        $this->title = $todo->title;
        $this->completed = $todo->completed;
    }

    public function getTodo(): todo
    {
        return $this;
    }
}