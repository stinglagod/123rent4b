<?php

namespace rent\forms\support\task;

use rent\forms\CompositeForm;
/**
 * @property FilesForm $files
 */
class CommentForm extends CompositeForm
{
    public ?string $message=null;
    public ?int $author_id=null;
    public ?bool $waitingResponse=true;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->files = new FilesForm();
    }

    public function rules(): array
    {
        return [
//            [['message'], 'string','length' => [3]],
            [['message'], 'string','min' => 3],
            [['author_id'], 'integer'],
            [['waitingResponse'], 'boolean'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'message' => 'Сообщение',
            'author_id' => 'Автор',
            'waitingResponse' => 'Ожидаем ответа?',
        ];
    }
    protected function internalForms(): array
    {
        return ['files'];
    }
}