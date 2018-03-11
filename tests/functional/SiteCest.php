<?php

use app\models\User;

class SiteCest
{
    public function providerCheckSwitchLocale()
    {
        return [
            ['requested' => 'ru-RU', 'expected' => 'ru-RU'],
            ['requested' => 'en-US', 'expected' => 'en-US'],
            ['requested' => '3412sadfafsaf', 'expected' => 'ru-RU'],
        ];
    }

    /**
     * @dataProvider providerCheckSwitchLocale
     * @param FunctionalTester $I
     */
    public function checkSwitchLocale(\FunctionalTester $I, \Codeception\Example $example)
    {
        $I->amLoggedInAs(User::findByUsername('admin'));
        $I->amOnRoute('/site/switch-locale', ['locale' => $example['requested']]);
        $I->canSeeResponseCodeIs(200);
        /** @var User $user */
        $user = $I->grabRecord(User::class, ['username' => 'admin']);
        expect_that($user->locale == $example['expected']);
    }
}