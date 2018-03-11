<?php

class PaymentRequestFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amLoggedInAs(1);
        $I->amOnPage('/payment-requests/create');
        $I->see('Logout (admin)');
        $I->see('Create new Payment Request', 'h1');
    }

    // demonstrates `amLoggedInAs` method
    public function checkEverythingOk(\FunctionalTester $I)
    {

    }

    public function checkRequiredFields(\FunctionalTester $I)
    {
        $I->submitForm('#invoice-create-form', []);
        $I->expectTo('see validations errors');
        $I->see('Description cannot be blank.');
        $I->see('Original Currency cannot be blank.');
    }
}