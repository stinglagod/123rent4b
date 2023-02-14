<?php

namespace rent\forms\manage\CRM;

use rent\entities\Client\Client;
use rent\entities\CRM\Contact;
use yii\base\Model;
/**
* @property string $name
* @property string $surname
* @property string $patronymic
* @property integer $status
* @property string $telephone
* @property string $email
* @property string $note
**/
class ContactForm extends Model
{
    public ?string $name=null;
    public ?string $surname=null;
    public ?string $patronymic=null;
    public ?int $status=null;
    public ?string $telephone=null;
    public ?string $email=null;
    public ?string $note=null;
    public ?Contact $_contact=null;

    public function __construct(Contact $contact=null,$config = [])
    {
        parent::__construct($config);
        $this->status=Contact::STATUS_ACTIVE;
        if ($contact) {
            $this->name         = $contact->name;
            $this->surname      = $contact->surname;
            $this->patronymic   = $contact->patronymic;
            $this->status       = $contact->status;
            $this->telephone    = $contact->telephone;
            $this->email        = $contact->email;
            $this->note         = $contact->note;

            $this->_contact     = $contact;
        }
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name','surname','patronymic'], 'string', 'max' => 100],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 254],

            [['note'], 'string', 'max' => 254],

            [['telephone'], 'string', 'max' => 15],

            ['status', 'default', 'value' => Contact::STATUS_ACTIVE],
            ['status', 'in', 'range' => [
                Contact::STATUS_ACTIVE,
                Contact::STATUS_DELETED,
                Contact::STATUS_NOT_ACTIVE
            ]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
            'status' => 'Статус',
            'note' => 'Примечание',
            'telephone' => 'Телефон',
            'email' => 'Email',
        ];
    }
}