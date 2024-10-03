<?php
use Catalog\Form\UserMarketplaceForm;

$userForm = new UserMarketplaceForm('frmusermarketplace');
$userForm->setMobile($Mobile_detect->isMobile());
