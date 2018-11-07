<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/15/2018
 * Time: 12:43 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="payment_order")
 */
class PaymentOrder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Agency", inversedBy="paymentOrder")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
     private $user;
    /**
     * @ORM\Column(type="integer")
     */
    private $payId;
    /**
     * @ORM\Column(type="integer")
     */
    private $amount;
    /**
     * @ORM\Column(type="string")
     */
    private $IpAddress;
    /**
     * @ORM\Column(type="string")
     */
    private $authorizationCode;
    /**
     * @ORM\Column(type="integer")
     */
    private $bin;
    /**
     * @ORM\Column(type="integer")
     */
    private $last4;
    /**
     * @ORM\Column(type="integer")
     */
    private $expMonth;
    /**
     * @ORM\Column(type="integer")
     */
    private $expYear;
    /**
     * @ORM\Column(type="integer")
     */
    private $chanel;
    /**
     * @ORM\Column(type="string")
     */
    private $cardType;
    /**
     * @ORM\Column(type="string")
     */
    private $bank;
    /**
     * @ORM\Column(type="string")
     */
    private $countryCode;
    /**
     * @ORM\Column(type="string")
     */
    private $brand;
    /**
     * @ORM\Column(type="integer")
     */
    private $customerId;
    /**
     * @ORM\Column(type="string")
     */
    private $customerEmail;
    /**
     * @ORM\Column(type="string")
     */
    private $customerCode;
    /**
     * @ORM\Column(type="string")
     */
    private $paidAt;
    /**
     * @ORM\Column(type="string")
     */
    private $createdAt;
    /**
     * @ORM\Column(type="string")
     */
    private $transactionDate;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPayId()
    {
        return $this->payId;
    }

    /**
     * @param mixed $payId
     */
    public function setPayId($payId)
    {
        $this->payId = $payId;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getIpAddress()
    {
        return $this->IpAddress;
    }

    /**
     * @param mixed $IpAddress
     */
    public function setIpAddress($IpAddress)
    {
        $this->IpAddress = $IpAddress;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @param mixed $authorizationCode
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;
    }

    /**
     * @return mixed
     */
    public function getBin()
    {
        return $this->bin;
    }

    /**
     * @param mixed $bin
     */
    public function setBin($bin)
    {
        $this->bin = $bin;
    }

    /**
     * @return mixed
     */
    public function getLast4()
    {
        return $this->last4;
    }

    /**
     * @param mixed $last4
     */
    public function setLast4($last4)
    {
        $this->last4 = $last4;
    }

    /**
     * @return mixed
     */
    public function getExpMonth()
    {
        return $this->expMonth;
    }

    /**
     * @param mixed $expMonth
     */
    public function setExpMonth($expMonth)
    {
        $this->expMonth = $expMonth;
    }

    /**
     * @return mixed
     */
    public function getExpYear()
    {
        return $this->expYear;
    }

    /**
     * @param mixed $expYear
     */
    public function setExpYear($expYear)
    {
        $this->expYear = $expYear;
    }

    /**
     * @return mixed
     */
    public function getChanel()
    {
        return $this->chanel;
    }

    /**
     * @param mixed $chanel
     */
    public function setChanel($chanel)
    {
        $this->chanel = $chanel;
    }

    /**
     * @return mixed
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * @param mixed $cardType
     */
    public function setCardType($cardType)
    {
        $this->cardType = $cardType;
    }

    /**
     * @return mixed
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * @param mixed $bank
     */
    public function setBank($bank)
    {
        $this->bank = $bank;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param mixed $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return mixed
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param mixed $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param mixed $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * @return mixed
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * @param mixed $customerEmail
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;
    }

    /**
     * @return mixed
     */
    public function getCustomerCode()
    {
        return $this->customerCode;
    }

    /**
     * @param mixed $customerCode
     */
    public function setCustomerCode($customerCode)
    {
        $this->customerCode = $customerCode;
    }

    /**
     * @return mixed
     */
    public function getPaidAt()
    {
        return $this->paidAt;
    }

    /**
     * @param mixed $paidAt
     */
    public function setPaidAt($paidAt)
    {
        $this->paidAt = $paidAt;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * @param mixed $transactionDate
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;
    }


}