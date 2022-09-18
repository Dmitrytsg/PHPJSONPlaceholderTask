<?php

require_once 'vendor/autoload.php';
use GuzzleHttp\Client;

include_once 'model/user.php';
require_once 'model/post.php';
require_once 'model/todo.php';

class users
{
    private array $userList;
    private array $AllPostsList;
    private array $AllTodosList;

    //Конструктор класса, получаем информацию от Api
    public function __construct()
    {
        $client = new Client();
        $request = $client->get('http://jsonplaceholder.typicode.com/users');
        $usersFromApi = json_decode($request->getBody());

        //Заполняем список всех постов всех пользователей
        $request = $client->get('http://jsonplaceholder.typicode.com/posts');
        $AllPostsFromApi = json_decode($request->getBody());
        foreach ($AllPostsFromApi as $post) {
            $new_post = new post($post);
            $this->AllPostsList[] = $new_post;
        }

        //Заполняем список всех заданий всех пользователей
        $request = $client->get('http://jsonplaceholder.typicode.com/todos');
        $AllTodosFromApi = json_decode($request->getBody());
        foreach ($AllTodosFromApi as $todo) {
            $new_todo = new todo($todo);
            $this->AllTodosList[] = $new_todo;
        }

        //для каждого пользователя получаем список его Постов и Заданий. И инициализируем объект класса user().
        //Все объекты класса user помещаются в $userList
        foreach ($usersFromApi as $user) {
            //получаем Посты пользователя
            $request = $client->get('http://jsonplaceholder.typicode.com/posts?userId=' . $user->id . '');
            $postsFromApi = json_decode($request->getBody());

            //получаем Задания пользователя
            $request = $client->get('http://jsonplaceholder.typicode.com/todos?userId=' . $user->id . '');
            $todosFromApi = json_decode($request->getBody());

            $new_user = new user($user->id, $user->name, $user->email, $user->address, $postsFromApi, $todosFromApi);
            $this->userList[] = $new_user;
        }
    }

    //создание нового поста
    public function createPost(int $userId, string $title, string $body): int
    {
        $client = new Client();

        $client->setDefaultOption('verify', false);

        $stream = [
            'json' => [
                'title' => $title,
                'body' => $body,
                'userId' => $userId
            ]
        ];

        $response = $client->post('https://jsonplaceholder.typicode.com/posts', $stream);

        $post = json_decode($response->getBody());

        $this->userList[$userId - 1]->addPost($post);

        $new_post = new post($post);
        $this->AllPostsList[] = $new_post;

        return 0;
    }

    //изменение поста
    public function updatePost(int $postId, string $title, string $body): int
    {
        $client = new Client();
        $client->setDefaultOption('verify', false);

        $userId = $this->AllPostsList[$postId-1] -> getUserId();

        $stream = [
            'json' => [
                'id' => $postId,
                'title' => $title,
                'body' => $body,
                'userId' => $userId
            ]
        ];

        $response = $client->put('https://jsonplaceholder.typicode.com/posts/'.$postId.'', $stream);

        $post = json_decode($response->getBody());

        foreach ($this->userList[$userId - 1]->getPosts() as $post)
        {
            if($post->getId() == $postId)
            {
                $post -> setTitle($title);
                $post -> setBody($body);
            }
        }

        $this->AllPostsList[$postId-1] -> setTitle($title);
        $this->AllPostsList[$postId-1] -> setBody($body);

        return 0;
    }

    //удаление поста
    public function deletePost(int $postId)
    {
        $client = new Client();
        $client->setDefaultOption('verify', false);

        $response = $client->delete('https://jsonplaceholder.typicode.com/posts/'.$postId.'');

        $post = json_decode($response->getBody());

        $userId = $this->AllPostsList[$postId-1] -> getUserId();
        ($this->userList)[$userId-1] -> deletePost($postId);

        unset($this->AllPostsList[$postId-1]);

    }

    /**
    * @return users Array
    */
    public function getUsers(): array
    {
        return $this->userList;
    }

    /**
     * @return array
     */
    public function getAllPostsList(): array
    {
        return $this->AllPostsList;
    }

    /**
     * @return array
     */
    public function getAllTodosList(): array
    {
        return $this->AllTodosList;
    }

    /**
     * @return array
     */
    public function getUserPosts(int $userId): array
    {
        return $this->userList[$userId-1]->getPosts();
    }

    /**
     * @return array
     */
    public function getUserTodos(int $userId): array
    {
        return $this->userList[$userId-1]->getTodos();
    }

    /**
     * @return user
     */
    public function getUser(int $userId): user
    {
        return $this->userList[$userId-1];
    }
}