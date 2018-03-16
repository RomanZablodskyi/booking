<?php

class Seller extends Superuser
{
    protected static $table = 'Superuser';
    protected $status;

    public function __construct($id)
    {
        parent::__construct($id);
    }

    public static function confrimPay($id){
        $order = new Order($id);

        $order->changeStatus(2);
    }
}