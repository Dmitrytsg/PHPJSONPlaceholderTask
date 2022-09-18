<?php

require_once 'address.php';
require_once 'post.php';
require_once 'todo.php';

class user
{
    private int $id;

    private string $name;

    private string $email;

    private Address $address;

    private array $postsList;

    private array $todosList;

    //конструктор объекта класса user() получает основные параметры и список Постов и Заданий.
    //все посты и задания этого юзера помещаются в $postsList и $todosList соответственно.
    //иницаилазируются объекты классов post и todo
    public function __construct(int $id, string $name, string $email, object $address, array $posts, array $todos)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->address = new Address($address->street, $address->suite, $address->zipcode, $address->city);

        foreach ($posts as $post)
        {
            $new_post = new post($post);
            $this->postsList[] = $new_post;
        }

        foreach ($todos as $todo)
        {
            $new_todo = new todo($todo);
            $this->todosList[] = $new_todo;
        }

    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address->getAddress();
    }

    /**
     * @return array
     */
    public function getPosts(): array
    {
        return $this->postsList;
    }

    /**
     * @return array
     */
    public function getTodos(): array
    {
        return $this->todosList;
    }

    public function addPost(object $post)
    {
        $new_post = new post($post);
        $this->postsList[] = $new_post;
    }

    public function deletePost(int $postId)
    {
        $key = 0;
        foreach ($this->postsList as $post)
        {
            if($post->getId() == $postId)
            {
                unset(($this->postsList)[$key]);
            }
            $key++;
        }
    }
}