<?php

namespace rent\TelegramBots\support;


use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use rent\repositories\support\TaskRepository;

/**
 * Generic command
 *
 * Gets executed for generic commands, when no other appropriate one is found.
 */
class ListCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'list';

    /**
     * @var string
     */
    protected $description = 'Выводим список открытых заявок';

    protected $usage = '/list';
    /**
     * @var string
     */
    protected $version = '1.1.0';
    private TaskRepository $taskRepository;


    public function __construct(Telegram $telegram,?Update $update = null)
    {
        parent::__construct($telegram, $update);
        $this->taskRepository = new TaskRepository();
    }

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $user_id = $message->getFrom()->getId();
        $command = $message->getCommand();
        // To enable proper use of the /whois command.
        // If the user is an admin and the command is in the format "/whoisXYZ", call the /whois command
        if (stripos($command, 'whois') === 0 && $this->telegram->isAdmin($user_id)) {
            return $this->telegram->executeCommand('whois');
        }

//        if ($command=='/'.$this->usage) {
        if (stripos($command, 'list') === 0) {
            $response='Список открытых заявок:'.PHP_EOL;
            if ($tasks=$this->taskRepository->findActive()) {
                foreach ($tasks as $task) {
                    $response.= $task->name . ' (Клиент: ' .$task->client_name .', инициатор: ' . $task->responsible_name.')' .PHP_EOL;
                }
            }

            return $this->replyToChat($response);
        }


        return $this->replyToChat("Command /{$command} not found.. :(");
    }
}