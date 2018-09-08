<?php
 
 
class FirstCest
{
    public function frontpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
         
        $I->fillField('#username', 'username');
        $I->fillField('#userpswd', 'password');
        $I->click('#btn_login');
        $I->see('登录成功');
        //codeception
        $I->executeJS('
        var realConfirm = window.confirm;
        window.confirm = function(){
        window.confirm = realConfirm;
        return true;
        };');
        sleep('3');
        $I->acceptPopup();//接受弹出层
        sleep('2');
        $I->switchToIFrame('leftFrame');
        $I->click('#menu_show');
        sleep('1');
        $I->click('#option_show');
        $I->switchToIFrame(); //切换frame
        $I->switchToIFrame('rightFrame');
        sleep('2');
        $I->see('successed connect server');
        // sleep('5');
    }
}

