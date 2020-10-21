<?php

declare(strict_types=1);

namespace Mooore\eCurring\Resource;

use Mooore\eCurring\Exception\ApiException;

class Customer extends AbstractResource
{
    /**
     * @var string
     */
    public $gender;
    /**
     * @var string
     */
    public $first_name;
    /**
     * @var string
     */
    public $middle_name;
    /**
     * @var string
     */
    public $last_name;
    /**
     * @var string
     */
    public $company_name;
    /**
     * @var string
     */
    public $vat_number;
    /**
     * @var string
     */
    public $bank_holder;
    /**
     * @var string
     */
    public $iban;
    /**
     * @var string
     */
    public $payment_method;
    /**
     * @var string
     */
    public $bank_verification_method;
    /**
     * @var string
     */
    public $card_holder;
    /**
     * @var string
     */
    public $card_number;
    /**
     * @var string
     */
    public $postalcode;
    /**
     * @var string
     */
    public $house_number;
    /**
     * @var string
     */
    public $house_number_add;
    /**
     * @var string
     */
    public $street;
    /**
     * @var string
     */
    public $city;
    /**
     * @var string
     */
    public $country_iso2;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $telephone;
    /**
     * @var string
     */
    public $language;
    /**
     * @var object
     */
    public $relationships;

    /**
     * @var array
     */
    protected $exportProperties = [
        'gender',
        'first_name',
        'middle_name',
        'last_name',
        'company_name',
        'vat_number',
        'bank_holder',
        'iban',
        'payment_method',
        'bank_verification_method',
        'card_holder',
        'card_number',
        'postalcode',
        'house_number',
        'house_number_add',
        'street',
        'city',
        'country_iso2',
        'email',
        'telephone',
        'language',
        'relationships'
    ];

    /**
     * @param array $data
     * @return Customer
     * @throws ApiException
     */
    public function update(array $data = []): Customer
    {
        $data = !empty($data) ? $data : $this->toArray();

        $updated = $this->client->customers->update($this->id, $data)->toArray();

        foreach ($updated as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * @param int $subscriptionId
     * @param array $attributes
     * @return Subscription
     * @throws ApiException
     */
    public function createSubscription(int $subscriptionId, array $attributes = []): Subscription
    {
        return $this->client->subscriptions->create($this->id, $subscriptionId, $attributes);
    }

    /**
     * @param string $iban
     * @param string $bankHolder
     * @return Customer
     * @throws ApiException
     */
    public function updateIBAN(string $iban, string $bankHolder): Customer
    {
        return $this->update([
            'iban' => $iban,
            'bank_holder' => $bankHolder
        ]);
    }

    /**
     * @param string $city
     * @param string $postalCode
     * @param string $street
     * @param string $houseNumber
     * @param string|null $houseNumberAddition
     * @return Customer
     * @throws ApiException
     */
    public function updateAddress(
        string $city,
        string $postalCode,
        string $street,
        string $houseNumber,
        string $houseNumberAddition = null
    ): Customer {
        return $this->update([
            'city' => $city,
            'postalcode' => $postalCode,
            'street' => $street,
            'house_number' => $houseNumber,
            'house_number_add' => $houseNumberAddition
        ]);
    }
}
